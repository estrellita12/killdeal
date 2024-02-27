<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/register.lib.php');

// 불법접근을 막도록 토큰생성
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);
set_session("ss_cert_no",   "");
set_session("ss_cert_hash", "");
set_session("ss_cert_type", "");
set_session("ss_hash_token", TB_HASH_TOKEN);



if(!$is_member)
    alert('로그인 후 이용하여 주십시오.', TB_MURL);

if($member['id'] == 'admin')
    alert('관리자의 회원정보는 관리자 화면에서 수정해 주십시오.', TB_MURL);

/*
if(!($member[passwd] == sql_password($_POST[mb_password]) && $_POST[mb_password]))
    alert("비밀번호가 틀립니다.");

// 수정 후 다시 이 폼으로 돌아오기 위해 임시로 저장해 놓음
set_session("ss_tmp_password", $_POST[mb_password]);
*/

if($_POST['mb_password']) {
    // 수정된 정보를 업데이트후 되돌아 온것이라면 비밀번호가 암호화 된채로 넘어온것임
    if($_POST['is_update'])
        $tmp_password = $_POST['mb_password'];
    else
        $tmp_password = get_encrypt_string($_POST['mb_password']);

    if($member['passwd'] != $tmp_password)
        alert('비밀번호가 틀립니다.');
}

$tb['title'] = '회원정보수정';

set_session("ss_reg_mb_name", $member['name']);
set_session("ss_reg_mb_hp", $member['cellphone']);



include_once('./_head.php');

$required = ($w=='') ? ' required' : '';
$readonly = ($w=='u') ? ' readonly' : '';

$agree  = preg_replace('#[^0-9]#', '', $agree);
$agree2 = preg_replace('#[^0-9]#', '', $agree2);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
if($config['register_use_addr'])
    add_javascript(TB_POSTCODE_JS, 0); //다음 주소 js

$register_action_url = TB_HTTPS_MBBS_URL.'/register_form_update.php';
include_once(TB_MTHEME_PATH.'/register_form.skin.php');

include_once("./_tail.php");
?>