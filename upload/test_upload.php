<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>用户详情页</title>
<style>
.divmiddle{width:320px;text-align:center;border-bottom: 1px #b7b8ba solid;
padding: 10px;}
.divleft{width:120px;text-align:right;float:left;border-bottom: 1px #b7b8ba solid;border-left: 1px #b7b8ba solid;border-right: 1px #b7b8ba solid;
padding: 10px;}
.divright{width:200px;text-align:left;float:left;border-bottom: 1px #b7b8ba solid;border-right: 1px #b7b8ba solid;
padding: 10px;}
.divcenter{width:364px;height:484px; padding: 10px;}
</style>
</head>
<body>
    <div class="divcenter">
        <input type="file" id="fileIcon" />
        <input type="hidden" id="hidFileUpload" name="hidUploadFile" />
    </div>
    <br/>
    <div class="divcenter">
        <input type="submit" value="上传" />
    </div>
    <script src="../Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="../Public/js/jquery.ajaxfileupload.js" ></script>
    <script type="text/javascript">
        var isCanceled = false;
        $('input[type="file"]').ajaxfileupload({
          action: './upload_process.php',
          valid_extensions : ['jpg','png'],
          params: {
            extra: 'info'
          },
          type: 'post',       //提交的方式
          secureuri :false,   //是否启用安全提交
          fileElementId:'fileIcon',
          onComplete: function(response) {
            console.log(response);
            console.log('custom handler for file:');
            alert(JSON.stringify(response));
          }
        });
    </script>
</body>
</html>