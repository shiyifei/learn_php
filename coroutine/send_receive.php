<?php

function gen()
{
	$ret = (yield 'yield1');
	var_dump($ret);
	$ret = (yield 'yield2');
	var_dump($ret);
}

$gen = gen();
var_dump($gen->current());
var_dump($gen->send('ret1'));
// var_dump($gen->send('ret2'));


/**
最终的输出是：string(6) "yield1" string(4) "ret1" string(6) "yield2" string(4) "ret2" NULL
var_dump($gen->current())  "yield1"
var_dump($gen->send('ret1')) "ret1" "yield2"    
解读：
	$ret=(yield 'yield1') 这句中的$ret会被传入的"ret1"取代，然后 var_dump($ret) 会输出 $ret1, 
	然后send()语句会导致gen()继续往下走，导致语句 $ret = (yield 'yield2') 被执行，var_dump($gen->send('ret1')) 这句的执行结果就是 yield2
	var_dump($gen->send('ret1')) 这句话相当于这两句的执行效果：  $a = $gen->send('ret1'); var_dump($a);

var_dump($gen->send('ret2')) "ret2" null     
	$gen->send('ret2') 传入后，var_dump($ret) 这里的$ret已经被"ret2"取代，所以这里打印"ret2"
	null 这句话实际上是$gen->send('ret2')的返回值
**/