<html>
<head>
	<title>testing pdo</title>
</head>
<body>


<?php
	echo 'arrive here,111<br/>';
	$pdo = new PDO('mysql:host=192.168.3.125:3306;dbname=test','root','SYF!123');
	echo 'arrive here,222<br/>';
	$query = 'select * from users ';
	$result = $pdo->query($query);
	$rows = $result->fetchAll();
	echo '<ul>';
	foreach($rows as $row) {
		echo '<li>'.$row['username'].'&nbsp;'.$row['age'].'&nbsp;'.$row['email'].'</li>';
	}
	echo '</ul>';

?>

</body>
</html>
