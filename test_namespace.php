<?php
namespace Test;
//使用命名空间之前需要先将相关类引入进来
include 'test_namespace1.php';
include 'test_namespace2.php';


class Person
{
	public function sayHello()
	{
		echo 'hello from local';
	}
}

//引入进来后才能用命名空间去访问
//use Server\Person;
$server_person = new \Server\Person();
$server_person->sayHello();

//use Client\Person;
$client_person = new \Client\Person();
$client_person->sayHello();


$local_person = new \Test\Person();
$local_person->sayHello();


$local_person = new Person();
$local_person->sayHello();



