<?php

$arrA = [1,2,3,4,5,6,7,8,9,'a',9];
var_dump(max($arrA));
$arrPos = array_keys($arrA,max($arrA), true);
var_dump($pos);
return;



$arrA = ['a'=>1, 'b'=>2, 'c'=>3, 'aab', 'd'=>['are', 'you', 'ok']];
$arrB = [1, 2, 'c'=>4, 'aac', 'd'=>['how','do','you','do']];

$arrC = array_merge($arrA, $arrB);
$arrD = $arrA + $arrB;
$arrE = array_merge_recursive($arrA, $arrB);

//array_merge 与 + 不同之处： 
    //如果有相同键的元素，array_merge以后一个数组的值为准，+ 以前一个数组的值为准
    //如果只给了一个数组并且该数组是数字索引的，则array_merge后的键名会以连续方式重新索引。 
    //如果有下标相同的元素，array_merge会顺延下标，但是 + 会用前一个数组的值覆盖后一个数组
    //所以array_merge后的元素总个数会比 + 操作后的元素多一些
    //array_merge: 如果有相同字母键，则后一个数组会取代前者，+操作则相反
    //array_merge: 如果是隐形数字下标键，则前后数组会顺延， +操作会舍弃后一个数组中相同下标的数据


var_dump($arrA, $arrB);
print_r($arrC);
print_r($arrD);
print_r($arrE);
echo '<hr/>';

/*
    $arrC:Array
(
    [a] => 1
    [b] => 2
    [c] => 4
    [0] => aab
    [d] => Array
        (
            [0] => how
            [1] => do
            [2] => you
            [3] => do
        )

    [1] => 1
    [2] => 2
    [3] => aac
)

$arrD:Array
(
    [a] => 1
    [b] => 2
    [c] => 3
    [0] => aab
    [d] => Array
        (
            [0] => are
            [1] => you
            [2] => ok
        )

    [1] => 2
    [2] => aac
)

$arrE:Array
(
    [a] => 1
    [b] => 2
    [c] => Array
        (
            [0] => 3
            [1] => 4
        )

    [0] => aab
    [d] => Array
        (
            [0] => are
            [1] => you
            [2] => ok
            [3] => how
            [4] => do
            [5] => you
            [6] => do
        )

    [1] => 1
    [2] => 2
    [3] => aac
)
 */


$arr = [];
$arr[2] = 'are you ok';
$arr[1] = 'hi';
$arr[0] = 'what are you doing now?';

// foreach是按照元素插入的顺序来显示的，而不是根据下标
foreach($arr as $k => $v) {
    echo "k={$k}, v={$v} <br/>";
}
echo '<hr/>';

// 如果想按照下标显示，需要使用for循环
$count = count($arr);
for($i=0; $i<$count; ++$i) {
    echo "k={$i}, v={$arr[$i]} <br/>";
}
echo '<hr/>';


//如何判断关联数组严格相等呢？

$arrB = ['what are you doing now?', 'hi', 'are you ok'];


$isEqual = ($arr == $arrB);

$arrC = [ '2'=>'are you ok', '1'=>'hi', '0'=>'what are you doing now?'];
$isStrictEqual = ($arr === $arrC);

var_dump($isEqual, $isStrictEqual);
echo '<hr/>';



// $input1 = '((2+5)+(12+16))/(3+9)';
// $input2 = '()(2+5))+(((12+16))/(3+9)';
// $input3 = '((2+5)+(12+16(3)))/(3+9)';


$input1 = '))))((((()';
$input2 = '((()))()';
$input3 = '((())())';
$input4 = ')(()()()()()';

/**
 * 判断一个字符串表达式中的括号是否合法
 * @param  string  $input 
 * @return boolean        [description]
 */
function isValid($input) {
    $length = strlen($input);
    $arrStack = [];

    for ($i=0; $i<$length; ++$i) {
            if ($input[$i] == '(') {
                array_push($arrStack, '(');
            } elseif ($input[$i] == ')' && '(' != array_pop($arrStack)) {
                return '计算表达式不合法，缺少左括号';
            }
    }

    if(!empty($arrStack)) {
        return '计算表达式不合法，缺少右括号';
    }

    return '计算表达式合法';
}

$result = isValid($input1);
var_dump($result);
$result = isValid($input2);
var_dump($result);
$result = isValid($input3);
var_dump($result);
$result = isValid($input4);
var_dump($result);