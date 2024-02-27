<?php
  //$str_name = jsonfy("B856E909E78FDFA2690CD9705BFD4D92"); //  34A89619950D42C8
  
 function jsonfy($name) {
     
    //$result = passthru("/usr/local/java/bin/java -classpath .:hcdesutil.jar AA ".$name); 
	$result = exec("/usr/local/java/bin/java -Dfile.encoding=euc-kr -classpath .:hcdesutil.jar AA ".$name); 
	//$result = shell_exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar AA ".$name);
	//$dec_str_name = base64_decode($result);
	//echo($result."|".$dec_str_name);
	// $dec_result= base64_decode($result);
	// echo($dec_result);
	return $result;
  }
  //암호화 실행
  function jsonfy2($name) {
     
   
	$result = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$name); 
	
	return $result;
  } 
  

/* 
34A89619950D42C8
MEM_NM=701BC3B3070A5DD6
ENTR_NO=7A056D96076367AD9F864B7B869D8201
SHOP_NO=6831A9DA1B37FA0E34799E99601BB6FE
MEM_NO=B856E909E78FDFA2690CD9705BFD4D92
mem_no:100001865241 
SHOPEVENT_NO=73FB843F801B403D809F23C00A436192
SSO_KEY=35836
*/

  
    
/*
   $dec_str_name = base64_decode($str_name);
   echo $dec_str_name;
   echo "\n";
 */


 $way = "10000"; //(인코딩: MTAw)
 $way2 = 100;
 $way_enc = base64_encode($way);
 $way_enc2 = base64_encode($way2);
 //echo $way_enc."|".$way_enc2;

 $str_name2 = jsonfy2($way_enc); 
 echo $str_name2; 

 $test ="8D7E3454D1BB9B54";
 // $test ="701BC3B3070A5DD6";


 
 $test2 = jsonfy($test);//복호화
 $test3 = base64_decode($test2);//디코딩
 $test4 = iconv("EUC-KR","UTF-8",$test3);





 //echo($test3);
 //echo("iconv:".$test4);

 //phpinfo();
  
?>
