
var redis      = require("redis");
redis_client   = redis.createClient(6379,"127.0.0.1");
redis_client.on("error", function (err) {
    console.log("Error(redis): " + err);
});
var key = "1000";
//$res = redis_client.setnx()
redis_client.on("connect",function(){
    
    //redis_client.subscribe("orderchannel:"+key); 
    redis_client.smembers('orderSubUsers:'+key, function (err, reply) {
        if (!err) {
            console.log(reply);
        } else {
            console.log("in getMembers[");
            console.log(err);
            console.log("]");
        }
    });


    redis_client.set("aaa",123,redis.print);
    var output = redis_client.get("aaa",redis.print);
    console.log('output='+output);

});
