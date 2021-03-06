<?php
header('Content-Type: text/html;charset=utf-8');
class RsaCrypt {

    const CERPATH ='../Application/Runtime/Data/server.cer';  //生成证书路径
    const PFXPATH = '../Application/Runtime/Data/server.pfx';  //秘钥文件路径


    const FILEDIR =  '../Application/Runtime/Data/';
    

     /**
      * 生成公钥私钥
      */
     public static function generateCertKey()
     {
        $dn = array('countryName'=>'CN', 'stateOrProvinceName'=>'beijing', 'localityName'=>'beijing','organizationName'=>'clcw',
             'organizationalUnitName'=>'clcw', 'commonName'=>'clcw', 'emailAddress'=>'service@clcw.com.cn');

        $privkeypass = 'secret';       //私钥密码
        $numberOfDays = 365;           //有效时长，单位为天

        //生成证书
        $privkey = openssl_pkey_new();
        $csr = openssl_csr_new($dn, $privkey);
        $sscert = openssl_csr_sign($csr, null, $privkey, $numberOfDays);

        openssl_x509_export_to_file($sscert, self::CERPATH);
        openssl_pkcs12_export_to_file($sscert, self::PFXPATH, $privkey, $privkeypass);

        (file_exists(self::CERPATH)) or die('公钥的文件路径错误');
        (file_exists(self::PFXPATH)) or die('密钥的文件路径错误');
     }

     public static function verifyData($originData, $decryptData)
     {
        $cer_key = file_get_contents(self::$cerpath);
        $cer = openssl_x509_read($cer_key);
        $res = openssl_verify($originData, $decryptData, $cer);
        var_dump($res);
     }

     /**
      * 生成公钥私钥文件
      * @param $appName string 应用名称
      */
     public static function generateKey($appName)
     {
        $result = ['status'=>0, 'msg'=>''];
        if (!extension_loaded('openssl') ) {
            $result['msg'] = 'php需要openssl支持';
        }
        //创建公钥
        $res = openssl_pkey_new();//array('private_key_bits'=>512) 这一串参数不加，否则只能加密54个长度的字符串
        //提取私钥
        openssl_pkey_export($res, $privatekey);
        //生成公钥
        $public_key = openssl_pkey_get_details($res);
        $publickey = $public_key['key'];

        $path = self::FILEDIR.$appName;
        try{
            file_put_contents($path.'_public.pem', $publickey);
            file_put_contents($path.'_private.pem', $privatekey);
            $result['status'] = 1;
        }catch(Exception $e) {
            // throw new \Exception($e->getMessage());
            $result['msg'] = $e->getMessage();
        }
        return $result; 
     }

     /**
      * 用私钥加密数据
      * @param $data string 需要加密的字符串(最好不要超过200个字符)
      * @param $appName string 应用名称
      */
     public static function privateEncrypt($data, $appName)
     {
    		$arr = ['method'=>__METHOD__,'data'=>$data, 'appName'=>$appName];
    		var_dump($arr);
		
        $result = ['status'=>0, 'msg'=>''];
        $privatefilepath = self::FILEDIR.$appName.'_private.pem';        
        if (!file_exists($privatefilepath)) {
            $result['msg'] = '密钥的文件路径错误';
        }
        //生成resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
        $privatekey = openssl_pkey_get_private(file_get_contents($privatefilepath));
        if (empty($privatekey)) {
            $result['msg'] = '密钥不可用';
        }
        $encryptData = '';
        //用私钥加密
        if (openssl_private_encrypt($data, $encryptData, $privatekey)) {
            $result['msg'] = base64_encode($encryptData);
            $result['status'] = 1;
        } else {
            $result['msg'] = '加密失败！';
        }
        return $result;
     }

