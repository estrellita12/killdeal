<?php
include_once("_common.php");

// 관리자 세션 변수에 등록
if(is_admin()) {
	set_session('admin_ss_mb_id', get_session('ss_mb_id'));
}

// 세션 변수에 등록
set_session('ss_mb_id', $_GET['mb_id']);

if($_GET['lg_type'] == 'S')
	goto_url(TB_MYPAGE_URL.'/page.php?code=seller_main');
else if($_GET['lg_type'] == 'P') //가맹점정보->주문리스트로 변경_20191029
	goto_url(TB_MYPAGE_URL.'/page.php?code=partner_order_admlist');
else
	goto_url(TB_URL);
?>