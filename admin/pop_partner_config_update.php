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
$pfrm['db_link_yes']		    = $_POST['db_link_yes'];
$pfrm['non_mem_access']		    = $_POST['non_mem_access'];
$pfrm['non_mem_allow']		    = $_POST['non_mem_allow'];

$pfrm['send_type']		        = $_POST['send_type'];
$pfrm['encryption_yes']		    = $_POST['encryption_yes'];
$pfrm['home_url']		    = $_POST['home_url'];
$pfrm['login_url']		    = $_POST['login_url'];

//$pfrm['update_time']		= TB_TIME_YMDHIS;
update("shop_partner", $pfrm," where mb_id='$mb_id'");

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id,$pageName,'가맹점 연동 정보 수정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_config.php?mb_id='.$mb_id);

?>
