<?php

/**
 * 查找一个字符串中的最长回文子串
 * 比如：cababcaac的最长回文子串就是caac 其中的aba bab也都是回文子串
 */


$input = '1234567qwertyuiopoiuytrewq65432asdfghjklgfdsa';

/**
 什么算是回文子串呢？：
 奇数个字符的话，则以中间字符为中心，左右对称
 偶数个字符的话，是以中间字符的间隙为中心，左右对称。
 这里在字符串$input前后以及每一个间隙加入#后，那么实际长度就等于n+n+1=2n+1,然后保证了中心点字符要么是#要么是字符


    新字符串为： #c#a#b#a#b#c#a#a#c#
    然后求每一个字符的最长回文半径：
位置：  0123456789
        #c#a#b#a#b#c#a#a#c#
 RL:    1212141412121252121
 RL-1:  0101030301010141010

 遍历RL-1:中的最大值，找到对应的回文子串，
 这里最大回文半径是4，最长回文子串就是：#c#a#a#c# 然后去掉其中的#就是caac

 最大回文半径的算法思想：
 以当前字符为中心，前后不断扩展并比较是否相同，如果左右对称字符相同，则算是回文。
 左右扩展的最大长度就是最大回文半径，RL-1实际上就是目标字符串的长度  
 #c#a#a#c# 中间的#最大回文半径是4，其实就等于目标串 caac的长度，为什么呢？
 以中间的#为中心的左右对称，那么直径长度就是9，最后减掉其中的#，剩下的就是目标字符串 2n+1=9, 结果n=4
 **/

/**
 * 
 * @param  string $input 输入源字符串
 * @return 最长回文子串
 */
function longestRecycleStr($input) {
    //先将原来的字符串每个间隙填充一个#，组成一个新的字符串，现在的字符串个数为2n+1(n是$input的长度)
    //然后遍历该字符串，查找每一个字符的最长回文半径，
    $len = 2*strlen($input)+1;
    $source = '';
    for ($i=0; $i<$len; ++$i) {
        if ($i%2==0) {
            $source{$i} = '#';
        }else {
            $source{$i} = $input[ceil($i/2)-1];
        }
    }
    echo "source:{$source}\n";

    $RL = [];
    for ($i=0; $i<$len; ++$i) {
        if ($i==0) {
            $RL[0] = 1;
        } elseif ($i == 1) {
            $RL[$i] = 2;
        } else {
            $curr = $source[$i];
            $halfLen = 1;
            $index = 1;
            while($index <= $i && $index+$i <= $len) {
                $posLeft = $i-$index;
                //左侧字符等于右侧字符,不断延伸，继续比较
                if (isset($source[$i+$index]) && $source[$posLeft] == $source[$i+$index]) {
                    $halfLen = $index+1;
                    ++$index;
                } else {
                    break;
                }
            }
            $RL[$i] = $halfLen;
        }
    }
    print_r($RL);

    $maxHalfLen = max($RL);
    $arrPos = array_keys($RL, $maxHalfLen, true);

    $result = [];
    foreach ($arrPos as $k => $v) {
        $posStart = $v-$maxHalfLen+1;
        $result[$v] = str_replace('#', '', substr($source, $posStart, 2*$maxHalfLen-1));
    }
    return $result;
}

$begin = microtime(true);
$result = longestRecycleStr($input);
echo "spend time:".(microtime(true)-$begin)."s <br/>";
print_r($result);



/*
1, 3 ,5, 7
0, 1, 2, 3
*/