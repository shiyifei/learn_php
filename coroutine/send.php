<?php

function my_gen() {
    yield 'foo';
    $ret = (yield 'bar');
}
$gen = my_gen();
var_dump($gen->send('something'));

//如之前提到的在send之前, 当$gen迭代器被创建的时候一个renwind()方法已经被隐式调用
//所以实际上发生的应该类似:
//$gen->rewind();
//var_dump($gen->send('something'));
//这样renwind的执行将会导致第一个yield被执行, 并且忽略了他的返回值.
//真正当我们调用yield的时候, 我们得到的是第二个yield的值! 导致第一个yield的值被忽略.

//最终的输出结果： string(3) "bar"


echo '<hr/>';
$gen1 = my_gen();
var_dump($gen1->current());
$gen1->next();
//$gen1->rewind();		//Fatal error: Uncaught Exception: Cannot rewind a generator that was already run
var_dump($gen1->current());
var_dump($gen1->send('something'));

//最终的输出结果：string(3) "foo" string(3) "bar" NULL