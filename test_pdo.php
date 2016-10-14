<?php
    $dbn =  new PDO('mysql:host=192.168.1.160;dbname=test;','root','secret');
    $dbn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $dbn->exec('set names utf8');
    
    $sql  = 'insert into users(username,email) values(:username,:email)';
    $stmt = $dbn->prepare($sql);
    $stmt->execute(array(':username'=>'wangzhongwei',':email'=>'wangzhongwei@163.com'));
    
    echo $dbn->lastinsertid();
?>