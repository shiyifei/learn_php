<?php 

class Exception_A 
{
	private  $worker;
	public function __construct(Exception_Worker $worker) 
	{
		$this->worker = $worker;
	}

	public function processAge($number) 
	{
		// echo 'in '.__METHOD__.",arrive here<br/>";
		return $this->worker->processAge($number);
	}
}	