<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2016-10-13 15:42:18
 * @version $Id$
 */

class Learn  {
    
    function __construct(){
        
    }

    function testStatic()
    {
    	static $a = 0;
    	echo $a;
    	++$a;
    }
}


$learn = new Learn();
echo "ref testStatic ont times";
$learn->testStatic();
echo "<br/>";
echo "ref testStatic two times";
$learn->testStatic();
echo "<br/>";