<?php

class MyException extends Exception
{
	public function __construct($message = '', $code = 0, Exception $previous = NULL )
	{
		// echo 'in '.__METHOD__.",arrive here<br/>";
		parent::__construct($message, $code, $previous);
	}
}