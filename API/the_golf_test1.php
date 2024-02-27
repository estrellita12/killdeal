<?php
    include_once('../common.php');



//$val  = '{"uuid":1234,"name":"민효선","cell_phone":"01037089132","email":"test@naver.com"}';
$val = $_COOKIE['SSO_USER_TOKEN'];
$val2= encrypt_mcrypt_thegolfshow($val);



	echo $_GET['mkey'];
   $cookie_value = $_GET['mkey'];

//   $key = "RVFBMVNFeGdyZ2JSU0hFRZZxsMaXCltO1lMX3m9/4hlvB37qFCvJVSuGwW4Xa6tdY8WmoCavr2mc8zR2TU8v2D+ISKO3jgVxUT1pC3a6dSUWex4eSSp4H/LE5xagiA4BWKoVYx9VLnZHcMwuFQsxkw==";

$mkey = "RVFBMVNFeGdyZ2JSU0hFRYaRaoSIXpBU0b iiPnbd0EX/xVzkjHbGbFHPofO9dAP2ef9il831tjRCyXMLXwDkLcMW2FUwz v8VNN7mdcCbPn745Kxdr4faOWmNXX437hW94RuG2Asa2Zj8FvdNc86tMOyGFDN9GSfMp5l7VbVUI=";

$user = json_decode(decrypt_mcrypt_thegolfshow($mkey));
print_r($user);

 $key=decrypt_mcrypt_thegolfshow($key);
     // $cookie_value = $_GET['mkey'];
	  $cookie_value = json_decode($key,true);
	  				$name =  $cookie_value["name"];

	  print_r($cookie_value);
	  echo $name;

//	 echo "<br> 값->".decrypt_mcrypt_thegolfshow($cookie_value);
     
?>