<html>
<head>
	<title>testing pdo</title>
</head>
<body>


<?php
	echo 'arrive here,111<br/>';
	$pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=test','root','SYF!123mysql');
	echo 'arrive here,222<br/>';
	$query = 'select * from users ';
	$result = $pdo->query($query);
	$rows = $result->fetchAll();
	echo '<ul>';
	foreach($rows as $row) {
		echo '<li>'.$row['username'].'&nbsp;'.$row['age'].'&nbsp;'.$row['email'].'</li>';
	}
	echo '</ul>';


	$times = 100000;
	$values = [];
	$comment = '带看人服务周到，态度亲切，技术也不错，工作态度值得称赞';

	for ($i=11; $i<=$times; ++$i) {
		$values[] = '('.($i%100).','.($i%200).','.($i%300).','.($i%6).',"'.$comment.'称赞'.($i%10).'次")';
	}
	$strValues = implode(',', $values);


	 //将用户禁用写入数据库
    try {
        $query = 'insert into order_comment(order_id,user_id,employee_id,level,comment) values ';
        $query .= $strValues;

        $result = $pdo->exec($query);
        if (!$result) {
            echo '写入数据失败';
            return;
        }        
    } catch(PDOException $e) {
        $retArr['code'] = '500';
        $retArr['msg'] = $e->getCode().'|'.$e->getMessage().'<br/>';
        echo json_encode($retArr);
        return;
    }
    echo '写入数据成功';
    ;




?>

</body>
</html>
