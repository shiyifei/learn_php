<?php
echo 'arrive here 111<hr>';
error_reporting(E_ALL);

//在register_shutdown_function中可以捕获到Fatal Error错误, 该语句应该放在文件前面
register_shutdown_function('shutdown_fun');

function testError()
{
    ini_set('memory_limit', "2M");
    $rows = [];
    for ($i=1; $i<100000; ++$i) {
        $row = ['username'=>'user'.sprintf('%6d',$i), 'userid'=>$i, 'tag'=>'年年岁岁花相似，岁岁年年人不同'];
        $row['createtime'] = date('Y-m-d H:i:s');
        $row['email'] = $row['username'].'@163.com';
        $row['age'] = mt_rand(21,45);
        $row['gander'] = (mt_rand(1,10000) %2 == 0)?'famale':'male';
        $rows[] = $row;
    }
    print_r(count($rows));
}

try {
    //调用不存在的方法时会捕获到Error(这里不是Exception)，但不会进入到shutdown_fun中
    test();
} catch (Error $e) {
    echo 'in '.__FILE__.",222, error:".$e->getMessage()."<hr/>";
} catch(Exception $e) {
    echo 'in '.__FILE__.",333, exception:".$e->getMessage()."<hr/>";
}

//上述try catch替代语法
/*try {
    //调用不存在的方法时会捕获到Error(这里不是Exception)，但不会进入到shutdown_fun中
    test();
} catch (Throwable $e) {
    echo 'in '.__FILE__.",334, error:".$e->getMessage()."<hr/>";
}*/

try {
    /**
     * PHP错误的一个重要级别, 如异常/错误未捕获时, 内存不足时, 或是一些编译期错误(继承的类不存在), 
     * 将会以E_ERROR级别抛出一个Fatal Error, 是在程序发生不可回溯的错误时才会触发的, 
     * PHP程序无法捕获这样级别的一种错误, 只能通过register_shutdown_function在后续进行一些处理操作。
     * 出现该错误时，程序会中断。
     */
    testError();
} catch (Error $e) {
    echo 'in '.__FILE__.",444, error:".$e->getMessage()."<hr/>";
} catch(Exception $e) {
    echo 'in '.__FILE__.",555, exception:".$e->getMessage()."<hr/>";
}

//记录报错详情语句
function shutdown_fun() {
    if(function_exists('error_get_last')){
        $msg = error_get_last();
        if(isset($msg['file'])&&!empty($msg['file'])){
            $err_type = array(E_RECOVERABLE_ERROR,E_ERROR,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR,E_USER_DEPRECATED);

            $msg['servername'] = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'cli';
            $msg['REQUEST_URI']= $msg['servername'].(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'');
            $msg['POST'] = $_POST;
            $msg['operator'] = isset($_SESSION['mastername'])?$_SESSION['mastername']:'system';
            $msg['ip'] = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'unknow';
            
            $myinfo = date('Y-m-d H:i:s').' '.json_encode($msg);
            file_put_contents('/tmp/error.log', $myinfo, FILE_APPEND);
        }
    }
}

