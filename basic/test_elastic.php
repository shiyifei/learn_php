<?php
error_reporting(E_ALL);
// echo '111<hr/>';
require "./vendor/autoload.php";
$hosts = ['192.168.56.105:9200', '192.168.56.101:9200', '192.168.56.103:9200'];

$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
// echo '222<hr/>';
// var_dump($client);

//插入单条记录
$params = [
    'index' => 'index',
    'type' => 'fulltext',
    'id' => 55,
    'body' => ['content' => '优信集团点点滴滴']
];
$response = $client->index($params);
print_r($response);
echo '<hr/>';

//批量插入
/*$params = [ 'body' =>[]];
for ($i=43; $i <= 50; ++$i) {
    $params['body'][] = [
        'index' => ['_index'=>'index', '_type'=>'fulltext', '_id' => $i],
    ];
    $params['body'][] = [
        'content' => '祝愿我们公司的股票涨幅达到' . $i . '%'
    ];
}
echo json_encode($params);

$responses = $client->bulk($params);
print_r($responses);
echo '<hr/>';*/

//更新一条记录
// $params = ['index' => 'index', 'type' => 'fulltext', 'id' => 15, 
//         'body' => [ 'doc'=>['content'=>'最近优信集团的股票已经跌到4美元以下了']  ]
//         ];
// $ret = $client->update($params);
// print_r($ret);
// echo '<hr/>';

//删除一条记录
/*$params = ['index' => 'index', 'type' => 'fulltext', 'id' => 15];
$ret = $client->delete($params);
print_r($ret);
echo '<hr/>';*/


//立即取回结果没有问题
try {
    $params = ['index' => 'index', 'type' => 'fulltext', 'id' => 55];
    $response = $client->get($params);

} catch (\Exception $e) {
    echo $e->getMessage();
}
print_r($response);
echo '<hr/>';

//之前写入的数据能够被查到
/*$params = [
    'index'=>'index',
    'type' => 'fulltext',
    'body' => [
        'query'=>[
            'match' => ['content'=>'韩']
        ]
    ],
];
$response = $client->search($params);
print_r($response);
echo '<hr/>';*/

//刚写入的数据在这里搜索不到，全文搜索 必须等1s后才可以 (在这里体现近实时)
$params = [
    'index'=>'index',
    'type' => 'fulltext',
    'body' => [
        'query'=>[
            'match' => ['content'=>'优信集团']
        ]
    ],
];
$response = $client->search($params);
print_r($response);
echo '<hr/>';
return;

//刚写入的数据在这里搜索不到，精确匹配 必须等1s后才可以 (在这里体现近实时)
/*$params = [
    'index'=>'index',
    'type' => 'fulltext',
    'body' => [
        'query'=>[
            "constant_score" => [
                'filter' => ['term' => ['_id'=>52]]
            ]
        ]
    ],
];
$response = $client->search($params);
print_r($response);
echo '<hr/>';*/



include 'config.php';
//模拟将数据从数据库写入到es中
$dbConn = new PDO('mysql:host='.$dbTest['host'].';dbname='.$dbTest['dbname'], $dbTest['user'], $dbTest['pass']);
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'select * from (SELECT * FROM (
                    select
                    md.rent_status,md.brandid,md.seriesid,md.modeid,md.dealerid,
                    md.rent_price,md.back_price,c.cityid,md.type,de.dealername,ct.cityname,
                    de.dealertype,md.createtime
                    FROM finance_rent_mode_dealer as md
                    LEFT JOIN finance_rent_project_package  as p ON md.dealerid = p.dealerid AND md.modeid = p.modeid
                    LEFT JOIN finance_rent_project_city  as c  ON c.rent_project_id = p.rent_project_id
                    LEFT JOIN finance_rent_project_detail as pd ON pd.rent_project_id = p.rent_project_id and p.id = pd.project_pkg_id
                    left join dealer de on de.dealerid = md.dealerid
                    left join city ct on ct.cityid = c.cityid
                     where 1  
                    and md.rent_status = 1
                    and md.type in (2)
                    AND md.`status` = 1
                    AND md.is_have_project = 1
                    AND md.is_have_series_pic = 1
                    AND md.is_have_bright = 1
                    AND p.package_type=1
                    AND p.`status` =3
                    AND c.`status` =1
                    AND pd.`status` =1
                    AND pd.structure_type = 1
                    AND pd.term_month  = 48
                    AND pd.is_available = 1
                    ORDER BY  md.type DESC,md.createtime ASC
                ) as g
                GROUP BY modeid,cityid,rent_status) aa';
$params = ['body'=>[]];
$i = 1;
foreach ($dbConn->query($sql) as $row) {
    $params['body'][] = [
        'index' => ['_index'=>'car_saling', '_type'=>'source', '_id' => $i],
    ];
    $data = [];
    $data['dealerid'] = $row['dealerid'];
    $data['brandid'] = $row['brandid'];
    $data['seriesid'] = $row['seriesid'];
    $data['modeid'] = $row['modeid'];
    $data['rent_status'] = $row['rent_status'];
    $data['type'] = $row['type'];
    $data['dealername'] = $row['dealername'];
    $data['rent_price'] = $row['rent_price'];
    $data['back_price'] = $row['back_price'];
    $data['cityid'] = $row['cityid'];
    $data['dealertype'] = $row['dealertype'];
    $data['createtime'] = $row['createtime'];
    $params['body'][] = $data;
    ++$i;
}
// echo json_encode($params);

//$responses = $client->bulk($params);
//print_r($responses);
echo '<hr/>';


//查询在北京售卖奥迪A4的店铺Id, 精确匹配,查询20条
/*$params = [
    'index'=>'car_saling',
    'type' => 'source',
    'size' => 20,
    'body' => [
        'query'=>[
            "constant_score" => [
                'filter' => [
                    "bool"=>[
                        "must"=>[
                            ["term"=>["seriesid"=>621]],
                            ["term"=>["type"=>2]],
                            ["term"=>["cityid"=>201]],
                        ]
                    ]
                ]
            ]
        ],
        "sort" => ["createtime"=>["order"=>"asc"]],                 //按照创建时间升序排列
        '_source'=>['dealerid', 'dealername', 'createtime']         //只查询出店铺相关字段即可
    ],

];
$response = $client->search($params);
print_r($response);
echo '<hr/>';
*/


//查询在北京售卖奥迪A4L车型的4S店铺，店铺名称中有“袁亚杰”三个字, 全文搜索
$params = [
    'index'=>'car_saling',
    'type' => 'source',
    'size' => 20,
    'body' => [
        'query'=>[
            "bool"=>[
                "must"=>[
                    ["term"=>["seriesid"=>621]],
                    ["term"=>["type"=>2]],
                    ["term"=>["cityid"=>201]],
                ],
                "should"=>[
                    ["match" => ["dealername"=>"袁亚杰"]]
                ]
            ]
        ],
        "sort" => ["_score"=>["order"=>"desc"], "createtime"=>["order"=>"asc"]],
    ],
];
$response = $client->search($params);
print_r($response);
echo '<hr/>';
