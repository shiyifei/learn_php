var redis = require("redis");
var rclient = redis.createClient(6379, "127.0.0.1");

function getRedisData() {
    //客户端连接redis成功后执行回调
    rclient.on('ready',function(){
        rclient.subscribe("order:1");
    });

    rclient.on("error",function(error){
        console.log("Redis error:"+error);  
    });

    //客户端订阅成功事件,这里发现count没有变化
    rclient.on('subscribe',function(channel, count){
        console.log("client subscribed to "+channel+","+count+" total subscriptions");
    });

    //收到消息后执行回调，message是redis发布的消息
    rclient.on('message', function(channel, message){
        console.log("我在频道【"+channel+"】接收到了消息:"+message);
    });

    //监听取消
    rclient.on('unsubscribe',function(channel,count){
        console.log("client unsubscribed from "+channel+","+count+" total subscriptions");
    });
}
for(var i=0;i<10;i++) {
        rclient.publish("order:1",60000+(i*1000));
    }
getRedisData();
rclient.unsubscribe("order:1");