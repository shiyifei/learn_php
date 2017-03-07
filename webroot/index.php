<?php
	echo "arrive here<br/>";
	var_dump($_GET);
	$controll_action = $_GET['_ca_'];
	
	define('APP_PATH','/home/www/');

	echo APP_PATH;


	$params  = explode('/',$controll_action);
	$params_count = count($params);

	$otherParams = $params;
	if($params_count>1) {
		$controller = $params[0];
		$action  = $params[1];
		unset($params[0]);
		unset($params[1]);
	}else if($params_count==1) {
		$controller = $params[0];
		$action = 'index';
		unset($params[0]);
	}

	$filename = strtolower($controller).'.php';
	$controller_path = APP_PATH .'application/controllers/';

	if(!file_exists($controller_path.$filename)) {
		throw new Exception('controller '.$filename.' is not exists!');
		return;
	}
	include($controller_path.$filename);

	$classname = ucfirst($controller);
	if(!class_exists($classname)) {
		throw new Excpetion('class '.$classname.' is not exists!');
	}
	$reflecObj = new ReflectionClass($classname);
	if(!$reflecObj->hasMethod($action)){
		throw new Exception('method '.$action.' is not exists!');
	}

	$currentObj = new $classname();
	echo "classname=$classname,action=$action,params=".json_encode($params)."<br/>";
	call_user_func_array([$currentObj,$action],$params);
	return;
?>
