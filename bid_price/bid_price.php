<?php
    session_start(); 
    if(empty($_SESSION['user_id'])) {
        header('Location:http://'.$_SERVER['HTTP_HOST'].'/bid_login.php');
        return;
    } 
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>测试nodejs socket.io的客户端</title>    
</head>
<body>
<select name="order" id="selectOrders">
    <option value="">请选择一个拍单</option>
    <option value="1000" selected="selected">奥迪A4</option>
    <option value="1001">宝马Q3</option>
</select><br/>
<label>买车出价</label><br/>
<input type="text" id="txtPrice" /><br/>
<input type="hidden" id="socketId" />
<button onclick="sendprice()">竞拍出价</button>
<br/>
<label>实时出价信息</label><br/>
<textarea rows="50" cols="60" id="areaInfo"></textarea>
</body>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="../socket/socket.io.js"></script>
<script>    
    var currentOrderId = "<?php echo intval($_GET['order_id']);?>";
    $("#selectOrders").val(currentOrderId);
    socket = io.connect('http://192.168.3.125:8006');
    var eleArea = document.getElementById("areaInfo");
    
    //todo:连接成功后获取当前socket.id，连同当前用户Id发给用户
    socket.on('connect_success',function(data){
        $("#socketId").val(data.msg);  //获取到当前客户端的socketId 
        var postdata = {order_id:$("#selectOrders").find("option:selected").val()};
        socket.emit('login',postdata,function(result){
            console.log("登录成功");
        });
    }); 
    socket.on("receive", function(data){  
        console.log(data);             
        eleArea.innerHTML = '当前价格：'+data.nowprice+"\n";
    });
    var sendprice = function() {
        var post ={order_id:$("#selectOrders").find("option:selected").val(), price:$("#txtPrice").val(), socketId:$("#socketId").val()};
        //todo:向php页面提交拍单请求，参数：价格、拍单Id
        $.post('bid_operate.php?action=bidding',post,function(data){
            if (data.status) {
                // socket.emit("postprice",{order_id:post.order_id, price:post.price});
                alert("拍单提交成功");  
            }else{
                alert("拍单失败:"+data.msg);
            }
        },'json');
    }
</script>
</html>
