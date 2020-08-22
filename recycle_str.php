<?php
/**
 * 给定一个字符串s,找到s中最长的回文子串。
 * 你可以假定s的最大长度为1000
 */
$input = 'cbabadcaacqwertyuioplkjhgpooooooooiuytrewqertyuioooooiuytrewqwertyuiopasdfghjklmnbvcxzaqsdfghjkllkjhgfdsafdsasdfghjkloiuytrewqqwertyui';

function longestRecycleStr($input)
{
    //如何判断是回文子串呢？
    //该字符串要满足：
        //1、首尾字母要相同，
        //2、中间如果有奇数个字符的话，则除掉中间的字符的字符两边要对称
        //3、如果是偶数个字符的话，则要保证左右两边对称即可。

    /*算法：
    1.遍历字符串中的各个字符，
        栈为空时，将字符压入栈，
        如果当前字符跟栈中的某一个字符相同，则截取栈中字符直到相同字符处，
            如果长度是偶数，则一分为2，查看左右两个子串是否完全相同，若相同，则是回文子串，并保留长度
            如果长度是奇数，则找到中间字符，查看左右两个子串是否完全相同，若相同，表示是回文子串
            然后将这些字符重新压入栈中找下一个字符，继续查找下一个直到结束。*/
    $arrStack = [];
    $arrResult = [];
    $len = strlen($input);
    for ($i=0; $i<$len; ++$i) {
        if (count($arrStack) == 0) {
            array_push($arrStack, $input[$i]);
        } else {
            //如果之前的数组里存在当前字符，则有可能是回文子串
            $retArr = array_keys($arrStack, $input[$i], true);
//            echo "curr:{$input[$i]}, arrStack:".json_encode($arrStack).",retArr:".var_export($retArr, true)."\n";
            if (!empty($retArr)) {
                $retCount = count($retArr);
                array_push($arrStack, $input[$i]);
                for ($j=0; $j<$retCount; ++$j) {
                    $arrTmp = array_slice($arrStack, $retArr[$j]);
//                    echo "pos:{$retCount[$j]},arrTmp:".var_export($arrTmp, true)."\n";

                    $isRecycleStr = checkIsRecycle($arrTmp);
                    if ($isRecycleStr) {
                        $arrResult[count($arrTmp)][] = implode('', $arrTmp);
                    }
                }
            }else {
                array_push($arrStack, $input[$i]);
            }
        }
    }

//    krsort($arrResult);
//    print_r($arrResult[0]);
    $maxKey = max(array_keys($arrResult));
    return $arrResult[$maxKey];
}

/**
 * 判断数组中的文字是否为回文字符串
 * @param $arrInput
 * @return bool
 */
function checkIsRecycle($arrInput)
{
    $count = count($arrInput);
    if ($count %2 == 0) {
        $middle = $count/2;
    } else {
        $middle = floor($count/2);
    }
    $result = true;
    for ($i=0; $i<$middle; ++$i) {
        if ($arrInput[$i] !== $arrInput[$count-1-$i]) {
            return false;
        }
    }
    return $result;
}

$begin = microtime(true);
$output = longestRecycleStr($input);

print_r($output);
echo "耗时：".(microtime(true)-$begin)."s,最长回文子串长度为：".strlen($output[0]);

