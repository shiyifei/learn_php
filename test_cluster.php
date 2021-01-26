<?php
ini_set('error_reporting', E_ALL);

/**
 * 本示例演示galery cluster中mysql集群各节点服务器的增删改查等操作并与单实例数据库进行性能比较
**/



class Test_cluster 
{   
    //这三台服务器在一个集群中
    protected $dbconn1;
    protected $dbconn2;
    protected $dbconn3;

    protected $dbconn4; //单个mysql服务器

    protected $memcache;

    public function __construct()
    {
        include 'config.php';
        try {
            $this->dbconn1 = 
            new PDO('mysql:host=192.168.56.105;dbname='.$dbInfo['dbname'], $dbInfo['user'], $dbInfo['pass']);
            $this->dbconn2 = 
            new PDO('mysql:host=192.168.56.101;dbname='.$dbInfo['dbname'], $dbInfo['user'], $dbInfo['pass']);
            $this->dbconn3 = 
            new PDO('mysql:host=192.168.56.103;dbname='.$dbInfo['dbname'], $dbInfo['user'], $dbInfo['pass']);
            $this->dbconn4 = 
            new PDO('mysql:host=192.168.56.102;dbname='.$dbInfo['dbname'], $dbInfo['user'], $dbInfo['pass']);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $this->memcache = new Memcached();
        $this->memcache->addServer('127.0.0.1', 11211);
    }

    private function getConn($node) 
    {
        $dbConnection = null;
        switch ($node) {
            case 1:
                $dbConnection = $this->dbconn1;
                break;
            case 2:
                $dbConnection = $this->dbconn2;
                break;
            case 3:
                $dbConnection = $this->dbconn3;
                break;
            default:
                break;
        }
        return $dbConnection;
    }

    /**
     * 处理数据，测试更新数据
     * @return void
     */
    public function updateOnServer($node)
    {
        $dbConnection = $this->getConn($node);
        $ids = [];
        for ($i=1; $i <= 20000; ++$i) {
            array_push($ids, mt_rand(1, 60000));
        }
        $ids = array_unique($ids);
        sort($ids);
        for ($i=1; $i<=200; ++$i) {
            $ids[] = $i;
        }
        $ids = array_unique($ids);

        $begin = microtime(true);
        try {
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbConnection->beginTransaction();
            $salt = mt_rand(1,60);
            $time = date('Y-m-d H:i:s', time() + $salt);
            $sql1 = 'update users set email=concat(username,'.$salt.',"@sohu.com"),createtime="'.$time.'" where id in ('.implode($ids,',').')';
            $sql2 = 'insert into user_operate_log(opr_type,operator,node) values(2,3,"node'.$node.'")';

            echo 'db=node'.$node.',sql1='.substr($sql1,0,200)."\n";
            echo 'db=node'.$node.',count ids:'.count($ids)."\n";

            $count = $dbConnection->exec($sql1);
            if ($count === false) {
                echo 'db=node'.$node.','.print_r($dbConnection->errorInfo(), true);
            }
            
            echo 'db=node'.$node.',update result:'.var_export($count,true)."\n";
            
            $ret = $dbConnection->exec($sql2);
            // echo 'db=node'.$node.',sql2='.$sql2."\n";
            echo 'db=node'.$node.',insert write log result:'.var_export($ret,true)."\n";
            if ($ret === false) {
                echo 'db=node'.$node.','.print_r($dbConnection->errorInfo(), true);
            }
            $this->memcacheWriteArr($ids, 90);
            $dbConnection->commit();
        } catch (Exception $e) {
            echo 'db=node'.$node.',Error!: '.$e->getCode().':'. $e->getMessage() . "\n";
            $dbConnection->rollBack();
        }
        echo 'db=node'.$node.',time interval:'.(microtime(true)-$begin).'s'."\n";
    }

    /**
     * 在某一个节点上添加数据
     * @param  [type] $node 数据库
     * @return [type]     [description]
     */
    public function insertOnServer($node)
    {
        $dbConnection = $this->getConn($node);
         try {
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $insertSql = 'insert into users (username,email) values ';
            for ($i=1; $i<1000; ++$i) {
                $insertSql .= "('node{$node}user{$i}', 'node{$node}user{$i}@1.com'),";
            }
            $insertSql = substr($insertSql, 0, -1).';';
            $count = $dbConnection->exec($insertSql);
            echo 'db=node'.$node.',insert result:'.var_export($count, true)."\n";

            $sql2 = 'insert into user_operate_log(opr_type,operator,node) values(1,3,"node'.$node.'")';
            $dbConnection->exec($sql2);
            echo 'count='.$count."\n";        
         } catch (Exception $e) {
            echo 'db=node'.$node.',Error!: '.$e->getCode().':'. $e->getMessage() . "\n";
            //$dbConnection->rollBack();
        }
    }

    /**
     * 在某一个节点上删除数据
     * @param  int $node 服务器节点
     * @return void
     */
    public function deleteOnServer($node)
    {
        $dbConnection = $this->getConn($node);
        $ids = [];
        for ($i=1; $i <= 200; ++$i) {
            //array_push($ids, mt_rand(1, 60000));
            array_push($ids, 12200+$i);
        }
        $ids = array_unique($ids);
        sort($ids);

        $begin = microtime(true);
        try {
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbConnection->beginTransaction();
            $salt = mt_rand(1, 60);
            $time = date('Y-m-d H:i:s', time() + $salt);
            $sql1 = 'delete from users where id in ('.implode($ids,',').')';
            $sql2 = 'insert into user_operate_log(opr_type,operator,node) values(3, 3,"node'.$node.'")';

            echo 'db=node'.$node.',sql1='.substr($sql1, 0, 200)."\n";
            echo 'db=node'.$node.',count ids:'.count($ids)."\n";

            $count = $dbConnection->exec($sql1);
            if ($count === false) {
                echo 'db=node'.$node.','.print_r($dbConnection->errorInfo(), true);
            }
            
            echo 'db=node'.$node.',delete result:'.var_export($count,true)."\n";
           
            
            $ret = $dbConnection->exec($sql2);
            echo 'db=node'.$node.',sql2='.$sql2."\n";
            echo 'db=node'.$node.',delete write log result:'.var_export($ret,true)."\n";
            if ($ret === false) {
                echo 'db=node'.$node.','.print_r($dbConnection->errorInfo(), true);
            }            
            $dbConnection->commit();
        } catch (Exception $e) {
            echo 'db=node'.$node.',Error!: '.$e->getCode().':'. $e->getMessage() . "\n";
            $dbConnection->rollBack();
        }
        echo 'db=node'.$node.',time interval:'.(microtime(true)-$begin).'s'."\n";
    }

    /**
     * 测试一下memcache的存取
     * @return void
     */
    public function testMemcache()
    {
        $memcache = new Memcached();
        $memcache->addServer('127.0.0.1', 11211);
        
        $arrA = [1,2,333,546,654];
        $ret = $memcache->set('arrA', $arrA, 180);
        var_dump($ret);
        echo '<hr/>';
        $result = $memcache->get('arrA');
        var_dump($result);
        echo '<hr/>';
    }

    /**
     * 查询数据库
     * @return void
     */
    public function selectOnServer($node=0) 
    {      
        if ($node>0) {
            $dbnumber = $node;
        } else {
            $dbnumber = $this->makeDbNumber();//产生一个随机的dbnumber
        }
        
        $ids = $this->memcacheGetArr('writeKeys');
        if (empty($ids)) {
            $begin = microtime(true);
            for ($i=1; $i <= 20000; ++$i) {
                array_push($A, mt_rand(1, 60000));
                if ($i < 300) {
                    $ids[] = $i;
                }
            }
            echo 'in testSelect(), db=node'.$dbnumber.',time interval:'.(microtime(true)-$begin)."s \n";
        }
        echo 'in testSelect(), db=node'.$dbnumber.',count(ids):'.count($ids)." \n";

        $dbConn1 = $this->getOneConn(1);
        $dbConn2 = $this->getOneConn(2);
        $dbConn3 = $this->getOneConn(3);
        $sql = 'select id,username,createtime from users where id in(' . implode($ids, ',') . ') order by createtime desc limit 0,200';
        // echo 'in testSelect(), sql='.$sql."\n";
        foreach ($dbConn1->query($sql) as $row) {
            echo 'current node:'.$dbnumber.', db=node1,'.$row['id']."\t".$row['username']."\t".$row['createtime']."\n";
        }

        foreach ($dbConn2->query($sql) as $row) {
            echo 'current node:'.$dbnumber.', db=node2,'.$row['id']."\t".$row['username']."\t".$row['createtime']."\n";
        }

        foreach ($dbConn3->query($sql) as $row) {
            echo 'current node:'.$dbnumber.', db=node3,'.$row['id']."\t".$row['username']."\t".$row['createtime']."\n";
        }
        echo 'in testSelect(), db=node'.$dbnumber.',*******************************'."\n";
    }

    public function getLastGTID()
    {   
        $dbnumber = $this->makeDbNumber();//产生一个随机的dbnumber
        $dbConn = $this->getOneConn($dbnumber);
        $i = 0;
        $sql = "show status like 'wsrep_last_committed';";
        $gtid = 0;

        while ($i < 5000) {
            foreach ($dbConn->query($sql) as $row) {
                if ($row['Value'] !== $gtid) {
                    echo 'in getLastGTID(),node'.$dbnumber.',wsrep_last_committed:'.$row['Value']."\n";
                }
                $gtid = $row['Value'];
            }
            ++$i;
        }
    }


    /**
     * 随机产生一个数据库节点序号
     * @return int
     */
    private function makeDbNumber()
    {
        $dbnumber = $this->memcache->get('dbnumber');
        if (empty($dbnumber)) {
            $random = mt_rand(1, 10000);
            $this->memcache->set('dbnumber', $random, 90);
            $dbnumber = $random;
        } else {
            $this->memcache->increment('dbnumber', 1);
            ++$dbnumber;
        }
        $dbnumber = $dbnumber % 3 + 1;
        return $dbnumber;
    }

    /**
     * 根据参数返回一个数据库连接
     */
    private function getOneConn($dbnumber)
    {
        switch ($dbnumber) {
            case 1:
                return $this->dbconn1;
            case 2:
                return $this->dbconn2;
            case 3:
                return $this->dbconn3;
        }
    }

    /**
     * 向memcache中的数组追加写入数据
     * @param  array  $input   要写入的数组
     * @param  int $expires 超时时间(s)
     * @return bool
     */
    public function memcacheWriteArr(array $input, $expires=180)
    {
        $key = 'writeKeys';
        $result = $this->memcache->get($key);
        // echo 'in '.__METHOD__.',result:'.json_encode($result)."\n";
        if (empty($result)) {
            $ret =  $this->memcache->set($key, $input, $expires);
        } else {
            $result = array_merge($result, $input);
            $ret = $this->memcache->set($key, $result, $expires);
        }
        return $ret;
    }

    /**
     * 读取memcache中的数组
     * @param  string $key
     * @return mixed
     */
    public function memcacheGetArr($key)
    {
        $a =  $this->memcache->get($key);
        echo 'in '.__METHOD__.',count($a):'.count($a)."\n";
        return $a;
    }
   

    /**
     * 测试插入数据的性能
     * @return void
     */
    public function testInsert()
    {
        try {
            //测试集群插入10万条数据
            $begin = microtime(true);
            $insertSql = 'insert into users (username,email) values ';
            for ($i=200000; $i<300000; ++$i) {
                $insertSql .= "('user{$i}', 'user{$i}@1.com'),";
            }
            $insertSql = substr($insertSql, 0, -1).';';
            // var_dump($insertSql);
            $count = $this->dbconn1->exec($insertSql);
            echo 'count='.$count."\n";
            echo 'in mysql cluster,time interval:'.(microtime(true)-$begin).'s'."\n";

            //测试单机插入效果
            $begin = microtime(true);
            $insertSql = 'insert into users (username,email) values ';
            for ($i=200000; $i<300000; ++$i) {
                $insertSql .= "('user{$i}', 'user{$i}@1.com'),";
            }
            $insertSql = substr($insertSql, 0, -1).';';
            $count = $this->dbconn4->exec($insertSql);
            echo 'count='.$count."\n";
            echo 'in mysql standalone,time interval:'.(microtime(true)-$begin).'s'."\n";

        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }
}

$processor = new Test_cluster();

echo '当前时间：'.date('Y-m-d H:i:s').',参数：'.json_encode($argv)."\n";

if (isset($_GET['db'])) {
    $db = intval($_GET['db']);
} else {
    $db = intval($argv[1]);
}
if (isset($_GET['opr'])) {
    $opr = addslashes($_GET['opr']);
} else {
    $opr = addslashes($argv[2]);
}

switch ($db) {
    case 1:
    case 2:
    case 3:
        if ($opr == 'insert') {
            $processor->insertOnServer($db);
        } elseif ($opr == 'update') {
            $processor->updateOnServer($db);
        } elseif ($opr == 'delete') {
            $processor->deleteOnServer($db);
        } elseif ($opr == 'select') {
            $processor->selectOnServer($db);
        }
        break;
    case 5:
        $processor->getLastGTID();
        break;
    case 6:
        $processor->testInsert();
        break;
    case 7:
        $processor->testMemcache();
        break;
}



