<?php
error_reporting(E_ALL);

/**
 * 测试PHP自带的SPLQueue性能，与现有的array做一下对比
 */

class Test 
{
	function abc($a ,$b) {
		return $a + $b;
	}

	/**
	 * 写入10万条数据并弹出，测试性能
	 * @return [type] [description]
	 */
	function testSplQueue()
	{
		$splq = new SplQueue();
		for ($i=0; $i<1000000; ++$i) {
			$data = "hello {$i} \n";
			$splq->enqueue($data);

			if($i%100 == 99 && count($splq)>100) {
				$random = mt_rand(10,99);
				for($j=0; $j<$random; ++$j) {
					$splq->shift();
				}			
			}

		}

		$count = count($splq);

		for($j=0; $j<$count; ++$j) {
			$splq->dequeue();
		}
	}


	/**
	 * 写入10万条数据并弹出，测试性能
	 * @return void
	 */
	function testArrQueue()
	{
		$arrQ = array();
		for ($i=0; $i<100000; ++$i) {
			$data = "hello {$i} \n";
			array_push($arrQ, $data);

			if ($i%100 == 99 && count($arrQ)>100) {
				$random = mt_rand(10,99);
				for($j=0; $j<$random; ++$j) {
					array_shift($arrQ);
				}			
			}

		}

		$count = count($arrQ);

		for($j=0; $j<$count; ++$j)  {
			array_shift($arrQ);
		}
	}

	/**
	 * 写入10万条数据并弹出，测试性能
	 * @return [type] [description]
	 */
	function testSplStack()
	{
		$stack = new SplStack();
		for ($i=0; $i<1000000; ++$i) {
			$data = "hello {$i} \n";
			$stack->push($data);

			if($i%100 == 99 && count($stack)>100) {
				$random = mt_rand(10,99);
				for($j=0; $j<$random; ++$j) {
					$stack->shift();
				}			
			}

		}

		$count = count($stack);

		for ($j=0; $j<$count; ++$j) {
			$stack->pop();
		}
	}


	/**
	 * 写入10万条数据并弹出，测试性能
	 * @return void
	 */
	function testArrStack()
	{
		$arrQ = array();
		for ($i=0; $i<100000; ++$i) {
			$data = "hello {$i} \n";
			array_push($arrQ, $data);

			if ($i%100 == 99 && count($arrQ)>100) {
				$random = mt_rand(10,99);
				for($j=0; $j<$random; ++$j) {
					array_shift($arrQ);
				}			
			}

		}

		$count = count($arrQ);

		for($j=0; $j<$count; ++$j)  {
			array_pop($arrQ);
		}
	}


	function sayHello()
	{
		echo "hello, shiyf<hr/>";
	}

	/**
	 * 在一个单独的方法里测试两个队列的性能
	 * @param  string $callback 调用的方法名
	 * @return 耗费时长 以ms为单位
	 */
	function testInterval($callback) 
	{
		$begin = microtime(true);
		call_user_func(array($this, $callback));	
		$interval = round((microtime(true) - $begin)*1000, 2);
		return $interval;
	}
}

/*$obj = new Test();
$output = $obj->abc(1,124);
echo "arrive here,output={$output}<hr/>";
echo 'sayHello spend time: '.$obj->testInterval('sayHello').'ms <hr/>';
echo 'splQueue spend time: '.$obj->testInterval('testSplQueue').'ms <hr/>';
echo 'arrQueue spend time: '.$obj->testInterval('testArrQueue').'ms <hr/>';
echo 'splStack spend time: '.$obj->testInterval('testSplStack').'ms <hr/>';
echo 'arrStack spend time: '.$obj->testInterval('testArrStack').'ms <hr/>';*/

//如何删除SplQueue中的元素呢？
$splQueue = new SplQueue();
$splQueue->enqueue('hello');
$splQueue->enqueue('shiyf');
$splQueue->enqueue('what');
$splQueue->enqueue('are');
$splQueue->enqueue('you');
$splQueue->enqueue('doing');
$splQueue->enqueue('now?');
var_dump($splQueue);
//用unset()方法,可以删除成功
unset($splQueue[3]);
echo 'after unset()，queue is:';
var_dump($splQueue)."\n";

//用SplQueue->offsetUnset()方法，也可以删除成功
$splQueue->offsetUnset(2);
echo 'after offsetUnset()，queue is:';
var_dump($splQueue)."\n";

/**
 * 结果显示如下：
 * 
 	sayHello spend time: 0ms
	splQueue spend time: 150.8ms
	arrQueue spend time: 5289.13ms
	splStack spend time: 141.67ms
	arrStack spend time: 2761.9ms

	从结果上来看性能上splQueue是array的 34倍，splStack是array的近22倍 
	相当可观啊，以后用到队列的地方就用SPLQueue就对了
	该用栈的时候就直接用SPLStack就可以了
 */