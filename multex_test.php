<?php
    session_start();
    $curSessionId = session_id();
    echo 'sessionId='.$curSessionId.'<br/>';
    $redis = new Redis();
    $redis->connect('192.168.1.102',6379);
    $userSession = $redis->get('online:1');
    echo "userSession:{$userSession},user_id:{$_SESSION['user_id']},user_name:{$_SESSION['user_name']}<br/>";

    if (empty($_SESSION['user_id'])) {
        echo 'session is not exist, redirect login.php<br/>';
    }
