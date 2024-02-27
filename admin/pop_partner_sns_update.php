<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/register.lib.php");

check_demo();

check_admin_token();

$mb_id = trim($_POST['mb_id']);

$mb = get_member($mb_id);
if(!$mb['id']) {
    alert('존재하지 않는 회원자료입니다.');
}

if($mb_id == 'admin') {
    alert('최고관리자는 수정하실 수 없습니다.');
}

if($member['id'] != 'admin' && $mb['grade'] <= $member['grade']) {
    alert('자신보다 레벨이 높거나 같은 회원은 수정할 수 없습니다.');
}

if($mb_id == $member['id'] && $mb_grade != $mb['grade']) {
    alert($mb_id.' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');
}


unset($pfrm);
$pfrm['de_sns_login_use']		    = $_POST['de_sns_login_use'];
$pfrm['de_naver_appid']		    = $_POST['de_naver_appid'];
$pfrm['de_naver_secret']		    = $_POST['de_naver_secret'];
$pfrm['de_kakao_rest_apikey']		    = $_POST['de_kakao_rest_apikey'];
$pfrm['de_googl_shorturl_apikey']		    = $_POST['de_googl_shorturl_apikey'];
$pfrm['de_insta_url']		    = $_POST['de_insta_url'];
$pfrm['de_insta_client_id']		    = $_POST['de_insta_client_id'];
$pfrm['de_insta_redirect_uri']		    = $_POST['de_insta_redirect_uri'];
$pfrm['de_insta_access_token']		    = $_POST['de_insta_access_token'];

$pfrm['de_sns_facebook']		    = $_POST['de_sns_facebook'];
$pfrm['de_sns_twitter']		    = $_POST['de_sns_twitter'];
$pfrm['de_sns_instagram']		    = $_POST['de_sns_instagram'];
$pfrm['de_sns_pinterest']		    = $_POST['de_sns_pinterest'];
$pfrm['de_sns_naverblog']		    = $_POST['de_sns_naverblog'];
$pfrm['de_sns_naverband']		    = $_POST['de_sns_naverband'];
$pfrm['de_sns_kakaotalk']		    = $_POST['de_sns_kakaotalk'];
$pfrm['de_sns_kakaostory']		    = $_POST['de_sns_kakaostory'];

//$pfrm['update_time']		= TB_TIME_YMDHIS;
update("shop_partner", $pfrm," where mb_id='$mb_id'");

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id,$pageName,'가맹점 소셜 네트워크 설정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_sns.php?mb_id='.$mb_id);

?>
