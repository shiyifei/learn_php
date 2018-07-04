<?php

set_time_limit(0);
// Load this lib
require_once __DIR__ . '/vendor/automattic/php-thrift-sql/ThriftSQL.phar';

// Try out a Hive query via iterator object
$hive = new \ThriftSQL\Hive( '172.16.2.20', 10000, 'shiyifei', '6f6fe281ef0d8a2403676b3d4cdbab46', 10);
$hive->setSasl(true);

$begin = microtime(true);
$hiveTables = $hive
  ->connect()
  ->queryAndFetchAll('desc bigda.ba_xc_red_line_price_data');

echo '查询表结构耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
// var_dump($hiveTables);
foreach($hiveTables as $item) {
    echo $item[0].' '.$item[1].' '.$item[2].'<br/>';
}
echo '<hr/>';

$begin = microtime(true);
$sql = 'select model_id,province_id,red_line_price from bigda.ba_xc_red_line_price_data where dt_ymd=20180701 and model_id=90015674';//rn between 120000 and 121000
  $hiveTables = $hive
  ->connect()
  ->queryAndFetchAll($sql);

echo '查询1000条数据耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
var_dump($hiveTables);
echo '<hr/>';

$begin = microtime(true);
$sql = 'select max(rn) from bigda.ba_xc_red_line_price_data where dt_ymd=20180626';
  $hiveTables = $hive
  ->connect()
  ->queryAndFetchAll($sql);


echo '查询rn最大值耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
var_dump($hiveTables);
echo '<hr/>';
// Execute the Hive query and iterate over the result set
// foreach( $hiveTables as $rowNum => $row ) {
//   print_r( $row );
// }


/*// Try out an Impala query via iterator object
$impala = new \ThriftSQL\Impala( 'impala.host.local' );
$impalaTables = $impala
  ->connect()
  ->getIterator( 'SHOW TABLES' );

// Execute the Impala query and iterate over the result set
foreach( $impalaTables as $rowNum => $row ) {
  print_r( $row );
}*/

// Don't forget to close socket connection once you're done with it
$hive->disconnect();
// $impala->disconnect();