<?php 

function encrypt_aes($iv,$key, $msg) {
    $cipherText = openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv);
    return $cipherText;
}

function decrypt_aes($iv,$key, $payload) {
    $plainText = openssl_decrypt($payload, 'AES-128-CBC', $key, false, $iv);
    return $plainText;
}


function encrypt_mkey($iv,$key, $msg) {
    $cipherText = openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv);
    return urlencode(  base64_encode( $cipherText ) );
}

function decrypt_mkey($iv, $key, $msg) {
    $payload = base64_decode( urldecode($msg) );
    $plainText = openssl_decrypt($payload, 'AES-128-CBC', $key, false, $iv);
    return $plainText;
}



$iv = "0987654321654321";
$key = "1234567890123456";
$value = array('uuid'=>'choimr', 'name'=>'둘리', 'email'=>'choimr@mwd.kr', 'cellphone'=>'01075541207'); // PHP 배열
$msg = json_encode($value,JSON_UNESCAPED_UNICODE);
$enc = urlencode( base64_encode( openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv) ) );

echo $msg;
echo "<br>";
echo openssl_encrypt( $msg, 'AES-128-CBC', $key, false, $iv);
echo "<br>";
echo base64_encode( openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv) );
echo "<br>";
echo urlencode( base64_encode( openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv) ) );
echo "<br>";
echo "--------------";
echo "<br>";
echo urldecode($enc);
echo "<br>";
echo base64_decode( urldecode($enc) );
echo "<br>";
echo openssl_decrypt( base64_decode( urldecode($enc) ) , 'AES-128-CBC', $key, false, $iv);
?>


