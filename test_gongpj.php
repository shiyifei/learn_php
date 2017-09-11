<?php
header('Content-Type: text/html;charset=utf-8');
include 'pinyin.php';
class Gongpj {
    private $apiUrl = 'http://openapi.gongpingjia.com';
    private $testApiUrl = 'http:/openapi.eyelee.cn';
    private $interfaceUrl = '/api/modeldetail/lastupdated/';

    public function getDataFromApi()
    {
        $url = $this->apiUrl.$this->interfaceUrl;
        $time = '2017-01-01 00:00:00';
        $data = ['start_time'=>$time,'end_time'=>date('Y-m-d H:i:s'),'page_size'=>100,'page'=>1];
        $result = $this->http_get($url, $data);
        $arrResult = json_decode($result,TRUE);
        var_dump($arrResult);
        return $arrResult;
    }

    public function writeToDB(array $data) {
        $insertArr = [];
        
        $carData = $data['data'];
        foreach($carData as $key=>$item) {
            $time = date('Y-m-d H:i:s');
            $record = [];
            $record['model_id'] = $item['gpj_id']; //公平价款型ID
            $record['model_detail']  = $item['detail_model'];
            $record['brand_name']  = $item['brand']['name'];
            $record['brand_slug']  = $item['brand']['slug'];
            $record['brand_pinyin']  = $item['brand']['pinyin'];
            $record['brand_first']  = substr($record['brand_pinyin'],0,1);
            $record['series_topic']  = $item['model']['mum'];
            $record['series_name']  = $item['model']['name'];
            $record['series_slug']  = $item['model']['slug'];
            $record['series_pinyin']  = $item['model']['pinyin'];
            $record['series_first']  = substr($record['series_pinyin'],0,1);
            $record['listed_year']  = $item['listed_year'];
            $record['delisted_year']  = $item['delisted_year'];
            $record['createtime']   = $time;
            $record['udpatetime']   = $time;
            $insertArr[] = $record;
        }
        unset($carData);        
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

    public function getAllCitys()
    {
        $dsn = 'mysql:dbname=ant_nest;host=192.168.1.102;';
        $user = 'dbuser';
        $password = '123456';

        try{
            $conn = new PDO($dsn,$user,$password);
            $sql = 'select distinct province_name from cr_evaluation_gpj_city';
            $result = [];
            foreach($conn->query($sql) as $row) {
                $result[] = $row['province_name'];
            }
            foreach($result as $item) {
                    $oldvalue = $item;
                    $pinyin = PinYin::utf8ToPinyin($item, true);

                    $sql = "update cr_evaluation_gpj_city set province_short='{$pinyin}' where province_name='{$oldvalue}';";
                    echo $sql."<br/>";
                    $ret = $conn->exec($sql);
                    // var_dump($ret);
             }
            
        }catch(PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return [];
        }
    }

}

$gongPJ = new Gongpj();
// $data = $gongPJ->getDataFromApi();
//$gongPJ->writeToDB($data);

$result = $gongPJ->getAllCitys();
var_dump($result);


/*$PingYing = new GetPingYing();
foreach($result as $city) {
    //echo $city."<br/>";
    $city = iconv('utf-8','gb2312',$city);
    echo '<br>',$PingYing->getFirstPY($city),'<br>';
    echo $PingYing->getAllPY($city),'<br>';   
}
*/


