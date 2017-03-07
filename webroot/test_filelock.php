<?php

class Test_filelock {
    
    function __construct(){
        
    }

    function writeFile() {
    	$fp = fopen('/tmp/lock.txt','w+');
    	if($fp) {
    		if( flock($fp, LOCK_EX) ) { //排他性锁定
	    		ftruncate($fp,0);
	    		fwrite($fp,"what are you doing now,shiyifei?\n");
	    		sleep(5);
	    		fflush($fp);
	    		flock($fp,LOCK_UN);//释放锁定
	    	}else {
	    		echo "could not get the lock";
	    	}
	    	fclose($fp);
    	}
    }

    function readFile() {
    	$fp = fopen('/tmp/lock.txt','r+');
    	if($fp) {
    		if( !flock($fp,LOCK_EX|LOCK_NB )) {
	    		echo 'unable to obtain lock';
	    		exit(-1);
    		}
    		while(($buffer = fgets($fp,4096)) !== FALSE) {
    			echo $buffer;
    		}
    		if(! feof($fp)) {
    			echo "Error:unexpected fgets() fail";
    		}
    		fclose($fp);
    	}
    	
    	
    }
}

$action = htmlentities($_GET['opr']);
if(!ctype_digit($action)) {
	echo 'parameters invalid!';
}
$obj_filelock = new Test_filelock();
if($action == 1) {
	$obj_filelock->writeFile();
}
else if($action == 2) {
	$obj_filelock->readFile();
}

