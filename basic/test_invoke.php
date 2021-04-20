<?php


class Basic {
    public $varBasic;
    public function __construct() {
        echo "in Basic 构造函数<br/>";
        $this->varBasic = 'what are you doing now?';
    }
}


class A extends Basic {
    function __invoke($input) {
        $this->varBasic = $input;
        echo "I am in Basic::_invoke(), input={$input}, varBasic=".$this->varBasic."<br/>";
    }

    public function __construct(){
        echo 'I am in A::consruct() varBasic='.$this->varBasic.'<br/>';
    }
}


$objectA = new A;
$objectA(512);

// $aa = $objectA(256);

var_dump($objectA->varBasic);


var_dump(is_callable($objectA));


echo '<hr/>';



