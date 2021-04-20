<?php
header('Content-Type: text/html;charset=utf-8');
	//操作redis的类
class Test_redis 
{
	private $redis = null;
	public function __construct() {
		$this->redis = new Redis();
		$this->redis->connect('192.168.1.102',6380);
	}
	
	public function test() {	
		$this->redis->set('name','areyouok');
		$output = $this->redis->get('name');
		var_dump($output);
	}
	/**
	 * 测试方法是否存在
	 * @param $methodName string 方法名
	 */
	public function isMethodExist($methodName)
	{
		$refObj = new ReflectionClass($this->redis);
		//var_dump($refObj->getMethods());
		$result = $refObj->hasMethod($methodName);
		var_dump($result);
	}
	
	/**
	 * 使用setnx模拟并发抢购加锁机制
	 * @param int $orderId 拍卖的单据号
	 */
	public function  buy_lock($orderId, $userId)
	{		
		$ttl  = 10;
		$orderId = (int) $orderId;
		$userId = (int) $userId;
		$key = 'lock_order_'.$orderId;	//针对该订单号加锁

		$ok = $this->redis->setnx($key, 1); //如果key写入值则返回false
		echo 'ok:';
		var_dump($ok);
		if ($ok) {
			$this->redis->expire($key,$ttl);		//设置key在10秒后失效，假定针对此次竞拍
			echo "redis get({$key})".$this->redis->get($key)."<br/>";
			echo "setnx {$key} is successful<br/>";
		    /*$ret = $this->update();			//模拟将订单写入数据库，执行方法后成功返回true,失败返回false
		    echo 'return:'.$ret.'<br/>';
		    if ($ret) {		    	
		        	$this->redis->del($key);		//执行成功后删除当前key,以便于下次
		    }		    */
		} else {
			echo "setnx {$key} is failed<br/>";
		}
	}
	

	/**
	 * 测试redis消息订阅功能
	 */
	public function test_subscribe()
	{
		$this->redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
		echo '222';
		$this->redis->subscribe(array('redischat'), array($this, 'process_msg'));
	}

	public function process_msg($redis, $chan, $msg)
	{
		echo $chan.'|'.$msg."\n";
	}

	/**
	 * 测试redis发布消息功能
	 */
	public function test_publish()
	{
		for($i=1; $i<10; ++$i) {
			$this->redis->publish('redischat', 'user'.$i.' is bidding, price is:'.(7000+$i*200));
		}
	}

