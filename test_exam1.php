<?php

/**
 * PHP笔试题，一个字符串里有很多个小括号，要求 小括号可以嵌套，但是必须保证左右匹配
 * 给出一个方法，要求能验证该字符串中是否符合小括号规定的格式
 */

$input1 = '))))((((()';
$input2 = '((()))()';
$input3 = '((())())';
$input4 = ')(()()()()()';

/**
//首先将该字符串分解成数组,相当于该数组全部压入到栈里
//做出栈操作，即：从右往左一个一个地弹出元素，看能否找到匹配的左括号
    1.当前是右括号，
        如果再弹出一个是左括号，则没有问题
        如果再弹出一个右括号，则要继续弹出所有左括号，记录右括号的个数，
        弹出左括号的过程中如果出现右括号个数为负数的情况，则表示左括号在右括号的右边，也属于非法字符串
        然后弹出相同个数的元素，要保证都是左括号才行。

    2、如果当前是左括号，则表示不是合法的格式
 */


/**
 * 检验给定的字符串是否符合格式
 * @param $input 输入的字符串
 * @return bool
 */
function checkIsValid($input)
{
    $arrStack = str_split($input, 1);
    $countLeft = substr_count($input, '(');
    $numOfLeft = 0;
    $numOfRight = 0;
    if ($curr = array_pop($arrStack)) {
        if ($curr == '(' && $numOfRight == 0) { //表示最后一个字符是左括号，不合法
            return false;

        } elseif ($curr == ')') {
            ++ $numOfRight;//右括号的个数

            while (true) {
                $prev = array_pop($arrStack);
                if ($prev == '(') {
                    --$numOfRight;//遇到左括号则右括号个数减1
                    ++$numOfLeft;
                    if ($numOfRight<0) {
                        return false;
                    }
                    if ($numOfLeft == $countLeft) {
                        break;
                    }
                } elseif (is_null($prev)) {
                    if ($numOfRight === 0) {
                        return true;
                    }
                    return false;
                } elseif ($prev == ')') {
                    ++ $numOfRight;
                }
            }
//            echo "numOfRight:{$numOfRight} <br/>";
            for ($i=0; $i<$numOfRight; ++$i) {
                $prev = array_pop($arrStack);
                if ($prev != '(') {
                    return false;
                }
            }
        } elseif ($numOfRight !== 0) {    //没有元素了
            return false;
        }
    }
    return  true;
}

$result = checkIsValid($input1);
echo "input1 is valid:".var_export($result, true)."<br/>";
$result = checkIsValid($input2);
echo "input2 is valid:".var_export($result, true)."<br/>";
$result = checkIsValid($input3);
echo "input3 is valid:".var_export($result, true)."<br/>";
$result = checkIsValid($input4);
echo "input4 is valid:".var_export($result, true)."<br/>";
