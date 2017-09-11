<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>模拟拍单登录</title>
</head>
<body>
<label>登录</label><br/>
<form method="post" action="bid_operate.php?action=login">
用户名：<input type="text" name="username" id="txtContent" /><br/>
密码:<input type="password" name="password" /><br/>
<input type="submit" name="btnSubmit" value="提交" />
<input type="reset" name="btnReset" value="重置" />
</form>
</body>
</html>
