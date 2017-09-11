<?php
	class User 
	{
		protected $id;
		public $username;
		protected $email;
		protected $age;


		public function __construct($username,$email,$age)
		{
			header("Content-type:text/html;charset=utf-8;");
			$this->username = $username;
			$this->email = $email;
			$this->age = $age;
		}

		public function objectToArray($object)
		{
			echo '对象数组转化:'.__METHOD__.',输入:'.$object->username."\n";
			$result = [];
			if(is_object($object)) {
				foreach($object as $key=>$value) {
					$result[$key] = $value;
	 			}
			}
			echo '对象数组转化:'.__METHOD__.',输出:'.json_encode($result)."\n";
			return $result;
		}
	}




	$userObj = new User('wangzhongwei','wangzhongwei@1.com',33);


	var_dump($userObj);

	echo '<br/>';
	$objArr = $userObj->objectToArray($userObj);
	echo json_encode($objArr);