	/**
	 * 模拟将竞拍出价写入数据库的过程
	 */
	private function update()
	{
		//usleep(1000);
		$random = mt_rand(1,100000);
		if($random % 2 === 0) {
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 操作list类型
	 */
	public function operateList()
	{
		$task1Key = 'needToProcess';
		$task2Key = 'Processing';
		
		$taskArr = array(['exec_time'=>1,'orderId'=>'123'],['exec_time'=>1,'orderId'=>'124'],['exec_time'=>1,'orderId'=>'125'],['exec_time'=>1,'orderId'=>'126']);
		foreach($taskArr as $task) {
			$this->redis->rpush($task1Key,json_encode($task));
		}
		$taskList = $this->redis->lRange($task1Key,0,-1);
		foreach($taskList as $taskJson) {
			$this->redis->rPush($task2Key,$taskJson);		//将内容插入另一个队列
			$this->redis->lRem($task1Key,$taskJson,1);		//从队列中删除一项
		}
		echo 'after processing<br/>';
		$taskList = $this->redis->lRange($task1Key,0,-1);
		var_dump($taskList);
		echo '<br/>';
		$task2List = $this->redis->lRange($task2Key,0,-1);
		var_dump($task2List);				
	}

	/**
	 * 模拟用户1竞拍出价并发场景
	 * 在watch与multi操作之间如果有其他人竞拍出价，则本次出价不能成功
	 * sleep(3) 这个操作模拟用户1出价处理比较慢的场景
	 * @param $orderId 订单Id
	 * @param $price  用户1的竞拍报价
	 */
	public function user1_buy($orderId, $price)
	{
		$key = 'order:'.$orderId;
		$value = $this->redis->get($key);
		echo 'in '.__METHOD__.',before providing price:'.$price.',the price is:'.$value.'<br/>';

		$this->redis->watch($key);//添加对该订单当前报价的监控
		sleep(3);						
		$result = $this->redis->multi()->set($key, $price)->exec(); //从multi()方法到exec()方法的一系列步骤可以看做是一个原子操作
		var_dump($result);
		if ($result) {
			echo '竞拍出价成功<br/>';
		} else {
			echo '竞拍出价失败,出价过程中已经有其他人出价<br/>';	//在watch()与multi()中间有其他人成功出价的话，则本次操作会失败回滚
		}
		//$this->redis->unwatch();									//unwatch()方法此时不再需要，因为执行EXEC命令后redis会取消对所有键的监控
		$value = $this->redis->get($key);
		echo 'in '.__METHOD__.',after providing price,the price is:'.$value.'<br/>';
	}

	/**
	 * 模拟用户2拍卖出价并发场景
	 * @param $orderId 订单Id
	 * @param $price  用户1的竞拍报价
	 */
	public function user2_buy($orderId, $price)
	{
		$key = 'order:'.$orderId;
		$value = $this->redis->get($key);
		echo 'in '.__METHOD__.',before providing price:'.$price.',the price is:'.$value.'<br/>';

		$this->redis->watch($key);
		$result = $this->redis->multi()->set($key, $price)->exec();
		var_dump($result);
		if ($result) {
			echo '竞拍出价成功<br/>';
		} else {
			echo '竞拍出价失败,出价过程中已经有其他人出价<br/>';
		}
		$value = $this->redis->get($key);
		echo 'in '.__METHOD__.',after providing price,the price is:'.$value.'<br/>';
	}

	public function test_setnx() {
		$isOk = $this->redis->setnx('order:1',1);
		var_dump($isOk);
		if (!$isOk) {
			echo "order:1 have processed<br/>";
		} else {
			echo "order:1 processing<br/>";
		}
	}

	public function test_watch1() {
		$watchKey = 'vin:orderId:6533';
		$this->redis->set($watchKey, uniqid());
		//$this->redis->expire($watchKey, 180);

		$this->redis->watch($watchKey);
		//todo:插入数据库操作
		sleep(1);

		$result = $this->redis->multi()->set('a',1)->get($watchKey)->exec();
		var_dump($result);

	}
	public function test_watch2() {		
		$watchKey = 'vin:orderId:6533';
		$this->redis->set($watchKey, uniqid());
		//$this->redis->expire($watchKey, 180);
		//sleep(1);
		$this->redis->watch($watchKey);
		//todo:插入数据库操作
		sleep(1);

		$result = $this->redis->multi()->set('a',1)->get($watchKey)->exec();
		var_dump($result);

	}
	
}


$testRedis  = new Test_redis();

if(isset($argv) && count($argv)>1) {
	$action = $argv[1];
}else {
	$action = htmlentities($_GET['action']);
}

switch($action) {
	case 'test':
		$testRedis->test();
		break;
	case 'buy_lock':
		$orderId= 1;
		$userId= mt_rand(1,1000);
		$testRedis->buy_lock($orderId, $userId);
		break;
	case 'session1':
		$testRedis->write_lock_session1();
		break;
	case 'session2':
		$testRedis->write_lock_session2();
		break;
	case 'ismethodexist':	//测试方法是否存在
		$methodName = $_GET['method'];
		echo $methodName.':';
		$testRedis->isMethodExist($methodName);
		break;
	case 'subscribe':	//测试订阅消息
		$testRedis->test_subscribe();
		break;
	case 'publish':		//测试发布消息
		$testRedis->test_publish();
		break;
	case 'operateList':	//测试操作redisList
		$testRedis->operateList();
		break;
	case 'user1_buy':	//模拟用户1竞拍出价
		$testRedis->user1_buy(2,650);	
		break;
	case 'user2_buy':	//模拟用户2竞拍出价
		$testRedis->user2_buy(2,660);
		break;
	case 'setnx':
		$testRedis->test_setnx();
		break;
	case 'watch1':
		$testRedis->test_watch1();
		break;
	case 'watch2':
		$testRedis->test_watch2();
		break;
	default:
		break;

}
