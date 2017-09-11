<?php 
    //用户禁用接口
    $myinfo = "in userDisable.php,arrive here 111 \n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

    $retArr = ['code'=>'000000','msg'=>'','data'=>array()];
    session_start();
    $account_id = (int) $_GET['account_id']; 

    $myinfo = "in userDisable.php,account_id={$account_id}\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

    if(empty($account_id)) {
        $retArr['code'] = '100021';
        $retArr['msg'] = '传入参数非法！';
        echo json_encode($retArr);
        return;
    }

    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    session_unset();
    session_write_close();

    //session_start();
    $redis = new Redis();
    $redis->connect('192.168.1.102',6379);
    $oldSession = $redis->get('online:'.$account_id);
    if (!empty($oldSession)) {
        session_id($oldSession);
        session_start();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        session_unset();
        session_write_close();
    }

    //将用户禁用写入数据库
    try {
        $pdo = new PDO('mysql:dbname=test;host=127.0.0.1;','dbuser','123456');  
        $query = 'update employee_account set status=2 where account_id='.$account_id;
        $result = $pdo->exec($query);
        if (!$result) {
            echo '该用户已禁用';
        }        
    } catch(PDOException $e) {
        $retArr['code'] = '100022';
        $retArr['msg'] = $e->getCode().'|'.$e->getMessage().'<br/>';
        echo json_encode($retArr);
        return;
    }
    $redis->lpush('empsso:disableUsers', $account_id);//将用户插入到禁用队列中



    echo json_encode($retArr);
    return;
    