<?php
/**
 *  这里实现使用定义多个spl_autoload_register方法来自动加载类
 */

//测试 session_start()放在加载器的前面的写法
session_start();


/**
 * 一种类自动加载器方法
 * @param $className
 */
function my_autoloader($className)
{
    include_once('model/' . $className . '.php');
}

$ret = class_exists('ModelB');
echo 'before spl_autoload_register(), class ModelB is exists:'.var_export($ret, true).'<hr/>';

spl_autoload_register('my_autoloader');

$ret = class_exists('ModelB');
echo 'after spl_autoload_register(), class ModelB is exists:'.var_export($ret, true).'<hr/>';

$objB = new ModelB();

$ret = class_exists('ModelB');
echo 'after new ModelA(), class ModelB is exists:'.var_export($ret, true).'<hr/>';


$ret = class_exists('LogicB');
echo 'before other spl_autoload_register(), class LogicB is exists:'.var_export($ret, true).'<hr/>';

//注册另一种加载类的方法
spl_autoload_register(function ($className) {
    include 'logic/' . $className . '.php';
});

$logicB = new LogicB();

$ret = class_exists('LogicB');
echo 'after other spl_autoload_register, class LogicB is exists:'.var_export($ret, true).'<hr/>';



