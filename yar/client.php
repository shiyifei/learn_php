<?php
$client = new Yar_Client('http://192.168.1.15/yar/server.php');
$result = $client->Add(2,4);
var_dump($result);

$result = $client->Sub(22,4);
var_dump($result);

