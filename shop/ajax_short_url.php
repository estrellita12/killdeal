<?php
include_once("./_common.php");

$client_id = "1NcWTVzOmThQLpXwzS01"; // 네이버 개발자센터에서 발급받은 CLIENT ID
$client_secret = "3JgwrMQB03";// 네이버 개발자센터에서 발급받은 CLIENT SECRET
//$encText = urlencode("https://developers.naver.com/docs/utils/shortenurl");
$encText = urlencode($_POST['preUrl']);
$postvars = "url=".$encText;
$url = "https://openapi.naver.com/v1/util/shorturl?url=".$encText ;
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
$headers = array();
$headers[] = "X-Naver-Client-Id: ".$client_id;
$headers[] = "X-Naver-Client-Secret: ".$client_secret;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "status_code:".$status_code."<br>";
curl_close ($ch);

if($status_code == 200) {
    echo json_encode($response);
} else {
    echo "Error 내용:".$response;
}

?>

