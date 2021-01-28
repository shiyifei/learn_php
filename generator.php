<?php

function gen()
{
   $ret = (yield "gen1");   //step1
   $ret = (yield '111'.$ret);
   $ret = (yield "gen2");   //step2
   $ret = (yield '222'.$ret);
   $ret = (yield "gen3");   //step3
   $ret = (yield '333'.$ret);
   $ret = (yield "gen4");   //step4
   $ret = (yield '444'.$ret);
}

$my_gen = gen();
 
/*foreach($my_gen as $item) {	
	echo "currentItem:{$item}<br/>";
}*/

var_dump($my_gen->current());
$my_gen->send("first send");
var_dump($my_gen->current()); //向生成器中传入一个值，并且当做 yield 表达式的结果，然后继续执行生成器。 
							  //如果当这个方法被调用时，生成器不在 yield 表达式，那么在传入值之前，它会先运行到第一个 yield 表达式。
							  //As such it is not necessary to "prime" PHP generators with a Generator::next() call (like it is done in Python). 
$my_gen->send("second send");
var_dump($my_gen->current());

//发送方法的返回值是 迭代器中的当前yield语句的执行结果，这里对应的gen()方法中的语句是 yield '222'.$ret; 
$retVal = $my_gen->send("what are you doing now?");       //执行该语句时，$ret会被发送的字符串取代，然后返回 "222what are you doing now?"
echo 'send result is:'.var_export($retVal, true)."<br/>";
$retVal = $my_gen->send("aaa");                           //此时对应的语句是 $ret=(yield "gen3"), 会先执行该语句，返回该语句的值
echo 'send result is:'.var_export($retVal, true)."<br/>";
$retVal = $my_gen->send("bbb");
echo 'send result is:'.var_export($retVal, true)."<br/>";
$retVal = $my_gen->send("ccc");
echo 'send result is:'.var_export($retVal, true)."<br/>";


/*
echo "before send()<br/>";
//$my_gen->send("main send");
//$my_gen->next();//往前走一步
var_dump($my_gen->send("main send"));
echo "<br/>";
echo "after send()<br/>";
*/

echo '<hr/>';


//以下测试valid()方法的使用
function gen_zero_to_ten() {
    for ($i = 1; $i <= 10; $i++) {
        yield $i;
    }
}
//每次访问完迭代器后，valid()方法返回false,实际上这时候迭代器已经关闭了
$generator = gen_zero_to_ten();
foreach ($generator as $key => $value) {
    echo $key ," => ", $value, "<br/>";
    echo "is closed:", intval($generator->valid()),"<br/>";
}

//迭代器访问完成后，valid()实际上又变成true了
echo "after foreach, is closed: ", intval($generator->valid()),"<br/>";

//以下测试send()方法
function Printer() 
{
	while(true) {
		$string = yield;
		echo $string."<br/>";
	}	
}

$printer = Printer();
var_dump($printer->current());

$printer->send("I am learning");
var_dump($printer->current());
?>