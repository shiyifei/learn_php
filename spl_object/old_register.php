<?php
/**
 * 这里讲解如何通过 __autoloader方法实现类的自动加载
 */

//测试 session_start()放在加载器的前面的写法
session_start();

/**
 * 按照类名加载相关类
 * @param $className
 */
function __autoload($className)
{
    include('model/' . $className . '.php');
    include('logic/' . $className . '.php');
}

$ret = class_exists('ModelA');
echo 'before __autoload(), class ModelA is exists:'.var_export($ret, true).'<hr/>';


$objA = new ModelA();

$ret = class_exists('ModelA');
echo 'after __autoload(), class ModelA is exists:'.var_export($ret, true).'<hr/>';


$ret = class_exists('LogicA');
echo 'before __autoload(), class LogicA is exists:'.var_export($ret, true).'<hr/>';


$objA = new LogicA();

$ret = class_exists('LogicA');
echo 'after __autoload(), class LogicA is exists:'.var_export($ret, true).'<hr/>';