var RedisPort = 6379;
var RedisHost = "127.0.0.1";
var redis      = require("redis");
var rclient   = redis.createClient(RedisPort,RedisHost);
rclient.on("error", function (err) {
    console.log("Error(redis): " + err);
})

var io = require('socket.io').listen(8006);
var socketUsers = {};
io.sockets.on('connection', function(socket) {
    socket.emit('connect_success', {msg: socket.id});
    socket.on('login',function(data, fn){
        console.log("arrive here,login()"+data.order_id);
        socketUsers[socket.id] = {'order_id':data.order_id, 'socket':socket};

        //订阅
        rclient.subscribe("orderchannel:"+data.order_id); 
    });

    //收到消息后执行回调，message是redis发布的消息
    rclient.on('message', function(channel, price){
        console.log("我在频道【"+channel+"】接收到了消息:"+price);
        console.log(socketUsers);
        console.log("in message()\n");

        //channel名称：orderSubUsers:orderId
        var orderId = channel.substr(13);
        console.log("orderId=["+orderId+"]\n");

        console.log("aaaaaaa");
        var currSock = socketUsers[socket.id];
        console.log(currSock);
        for(var item in currSock) {
            if (item == 'order_id') {
                if(currSock[item] == orderId) {
                    socket.emit('receive', {'nowprice':price});
                }
            }
        }
        console.log("bbbbbbb");        
    });

    //监听断线
    socket.on('disconnect',function() {
        console.log('-链接断开['+socket.id+']-');   
        delete socketUsers[socket.id];
    });  
});

function getRedisData() {
    rclient.on("connect", function() {
        console.log("in ready(),");
    });

    rclient.on('error', function(error) {
         console.log("RedisServer is error!\n" + error);
    });
    //客户端订阅成功事件
    rclient.on("subscribe",function(channel, count){
        console.log("client subscribed to "+channel+","+count+" total subscriptions");
    });

    //收到消息后执行回调，message是redis发布的消息
    rclient.on('message', function(channel, price){
        console.log("我在频道【"+channel+"】接收到了消息:"+price);
        console.log(socketUsers);
        console.log("in message()\n");

        

        //channel名称：orderSubUsers:orderId
        var orderId = channel.substr(13);
        console.log("orderId=["+orderId+"]\n");

        var subUserIds = 'orderSubUsers:'+orderId;
        console.log("key=["+subUserIds+"]\n");

        //todo:获取到所有订阅该频道的userId
        var len = rclient.scard(subUserIds);
        rclient.smembers(subUserIds, function(err,replies){
            console.log("err=["+err+"],replies=["+replies+"]\n");
            if (!err) {
                console.log('aaaaa'+replies);
            }
        });
        console.log("before smembers(),len="+len);
        console.log("after smebmers()");
    });

    //监听取消
    rclient.on('unsubscribe',function(channel,count){
        console.log("client unsubscribed from "+channel+","+count+" total subscriptions");
    });
}   