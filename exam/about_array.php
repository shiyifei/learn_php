<?php


/**
 * 对数组中的元素重新赋值，使用引用赋值的方式，看最终结果是什么
 * @return [type] [description]
 */
function referMethod() {
	$arrA = ['a', 'b', 'c'];
	foreach ($arrA as $k => $v) {
		$v = &$arrA[$k];
	}
	echo $arrA[0];
	echo $arrA[1];
	echo $arrA[2];
}



/**
 * 第一种反转字符串方法 比如输入字符串"abcde" 返回"edcba"
 * @param  string $input [description]
 * @return [type]        [description]
 */
function reverseStr1(string $input) {
	$len = strlen($input);
	if ($len == 0) {
		return $input;
	}

	$result = '';
	for ($i=0; $i<$len; ++$i) {
		$result{$i} = $input{$len-1-$i};
	}
	return $result;
}

/**
 * 第二种字符串反转方法
 * @param  string $input [description]
 * @return [type]        [description]
 */
function reverseStr2(string $input) {
	$len = strlen($input);
	if ($len == 0) {
		return $input;
	}

	$result = [];
	for ($i=0; $i<$len; ++$i) {
		$result[] = $input{$len-1-$i};
	}
	return implode('', $result);
}

referMethod();
echo '<hr/>'; //这是分割线
$input = 'abcdef123456754e2wqwdasfasfasdfasdfasfdasfdasfasfadsfasfdsadfaqwertyuioasdfghjklzxcvbnm';
$begin = microtime(true);
for ($i=0; $i<10000; ++$i) {
	$output = reverseStr1($input);	
}
var_dump($output);
$end = microtime(true);
echo 'spent time:'.(($end-$begin)*1000).'ms <br/>';

echo '<hr/>';
$begin = microtime(true);
for ($i=0; $i<10000; ++$i) {
	$output = reverseStr2($input);	
}
var_dump($output);
$end = microtime(true);
echo 'spent time:'.(($end-$begin)*1000).'ms <br/>';



/**最终输出结果：
bcc
string(87) "mnbvcxzlkjhgfdsaoiuytrewqafdasdfsafsdafsafsadfsadfsafdsafdsafsafsadwqw2e457654321fedcba" spent time:27.903079986572ms
string(87) "mnbvcxzlkjhgfdsaoiuytrewqafdasdfsafsdafsafsadfsadfsafdsafdsafsafsadwqw2e457654321fedcba" spent time:25.130987167358ms
**/

/**
 * 结果解读：
 * 第一个方法预测结果：c c c
 * 第一个方法实际结果：b c c
 * 想一想，为什么呢？
 * 从最终结果上来看，第二种字符串反转（借助于数组）的方式性能更好一些。
 */