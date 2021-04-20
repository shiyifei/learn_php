<?php
require "../common/Request.php";

class AntService {
    private $appKey = 'AK2016.API.1013';
    private $secretKey = 'ax10ffer';
    public $apiUrl = 'http://antnest.dev.s.isou365.cn/';

    public function __construct() {
         $myinfo = "in ".__METHOD__."\n";
         file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
    }
    /**
     * 向蚁巢发送请求
     * @param $apiUrl string api接口地址
     * @param $params string 参数json串
     * @return 返回数组
     * @throws
     */
    public function sendRequest($apiUrl, $params,$isPost=1) {
        $result = ['status'=>FALSE, 'msg'=>''];
        $requestObj = new Request($this->appKey, $this->secretKey);
        $res = $requestObj->juhecurl($apiUrl, $params, $isPost);

        $returnArr = json_decode($res,TRUE);
        if (is_null($returnArr) || empty($returnArr)) {
            $result['msg'] =  "请求蚁巢接口异常: " . $apiUrl;
            $myinfo=json_encode(['reqapp'=>"{$this->appKey}|{$this->secretKey}",'apiUrl'=>$apiUrl,'params'=>$params,'response'=>$res]);
            file_put_contents('/tmp/error.log', $myinfo, FILE_APPEND);
        } elseif ($returnArr['code'] != '000000') {
            $myinfo=json_encode(['reqapp'=>"{$this->appKey}|{$this->secretKey}",'apiUrl'=>$apiUrl,'params'=>$params,'response'=>$res]);
            file_put_contents('/tmp/error.log', $myinfo, FILE_APPEND);
            $result['msg'] = $returnArr['msg'];
        } else {
            $result['status'] = TRUE;
            $result['data'] = $returnArr['data'];
        }
        return $result;
    }
}    
