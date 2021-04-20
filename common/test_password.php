<?php
/**
 * 随机生成一个密码，可以指定密码长度
 */


function generatePwd($len=8) {
        $sourceStr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#-_';
        $strLen = strlen($sourceStr);
        $output = '';
        for ($i=0; $i<$len; ++$i) {
            $output .= $sourceStr{mt_rand(0, $strLen-1)};
        }
        return $output;
    }

    $length = 6;
    $output = generatePwd($length);
    var_dump($output);

    echo '<hr/>';
    $input = 'IJu3xGmo';
    $output = md5($input);
    echo 'output:'.$output;