     /**
      * 用公钥解密数据
      * @param $data string 需要解密的字符串(最好不要超过200个字符)
      * @param $appName string 应用名称
      */
     public static function publicDecrypt($data, $appName)
     {
        $result = ['status'=>0, 'msg'=>''];
        $data = base64_decode($data);
        $publicfilepath = self::FILEDIR.$appName.'_public.pem';  
        if (!file_exists($publicfilepath)) {
            $result['msg'] = '公钥的文件路径错误';
        };
        //生成resource类型的公钥，如果公钥文件内容被破坏，openssl_pkey_get_public函数返回false
        $publickey = openssl_pkey_get_public(file_get_contents($publicfilepath));
        if (empty($publickey)) {
            $result['msg'] = '公钥不可用';
        }
        //解密数据
        $decryptData = '';
        if (openssl_public_decrypt($data, $decryptData, $publickey)) {
            $result['msg'] =  $decryptData;
            $result['status'] = 1;
        } else {
            $result['msg'] = '解密失败';
        }
        return $result;
     }


     /**
      * 用公钥加密数据
      * @param $data string 需要加密的字符串(最好不要超过200个字符)
      * @param $appName string 应用名称
      */
     public static function publicEncrypt($data, $appName)
     {
        $result = ['status'=>0, 'msg'=>''];
        $filepath = self::FILEDIR.$appName.'_public.pem';        
        if (!file_exists($filepath)) {
            $result['msg'] = '公钥的文件路径错误';
        }
        //生成resource类型的公钥，如果公钥文件内容被破坏，openssl_pkey_get_private函数返回false
        $publickey = openssl_pkey_get_public(file_get_contents($filepath));
        if (empty($publickey)) {
            $result['msg'] = '公钥不可用';
        }
        $encryptData = '';
        //用私钥加密
        if (openssl_public_encrypt($data, $encryptData, $publickey)) {
            $result['msg'] = base64_encode($encryptData);
            $result['status'] = 1;
        } else {
            $result['msg'] = '加密失败！';
        }
        return $result;
     }

     /**
      * 用私钥加密数据
      * @param $data string 需要解密的字符串(最好不要超过200个字符)
      * @param $appName string 应用名称
      */
     public static function privateDecrypt($data, $appName)
     {
        $result = ['status'=>0, 'msg'=>''];
        $data = base64_decode($data);
        $filepath = self::FILEDIR.$appName.'_private.pem';  
        if (!file_exists($filepath)) {
            $result['msg'] = '私钥的文件路径错误';
        };
        //生成resource类型的私钥，如果私钥文件内容被破坏，openssl_pkey_get_public函数返回false
        $privatekey = openssl_pkey_get_private(file_get_contents($filepath));
        if (empty($privatekey)) {
            $result['msg'] = '私钥不可用';
        }
        //解密数据
        $decryptData = '';
        if (openssl_private_decrypt($data, $decryptData, $privatekey)) {
            $result['msg'] =  $decryptData;
            $result['status'] = 1;
        } else {
            $result['msg'] = '解密失败';
        }
        return $result;
     }
}


/*// RsaCrypt::generateKey('kaixin_bi');
$input = json_encode(['userId'=>19999,'username'=>'areyouok','mobilephone'=>'13855556666','appIds'=>'123456', 
    'email'=>'shi_yi_fei@clcw.com.cn','gander'=>'famale','age'=>33,'createtime'=>'2017-01-01 08:12:12']);

$output = RsaCrypt::privateEncrypt($input,'kaixin_bi');
echo 'after encrypting, output:'.json_encode($output).'<br/>';
// $input = base64_decode($output);
$result = RsaCrypt::publicDecrypt($output['msg'],'kaixin_bi');
echo 'after decrypting, result:'.json_encode($result).'<br/>';

$output = RsaCrypt::publicEncrypt($input,'kaixin_bi');
echo 'after encrypting, output:'.json_encode($output).'<br/>';
// $input = base64_decode($output);
$result = RsaCrypt::privateDecrypt($output['msg'],'kaixin_bi');
echo 'after decrypting, result:'.json_encode($result).'<br/>';*/









