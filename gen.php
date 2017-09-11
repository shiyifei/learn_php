<?php

function gen(){
    echo "hello gen".PHP_EOL;//step1
    $ret = (yield "gen1");   //step2
    var_dump($ret);  //step3
    $ret = (yield "gen2");   //step4
    var_dump($ret);  //step5
}



$my_gen = gen();
var_dump($my_gen->current());
var_dump($my_gen->send("main send"));

?>