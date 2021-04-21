<?php


/**
 * 获取cc接口参数
 * @param type 1：点播账号 2:课程页面账号 3:直播账号
 */
function getUrlPar($post, $type = 1, $is_live = 0) {
    if ($type == 1) {
        $userid = '73F7E4126ED2C1D5';
        $salt   = 'MlsD3AN103escNNQiaBsQXRySys2IxiD';
    } elseif ($type == 2) {
        $userid = '45C0B8C9F7B065A8';
        $salt   = 'rext8tb2vsu2qlFE7V4kYjg2S4c6va0s';
    } elseif ($type == 3) {
        $userid = '1218D0533753B5A1';
        if ($is_live == 1) {
            $salt = '02P44pu0IGliFZuum87yaCNKmxbcVq1t';
        } else {
            $salt = 'J0zJcv3s1wonfLgMaFUMJ4pjVjzpOrrJ';
        }
    }
    //对参数值URL Encode
    $post = array_map('urlencode', $post);
    //参数按键名进行升序排序，拼接成字符串
    ksort($post);
    $param = "";
    foreach ($post as $key => $value) {
        $param .= $key . "=" . $value . "&";
    }
    //加入当前时间戳和直播平台API Key
    $time     = time();
    $hash_str = $param . "time=" . $time . "&salt=" . $salt;
    //对参数字符串执行MD5得到hash参数
    $hash = md5($hash_str);
    //原参数字符串加入当前时间戳和hash值，得到最终的参数字符串
    $param .= "time=" . $time . "&hash=" . $hash;
    return $param;
}

$data = ['userid'=>'73F7E4126ED2C1D5','videoid'=>'B0BFB148E02278B09C33DC5901307461','date'=>'2021-03-22','num_per_page'=>'100','page'=>'1'];
$str = getUrlPar($data, 1);
var_dump($str);

$output = urlencode($str);
var_dump($output);