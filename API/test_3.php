<?php
include_once('../common.php');
//상수 정의
// 키
$key ='rgf7ehmdjcRNIOXX';
$iv ='rgf7ehmdjcRNIOXY';






$str2 = openssl_encrypt($str1, 'AES-128-CBC', $key, 0, $iv );
//    echo 'AES128 encrypted:' .$str2 . '<br>';

$str3 = openssl_decrypt($str2, 'AES-128-CBC', $key, 0, $iv );
//    echo 'AES128 decrypted : ' . $str3 . '<br>';


//암호화 -> 복호화 
//$plain ->  

//인코딩용 
function binTwohex($str) {
    list($result) = unpack("H*0", $str);
    return $result;
}

//디코딩용
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
 
    //return binTwohex($enc);
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
	
	return binTwohex($encryptedMessage);

}
/*

//복호화
$string="84be075e73650f2fe441bdb24527831383ca351e3245c5458768f31e672a6a6ebfa68a16e3cba58d034fff60fdc2ad50e4e9159dd9d787aad4e473a2b316a30e32e335a1eccca3937405f2844edc0ab21b4aa7b79c9260cfd4db3a1ff9ac134d064141bbc866bf3ee870cc95a6d3acff4f203ae6eb3b4593f17e784c69e71050e657518474384e31f171387948ecc2e9";




$string_real = "{'name':'리프레쉬골드 테스트', 'email':'test@refreshgolf.com','cell_phone':'010-1234-1234'}";

$de = decrypt_mcrypt($string);
$en = encrypt_mcrypt($de);

//복호화
//var_dump($de);

//인코딩
//var_dump($en);

//get 방식 받기 


*/



// 리프레쉬 index.php 세션 저장 
// 20200120 이츠 골프 id파라미터 세션 생성
if($pt_id == 'refreshclub')
{
	$mkey = $_GET['mkey'];


		if(isset($_GET['mkey'])){
			$mkey = decrypt_mcrypt($mkey);
		}

		$mkey_value1 = json_decode($mkey,true);

		$refreshid = $mkey_value1["uuid"];
		$name =  $mkey_value1["name"];
		$email = $mkey_value1["email"];
		$cellphone = $mkey_value1["cell_phone"];	

/*
		set_session('ss_mb_id', 'refreshclub_'.$refreshid);
		set_session('ss_mb_name', $name);
		set_session('ss_mb_phone', $cellphone);
		set_session('ss_mb_email', $email);
*/
		$is_member = 1;
		
		if(get_session('ss_mb_id'))
		{
			$is_member = 1;
		}

		else
		{
			$msg = get_session('ss_mb_id');
			$is_member = 1;
			//echo "<script type=\"text/javascript\">alert('$msg');</script>";
		}

	if($_POST['gs_id'] != null)
	{
		//$gs_id = $_POST['gs_id'];
		//$_POST['gs_id'] = null;
		echo "<script>document.location.replace('https://itsgolf.killdeal.co.kr/m/shop/view.php?gs_id=".$_POST['gs_id']."')</script>";
	}
	
}

if($pt_id == 'refreshclub')
{

	if(get_session('ss_mb_id'))
	{
	   
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id'); 
        $member['grade'] = 4;//**왜 8인지 확인필요
        $member['pt_id'] = "refreshclub";//가맹점정보
		$member['cellphone'] = get_session('ss_mb_phone');
		$member['email'] = get_session('ss_mb_email');
    }
     var_dump("seesion info:", $_SESSION);
	 var_dump("member:",$member);

}




// E: 리프레쉬 ///



echo "PT_ID :" .$pt_id;

//echo "name = " .$mkey_de;



?>