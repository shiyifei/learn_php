<?php

error_reporting(E_ALL);

include './MyException.php';
include './exception_worker.php';
include './exception_A.php';


$a = class_exists('MyException');
var_dump($a);

$b = new MyException("arayouok");
echo 'MyException:'.$b->getMessage().'<br/>';




class Exception_B
{
	private $worker;
	public function __construct(Exception_A $worker) 
	{
		$this->worker = $worker;
	}

	private function processAge($number) 
	{
		// echo 'in '.__METHOD__.",arrive here<br/>";
		$this->worker->processAge($number);
	}

	public function process($age)
	{
		$this->processAge($age);
	}
}

$worker = new Exception_Worker();
$testA = new Exception_A($worker);

$test = new Exception_B($testA);
try{
	$ages = [12,43,56,75,121];
	foreach($ages as $age) {
		$test->process($age);
	}

} catch(Exception $e) {
	echo 'Exception:'.$e->getMessage();
	exit;
}


