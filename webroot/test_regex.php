<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2017-02-13 17:05:08
 * @version $Id$
 */

class Test_regex {

	var $pattern1 = '/([0-9]{2})([0-9]{2})([a-zA-Z]{2})/';
	var $pattern2 = '/(?:[0-9]{2})(?:[0-9]{2})(?:[a-zA-Z]{2})/';
	var $pattern3 = '/(?=[0-9]{2})(?=[0-9]{2})(?=[a-zA-Z]{2})/';
    
    function __construct(){
        
    }

    public function testMatch1($input) {
    	$output = preg_match($this->pattern1,$input,$matches);
    	var_dump($matches);
    }

    public function testMatch2($input) {
    	$output = preg_match($this->pattern2,$input,$matches);
    	var_dump($matches);
    }

    public function testMatch3($input) {
    	$output = preg_match($this->pattern3,$input,$matches);
    	var_dump($matches);
    }
}

$test_regex = new Test_regex();
$input = htmlentities($_GET['input']);
var_dump($input);
echo "<hr/>";
$test_regex->testMatch1($input);
echo "<hr/>";
$test_regex->testMatch2($input);
echo "<hr/>";
$test_regex->testMatch3($input);
echo "<hr/>";

echo "why not?<br/>";
preg_match('/^([0-9]{2})([0-9]{2})([a-zA-Z]{2})$/',$input,$matches);
var_dump($matches);
echo "<br/>";

$pregA = '/(o+)/';
$pregB = '/(o+?)/';
$input = 'zooood';
preg_match($pregA,$input,$matches);
var_dump($matches);
echo "<br/>";
preg_match($pregB,$input,$matches);
var_dump($matches);
echo "<br/>";

$appId = 'android';
$sessionId = '20710baf11bd8107165a189362bb8d2af16b86c8fad40163b309466faedb645e';
$time = '1487151826';
$key = 'erfdsfaeb25f716at0a859efb6zdc26078hgf9ddwp9d50e374';
echo md5($sessionId.$appId.$time.$key)."<br/>";
echo 'dc793d7c8391ae6531ed79915e8367aa';


