<?php
$a = ini_get("max_execution_time");
echo 'max_execution_time is:'.$a.'<br/>';
ini_set("max_execution_time", 5);
// set_time_limit(10);
$a = ini_get("max_execution_time");
echo 'after setting, max_execution_time is:'.$a.'<br/>';

$begin = microtime(true);
$c = '';
for ($i=0; $i<900000; ++$i) {
   $a = $i*$i;
   $b = $i+$i;
   $c .= "{$i}*{$i}={$a}, {$i}+{$i}={$b} \t"; 

   if ($i%10000 === 0) {
        file_put_contents('/tmp/log_execute'.$i.'.log', $c, FILE_APPEND);
   }
}
echo 'time interval is:'.(microtime(true)-$begin)."s \n";
