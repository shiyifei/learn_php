<?php
header('Content-Type:text/html; charset=utf-8');

$input = file_get_contents('/tmp/debug.log');
echo '内容长度:'.strlen($input);
echo '<br/>';

$start_time = microtime(TRUE);

$input_compressed  = bzcompress($input,6);

echo 'after compressing,time interval:'.(microtime(TRUE)-$start_time).', length:', strlen($input_compressed).'<br/>';


$input_uncompressed = bzdecompress($input_compressed);
echo 'after uncompressing,length:'.strlen($input_uncompressed).'<br/>';



$start_time = microtime(TRUE);
$input_compressed  = gzcompress($input,6);
echo 'after compressing,time interval:'.(microtime(TRUE)-$start_time).', length:', strlen($input_compressed).'<br/>';


$input_uncompressed = gzuncompress($input_compressed);
echo 'after uncompressing,length:'.strlen($input_uncompressed).'<br/>';
