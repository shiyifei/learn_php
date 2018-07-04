<?php
/**
 * Hive Package
 * @since: 2014-11-19
 * @author:ajdxz
 */
error_reporting(E_ALL);

$GLOBALS['THRIFT_ROOT'] = dirname(__FILE__).'/hive_thrift';
require_once $GLOBALS['THRIFT_ROOT'] . '/packages/hive_service/TCLIService.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TSaslClientTransport.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php';

class Hive
{
	/**
	 * TSocket
	 * @var $socket
	 */
	protected $_socket 			= null;
	
	/**
	 * TSaslClientTransport
	 * @var $transport
	 */
	protected $_transport		= null;
	
	/**
	 * TCLIServiceClient
	 * @var $client	
	 */
	protected $_client			= null;
	
	/**
	 * TBinaryProtocol
	 * @var $_protocol Object
	 */
	protected $_protocol		= null;
	
	/**
	 * response session
	 * @var $_openSessionResp
	 */
	protected $_openSessionResp	= null;
	
	/**
	 * sql operation Handle
	 * @var $_operationHandle
	 */
	protected $_operationHandle	= null; 
	
	/**
	 * Maximum rows of return
	 * @var $_maxRows
	 */
	protected $_maxRows			= 10000;
	
	/**
	 * Has more rows
	 * @var hasMoreRows bool
	 */
	protected $_hasMoreRows		= true;
	
	/**
	 * 连接Hive
	 * @param $conf
	 */
	public function connect() {
		$this->_socket = new TSocket('172.16.2.20', 10000);
		$this->_socket->setSendTimeout(10000);
		$this->_socket->setRecvTimeout(10000);  //15 s 是一个关键，如果15s未执行完，会报出异常。

		$a = class_exists('TSaslClientTransport');
		echo 'class TSaslClientTransport is exists:'.var_export($a,TRUE).'<br/>';

		$this->_transport = new TSaslClientTransport($this->_socket);
		$this->_protocol  = new TBinaryProtocol($this->_transport);
		$this->_client = new TCLIServiceClient($this->_protocol);

		// echo 'arrive here,111<br/>';

		
		try {
			$this->_transport->open();
		} catch(TTransportException $e) {
			echo 'transport Exception:'.$e->getMessage().'<br/>';
			return;
		} 
		
		// echo 'arrive here,222<br/>';

		//经过 sasl 用户验证返回 session object
		$openSessionReq = new TOpenSessionReq(array(
			'username' => 'shiyifei',
			'password' => '6f6fe281ef0d8a2403676b3d4cdbab46',
			'configuration' => null
		));

		// echo 'arrive here,333<br/>';

		$this->_openSessionResp = $this->_client->OpenSession($openSessionReq);
		// echo 'arrive here,444<br/>';

		$this->_maxRows = isset($conf['maxRows']) && is_numeric($conf['maxRows'])
					    ? $conf['maxRows'] : $this->_maxRows;
		// echo 'arrive here,555<br/>';
	}

	/**
	 * sql execute
	 * @var param $sql
	 */
	public function execute ($sql) {
		$query = new TExecuteStatementReq(array(
				"sessionHandle" => $this->_openSessionResp->sessionHandle,
				"statement" 	=> $sql,
				"confOverlay" 	=> null
		));
		try {
			$this->_operationHandle = $this->_client->ExecuteStatement($query);// add  try catch by shiyf
		} catch(Exception $e) {
			echo 'in '.__METHOD__.",exception:".$e->getMessage()."\n";
			// $backtrace = debug_backtrace();
   			//      	var_dump($backtrace);
		}
		
	}
	
	/**
	 * 获取参数
	 */
	public function fetch() {
		$rows = array();
		while ($this->_hasMoreRows) {
			// echo 'in '.__METHOD__.',arrive here<br/>';

			$rows += $this->fetchSet();
		}
		return $rows;
	}
	
