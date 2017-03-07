<?php
    $url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxc7bc001b61bbc6fa&secret=ba5bd13aedb31899069b8877a7c02b4b";
        $res = file_get_contents($url);
        var_dump($res);
        echo "<br/>";
        
        $result = json_decode($res);
        var_dump($result);
        echo $result->access_token;
        phpinfo();
?>