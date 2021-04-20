<?php
function getOS(){ 
     $os=''; 
     $Agent=$_SERVER['HTTP_USER_AGENT']; 
     if (preg_match('/win/i',$Agent) && strpos($Agent, '95')){ 
        $os='Windows 95'; 
     } elseif (preg_match('/win 9x',$Agent)&&strpos($Agent, '4.90')){ 
      $os='Windows ME'; 
     }elseif(preg_match('/win',$Agent)&&ereg('98',$Agent)){ 
      $os='Windows 98'; 
     }elseif(preg_match('/win',$Agent)&&preg_match('nt 5.0',$Agent)){ 
      $os='Windows 2000'; 
     }elseif(preg_match('/win',$Agent)&&preg_match('nt 6.0',$Agent)){ 
      $os='Windows Vista'; 
     }elseif(preg_match('/win',$Agent)&&preg_match('nt 6.1',$Agent)){ 
      $os='Windows 7'; 
     }elseif(preg_match('/win',$Agent)&&preg_match('nt 5.1',$Agent)){ 
      $os='Windows XP'; 
     }elseif(preg_match('/win',$Agent)&&preg_match('nt',$Agent)){ 
      $os='Windows NT'; 
     }elseif(preg_match('/win',$Agent)&&ereg('32',$Agent)){ 
      $os='Windows 32'; 
     }elseif(preg_match('/linux',$Agent)){ 
      $os='Linux'; 
     }elseif(preg_match('/unix',$Agent)){ 
      $os='Unix'; 
     }else if(preg_match('/sun',$Agent)&&preg_match('os',$Agent)){ 
      $os='SunOS'; 
     }elseif(preg_match('/ibm',$Agent)&&preg_match('os',$Agent)){ 
      $os='IBM OS/2'; 
     }elseif(preg_match('/Mac',$Agent)&&preg_match('PC',$Agent)){ 
      $os='Macintosh'; 
     }elseif(preg_match('/PowerPC',$Agent)){ 
      $os='PowerPC'; 
     }elseif(preg_match('/AIX',$Agent)){ 
      $os='AIX'; 
     }elseif(preg_match('/HPUX',$Agent)){ 
      $os='HPUX'; 
     }elseif(preg_match('/NetBSD',$Agent)){ 
      $os='NetBSD'; 
     }elseif(preg_match('/BSD',$Agent)){ 
      $os='BSD'; 
     }elseif(ereg('/OSF1',$Agent)){ 
      $os='OSF1'; 
     }elseif(ereg('/IRIX',$Agent)){ 
      $os='IRIX'; 
     }elseif(preg_match('/FreeBSD',$Agent)){ 
      $os='FreeBSD'; 
     } elseif($os==''){ 
      $os='Unknown'; 
     } 
     return $os; 
}

$os = getOS();
var_dump($os);
echo '<hr/>';

$Agent= $_SERVER['HTTP_USER_AGENT'];
var_dump($Agent);

echo '<hr/>';

$a = preg_match('/win/i', $Agent);
var_dump($a);
?>