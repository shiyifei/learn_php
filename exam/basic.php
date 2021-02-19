<?php
/**
  本示例演示一个变量的引用赋值，并演示了unset()语句以及 错误级别设置相关内容
**/

error_reporting(E_ERROR | E_WARNING);


$a = 1;				//$a变量赋值为1
$b = &$a;           //定义了一个变量$b, 该变量指向$a, 如果$a的值改变，$b的值也会跟着改变。 但是$a和$b是两个不同的变量，变量的内存地址是不同的。
var_dump($a, $b);


unset($b);          //这里释放了$b占用的内存，实际上其变量的内存地址被重置了。
echo $a;			//这里不受影响，因为$a和$b的变量的内存地址是不同的。
echo $b;			//这里会有报错，是一个Notice级别的报错信息，但由于错误级别设置到Warning，所以这里也不会显示。
					//error_reporting(E_ALL)时会显示报错 Notice: Undefined variable: b in /home/shiyf/project/learn_php/exam/basic.php on line 11

##最终的输出结果是 1



##由于报错信息是一个通知级别的错误，所有这里不会展示出来。 
##报错信息： Notice: Undefined variable: b in /home/shiyf/project/learn_php/exam/basic.php on line 11
##已经释放掉的变量不允许再访问