<?php
class ConcurrencyDemo
{
    function callback($ret, $callInfo)
    {
        echo $callInfo['method'],' result:',var_export($ret,true),"\n";
    }

    public function sendMultiReq()
    {
        $host = 'http://192.168.1.15/yar/';
        Yar_Concurrent_Client::call($host.'server.php','Add',[23,56],array($this,'callback'));
        Yar_Concurrent_Client::call($host.'music.php','getList',['caiqin'],array($this,'callback'));
        Yar_Concurrent_Client::loop();
    }
}
$demo = new ConcurrencyDemo();
$demo->sendMultiReq();
