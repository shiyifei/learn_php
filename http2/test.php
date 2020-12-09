<?php

$a = CURL_VERSION_HTTP2;
echo 'CURL_VERSION_HTTP2:';
var_dump($a);
echo '<hr/>';
$b = CURL_HTTP_VERSION_2_0;
echo  'CURL_HTTP_VERSION_2_0:';
var_dump($b);
echo '<hr/>';

class Http2Helper 
{
	/**
	 * 测试本地或远程是否支持http2
	 * @param  string  $url 要测试的地址
	 * @return array ['code'=>'', 'msg'=>''] code==1 表示外部url支持http2
	 */
	public static function isSupportHttp2($url) 
	{
		$result = ['code' => 1, 'msg' => ''];
		$curlVersion = curl_version();
		// var_dump($curlVersion);
		if ($curlVersion['features'] && CURL_VERSION_HTTP2 !== 0) {
			 $ch = curl_init();
			 curl_setopt_array($ch, [
				 CURLOPT_URL => $url,
				 CURLOPT_HEADER => true,
				 CURLOPT_NOBODY => true,
				 CURLOPT_RETURNTRANSFER => true,
				 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
				]);

		 	$response = curl_exec($ch);

		 	//查看返回内容
		 	// var_dump($response);
		 	
			if ($response !== false && strpos($response, 'HTTP/2') === 0) {
			 	$result['msg'] =  "HTTP/2 support！"; 
			} elseif ($response !== false) {
			 	$result['msg'] = "服务器上没有HTTP/2支持。";
			 	$result['code'] = -1;
			} else {
			 	$result['msg'] = curl_error($ch);
			 	$result['code'] = -2;
			}
			curl_close($ch);
		} else {
		 	$result['msg'] = "客户端上没有HTTP/2支持。";
		 	$result['code'] = -3;
		}
		return $result;
	}
}

$begin = microtime(true);
// $url = 'https://www.zhihu.com';
$url = 'https://www.alipay.com/';
// $url = 'https://www.baidu.com/';
$retArr = Http2Helper::isSupportHttp2($url);
$end = microtime(true);
echo "function isSupportHttp2() spent time:".(($end-$begin)*1000).'ms <br/>';
var_dump($retArr);


