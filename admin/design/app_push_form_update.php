<?php
include_once("./_common.php");

check_demo();
check_admin_token();

$upl_dir = TB_DATA_PATH."/appPush";
$upl = new upload_files($upl_dir);

unset($value);
/*
if($_POST['pu_img_del']) {
	$upl->del($_POST['pu_img_del']);
	$value['pu_img'] = '';
}
*/
if($_FILES['pu_img']['name']) {
	$value['pu_img'] = $upl->upload($_FILES['pu_img']);
}

$value['mb_id']		= 'admin';
$value['pu_to']     = $_POST['pu_to'];
$value['pu_title']   = $_POST['pu_title'];
$value['pu_body']   = $_POST['pu_body'];
$value['pu_link']   = $_POST['pu_link'];

if($w == '') {
    $value['wdate']   = TB_TIME_YMDHIS;
	insert("shop_app_push", $value);
	$pl_no = sql_insert_id();

	goto_url(TB_ADMIN_URL."/design.php?code=app_push_list&w=u&pu_no_no=$pu_no");

} else if($w == 'u') {
	update("shop_app_push", $value, "where pu_no='$pu_no'");

	goto_url(TB_ADMIN_URL."/design.php?code=app_push_list&w=u&pu_no=$pu_no$qstr&page=$page");
}
?>
