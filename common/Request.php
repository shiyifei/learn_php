<?php
require "Xcrypt.php";
/**
 * 客户端接口请求类
 *
 * 请妥善保存app_key和secret_key, 以免数据泄露
 *
 * @param string $app_key
 * @param string $secret_key
 */
class Request
{
    private $_app_key = '';
    private $_access_token = '';

    public function __construct($app_key, $secret_key)
    {
        $myinfo = "in ".__METHOD__."\n";
        file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);

        $m = new Xcrypt($secret_key, 'ecb', 'auto');
        $time_now = time();
        $access_key = $app_key . '_' . $secret_key . '_' . $time_now;
        $this->_app_key = $app_key;
        $this->_access_token = $m->encrypt($access_key, 'base64');
    }

    /**
     * 请求接口返回内容
     *
     * @param  string $url [请求的URL地址]
     * @param  string $header [header权限校验参数]
     * @param  json $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     * @throws
     */
    public function juhecurl($url, $params='', $ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        $header = array(
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            'appkey:' . $this->_app_key,
            'token:' . $this->_access_token,
        );
        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt ($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=1');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $params = json_decode($params, true);
        $data = http_build_query($params);

        // $data .= '&XDEBUG_SESSION_START=sublime.xdebug';
        /*备注： 如果后期需要对http通讯过程中的数据做加密传输，则打开以下代码*/
        // $m = new \Common\Org\Xcrypt($key, 'ecb', 'auto');
        // $data = $m->encrypt($a);
        if ( $ispost ) {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $data );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }  else {
            $strUrl = !empty($data) ? $url.'?'.$data : $url;
            curl_setopt( $ch , CURLOPT_URL , $strUrl);
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            throw new \Exception("AN接口访问异常！");
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}
