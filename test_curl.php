<?php
    header('Content-Type:text/html; charset=utf-8');

    // $a = extension_loaded('curl');
    // var_dump($a);

    $ch = curl_init();
	$url = "http://10.66.12.18:8080/trade/addTradeAccount.do?account_id=442&account_name=shiyf&email=shiyf@clcw.com.cn&mobile=13716596209&province=湖北&city=黄石&address=热推&real_name=石翼飞&createtime=2017-06-26%2010:09:37";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    var_dump($output);
    echo '<hr/>';
    if (empty($output)) {

    } else {
        $result = json_decode($output,TRUE);
        var_dump($result);
    }   
    echo '<hr/>';
	
	function curl($url,$post_data=null,$json=true){
        $ch= curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        
        if(!empty($post_data)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json","X-Accept: application/json"));
        }
        $result = curl_exec($ch);

        $myinfo = "in curl(), arrive here,result:".var_export($result,TRUE)."\n";
        file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND); 

        curl_close($ch);
        if($result===false) {
            return curl_error($ch);
        } else { 
            if ($json) $result = json_decode($result,true); 
            return $result;
        }
        
    }

    //$url = 'http://10.66.12.18:8080/trade/addTradeAccount.do?account_id=442&account_name=shiyf&email=shiyf@clcw.com.cn&mobile=13716596209&province=湖北&city=黄石&address=热推&real_name=石翼飞&createtime=2017-06-26%2010:09:37';

    $result = curl($url);
    var_export($result);
?>