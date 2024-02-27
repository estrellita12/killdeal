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

//partner.config.php 실행X ->아래의 로직을 추가함._20190925
if($_SERVER['HTTP_HOST'] =='mall.golfpang.com')
{
	$pt_id = 'golfpang';
}	
else if($_SERVER['HTTP_HOST'] =='shopping.golfu.net') 
{
	$pt_id = 'golfu';
}	
else if($_SERVER['HTTP_HOST'] =='shop.uscore.co.kr') 
{
	$pt_id = 'uscore';
}
	
//외부연동_쿠키삭제 필요_20190925
if($pt_id == 'golfpang')
{
	
	//내부 세션 처리후 연동사이트 로그아웃처리
	goto_url('http://www.golfpang.com/web/logout.do');
}
else if($pt_id == 'golfu')
{
	//내부 세션 처리후 연동사이트 로그아웃처리
	goto_url('http://www.golfu.net/Member/LogOut.aspx');
}
else if($pt_id == 'uscore')
{
	goto_url('https://www.uscore.co.kr/mapp/index.php');
}


if($url) {
    $p = parse_url($url);
    if($p['scheme'] || $p['host']) {
        alert("url에 도메인을 지정할 수 없습니다.");
    }

    $link = $url;
} else {
    $link = TB_URL;
}

goto_url($link);
?>
