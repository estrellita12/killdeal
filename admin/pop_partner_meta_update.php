<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$mb_id = trim($_POST['mb_id']);

$mb = get_member($mb_id);
if(!$mb['id']) {
    alert('존재하지 않는 회원자료입니다.');
}

if($member['id'] != 'admin' && $mb['grade'] <= $member['grade']) {
    alert('자신보다 레벨이 높거나 같은 회원은 수정할 수 없습니다.');
}

if($mb_id == $member['id'] && $mb_grade != $mb['grade']) {
    alert($mb_id.' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');
}

unset($pfrm);
$pfrm['head_title']		= $_POST['head_title']; //웹브라우져 타이틀
$pfrm['meta_author']		= $_POST['meta_author']; //Author : 메타태그 1
$pfrm['meta_description']	= $_POST['meta_description']; //description : 메타태그 2
$pfrm['meta_keywords']		= $_POST['meta_keywords']; //keywords : 메타태그 3
$pfrm['add_meta']			= $_POST['add_meta']; //추가 메타태그
$pfrm["head_script"]		= $_POST["head_script"]; //<HEAD> 내부 태그
$pfrm["tail_script"]		= $_POST["tail_script"]; //<BODY> 내부 태그
update("shop_partner", $pfrm," where mb_id='$mb_id'");

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id, $pageName,'가맹점 정보 수정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_meta.php?mb_id='.$mb_id);

?>
