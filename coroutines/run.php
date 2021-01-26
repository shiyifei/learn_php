<?php
function co() {
    $a = (yield "a第1步");
    $a .= "哈哈";
    var_dump($a);
    var_dump(yield "a第2部");
    var_dump(yield "a第3部");
    var_dump(yield "a第4部");
}

function cob() {
    var_dump(yield "b第1步");
    var_dump(yield "b第2部");
    var_dump(yield "b第3部");
    var_dump(yield "b第4部");
}

$a = co();
$b = cob();
var_dump($a->current());
var_dump($a->send("send1"));
var_dump($a->send("send2"));

