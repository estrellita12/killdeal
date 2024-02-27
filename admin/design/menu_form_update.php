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
update('shop_default', $value);

goto_url(TB_ADMIN_URL.'/design.php?code=menu_form');
?>