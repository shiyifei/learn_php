<?php
/**
 * 假设这个页面的访问路径是: http://example.com/operator.php
 */

class Operator
{
    public function Add(int $a, int $b) :int
    {
        return $a + $b;
    }

    public function Sub(int $a, int $b) :int
    {
        return $a - $b;
    }
}

$server = new Yar_Server(new Operator());
$server->handle();
