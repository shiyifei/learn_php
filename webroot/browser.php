<?php

function isMobile() { 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备 
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) { 
        return TRUE; 
    } 
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 
    if (isset ($_SERVER['HTTP_VIA'])) { 
        //找不到为flase,否则为TRUE
        return (strpos($_SERVER['HTTP_VIA'], 'wap') !== FALSE) ? TRUE : FALSE; 
    } 
    //判断手机发送的客户端标志,兼容性有待提高 
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        /**$clientkeywords = ['nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 
        'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini','operamobi', 'openwave', 'nexusone','cldc', 'midp', 'wap', 'mobile']; 

        // 从HTTP_USER_AGENT中查找手机浏览器的关键字 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) { 
            return TRUE; 
        } 
        **/
        if(preg_match("/AppleWebKit.*Mobile.*/i",$_SERVER['HTTP_USER_AGENT'])) {
            return TRUE;
        }
    } 
    //协议法，因为有可能不准确，放到最后判断 
    if (isset ($_SERVER['HTTP_ACCEPT'])) { 
        // 如果只支持wml并且不支持html那一定是移动设备 
        // 如果支持wml和html但是wml在html之前则是移动设备 
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) 
        && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || 
        (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < 
        strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) { 
        return TRUE; 
        } 
    } 
    return FALSE; 
}

$result = isMobile();
if($result) {
    echo 'is mobile web browser<hr/>';
}
else {
    echo 'is not mobile web browser<hr/>';
}
?>