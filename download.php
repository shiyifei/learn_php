<?php
ini_set('memory_limit', '512M');
set_time_limit(0);

// header("Content-type:text/html;charset=utf-8");
header('Content-type:text/csv');
// header('Content-Type: application/octet-stream');
// header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition:attachment;filename=test.csv');
header("Pragma: no-cache");

$fp = fopen('php://output', 'a') or die("can't open php://output");
// fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));

$arrTitle = ['姓名', '邮箱', '工作地'];

foreach($arrTitle as $k => $v) {
    $arrTitle[$k] = iconv('utf-8', 'gbk//IGNORE', str_replace(["\r\n", "\r", "\n"], '', $v));
}
fputcsv($fp, $arrTitle);

// ob_flush();
// flush();

$arrContent = [['王宝宝','wangbaobao@163.com','河南省郑州市'],['薛刚','xuegang@163.com','北京市']];

foreach($arrContent as $k => $v) {
    foreach($v as $ki => $vi) {
        $v[$ki] = iconv('utf-8', 'gbk//IGNORE', str_replace(["\r\n", "\r", "\n"], '', $vi));
    }
    fputcsv($fp, $v);
}
fclose($fp) or die("Can't close php://output");
exit;

// ob_flush();
// flush();
