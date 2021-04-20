<?php 
class Exception_Worker
{
	public function __construct() 
	{
		// echo 'in '.__METHOD__ . ",arrive here<br/>";
	}

	public function processAge($number) 
	{
		// echo 'in '.__METHOD__.",arrive here<br/>";
		if ($number > 100) {
			throw new MyException("{$number} 不能大于100\n");
		}
		if ($number < 0) {
			throw new MyException("{$number} 不能小于0\n");
		}
		if($number <= 18) {
			echo "少年\n";
		} elseif ($number <= 45) {
			echo "青年\n";
		} elseif ($number <= 60) {
			echo "中年\n";
		} else {
			echo "老年\n";
		}
	}
}	