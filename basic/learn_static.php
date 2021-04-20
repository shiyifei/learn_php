<?php
/**
 *
 * @authors Your Name (you@example.org)
 * @date    2016-10-13 15:42:18
 * @version $Id$
 */

class Learn_static
{

    function __construct()
    {
    }

    function testStatic()
    {
        static $a = 0;
        echo $a;
        ++$a;
    }
}


$learn = new Learn_static();
echo "ref testStatic one times<br/>";
$learn->testStatic();
echo "<br/>";
echo "ref testStatic two times<br/>";
$learn->testStatic();
echo "<br/>";


/**结果如下：
 * ref testStatic one times  0
 * ref testStatic two times  1
 *
 **/