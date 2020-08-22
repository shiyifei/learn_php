<?php
$team = [
    '张三' => [
        '刘备'=>[
            '张飞' => null,
            '关羽' => [
                '马良' => null
            ],
        ],
        '孟达'=>null
    ],
    '李四' => [
        '王五' => [
            '曹操' => ['荀攸'=>null,
                '贾诩' => null,
                '郭嘉' => null,
                '程昱' => null,
            ],
            '张郃' => null,
        ]
    ],
];
/**
 * 要求：根据组织结构输出每个人的所有下属
 * 比如：根据示例中的组织结构可以构造出：
 *  张三： 刘备,张飞，关羽，马良，孟达
 *  刘备：张飞、关羽、马良
 *  张飞：
 *  关羽：马良
 *  马良：
 *  孟达：
 */



/*$items = [
    '刘备' => [
        '张飞' => null,
        '关羽' => [
            '马良' => null,
            '关平' => null,
        ],
    ]
];*/

$result = [];
function getChild($parentKey, $values, &$result) {
    if (empty($values)) {
        $result[$parentKey] = '';
        return;
    }
    foreach ($values as $k => $v) {
//        echo "k:{$k}， v:{$v}<br/>";
        if (empty($v)) {
            $result[$k] = '';
            $result[$parentKey][] = $k;
        } else {
            getChild($k, $v, $result);
            $result[$parentKey][] = $k;
            $result[$parentKey] = array_merge($result[$parentKey], $result[$k]);
        }
    }
}
foreach($team as $k => $v) {
    getChild($k, $v, $result);
}

//print_r($result);

foreach($result as $k => $v) {
    if (empty($v)) {
        echo $k.':'.$v."<br/>";
    } else {
        echo $k.':';
        foreach($v as $child) {
            echo $child."&nbsp;&nbsp;";
        }
        echo "<br/>";
    }
}
