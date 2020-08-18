<?php

$json = file_get_contents('./response.txt');

echo "before compressing, total length is:".(strlen($json)/1024/1024)."M \n";

$arr = json_decode($json, true);

$data = $arr['data'];

echo "before compressing, data length is:".(strlen(json_encode($data))/1024/1024)."M \n";
$str  = gzcompress(json_encode($data), 2);
$str = bin2hex($str);

$myinfo = "in test_compress.php,[{$str}]\n";
file_put_contents('/var/tmp/debug.log', $myinfo, FILE_APPEND);

echo "after compressing, data length is:".(strlen($str)/1024/1024)."M \n";


$arr['data'] = $str;

echo "after compressing, total length is:".(strlen(json_encode($arr))/1024/1024)."M \n";
print_r($arr);


$ret = hex2bin($str);
$ret = gzuncompress($ret);
$arr['data'] = json_decode($ret, true);

echo "after uncompressing, total length is:".(strlen(json_encode($arr))/1024/1024)."M \n";
print_r($arr);






