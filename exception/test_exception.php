<?php

class Common
{
    
    public static function getResponse($url) 
    {
        echo "in ".__METHOD__.",111\n";
        // 创建一个指向一个不存在的位置的cURL句柄
        $ch = curl_init($url);
         echo "in ".__METHOD__.",222\n";
        // 执行
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 ); // 
        $ret = curl_exec($ch);
        echo "in ".__METHOD__.",333\n";

        // 检查是否有错误发生
        if(curl_errno($ch)) {
            $a = 'Curl error: ' . curl_error($ch);

            // 关闭句柄
            curl_close($ch);
            throw new \Exception($a);
        }

        // 关闭句柄
        curl_close($ch);
        return $ret;
    }
}