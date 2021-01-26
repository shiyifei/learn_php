<?php

function gen(){
    echo "hello gen<br/>";//step1
    $ret = (yield "gen1");   //step2
    echo '111'.$ret.'<br/>';  //step3
    $ret = (yield "gen2");   //step4
    echo '222'.$ret.'<br/>';;  //step5
}



$my_gen = gen();

/*
foreach($my_gen as $item) {	
	echo "currentItem:{$item}<br/>";
}
*/

var_dump($my_gen->current());
echo "<br/>";

var_dump($my_gen->send("main send")); //向生成器中传入一个值，并且当做 yield 表达式的结果，然后继续执行生成器。 如果当这个方法被调用时，生成器不在 yield 表达式，那么在传入值之前，它会先运行到第一个 yield 表达式。As such it is not necessary to "prime" PHP generators with a Generator::next() call (like it is done in Python). 
echo "<br/>";

/*
echo "before send()<br/>";
//$my_gen->send("main send");
//$my_gen->next();//往前走一步
var_dump($my_gen->send("main send"));
echo "<br/>";
echo "after send()<br/>";
*/


function gen_zero_to_ten() {
    for ($i = 1; $i <= 10; $i++) {
        yield $i;
    }
}

$generator = gen_zero_to_ten();
foreach ($generator as $key => $value) {
    echo $key ," => ", $value, "\n";
    echo "is closed:", intval($generator->valid()),"\n";
}

echo "after foreach, is closed: ", intval($generator->valid()),"\n";

?>