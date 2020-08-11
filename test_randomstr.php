<?php

function GetFingerPrint() {
    $str = substr(str_shuffle('abcdefg!@#$%^&*hijklmnopqrstuvwxyz0123456789'), 0, 4);
    return sha1($str.uniqid().mt_rand(1,999999));
}


for ($i=0; $i<10; $i++) {
    $output = GetFingerPrint();
    echo "output:[{$output}] <br/>";
}



