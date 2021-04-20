<?php

header('Content-Type: text/html;charset=utf-8'); //modified by shiyf


function ccMobileDecode($string = '', $en_key = '')
{
    $key = md5($en_key);
    $x = 0;
    $data = base64_decode($string);
    $len = strlen($data);
    $l = strlen($key);
    $char = '';
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}

$key = 'zg_bind_password';

$input = 'nGtubJpvb5k=';
$output = ccMobileDecode($input, $key);
var_dump($input, $output);


function create_sn($data, $secret = '')
{
    unset($data['sn']);
    ksort($data);
    $time = !empty($data['salt_date']) ? $data['salt_date'] : date('Y-m-d', time());
    $a = http_build_query($data, '', '&', 1);
    var_dump($a);
    echo '<hr/>';
    $b = urldecode($a);
    var_dump($b);
    echo '<hr/>';
    $c = $b . $secret . $time;
    var_dump($secret, '<br/>', $time, '<br/>', $c);
    echo '<hr/>';

    $secret_str = md5($c);

    return $secret_str;
}


$pic = [
    ["key" => "car_key_pic", "name" => "车辆钥匙照片", "source" => 0, "url" => "/o/20200119/1815/5e242c33ae5aa608612.jpg"],
    [
        "key" => "vehicle_regist_pic",
        "name" => "登记证第一页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c36d903f523335.jpg"
    ],
    [
        "key" => "vehicle_regist_pic2",
        "name" => "登记证第二页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c39c94d7634666.jpg"
    ],
    [
        "key" => "vehicle_regist_pic3",
        "name" => "登记证第三页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c3cad3e8569607.jpg"
    ],
    [
        "key" => "vehicle_regist_pic4",
        "name" => "登记证第四页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c3f15537385366.jpg"
    ],
    [
        "key" => "vehicle_regist_pic5",
        "name" => "登记证第五页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c41d182a961217.jpg"
    ],
    [
        "key" => "vehicle_regist_pic6",
        "name" => "登记证第六页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c4423081629084.jpg"
    ],
    [
        "key" => "vehicle_license_pic",
        "name" => "行驶证照片",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c4724525888040.jpg"
    ],
    [
        "key" => "vehicle_license_pic2",
        "name" => "车辆信息页",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c49bb30c284188.jpg"
    ],
    [
        "key" => "vehicle_license_pic3",
        "name" => "年检信息页正面",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c4be72f1250923.jpg"
    ],
    [
        "key" => "vehicle_license_pic4",
        "name" => "年检信息页反面",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c4e06747845166.jpg"
    ],
    [
        "key" => "compulsory_insurance_pic",
        "name" => "交强险照片1",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c505c31b815146.jpg"
    ],
    [
        "key" => "compulsory_insurance_pic2",
        "name" => "交强险照片2",
        "source" => 0,
        "url" => "/o/20200119/1815/5e242c53036a9964769.jpg"
    ]
];
$strPic = json_encode($pic);

$data = [
    "engine_num" => "QWE",
    "mastername" => "v-kanglei",
    "order_id" => "506660",
    "pic_list" => $strPic,
    "src" => 1,
    'sn' => '',
    'salt_date' => ''
];


$secret = 'c982ab821f2c38136db1';
$output = create_sn($data, $secret);

echo "[{$output}]";




