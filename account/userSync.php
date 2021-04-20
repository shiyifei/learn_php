<?php
//同步用户接口

$retArr = ['code' => '000000', 'msg' => '', 'data' => array()];
//todo:接收用户信息
$account_id = (int)$_GET['account_id'];
$account_name = $_GET['account_name'];
$mobile = $_GET['mobile'];
$real_name = urldecode($_GET['real_name']);
$email = $_GET['email'];
$province = urldecode($_GET['province']);
$city = urldecode($_GET['city']);
$address = urldecode($_GET['address']);
$createtime = urldecode($_GET['createtime']);

if (empty($account_id)) {
    $retArr['code'] = '100011';
    $retArr['msg'] = 'account_id参数不允许为空';
    echo json_encode($retArr);
    return;
}
if (empty($account_name)) {
    $retArr['code'] = '100012';
    $retArr['msg'] = 'account_name参数不允许为空';
    echo json_encode($retArr);
    return;
}
if (empty($mobile)) {
    $retArr['code'] = '100013';
    $retArr['msg'] = 'mobile参数不允许为空';
    echo json_encode($retArr);
    return;
}
if (empty($real_name)) {
    $retArr['code'] = '100014';
    $retArr['msg'] = 'real_name参数不允许为空';
    echo json_encode($retArr);
    return;
}


//判断数据库中是否已存在该用户Id,有则修改无则添加
try {
    $pdo = new PDO('mysql:dbname=test;host=127.0.0.1;', 'dbuser', '123456');

    $query = 'select count(*) as count from employee_account where account_id=' . $account_id;
    $result = $pdo->query($query);
    $result = $result->fetch(PDO::FETCH_BOTH);

    $myinfo = "in userSync.php,result:" . json_encode($result) . "\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

    if ((int)$result['count'] == 0) {
        $sth = $pdo->prepare('insert into employee_account(account_id,account_name,mobile,real_name,email,province,city,address,createtime,status)  values
                (:account_id,:account_name,:mobile,:real_name,:email,:province,:city,:address,:createtime,1)');
    } else {
        $sth = $pdo->prepare('update employee_account set account_name=:account_name,mobile=:mobile,real_name=:real_name,email=:email,
                province=:province,city=:city,address=:address,createtime=:createtime,status=1 where account_id=:account_id');
    }
    $sth->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $sth->bindParam(':account_name', $account_name, PDO::PARAM_STR);
    $sth->bindParam(':real_name', $real_name, PDO::PARAM_STR);
    $sth->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $sth->bindParam(':email', $email, PDO::PARAM_STR);
    $sth->bindParam(':province', $province, PDO::PARAM_STR);
    $sth->bindParam(':city', $city, PDO::PARAM_STR);
    $sth->bindParam(':address', $address, PDO::PARAM_STR);
    $sth->bindParam(':createtime', $createtime, PDO::PARAM_STR);
    $result = $sth->execute();
    $myinfo = "in userSync.php,result:" . var_export($result, true) . "\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

    if (!$result) {
        $retArr['code'] = '100015';
        $retArr['msg'] = '数据库操作失败';
        echo json_encode($retArr);
        return;
    }
} catch (PDOException $e) {
    $retArr['code'] = '100016';
    $retArr['msg'] = '数据库操作异常:' . $e->getCode() . '|' . $e->getMessage();
    echo json_encode($retArr);
    return;
}

//将该用户从redis禁用列表中删除
$redis = new Redis();
$redis->connect('192.168.1.102', 6379);
$redis->lrem('empsso:disableUsers', 0, $account_id);//将用户插入到禁用队列中

echo json_encode($retArr);
return;

