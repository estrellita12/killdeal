<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/magazine";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['mgz_limg_del']) {
	$upl->del($_POST['mgz_limg_del']);
	$value['mgz_limg'] = '';
}
if($_POST['mgz_bimg_del']) {
	$upl->del($_POST['mgz_bimg_del']);
	$value['mgz_bimg'] = '';
}
if($_FILES['mgz_limg']['name']) {
	$value['mgz_limg'] = $upl->upload($_FILES['mgz_limg']);
}
if($_FILES['mgz_bimg']['name']) {
	$value['mgz_bimg'] = $upl->upload($_FILES['mgz_bimg']);
}

$value['mb_id']		 = 'admin';
$value['mgz_name']	 = $_POST['mgz_name'];
$value['mgz_it_code'] = $_POST['mgz_it_code'];
$value['mgz_use']	 = $_POST['mgz_use'];
$value['mgz_order']	 = $_POST['mgz_order'];

if($w == '') {
	insert("shop_goods_magazine", $value);
	$mgz_no = sql_insert_id();

	goto_url(TB_ADMIN_URL."/goods.php?code=magazine_form&w=u&mgz_no=$mgz_no");

} else if($w == 'u') {
	update("shop_goods_magazine", $value, "where mgz_no='$mgz_no'");

	goto_url(TB_ADMIN_URL."/goods.php?code=magazine_form&w=u&mgz_no=$mgz_no$qstr&page=$page");
}
?>