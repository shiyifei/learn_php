<?php
session_start();
switch($_GET['action']) {
    case 'login':
        checkLogin();
        break;
    case 'bidding':
        biddingCar();
        break;
    default:
        echo "参数非法！";
        break;
}
session_write_close();

function checkLogin() {
    $userList = array(['id'=>1,'username'=>'wangxiao','password'=>'secret'],
            ['id'=>2,'username'=>'shiyf','password'=>'secret'],['id'=>3,'username'=>'liming','password'=>'secret']);
    $username = trim(htmlentities($_POST['username']));
    $userpwd = trim(htmlentities($_POST['password']));
    $isSuccess = false;
    foreach($userList as $item) {
        if($username == $item['username'] && $userpwd == $item['password']) {
            $isSuccess = true;
            $_SESSION['username'] = $item['username'];
            $_SESSION['user_id'] = $item['id']; 
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/bid_price.php');
        }
    }

    if (!$isSuccess) {
        echo "用户名或密码错误<script>window.location.href='bid_login.php'</script>";
        return;
    }
}

function biddingCar() {
    //将出价信息写入数据库
    //考虑是从拍单主表和拍单出价表取最新价格合适？可以考虑在竞拍期间不写入最终价格，到竞拍结束的时候再写入价格。

    //先从redis中读取出该拍单的价格信息，拍单的信息从redis的集合中来找
    //判断价格是否合法：拍单的价格要比原来至少高出三百元
    //判断拍单状态

    $output = ['status'=>false, 'msg'=>''];

    $user_id  = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];
    $price    = $_POST['price'];
    $socketId = $_POST['socketId'];
    $createtime = microtime(TRUE);


    //判断价格是否有变动 multi->watch()->exec();
    //判断拍单 然后监控拍单的价格如果价格有变化停止

    //todo:将用户的出价信息写入到bidding_log表
    //todo:将最新的价格写入到bidding_car表
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $watchKey = 'bidOrder:'.$order_id;
    $redis->watch($watchKey);

    $pdo = new PDO('mysql:host=192.168.3.125:3306;dbname=test','root','secret');
    try{
        $pdo->beginTransaction();
        //插入出价表
        $sth = $pdo->prepare('insert into order_bidding_log(order_id,user_id,price,createtime,status) values(?,?,?,?,1);');
        $result1 = $sth->execute([$order_id, $user_id, $price, $createtime]);

        $myinfo =  'in '.__METHOD__.',result1:'.var_export($result1,TRUE)."\n";
        file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

        if ($result1) {
            $sth = $pdo->prepare('update orders set user_id=?, price=? where id=?');
            $result2 = $sth->execute([$user_id, $price, $order_id]);//更改主表
            
            $myinfo =  'in '.__METHOD__.',result2:'.var_export($result2,TRUE)."\n";
            file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
            
            $ret = $redis->multi()->hset($watchKey,'price', $price)->hset($watchKey,'user_id',$user_id)->exec();

            $myinfo =  'in '.__METHOD__.',ret:'.var_export($ret,TRUE)."\n";
            file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

            if (is_array($ret)) {
                $count = count($ret);
                if ($ret[$count-1] === 0) {
                    $pdo->commit();
                    //将user_id与socketId关联起来
                    //$redis->sAdd('socketids:'.$user_id, $socketId);

                    //将user_id记录到一个订阅列表中，后续会查找其中的socketId,找到后会发消息
                    $redis->sAdd('orderSubUsers:'.$order_id, $socketId);

                    //发布消息
                    $redis->publish('orderchannel:'.$order_id, $price);

                    $output['status'] = true;
                } else {
                    $pdo->rollback();
                }
            } else {
                $pdo->rollback();
            }
        } else {
            $pdo->rollback();
            $output['msg'] = "插入order_bidding_log错误";
        }

        

    } catch(Exception $e) {
        $myinfo =  'in '.__METHOD__.','.$e->getMessage();
        file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
        $output['msg'] = "数据库操作异常:".$e->getMessage();
        $pdo->rollback();
    }

    echo json_encode($output);
    return;
    

}

    



?>
