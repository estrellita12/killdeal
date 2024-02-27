<?php

//상수 정의
// 키
$key ='rgf7ehmdjcRNIOXX';
$iv ='rgf7ehmdjcRNIOXY';


$str1 = '{"name":"\ub9ac\ud504\ub808\uc26c\uace8\ud504 \ud14c\uc2a4\ud2b8","email":"test@refreshgolf.com","cell_phone":"010-1234-1234"}';
//    echo 'plain:' . $str1 . '<br>';



$str2 = openssl_encrypt($str1, 'AES-128-CBC', $key, 0, $iv );
 //  echo 'AES128 encrypted:' .$str2 . '<br>';

$str3 = openssl_decrypt($str2, 'AES-128-CBC', $key, 0, $iv );
 //  echo 'AES128 decrypted : ' . $str3 . '<br>';


//암호화 -> 복호화 
//$plain ->  

function binTwohex($str) {
    list($result) = unpack("H*0", $str);
    return $result;
}

function hexTwobin($str) {
    return pack("H*", $str);
}

//인코딩
function encrypt_pattern($plain)
{
    $key = 'rgf7ehmdjcRNIOXX';
    $iv ='rgf7ehmdjcRNIOXY';
   
    $enc = openssl_encrypt($plain,'AES-128-CBC',$key, 0, $iv);
     return binTwohex($enc); // php 5.6 버전 이상
 
   return binTwohex($enc);
}

//디코딩
function decrypt_pattern($plain)
{
    //"32byte key";
    $key = 'rgf7ehmdjcRNIOXX';
    $iv ='rgf7ehmdjcRNIOXY';

	$plain = hexTwobin($plain);

    $enc = openssl_decrypt(MCRYPT_RIJNDAEL_128, $key, $plain, MCRYPT_MODE_CBC);
    
    $enc = openssl_decrypt($plain, 'AES-128-CBC', $key ,0, $iv);
      
    return  $enc; 
     
}

function encrypt_mcrypt($msg) {
	$iv = "rgf7ehmdjcRNIOXY";
	$key = "rgf7ehmdjcRNIOXX";

	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	if (!$iv) {
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	}
	$pad = $iv_size - (strlen($msg) % $iv_size);
	$msg .= str_repeat(chr($pad), $pad);
	$encryptedMessage = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $msg, MCRYPT_MODE_CBC, $iv);
	//return ($iv . $encryptedMessage);
	return binTwohex($encryptedMessage);
}


function decrypt_mcrypt($payload) {
	$iv = "rgf7ehmdjcRNIOXY";
	$key = "rgf7ehmdjcRNIOXX";
	
	$raw = pack("H*",$payload);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$data = $raw;
	$result = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	$ctrlchar = substr($result, -1);
	$ord = ord($ctrlchar);
	if ($ord < $iv_size && substr($result, -ord($ctrlchar)) === str_repeat($ctrlchar, $ord)) {
		$result = substr($result, 0, -ord($ctrlchar));
	}
	return $result;

}

function han ($s) { return reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }


$test = encrypt_mcrypt($str1);
$te1 ="84be075e73650f2fe441bdb24527831383ca351e3245c5458768f31e672a6a6ebfa68a16e3cba58d034fff60fdc2ad50e4e9159dd9d787aad4e473a2b316a30e32e335a1eccca3937405f2844edc0ab21b4aa7b79c9260cfd4db3a1ff9ac134d064141bbc866bf3ee870cc95a6d3acff4f203ae6eb3b4593f17e784c69e71050e657518474384e31f171387948ecc2e9";
$test2 = decrypt_mcrypt($test);
$test3 = to_han(stripslashes(json_encode($test2)));
var_dump($test3);


?>