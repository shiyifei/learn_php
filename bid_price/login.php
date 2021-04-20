<?php
    session_start();
    var_dump($_SESSION);

    var_dump($_SESSION['user_name']);

    /*if(is_null($_SESSION['user_name'])) {
        echo "user_name:{$_SESSION['user_name']},user_id:{$_SESSION['user_id']}";
    } else {
        $_SESSION['user_name'] = 'areyouok';
        $_SESSION['user_id'] = 66;
    }*/

    $_SESSION['user_name'] = 'areyouok';
    $_SESSION['user_id'] = 66;

    