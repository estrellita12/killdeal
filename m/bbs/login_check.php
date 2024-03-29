<?php
define('_PURENESS_', true);
include_once("./_common.php");

$mb_id = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);
$pt_id = trim($_POST['pt_id']);

// 2023-01-12 골프몬 로그인 버튼 클릭 시 골프몬측 스크립트함수 작동
/*
if($pt_id == 'golfmon'){
    echo "<script> window.HybridApp.goLogin(); </script>";
    return;
}
*/

if(!$mb_id || !$mb_password)
    alert('회원아이디나 비밀번호가 공백이면 안됩니다.');

$mb = get_member($mb_id);

// 가입된 회원이 아니다. 패스워드가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 패스워드를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 패스워드가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if(!$mb['id'] || !check_password($mb_password, $mb['passwd'])) {
    alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}

// 차단된 아이디인가?
if($mb['intercept_date'] && $mb['intercept_date'] <= date("Ymd", TB_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['intercept_date']);
    alert('회원님의 아이디는 접근이 금지되어 있습니다.\\n처리일 : '.$date);
}

// 인트로 사용시 승인된 회원인지 체크
if(!is_admin($mb['grade']) && !$mb['use_app'] && $config['cert_admin_yes']) {
    alert("승인 된 회원만 로그인 가능합니다.");
}

// 관리비를 사용중일때 기간이 만료되었다면 로그인 차단
if($config['pf_expire_use'] && $config['pf_login_no']) {
    if(is_partner($mb['id']) && !is_null_time($mb['term_date'])) {
        if($mb['term_date'] < TB_TIME_YMD) {
            alert("회원님의 아이디는 관리비 미납으로 접근이 금지되어 있습니다.");
        }
    }
}

// (2021-01-12) 본사, 마니아몰, 박사장몰 회원 DB 공유로 인해 로그인 조건 추가
if( isset($_SESSION['pt_id']) ){
    if( !is_admin($mb['grade']) && !is_partner($mb['id']) && $mb['id']!='majorgolf' && $mb['id']!='hanshin' ){
        if( $mb['pt_id'] != $_SESSION['pt_id'] ){
            alert('가입된 회원아이디가 아닙니다.');
        }
    }
}


// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['id']);

// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['reg_time'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// 포인트 체크
$sum_point = get_point_sum($mb['id']);

$sql= " update shop_member set point = '$sum_point' where id = '{$mb['id']}' ";
sql_query($sql);


// 자동로그인 : 아이디 쿠키에 한달간 저장
if($auto_login) {
    // 쿠키 한달간 저장
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['passwd']);
    set_cookie('ck_mb_id', $mb['id'], 86400 * 31);
    set_cookie('ck_auto', $key, 86400 * 31);
} else {
    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

if($url) {
    // url 체크
    check_url_host($url);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match("/\?/", $link))
        $split= "&amp;";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    $post_check_keys = array('mb_id', 'mb_password', 'x', 'y', 'url', 'slr_url');

    foreach($_POST as $key=>$value) {
        if ($key && !in_array($key, $post_check_keys)) {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else  {
    $link = TB_MURL;
}

goto_url($link);
?>
