<?php
header('Content-Type: text/html;charset=utf-8');
include 'pinyin.php';
class Gongpj1 {
    private $apiUrl = 'http://openapi.gongpingjia.com';
    private $testApiUrl = 'http:/openapi.eyelee.cn';
    private $interfaceUrl = '/api/modeldetail/lastupdated/';

    public function testInterface()
    {
        $url = $this->apiUrl.$this->interfaceUrl;
        $time = '2017-01-01 00:00:00';
        $data = ['start_time'=>$time,'end_time'=>date('Y-m-d H:i:s'),'page_size'=>100,'page'=>1];
        $result = $this->http_get($url, $data);
        $arrResult = json_decode($result,TRUE);
        var_dump($arrResult);
    }

    private function http_get($url,$data) {
        $params = '';
        foreach($data as $key=>$value) {
                $params .= $key.'='.$value.'&';
        }
        if (strpos($url,'?') === FALSE) {
            $params = '?'.$params;
        }
        $params = substr($params, 0, -1);
        $url .= $params;
        echo 'url='.$url.'<br/>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  , 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }

    public function getPinyin(array $chineseArr) {
        $result = [];
        foreach($chineseArr as $item) {
            $pinyin = pinyin::to($item); 
            echo "update cr_evaluate_city set city_pinyin='{$pinyin}' where city='{$item}';<br/>";
            $result[$pinyin] = $item;
        }
        return $result;
    }
	
	

}

$gongPJ = new Gongpj1();
// $gongPJ->testInterface();
$arrChinese =
array('����','�Ϻ�','���','����','���','����','̨��','�ӱ�','ɽ��','���ɹ�','����','����','������','����','�㽭','����','����',
    '����','ɽ��','����','����','����','�㶫','����','����','�Ĵ�','����','����','����','����','����','����','�ຣ','�½�');
$arrCity = [];
$handle = @fopen("area.txt", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgetss($handle, 4096);
        $arrCity[] = $buffer;
    }
    fclose($handle);
}
var_dump($arrCity);
$result = $gongPJ->getPinyin($arrCity);
print_r($result);


