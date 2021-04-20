<?php

//测试主节点读写
$objCluster = new RedisCluster(null, ['192.168.56.105:7000', '192.168.56.101:7000', '192.168.56.103:7000']);

$objCluster->lPush('wuguo', 'sunquan');
$objCluster->lPush('wuguo', 'zhouyu');
$objCluster->lPush('wuguo', 'lvmeng');
$objCluster->lPush('wuguo', 'luxun');
$wuguoPersons = $objCluster->lRange('wuguo', 0, -1);

$objCluster->sAdd('weiguo', 'caocao');
$objCluster->sAdd('weiguo', 'guojia');
$objCluster->sAdd('weiguo', 'simayi');
$objCluster->sAdd('weiguo', 'zhangliao');
$weiguoPersons = $objCluster->sMembers('weiguo');

$objCluster->set('person1', 'liubei');
$objCluster->set('person2', 'guanyu');
$objCluster->set('person3', 'zhangfei');
$objCluster->set('person4', 'zhugeliang');
$objCluster->set('person5', 'zhaoyun');
$objCluster->set('person6', 'machao');

$name1 = $objCluster->get('person1');
$name2 = $objCluster->get('person2');
$name3 = $objCluster->get('person3');
$name4 = $objCluster->get('person4');
$name5 = $objCluster->get('person5');
$name6 = $objCluster->get('person6');

echo '<hr/>以下是主节点写入的内容：';
var_dump($name1, $name2, $name3, $name4, $name5, $name6, $wuguoPersons, $weiguoPersons);

echo '<hr/>';

//测试redis事务
try {
    $objCluster->multi();    
    //$objCluster->get('person1'); //加入这一句可能会报错，因为读写没有在一个主节点上操作。
    $objCluster->set('{shuguo}:person7', 'huangzhong');
    $objCluster->set('{shuguo}:person8', 'weiyan');
    $objCluster->get('{shuguo}:person7');
    $objCluster->get('{shuguo}:person8');
    var_dump($objCluster->exec());
    echo '<br/>';
} catch (Exception $e) {
    echo $e->getMessage().'<br/>';
}




//测试从节点读
$objCluster = new RedisCluster(null, ['192.168.56.105:7001', '192.168.56.101:7001', '192.168.56.103:7001']);

$name1 = $objCluster->get('person1');
$name2 = $objCluster->get('person2');
$name3 = $objCluster->get('person3');
$name4 = $objCluster->get('person4');
$name5 = $objCluster->get('person5');
$name6 = $objCluster->get('person6');

echo '<hr/>以下是从节点读取的内容：';
var_dump($name1, $name2, $name3, $name4, $name5, $name6, $wuguoPersons, $weiguoPersons);
echo '<hr/>';

//测试获取主节点
foreach ($objCluster->_masters() as $arrMaster) {
    echo 'master node:' . implode(':', $arrMaster).'<br/>';
}


