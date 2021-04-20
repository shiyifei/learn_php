var redis = require("redis");
var client = redis.createClient(6379,"127.0.0.1");

for(var i=0;i<10;i++) {
    client.publish("order:1",50000+(i*1000));
}

client.subscribe("order:1");
client.on("message",function(channel,message){
     console.log("我在频道【"+channel+"】接收到了消息:"+message);
});
