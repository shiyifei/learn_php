<?php
/**
 * 以协程的方式写入日志到文件中,注意其中的send()方法，实际上是迭代器和外界交互的入口
 **/

function logger($filename)
{
	$fileHandle = fopen($filename, 'a');

	while (true) {
		fwrite($fileHandle, yield."\n");
	}
}


$logger = logger('/var/tmp/debug.log');
$logger->send('This is the first line!');
$logger->send('This is the second line!');
$logger->send('Hello shiyf, What are you doing now?');

/**
 最终会写入3行内容到文件中，实际上内容$string会替代yield语句的内容，最终写入 $string\n 这个字符串
 正如你能看到,这儿yield没有作为一个语句来使用, 而是用作一个表达式, 即它能被演化成一个值. 这个值就是调用者传递给send()方法的值. 在这个例子里, yield表达式将首先被"This is the first line!"替代写入Log, 然后被"This is the second line!"替代写入Log.
上面的例子里演示了yield作为接受者
 **/

