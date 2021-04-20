<?php

/**
 * 使用curl并行发送多个请求获取数据
 * @param  array  $urls 多个请求数组
 * @return array
 */
function sendMultiRequest(array $urls)
{
    $conn = [];
    $res = [];
    //创建批处理curl句柄
    $mh = curl_multi_init();

    foreach ($urls as $k => $item) {
        $conn[$k] = curl_init();  //初始化各个子连接
        //设置url和相应的选项
        curl_setopt($conn[$k], CURLOPT_URL, $item['url']);
        curl_setopt($conn[$k], CURLOPT_HEADER, 0);
        curl_setopt($conn[$k], CURLOPT_RETURNTRANSFER, 1); //不直接输出到浏览器，而是返回字符串
        curl_setopt($conn[$k], CURLOPT_TIMEOUT, 10);
        if ($item['method'] == 'post') {
            curl_setopt( $conn[$k], CURLOPT_POST, true );
            $params = $item['params'];
            if (is_array($item['params'])) {
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
            curl_setopt($conn[$k], CURLOPT_POSTFIELDS, $params); 
        }
        //处理302跳转
        curl_setopt($conn[$k], CURLOPT_FOLLOWLOCATION, 1);

        //增加句柄
        curl_multi_add_handle($mh, $conn[$k]);   //加入多处理句柄
    }

    $active = null;     //连接数

    //防卡死写法:执行批处理句柄
    do {
        $mrc = curl_multi_exec($mh, $active);
        //这个循环的目的是尽可能地读写，直到无法继续读写为止
        //返回 CURLM_CALL_MULTI_PERFORM 表示还能继续向网络读写

    } while($mrc == CURLM_CALL_MULTI_PERFORM);

    // var_dump($mrc);
    // echo '<hr/>';
    // var_dump($active);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);

            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    foreach ($urls as $k => $url) {
        $info = curl_multi_info_read($mh);
        // var_dump($info);

        $headers = curl_getinfo($conn[$k]);
        // var_dump($headers);

        $res[$k] = curl_multi_getcontent($conn[$k]);

        //移除curl批处理句柄资源中的某一个句柄资源
        curl_multi_remove_handle($mh, $conn[$k]);

        //关闭curl会话
        curl_close($conn[$k]);
    }

    //关闭全部句柄
    curl_multi_close($mh);
    return $res;
}

/**
 * curl get请求
 * @param  string $sUrl 请求url地址
 * @return 响应内容
 */
function sendGetRequest($sUrl)
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

    // 不用 POST 方式请求, 意思就是通过 GET 请求
    curl_setopt($oCurl, CURLOPT_POST, false);

    $sContent = curl_exec($oCurl);
    curl_close($oCurl);
    return $sContent;
}

/**
 * 发送post请求
 * @param  string $sUrl  请求的url
 * @param  array $params post参数
 * @return string
 */
function sendPostRequest($sUrl, $params)
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
    curl_close($oCurl);
    return $sContent;
}



$params = ['orderid'=>24875, 'sign_timestamp'=>'1567478095','sign_platform'=>'zg', 'sign_timestamp'=>'1567478095',
            'sign'=>'06c2bf1422db1875e043ba3163e9c377'];
$urls = ['baidu' => ['url'=>'https://www.baidu.com/', 'method'=>'get', 'params'=>[]], 
'sina'=>['url'=>'https://www.sina.com.cn/', 'method'=>'get', 'params'=>[]], 
'tencent'=>['url'=>'https://www.qq.com/', 'method'=>'get', 'params'=>[]],
'jctest'=>['url'=>'http://jc.fat.xin.com/api/enquiryStep/updateEnquiryStatus', 'method'=>'post', 'params'=>$params]];

$begin  = microtime(true);
$res = sendMultiRequest($urls);
echo "use curl_multi_exec(), time interval:".(microtime(true)-$begin)."s \n";
echo '<hr/>';

//检测获取到的内容的编码格式
$encoding = mb_detect_encoding($res['tencent'], array("ASCII","GB2312","GBK",'BIG5', "UTF-8"));
//强制转换为utf-8格式
$res['tencent'] = iconv($encoding, "UTF-8//TRANSLIT",$res['tencent']);
// echo $res['tencent'];
// echo '<hr/>';



$begin  = microtime(true);
$res = [];
foreach($urls as $k => $item) {
    if ($item['method'] == 'get') {
        $res[$k] = sendGetRequest($item['url']);
    } elseif ($item['method'] == 'post') {
        $res[$k] = sendPostRequest($item['url'], $item['params']);
    }
    
}
echo "use multi curl_exec(), time interval:".(microtime(true)-$begin)."s \n";

// var_dump($res['jctest']);

// echo $res['tencent'];