	/**
	 * 执行设置
	 */
	public function fetchSet() {
		$rows = array();
		$fetchReq = new TFetchResultsReq (array(
			'operationHandle' => $this->_operationHandle->operationHandle,
			'orientation'	  => TFetchOrientation::FETCH_NEXT,
			'maxRows'		  => $this->_maxRows,
		));
		// echo 'in '.__METHOD__.',arrive here<br/>';
		return $this->_fetch($fetchReq);
	} 
	
	/**
	 * 得到数据 
	 * @param $rows
	 * @param $fetchReq
	 */
	protected function _fetch($fetchReq) {
		// echo 'in '.__METHOD__.',arrive here<br/>';
		try {
			$resultsRes = $this->_client->FetchResults($fetchReq);
		} catch (TException $e) {
			echo $e->getMessage()."\n";
		}
		
		// echo 'in '.__METHOD__.',resultRes:'.var_export($resultsRes->results->rows,TRUE)."<br/>";
		$rowData = array();
		foreach ($resultsRes->results->rows as  $key => $row) {
			$rows = array();
			foreach ($row->colVals as $colValue) {
				$rows[] =  trim($this->_getValue($colValue));
			}
			$rowData[] = $rows;
		}
		if (0 == count($resultsRes->results->rows)) {
			$this->_hasMoreRows = false;
		}
		return $rowData;
	}
	
	/**
	 * 获取返回数据中的数据
	 * @param colValue
	 */
	protected function _getValue($colValue) {
		if ($colValue->boolVal)
			return $colValue->boolVal->value;
		elseif ($colValue->byteVal)
			return $colValue->byteVal->value;
		elseif ($colValue->i16Val)
			return $colValue->i16Val->value;
		elseif ($colValue->i32Val)
			return $colValue->i32Val->value;
		elseif ($colValue->i64Val)
			return $colValue->i64Val->value;
		elseif ($colValue->doubleVal)
			return $colValue->doubleVal->value;
		elseif ($colValue->stringVal)
			return $colValue->stringVal->value;
	}
	
	/**
	 * __destruct
	 */
	function __destruct() {
		if ($this->_operationHandle) {
			$req = new TCloseOperationReq(array(
				'operationHandle' => $this->_operationHandle->operationHandle
			));
			$this->_client->CloseOperation($req);
		}
	}
}


set_time_limit(0);

$hiveObj = new Hive();
echo 'aaa<br/>';
$hiveObj->connect();
echo 'bbb<br/>';

$begin = microtime(true);
//查询一下hive表结构
$sql = 'desc bigda.ba_xc_red_line_price_data';
$result = $hiveObj->execute($sql);
echo 'ccc<br/>';
$output = $hiveObj->fetch();
echo 'ddd<br/>';
var_dump($output);
echo '查询表结构耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
echo '<hr/>';


/*
$begin = microtime(true);
//获取总条数 只获取一次就可以了
$sql = "select count(rn) from bigda.ba_xc_red_line_price_data where dt_ymd=20180619";
$hiveObj->execute($sql);
echo 'cccA<br/>';
$output = $hiveObj->fetch();
echo 'dddA<br/>';
var_dump($output);
echo '<hr/>';
echo '查询总条数耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
*/

$begin = microtime(true);
$hiveObj = new Hive();
$hiveObj->connect();
//获取rn最大值 只获取一次就可以了
$sql = "select max(rn) from bigda.ba_xc_red_line_price_data where dt_ymd=20180626";
$hiveObj->execute($sql);
echo 'cccA<br/>';
$output = $hiveObj->fetch();
echo 'dddA<br/>';
var_dump($output);
echo '查询rn最大值耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';
echo '<hr/>';


$begin = microtime(true);
//获取每页1000条数据
$hiveObj = new Hive();
$hiveObj->connect();
$sql = 'select model_id,province_id,red_line_price from bigda.ba_xc_red_line_price_data where dt_ymd=20180626 and rn between 157001 and 158000';
$result = $hiveObj->execute($sql);
echo 'eee<br/>';
$output = $hiveObj->fetch();
echo 'fff<br/>';
// var_dump($output);
echo '查询1000条记录耗时：'.(microtime(true)-$begin).'s, 输出结果:<br/>';

#### 默认24万条数据
