<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2017-02-28 16:04:28
 * @version $Id$
 */

class Practice {
    
    function __construct(){
        
    }

    /**
     * 自定义的字符串反转函数
     * @param string $input 输入字符串
     * @return  string
     */
    public function strrev1($input) {
    	$len = strlen($input);
    	$output = '';
    	for($i=$len-1;$i>=0;--$i) {
    		$output .= $input{$i};
    	}
    	return $output;
    }
    /**
     * 改进的字符串反转函数
     * @param  string $input 输入
     * @return string
     */
    public function strrev2($input) {
    	$len = mb_strlen($input,'utf-8');
    	$output = '';
    	for($i=-1;$i>=(-1)*$len;--$i) {
    		$output .= mb_substr($input,$i,1,'utf-8');
    	}
    	return $output;
    }
}

$objLearn = new Practice();
$input = 'what are you doing now?';
$output = $objLearn->strrev1($input);
var_dump($output);
echo "<hr/>";
$input = "选对方向你就成功了一半了";
$output = $objLearn->strrev1($input);
var_dump($output);
echo "<hr/>";

$input = 'what are you doing now?';
$output = $objLearn->strrev2($input);
var_dump($output);
echo "<hr/>";
$input = "选对方向你就成功了一半了";
$output = $objLearn->strrev2($input);
var_dump($output);
echo "<hr/>";

//调用系统的反转函数
$input = 'what are you doing now?';
$output = strrev($input);
var_dump($output);
echo "<hr/>";
$input = "选对方向你就成功了一半了";
$output = strrev($input);
var_dump($output);
echo "<hr/>";





