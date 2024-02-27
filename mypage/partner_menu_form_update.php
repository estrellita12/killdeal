<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
for($i=0; $i<count($gw_menu); $i++) {
	$seq = ($i+1);
	$value['de_pname_use_'.$seq] = $_POST['de_pname_use_'.$seq];
	$value['de_pname_'.$seq]	 = $_POST['de_pname_'.$seq];
}
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_menu_form');
?>