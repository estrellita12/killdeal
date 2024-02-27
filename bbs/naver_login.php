<?php
session_start();
// NAVER LOGIN
define('NAVER_CLIENT_ID', '1NcWTVzOmThQLpXwzS01');
define('NAVER_CLIENT_SECRET', '3JgwrMQB03');
define('NAVER_CALLBACK_URL', 'https://killdeal.co.kr/bbs/callback.php');

// 네이버 로그인 접근토큰 요청 예제
$naver_state = md5(microtime() . mt_rand());
$_SESSION['naver_state'] = $naver_state;
$naver_apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".NAVER_CLIENT_ID."&redirect_uri=".urlencode(NAVER_CALLBACK_URL)."&state=".$naver_state;
?>
<a href="<?=$naver_apiURL;?>"><img src="sns_naver.png"></a>


