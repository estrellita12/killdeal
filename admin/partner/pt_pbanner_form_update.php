<?php
include_once("./_common.php");

check_demo();
check_admin_token();

$upl_dir = TB_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['bn_file_del']) {
	$upl->del($_POST['bn_file_del']);
	$value['bn_file'] = '';
}
if($_FILES['bn_file']['name']) {
	$value['bn_file'] = $upl->upload($_FILES['bn_file']);
}

$value['mb_id']		= $_POST['mb_id'];
$value['bn_device'] = $_POST['bn_device'];
$value['bn_theme']	= $_POST['bn_theme'];
$value['bn_code']	= $_POST['bn_code'];
$value['bn_link']	= $_POST['bn_link'];
$value['bn_target'] = $_POST['bn_target'];
$value['bn_width']	= $_POST['bn_width'];
$value['bn_height'] = $_POST['bn_height'];
$value['bn_bg']		= preg_replace("/([^a-zA-Z0-9])/", "", $_POST['bn_bg']);
$value['bn_text']	= $_POST['bn_text'];
$value['bn_use']	= $_POST['bn_use'];
$value['bn_order']	= $_POST['bn_order'];

// 2021-10-29
if( $_POST['bn_sb_date'] != "" && $_POST['bn_sb_date'] != null ) $value['bn_sb_date']    = $_POST['bn_sb_date']." ". $_POST['bn_sb_time'];
if( $_POST['bn_ed_date'] != "" && $_POST['bn_ed_date'] != null ) $value['bn_ed_date']    = $_POST['bn_ed_date']." ". $_POST['bn_ed_time'];
if( $_POST['bn_sb_ini'] ) $value['bn_sb_date']   = '2000-01-01 00:00:00';
if( $_POST['bn_ed_ini'] ) $value['bn_ed_date']   = '3000-01-01 00:00:00';


if($w == "") {
	insert("shop_banner", $value);
	$bn_id = sql_insert_id();

	goto_url(TB_ADMIN_URL."/partner.php?code=pbanner_form&w=u&mb_id=$mb_id&bn_device=$bn_device&bn_id=$bn_id");
} else if($w == "u") {
	update("shop_banner", $value," where bn_id='$bn_id'");

	goto_url(TB_ADMIN_URL."/partner.php?code=pbanner_form&w=u&mb_id=$mb_id&bn_device=$bn_device&bn_id=$bn_id&page=$page");
}
?>
