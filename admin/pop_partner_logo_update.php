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

$upl_dir = TB_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($pfrm);
if($ico = $_FILES['favicon_ico']['name']) {
    if(!preg_match("/(\.ico)$/i", $ico))
        alert("파비콘 아이콘은 ico 파일만 업로드 가능합니다.");
}

$lg = sql_fetch("select * from shop_logo where mb_id = '{$mb_id}'");

if($basic_logo_del) {
    $upl->del($lg['basic_logo']);
    $pfrm['basic_logo'] = '';
}
if($mobile_logo_del) {
    $upl->del($lg['mobile_logo']);
    $pfrm['mobile_logo'] = '';
}
if($sns_logo_del) {
    $upl->del($lg['sns_logo']);
    $pfrm['sns_logo'] = '';
}
if($favicon_ico_del) {
    $upl->del($lg['favicon_ico']);
    $pfrm['favicon_ico'] = '';
}

if($_FILES['basic_logo']['name']) {
    $pfrm['basic_logo'] = $upl->upload($_FILES['basic_logo']);
}
if($_FILES['mobile_logo']['name']) {
    $pfrm['mobile_logo'] = $upl->upload($_FILES['mobile_logo']);
}
if($_FILES['sns_logo']['name']) {
    $pfrm['sns_logo'] = $upl->upload($_FILES['sns_logo']);
}
if($_FILES['favicon_ico']['name']) {
    $pfrm['favicon_ico'] = $upl->upload($_FILES['favicon_ico']);
}

$pfrm['mb_id'] = $mb_id;

if($lg['index_no']) {
    update("shop_logo", $pfrm, "where mb_id = '{$mb_id}'");
} else {
    insert("shop_logo", $pfrm);
}

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id, $pageName,'가맹점 정보 수정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_logo.php?mb_id='.$mb_id);

?>
