<?php
    
class RpcClient
{
    const RPC_EOL = "\r\n\r\n";


    public function request($host, $class, $method, $param, $version='1.0', $ext=[])
    {
        $fp = stream_socket_client($host, $errno, $errstr);
        if (!$fp) {
            throw new Exception ("stream_socket_client fail error:{$errno}, errstr:{$errstr}");
        }

        $req = ['jsonrpc'   =>'2.0', 
                'method'    =>  sprintf("%s::%s::%s", $version, $class, $method),
                'params'    => $param,
                'id'        => '',
                'ext'       => $ext
                ];

        $data = json_encode($req). self::RPC_EOL;
        fwrite($fp, $data);

        $result = '';
        while (! feof($fp)) {
            $tmp = stream_socket_recvfrom($fp, 1024);

            if ($pos = strpos($tmp, self::RPC_EOL)) {
                $result .= substr($tmp, 0, $pos);
                break;
            } else {
                $result .= $tmp;
            }
        }
        fclose($fp);
        return json_decode($result, true);
    }
}

$client = new RpcClient();

$method = '';
switch ($_GET['opr']) {
    case 'getList':
        $method = 'getList';
        $param = [1,2];
        break;
    case 'getBigContent':
        $method = 'getBigContent';
        $param = [];
        break;
    default:
        break;
}

//注意 第二个参数实际上是服务器上的UserInterface的全路径
$ret = $client->request('tcp://192.168.56.102:18308', \App\Rpc\Lib\UserInterface::class, $method, $param, '1.0');
var_dump($ret);