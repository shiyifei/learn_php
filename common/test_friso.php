<?php
	$str = 'Friso是使用c语言开发的一款开源的高性能中文分词器';
    $keywords = [];
    $result = friso_split($str, ['mode'=>FRISO_COMPLEX]);
    $keywords = array_column($result, 'word');
    var_dump($keywords);
?>

