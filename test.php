<?php

    $input = "192.168.3.118";
    /*$output = eregi("^(10│172.16│192.168).", $input);
    var_dump($input, $output);
    echo '<hr/>';*/
    $matches = '';
    $output = preg_match('/^(10|172.16|192.168)./', $input, $matches);
    var_dump($input, $output, $matches);
    echo '<hr/>';

    $output = get_real_ip();
    var_dump($output);
    echo '<hr/>';


    function get_real_ip()
    {
        $ip=false;
        //客户端IP 或 NONE 
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }

        //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { 
                array_unshift($ips, $ip); 
                $ip = false; 
            }
            $count = count($ips);
            for ($i = 0; $i < $count; $i++) {
                if (!preg_match('/^(10|172.16|192.168)./', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        //客户端IP 或 (最后一个)代理服务器 IP
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }


    
    $input = 6442329600;

    $output = date('Y-m-d H:i:s', $input);

    var_dump($input, $output);
    return;

    

    $listDay = ['2020-01-01', '2021-01-02'];
    $arrByDay = array_fill_keys($listDay, ['visit_count'=>0, 'friend_count'=>0, 'order_count'=>0]);

    var_dump($arrByDay);
    return;

    
    $date = date_create('2021-02-01');
    date_add($date, date_interval_create_from_date_string('1 month'));
    date_sub($date, date_interval_create_from_date_string('1 day'));
    echo date_format($date, 'Y-m-d');
    return;


    $time = date('Ymd', strtotime('-7 day'));
    var_dump(strtotime($time));

    $time = date('Y-m-d', strtotime('-7 day'));
    var_dump(strtotime($time));
    return;

    $arr = [1,2,3,4,55,6,8,0,22];
    $output = array_filter($arr);
    var_dump($arr, $output);

    return;


    $msg = "in test.php, arrive in here, time:".microtime(true)."\n";
    file_put_contents('/tmp/site.log', $msg, FILE_APPEND);
    mt_srand(microtime(true)*1000000);
    $random = mt_rand(0,100);
    $howLong = (50+$random)>125?2:0;
    $msg = "in test.php, howLong={$howLong}\n";
    file_put_contents('/tmp/site.log', $msg, FILE_APPEND);
    sleep($howLong); 
    echo json_encode($_POST);
    return;

    $a = 256 | 64;

    //64表示 不转义/
    //256表示 不转义中文到unicode编码
    //
    var_dump($a);

    $arr = ['a'=>'我在这里呀', 'b'=>'/还在这里呀'];
    $b = json_encode($arr);
    $c = json_encode($arr, 320);
    var_dump($b, $c);
    return;

    // $params = '{"carid":"42933348","cityid":"2501","mastername":"YldWdVozcG9ZVzh4"}';
    // $requestData = json_decode($params, true);
    
    //测试implode的用法
    $arrInput = ['a'=>123, 'b'=>34, 'c'=>'are', 'd'=>'you','e'=>666];
    $output = implode(',', $arrInput);
    var_dump($output);
    return;
    
    //
    //
    // 
    $statusSeries = [11,71,76,16,14,72,18];
    $currStatus = 16;
    $status = 72;
    $indexSource = array_search($currStatus, $statusSeries);
    $indexTarget = array_search($status, $statusSeries);

    var_dump($statusSeries);
    echo '<hr/>';
    var_dump($indexSource, $indexTarget);
    return;


    $a = 8.2195;

    $output = bcmul($a, 1.00, 2);
    var_dump($output);
    $output = floatval(number_format($a, 2));
    var_dump($output);
    return;
    
    // 
    // 
    $a = date("Y-m-d 00:00:00");
    $b = strtotime($a);
    $c = time();
    var_dump($a, $b, $c);
    echo '<hr/';


    $d = strtotime("-1 Month");
    $e = date('Y-m-d H:i:s', $d);
    $f = date('Y-m-d H:i:s');
    var_dump($d, $e, $f);
    return;

    // 
    $records = array(
        array(
            'id' => 2135,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ),
        array(
            'id' => 3245,
            'first_name' => 'Sally',
            'last_name' => 'Smith',
        ),
        array(
            'id' => 5342,
            'first_name' => 'Jane',
            'last_name' => 'Jones',
        ),
        array(
            'id' => 5623,
            'first_name' => 'Peter',
            'last_name' => 'Doe',
        )
    );
     
    $first_names = array_column($records, 'first_name');
    print_r($first_names);

    $output = array_column($records, NULL, 'id');
    var_dump($output);
    return;


    $params = $_POST['data'];
    $requestData = json_decode($params, true);
     

     var_dump($requestData);

     $output = base64_decode(base64_decode($requestData['mastername']));
     echo "[{$output}]";
    echo '<hr/>';

    $input = 'YldWdVozcG9ZVzh4';
    $output = base64_decode($input);
    var_dump($output);

    $output1 = base64_decode($output);
    var_dump($output1);

    $output2 = urlencode(base64_encode($output1));
    var_dump($output2);

    return;
    
    function removeKeyZero($k, $v) 
    {
        var_dump($k, $v);
        return ($v && !empty($k));
    }

    $arrInput = ['a'=>1, '111'=>23445, '0'=>123,  '2'=>443];
    $output = array_filter($arrInput, function($k, $v){return ($v && !empty($k));}, ARRAY_FILTER_USE_BOTH);

    var_dump($output);
    return;

    $a = bcdiv(937,365,2);
    $b = round($a, 1);

    $c = ['car_age'=>$b];

    var_dump($a, $b);
    echo json_encode($c);
    return;


    include "./test_exception.php";

    $obj = new Test();

    $url = 'https://402.net.com/';
    try {
        $obj->sendRequest($url);
    } catch (\Exception $e) {
        throw $e;
    }
    
    return;



	$a = 3.124545;
	$b = 6.221312;
	$c = bcadd($a,$b,12);
	var_dump($a, $b, $c);
	return;

    $myinfo = "in test.php, arrive here \n";
    file_put_contents('/var/tmp/debug.log', $myinfo, FILE_APPEND);

    $logFile = '/var/log/business/biz.log';
    $orderId = mt_rand(10000, 100000);
    $info = date('Y-m-d H:i:s').' '.'在网上处方药不能卖给没有处方的人员，产品ID:'.$orderId.'不合法，请重试'."\n";
    file_put_contents($logFile, $info, FILE_APPEND);
    echo "{$info}<hr/>";


class Test 
{
    public function __construct() {

    }

    public function sendRequest($url) 
    {
        echo "arrive here 111\n";
        try {
            echo "arrive in here 222\n";
            $resp = Common::getResponse($url);
            echo "arrive in here 333";
            return $resp;
        } catch (\Exception $e) {
            echo "in ".__METHOD__.",exception:".$e->getMessage();
        }
       
    }
}


function add($a,$b) 
{
    return $a + $b;
}

$picType = 0;

add(111, $picType=3);
var_dump($picType);


echo '<hr/>';
$array1 = array("color" => "red", 2, 4);
$array2 = array("a", "b", "color" => "green", "shape" => "trapezoid", 4);

$output = $array1 + $array2;
$result = array_merge($array1, $array2);
print_r($result);
echo '<br/>';



print_r($output);

