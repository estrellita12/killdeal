<?php
include_once('./_common.php');

if(!$is_member)
    alert('로그인 한 회원만 접근하실 수 있습니다.', TB_BBS_URL.'/login.php');

if($member['sns_id']) {
	goto_url(TB_BBS_URL.'/register_form.php?w=u');
}

$tb['title'] = '회원 비밀번호 확인';
include_once('./_head.php');

$url = clean_xss_tags($_GET['url']);

// url 체크
check_url_host($url);

$url = get_text($url);

include_once(TB_THEME_PATH.'/member_confirm.skin.php');

include_once('./_tail.php');
?>