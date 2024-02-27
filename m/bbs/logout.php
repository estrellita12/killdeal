<?php
define('_PURENESS_', true);
include_once("./_common.php");

// 이호경님 제안 코드
session_unset(); // 모든 세션변수를 언레지스터 시켜줌 
session_destroy(); // 세션해제함 

// 자동로그인 해제 --------------------------------
set_cookie("ck_mb_id", "", 0);
set_cookie("ck_auto", "", 0);
// 자동로그인 해제 end --------------------------------


if($_SERVER['HTTP_HOST'] =='mall.golfpang.com')
{
	$pt_id = 'golfpang';
	//setcookie("gp_id", "", time() - 3600); //만료시간을 3600초 전으로 셋팅하여 확실히 제거
	//setcookie("gp_id", "", 0, "/m/");
	

}
else if($_SERVER['HTTP_HOST'] =='shopping.golfu.net') 
{
	$pt_id = 'golfu';
}
else if($_SERVER['HTTP_HOST'] =='honggolf.killdeal.co.kr') {
	$pt_id = 'honggolf';

}
else if($_SERVER['HTTP_HOST'] =='shop.uscore.co.kr') 
{
	$pt_id = 'uscore';
	goto_url('https://www.uscore.co.kr/mapp/index.php');
}

//외부연동_쿠키삭제 필요_20191004
if($pt_id == 'golfpang')
{
	if (isset($_COOKIE['gp_id'])) {
    unset($_COOKIE['gp_id']);
    setcookie('gp_id', '', time() +1 , '/m/'); // empty value and old timestamp
    }
	//쿠키 제거X

    //내부 세션 처리후 연동사이트 로그아웃처리
	goto_url('http://m.golfpang.com/m/join/logout.do');
}
else if($pt_id == 'golfu')
{
	//내부 세션 처리후 연동사이트 로그아웃처리
	goto_url('http://www.golfu.net/Member/LogOut.aspx');
}
else if($pt_id == 'honggolf')
{

    if (isset($_COOKIE['SESSION'])) {
        unset($_COOKIE['LOGIN_INFO']);
        unset($_COOKIE['SESSION']);
        //setcookie('gp_id', '', time() +1 , '/m/'); // empty value and old timestamp
        }
        //쿠키 제거X
        
        //내부 세션 처리후 연동사이트 로그아웃처리
        goto_url('https://www.honggolf.com');

}



if($url) {
    $p = parse_url($url);
    if($p['scheme'] || $p['host']) {
        alert("url에 도메인을 지정할 수 없습니다.");
    }

    $link = $url;
} else {
    $link = TB_MURL;
}

goto_url($link);
?>
