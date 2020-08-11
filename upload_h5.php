<?php

   $myinfo = "in upload_h5.php,_POST:".json_encode($_POST)."\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
    
    $myinfo = "in upload_h5.php,_FILES:".var_export($_FILES, TRUE)."\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);




    $img = isset($_POST['img'])? $_POST['img'] : '';  
  
    // 获取图片  
    list($type, $data) = explode(',', $img);  
      
    // 判断类型  
    if(strstr($type,'image/jpeg')!=''){  
        $ext = '.jpg';  
    }elseif(strstr($type,'image/gif')!=''){  
        $ext = '.gif';  
    }elseif(strstr($type,'image/png')!=''){  
        $ext = '.png';  
    }  


    $myinfo = "in upload_h5.php,data=[{$data}]\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
    

    // 生成的文件名  
    $photo = './images/'.time().$ext;  
      
    // 生成文件  
    file_put_contents($photo, base64_decode($data), true);  

    $decode_data = base64_decode($data);
    $data_md5 = md5($decode_data);
    $myinfo = "in upload_h5.php,md5(decode_data)=[{$data_md5}]\n";
    file_put_contents('/tmp/shiyf.log', $myinfo, FILE_APPEND);
      
    // 返回  
    header('content-type:application/json;charset=utf-8');  
    $ret = array('img'=>$photo);  
    echo json_encode($ret);  
?>