<?php
/**
 * 面试题：
 * 求在dirB中的dirA的相对路径
 * 假如是一个项目中的两个不同文件，则要求在B文件中求出A文件的相对路径
 */
$dirA = '/a/b/c/d/e.php';
$dirB = '/a/b/12/34/c/d.php';

$result = getRefDir($dirA, $dirB);
echo "在文件B{$dirB}中，获取文件A：{$dirA}的相对路径是：[{$result}]\n";

/**
 * 求出dirB文件中， 文件A的相对路径
 * @param $dirA 文件A绝对路径
 * @param $dirB 文件B绝对路径
 * @return string
 */
function getRefDir($dirA, $dirB)
{
    $result = '';
    $arrA = array_values(array_filter(explode('/', $dirA))); //array_filter过滤掉空串 array_values只根据值处理，重置下标
    $arrB = array_values(array_filter(explode('/', $dirB)));
    $countA = count($arrA);
    $countB = count($arrB);

    if ($countA == 0 || $countB == 0) {
        return $dirA;
    }

    $tmpArr = [];
    $isSameRoot = false;    //是否有相同的根路径
    //对比每一个元素，如果有相同的元素，要判断$arrB中的下标是否是连续的，连续的才入栈
    for ($j = 0; $j < $countB; ++$j) {
        if ($arrB[$j] == $arrA[$j]) {
            if ($j == 0) {
                $isSameRoot = true;
                array_push($tmpArr, $j);
            } elseif ($isSameRoot) {
                //先取出当前$tmpArr中最后一个保存的元素
                $prev = array_pop($tmpArr);
//                echo "j={$j},prev={$prev}\n";
                if ($prev === $j - 1) { //表示是arrB中的上一个下标与当前的下标-1相同，这样的下标才是连续的。
                    array_push($tmpArr, $prev);
                    array_push($tmpArr, $j);
                } else {
                    array_push($tmpArr, $prev);
                }
            }
        }
    }
    if (!$isSameRoot) {
        return $dirA;
    }

    //开始对比$arrA和 $tmpArr, 有相同元素的则要加入上一级符号 "../"
    for ($i = 0; $i < $countA; ++$i) {
        if (in_array($i, $tmpArr, true)) {
            $result .= '../';
        } else {
            $result .= $arrA[$i] . '/';
        }
    }
    if (strlen($result) > 0) {
        $result = substr($result, 0, -1);
    }
    return $result;
}










