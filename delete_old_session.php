<?php 
$session= session_save_path();
echo $session;
if ($handle = opendir($session)) {
    $sum=0;
    foreach (glob("$session/sess_*") as $filename) {
      if (filemtime($filename) + 1800 < time()) {
        @unlink($filename);
        
        
      }
      echo fileatime($filename)."<br>";
      echo time()."<br>";
      $sum+=1;
    }
    echo $sum;
  
    }else{
        echo "not open;";
    }

    
