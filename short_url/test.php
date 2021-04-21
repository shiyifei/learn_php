<?php

//生成短网址 
function code62($x)
{ 
  $show = ''; 
  while ($x > 0) { 
    $s = $x % 62; 
    if ($s > 35) { 
      $s = chr($s + 61); 
    } elseif ($s > 9 && $s <= 35){ 
      $s = chr($s + 55); 
    } 
    $show .= $s; 
    $x = floor($x/62); 
  } 
  return $show; 
}

function shorturl ($url)
{ 
  $url = crc32($url);
  echo 'url:'.$url.'<br/>'; 
  $result = sprintf("%u", $url); 
  return code62($result); 
} 

echo shorturl('//www.jb51.net/'); 
//1EeIv2 
echo '<hr/>';
echo shorturl('11_12_13');
echo '<hr/>';
echo shorturl('11_12_13');
