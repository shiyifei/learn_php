<?php
 // var_dump($_GET);

    $url = 'http://bi.test.carsing.com.cn/accountAuth?account_id=442&account_name=shiyf&email=&mobile=13716596209&province=湖北&city=黄石&address=热推&real_name石翼飞&createtime=2017-06-26%2010:09:37';
    $encodeUrl = urlencode($url);

    var_dump($encodeUrl);

    $input = '';
    $output = md5($input);
    echo '<hr/>';
    var_dump($output);
 
?>