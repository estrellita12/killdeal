<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	//**모바일페이지 생성 필요
	goto_url(TB_MBBS_URL.'/register2.php');
}


//20190801
//리바트 첫 접근시 is_member =1이므로 이부분을 주석처리
/*
if($is_member) {
	goto_url(TB_URL);
}
*/

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes'] && $pt_id == 'admin') {
	alert($config['admin_reg_msg'], TB_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");

$tb['title'] = '약관동의';
//include_once("./_head.php");
//head_start

//관리자 로그인해도 체크가 X
if(!$is_admin)
{
    if($pt_id == "golf") 
    {
		 //header("Content-Type:text/html;charset=euc-kr");
         if(!get_session('mem_no')) //shopevent_no , shop_no
	     {
			 
		     exit('restrict access!!!');//한글 출력시 문자캐릭터셋 변경 필요
			
         }
    }
}

include_once(TB_PATH.'/head.sub.php');

if($pt_id == 'golfpang'){

	include_once(TB_THEME_PATH.'/head2.skin.php');

}else if($pt_id == 'golfu'){ //골프유닷넷

    include_once(TB_THEME_PATH.'/golfu_head.skin.php');

}else if($pt_id == 'dodamgolf'){ //20191120 도담골프 헤더 분기

    include_once(TB_THEME_PATH.'/dodam_head.skin.php');
    //include_once(TB_THEME_PATH.'/dodam/main.html');

}else { //killdeal
    include_once(TB_THEME_PATH.'/head.skin.php');
}


//head_end



//**회원정보를 insert하는 페이지로 수정할것(hwelfare_member)
$register_action_url = TB_BBS_URL.'/hwelfare_update.php';
include_once(TB_THEME_PATH.'/register2.skin.php');

include_once("./_tail.php");
?>