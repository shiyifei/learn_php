<?php 
/**
 * 注册服务
 * @author： 飘逸的罗伯特
 */

/*
$api_data = [
	'name' => 'goods1',
	'uris' => '/goods1.html',
	'methods' => 'POST',
	'upstream_url' => 'http://hz12.cn/goods'
];
*/

$host = '192.168.1.15';
$api_data = [
	'name' => 'exam_service',     	//服务名称
	'protocol' => 'http',         //HTTP方法列表。例如：GET,POST
	'url' => 'http://'.$host.'/exam' //指向您的API服务器的基本目标URL
];


var_dump(http_request('http://'.$host.':8002/services', $api_data));


$route_data = [
	'name' => 'exam-route',     	//服务名称
	'paths[]' => '/exam',
];


var_dump(http_request('http://'.$host.':8002/services/exam_service/routes', $route_data));



/**
 * 发送post请求
 * @param  [string] $url      请求地址
 * @param  [array]  $postdata post参数
 * @return [string]           注册信息
 */
function http_request($url, $postdata=[]){
	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

	$data = curl_exec($curl);
	curl_close($curl);

	return $data;
}
