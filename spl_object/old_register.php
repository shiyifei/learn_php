<?php
/**
 * 这里讲解如何通过 __autoloader方法实现类的自动加载
 */

//测试 session_start()放在加载器的前面的写法
session_start();

$ret = get_included_files();
echo '111,before __autoload(), included files:'.var_export($ret, true).'<hr/>';

$ret = class_exists('ModelC');
echo '111, after executing class_exists("ModelC"), class ModelC is exists:'.var_export($ret, true).'<hr/>';

$ret = get_included_files();
echo '112,after executing class_exists("ModelC"), included files:'.var_export($ret, true).'<hr/>';

//$ret = new ReflectionClass('ModelA');
$ret = class_exists('ModelA');
echo '111,after executing class_exists("ModelA"), class ModelA is exists:'.var_export($ret, true).'<hr/>';

$ret = get_included_files();
echo '113,after executing class_exists("ModelC"), included files:'.var_export($ret, true).'<hr/>';

/**
 * 按照类名加载相关类
 * @param $className
 */
function __autoload($className)
{
    echo "I am in ".__FUNCTION__."<br/>";
    include('model/' . $className . '.php');
    include('logic/' . $className . '.php');
}

$ret = get_included_files();
echo '222,after __autoload(), included files:'.var_export($ret, true).'<hr/>';

$ret = class_exists('ModelA');
echo '222,after __autoload(), class ModelA is exists:'.var_export($ret, true).'<hr/>';


$objA = new ModelA();

$ret = class_exists('ModelA');
echo 'after new ModelA(), class ModelA is exists:'.var_export($ret, true).'<hr/>';


$ret = class_exists('LogicA');
echo 'before new LoginA(), class LogicA is exists:'.var_export($ret, true).'<hr/>';


$objA = new LogicA();

$ret = class_exists('LogicA');
echo 'after new LogicA(), class LogicA is exists:'.var_export($ret, true).'<hr/>';