<?php
    //用户登录本应用的接口
    session_start();
    require "rsacrypt.php";
    require "config.php";
    require "AntService.php";

    $sign = $_GET['sign'];

    //获取该应用的PrivateKey
    $json = RsaCrypt::privateDecrypt($sign, $PRIVATE_KEY);
    
    $myinfo = "in userLogin.php,privatekey={$PRIVATE_KEY},json:".var_export($json, TRUE)."\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

    $jsonArr = json_decode($json['msg'], TRUE);
    if(is_array($jsonArr) ) {
        if(!empty($jsonArr['requestTime'])) {//防重放机制
            $redis = new Redis();
            $redis->connect('192.168.1.102',6379);

            $key = md5($jsonArr['account_id'].$jsonArr['requestTime']);
            $isOk = $redis->setnx($key, 1);
            if (!$isOk) {
                echo '地址仅允许登录一次，请不要重复登录';
                return;
            }

            if (!empty($jsonArr['account_id']) && !empty($jsonArr['access_token'])) {

                $myinfo = "in userLogin.php, account_id is not empty\n";
                file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
                //去蚁巢服务验证access_token
                $data = array(
                    'account_id' => $jsonArr['account_id'],
                    'access_token' => $jsonArr['access_token'],
                );
                try {
                    $antService = new AntService();
                } catch(Exception $e) {
                    throw new Exception($e->getMessage());
                }

                $apiUrl = $antService->apiUrl.'ucenter/cas/token/check';
                $params = json_encode($data);
                $res = $antService->sendRequest($apiUrl, $params);

                $myinfo = "in userLogin.php,res:".var_export($res, TRUE)."\n";
                file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

                if (!$res['status']) {
                    echo '验证access_token出错，err:'.$res['msg'];
                    return;
                }
                
                $_SESSION['user_id'] = $jsonArr['account_id'];
                $sessionKey = 'online:'.$jsonArr['account_id'];
                $redis->set($sessionKey, session_id());

                $myinfo = "in userLogin.php,sessionId:".$redis->get($sessionKey)."\n";
                file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

                $redis->expire($sessionKey, 1800);
                header('Location:http://192.168.3.125/index.php');
            } 
        }
        
    } else {
        echo "传入的sign参数非法！";
        return;
    }




