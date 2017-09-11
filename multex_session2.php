<?php
    session_start();
    $curSessionId = session_id();
    echo 'sessionId='.$curSessionId.'<br/>';
    session_write_close();

    $redis = new Redis();
    $redis->connect('192.168.1.102',6379);
    $oldSession = $redis->get('online:1');
    if (!empty($oldSession) && !empty($curSessionId) && $curSessionId != $oldSession) {
        echo 'oldSession not equal new sessionId<br/>';
        $redis->set('online:1', $curSessionId);
        $redis->expire('online:1', 1800);

        session_id($oldSession);
        session_start();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            echo 'arrive here,'.json_encode($params).'<br/>';
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        session_unset();
        session_write_close();
    
        session_id($curSessionId);
        session_start();    
        echo "oldSession:{$oldSession},user_id:{$_SESSION['user_id']},user_name:{$_SESSION['user_name']}";
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'areyouok';    
        session_write_close();
    }