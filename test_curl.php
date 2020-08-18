<?php

class Practice {

    private $taskInfo = ['a'=>1,'b'=>2, 'c'=>3];

    /**
     * 发送post请求
     * @param  string $sUrl  请求的url
     * @param  array $params post参数
     * @return string
     */
    function sendPostRequest($sUrl, $params, $reqTimeout=3, $conTimeout=1)
    {
        $oCurl = curl_init();
        // 设置请求头, 有时候需要,有时候不用,看请求网址是否有对应的要求
        $header[] = "Content-type: application/x-www-form-urlencoded";
        $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        // 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
        curl_setopt($oCurl, CURLOPT_HEADER, false);
        // 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
        curl_setopt($oCurl, CURLOPT_NOBODY, false);
        // 使用上面定义的 ua
        curl_setopt($oCurl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);

        // curl_setopt($oCurl, CURLOPT_TIMEOUT_MS, $reqTimeout);//超时100ms，使当前进程不等待
        curl_setopt($oCurl, CURLOPT_TIMEOUT, $reqTimeout);//超时3s，使当前进程不等待

        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $conTimeout);

        //用 POST 方式请求
        curl_setopt($oCurl, CURLOPT_POST, true);
        if (is_array($params)) {
            $flag = true;
            foreach ($params as $key => $val) {
                if (strpos($val, '@') === 0) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $params = http_build_query($params);
            }
        }
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $params); 

        $sContent = curl_exec($oCurl);
        if (curl_errno($oCurl)) {
            $sContent = curl_error($oCurl);    // 捕抓异常,返回
        }
        curl_close($oCurl);
        return $sContent;
    }


    public function showTaskInfo()
    {
        var_dump($this->taskInfo);
    }


}

$practice  = new Practice();
$url = 'http://192.168.56.102:8005/api/enquiryStep/updateEnquiryStatus';
$params = ['orderid'=>505029,'status_enum'=>'UserConfirmOrder','sign_platform'=>'zg',
'sign_timestamp'=>1569477931,'sign'=>'caa0bcb5bc6a01d6d34b809de46bd04c'];
$output = $practice->sendPostRequest($url, $params, 3, 1);

// var_dump($output);
// 
// $taskInfo = ['a'=>1, 'b'=>2];
$practice->showtaskInfo();
// var_dump($taskInfo);


try {
    $res = json_decode($output, true);
    if (empty($res)) {
        throw new \Exception("获取物流成本异常！".$output);
    }
    var_dump($res);
} catch(\Exception $e) {
    echo 'Exception:'.$e->getMessage();
}