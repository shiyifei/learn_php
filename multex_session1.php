<?php
    
    $redis = new Redis();
    $redis->connect('192.168.1.102',6379);
    session_start();
    $curSessionId = session_id();
    echo 'sessionId='.$curSessionId.'<br/>';
    $userSession = $redis->get('online:1');
    echo "userSession:{$userSession},user_id:{$_SESSION['user_id']},user_name:{$_SESSION['user_name']}";

    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'areyouok';

    $redis->set('online:1', $curSessionId);
    $redis->expire('online:1', 1800);
    session_write_close();