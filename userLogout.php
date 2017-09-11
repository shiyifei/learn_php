<?php
    //用户退出接口
    session_start();
    
    $retArr = ['code'=>'000000','msg'=>'','data'=>array()];

    $account_id = (int) $_GET['account_id']; 
    if(empty($account_id)) {
        $retArr['code'] = '100031';
        $retArr['msg'] = '传入参数非法！';
        echo json_encode($retArr);
        return;
    }

    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    session_unset();
    session_write_close();

    $redis = new Redis();
    $redis->connect('192.168.1.102',6379);
    $oldSession = $redis->get('online:'.$account_id);

    $myinfo = "in userLogin.php,oldSession={$oldSession}\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

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
echo json_encode($retArr);
return;