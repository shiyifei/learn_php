<?php
    $dbn =  new PDO('mysql:host=192.168.1.199;dbname=test;','test','secret');
    $dbn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $dbn->exec('set names utf8');
    
    $sql  = 'insert into users(username,email) values(:username,:email)';
    $stmt = $dbn->prepare($sql);
    $stmt->execute(array(':username'=>'wangzhongwei1',':email'=>'wangzhongwei@163.com'));
    
    echo $dbn->lastinsertid();
?>
