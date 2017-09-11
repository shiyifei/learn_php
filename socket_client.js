//socket client
<script type="text/javascript" src="./socket.io.js"></script>
<script>
    var socket = io.connect('http://192.168.3.125:8006');
    socket.on("news", function(data){
        console.log(data);
        socket.emit("other event", {my:"what are you doing now?"});
    });
</script>