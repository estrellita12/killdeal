<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/plan";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['pl_limg_del']) {
	$upl->del($_POST['pl_limg_del']);
	$value['pl_limg'] = '';
}
if($_POST['pl_bimg_del']) {
	$upl->del($_POST['pl_bimg_del']);
	$value['pl_bimg'] = '';
}
if($_FILES['pl_limg']['name']) {
	$value['pl_limg'] = $upl->upload($_FILES['pl_limg']);
}
if($_FILES['pl_bimg']['name']) {
	$value['pl_bimg'] = $upl->upload($_FILES['pl_bimg']);
}

$value['mb_id']		 = 'admin';
$value['pl_name']	 = $_POST['pl_name'];
$value['pl_memo'] = $_POST['pl_memo'];
$value['pl_it_code'] = $_POST['pl_it_code'];
$value['pl_use']	 = $_POST['pl_use'];

// 2021-10-29
if( $_POST['pl_sb_date'] != "" && $_POST['pl_sb_date'] != null ) $value['pl_sb_date']    = $_POST['pl_sb_date']." ". $_POST['pl_sb_time'];
if( $_POST['pl_ed_date'] != "" && $_POST['pl_ed_date'] != null ) $value['pl_ed_date']    = $_POST['pl_ed_date']." ". $_POST['pl_ed_time'];

if( $_POST['pl_sb_ini'] ) $value['pl_sb_date']   = '2000-01-01 00:00:00';
if( $_POST['pl_ed_ini'] ) $value['pl_ed_date']   = '3000-01-01 00:00:00';

if($w == '') {
    $value['pl_order']	 = 1;

    $sql = "update shop_goods_plan set pl_order=pl_order+1 where pl_order>0";
    sql_query($sql);

	insert("shop_goods_plan", $value);
	$pl_no = sql_insert_id();

	goto_url(TB_ADMIN_URL."/goods.php?code=plan_form&w=u&pl_no=$pl_no");

} else if($w == 'u') {
	update("shop_goods_plan", $value, "where pl_no='$pl_no'");

	goto_url(TB_ADMIN_URL."/goods.php?code=plan_form&w=u&pl_no=$pl_no$qstr&page=$page");
}
?>
