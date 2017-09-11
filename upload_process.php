<?php
    $upFilePath = "../Runtime/Cache/";

    echo json_encode($_FILES);
    return;
    $ok=@move_uploaded_file($_FILES['fileIcon']['tmp_name'], $upFilePath);
     if($ok === FALSE){
      echo json_encode(['file_info'=>'failed']);
     }else{
      echo json_encode(['file_info'=>'success']);
     }
     return;
?> 