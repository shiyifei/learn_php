<?php

/**
 * 一个简单的php算法题： 要求只获取那些不重复的数据，要保证高效率 *
 */

$input = [1,1,2,2,5,5,1,6,2,3,7,7,9,9];

for ($i=0; $i<100000; ++$i) {
    $input[] = mt_rand(0,9999);
}

/**
 * 自己写方法统计每个元素出现的次数
 * @param $input
 * @return array
 */
function removeRepeatMethod1($input)
{
    echo "begin use memory is:". memory_get_usage().'m <br/>';
    $output = [];
    foreach ($input as $k => $v) {
        //不要用in_array而要用 array_key_exists
        if (array_key_exists($v, $output)) {
            if ($output[$v] > 1 ) {
                continue;
            }
            ++$output[$v];
        } else {
            $output[$v] = 1;
        }
    }
    $result = [];
    foreach ($output as $k => $v) {
        if ($v == 1) {
            $result[] = $k;
        }
    }
    echo "end use memory is:". memory_get_usage().'m <br/>';
    return $result;
}

/**
 * 用系统提供的统计元素出现次数的方法才是最高效的，array_count_values
 * @param $input
 * @return array
 */
function removeRepeatMethod2($input)
{
    echo "begin use memory is:". memory_get_usage().'<br/>';
    $arr = array_count_values($input);
    $result = [];
    foreach($arr as $k => $v) {
        if ($v === 1) {
            $result[] = $k;
        }
    }
    echo "end use memory is:". memory_get_usage().'<br/>';
    return $result;
}

$begin = microtime(true);
$result = removeRepeatMethod1($input);
echo "spend time is:".(microtime(true)-$begin).'s <br/>';
echo "use memory is:". memory_get_usage().'<br/>';
print_r($result);
echo '<hr/>';

$begin = microtime(true);
$result = removeRepeatMethod2($input);
echo "spend time is:".(microtime(true)-$begin).'s <br/>';
echo "use memory is:". memory_get_usage().'<br/>';
print_r($result);







