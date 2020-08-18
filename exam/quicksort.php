<?php

/**
 * php实现的快速排序算法
 */

$source = [12,34,59,72,98,7,6,54,32,34,56,78,];

function mysort(&$source)
{
    $len = count($source);
    if ($len <= 1) {
        return ;
    }
    quickSort($source, 0, $len-1);
}


function quickSort(&$arr, $left, $right)
{
    if ($left >= $right) {
        return;
    }

    $i = $left;
    $j = $right - 1;

    //找数组中的任何一个元素作为基准值，保存到$arr[$right]中
    $random = mt_rand($left, $right);
    $tmp = $arr[$right];
    $arr[$right] = $arr[$random];
    $arr[$random] = $tmp;

    // echo 'basic number is arr[{$random}]:'.$arr[$right]."<br/>";

    //将现有数组中的值分为两个区间
    while ($i <= $j) {
        if ($arr[$i] <= $arr[$right]) {
            ++$i;
        } else {
            $tmp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $tmp;
            --$j;
        }
    }

    //交换 $arr[$i] 与 $arr[$right]的值
    if ($i < $right) {
        $tmp = $arr[$i];
        $arr[$i] = $arr[$right];
        $arr[$right] = $tmp;
    }

    quickSort($arr, $left, $i-1);
    quickSort($arr, $i+1, $right);
}

echo "before sort, source:".var_export($source, true);
echo '<hr/>';
mysort($source);
echo "after sort, source:".var_export($source, true);