<?php


// $remoteFile = 'https://newoss.zhulong.com/edu/201906/18/29/1624296azostajrz6bu2ug.jpg';

$remoteFile = 'https://f.zhulong.com/forum/202104/09/50/113250cfec1xl3a71hho2h.xls';


// $remoteFile = 'http://192.168.56.101:8081/aaa.xls';

$localFile = '/var/www/html/learn_php/oss/img/c.xls';

downloadFile($remoteFile, $localFile);

$output = file_exists($localFile);
var_dump($output);
echo '<hr/>';

download_image($remoteFile);




function downloadFile($file_url, $save_to)
{
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_POST, 0); 
	 curl_setopt ($ch,CURLOPT_REFERER,'http://inner.bbs.zhong.com');
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	 curl_setopt($ch,CURLOPT_URL,$file_url); 
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	 $file_content = curl_exec($ch);
	 curl_close($ch);

	 var_dump($file_content);

	 $downloaded_file = fopen($save_to, 'w');
	 fwrite($downloaded_file, $file_content);
	 fclose($downloaded_file);
}


function download_image($pic_url)
{
    $time = time();
    $pic_local_path = '/var/www/html/learn_php/oss/img';
    //$pic_local = $pic_local_path . '/img1-' . $time . '.jpg';
    $pic_local = $pic_local_path . '/img1-' . $time . '.xls';

    if (!file_exists($pic_local_path)) {
        mkdir($pic_local_path, 0777);
        @chmod($pic_local_path, 0777);
    }

    ob_start(); //打开输出
    readfile($pic_url); //输出图片文件
    $img = ob_get_contents(); //得到浏览器输出
    ob_end_clean(); //清除输出并关闭
    file_put_contents($pic_local, $img);
    return $pic_local;
}
