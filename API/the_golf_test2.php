<?php
    include_once('../common.php');


header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


//	$val="RVFBMVNFeGdyZ2JSU0hFRakDCCrleYA01SbOOH6jY9CaVzFI5MIgsFFIVd4rXAXl3TRE0ikCpEXFV6Vj1eOi7WMSS6Z6Ieeq1Oy+ggiTPRLmHgT+PdcNxrSAPL2l1kiqsBWtoafr8heMgdfDh8/tVA==";
    
	
	//$val  = '{"uuid":1234,"name":"민효선","cell_phone":"01037089132","email":"test@naver.com"}';
	
	$en_val = encrypt_mcrypt_thegolfshow($val);

	setcookie('SSO_USER_TOKEN', $en_val, time() + 86400); //하루

/*
   $url="https://thegolfshowmarket.com/API/the_golf_test1.php";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
   $SSO_USER_TOKEN = '='.$_COOKIE["SSO_USER_TOKEN"];
   curl_setopt($ch, CURLOPT_COOKIE, $SSO_USER_TOKEN);            
   $xml_contents = curl_exec ($ch);
   curl_close ($ch);
   return $xml_contents;   
*/


//$url="https://thegolfshowmarket.com/API/the_golf_test1.php";
$url="http://thegolfshow.co.kr/major_test.php";
/*
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_COOKIE, $_COOKIE["SSO_USER_TOKEN"]);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
$result = curl_exec($curl);
 
curl_close($curl);
*/

?>
 <form method="post" action="http://thegolfshow.co.kr/major_test.php">
<?
	setcookie('SSO_USER_TOKEN', $en_val, time() + 86400); //하루

                $cookie = $_COOKIE['SSO_USER_TOKEN'];
                echo "쿠키 정보：{$cookie}";
           
?>
<input type="submit" value="전송">
</ㄹ