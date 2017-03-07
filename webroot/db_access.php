<?php
    include 'backup.php';
    $dbname = 'b2b_hywang';
    $path   = '/tmp/db/';
    if ( ! is_dir($path)) {
        // if ( ! mkdir ( $path ,  0744 ,  true )) {
            // die( 'Failed to create folders...' );
        // }
        echo 'not dir';
    }
    return;
    $db_operator = new DBAccess($dbname,$path);
    $db->BackupDatabase();
?>