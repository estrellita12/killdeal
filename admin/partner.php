<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU2;
$pg_num = 2;
$snb_icon = "<i class=\"fa fa-handshake-o\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
    alert("Access Denied");
}

if($code == "pform")		$pg_title2 = ADMIN_MENU2_01;
if($code == "pbasic")		$pg_title2 = ADMIN_MENU2_02;
if($code == "anewlist")		$pg_title2 = ADMIN_MENU2_03;
if($code == "termlist")		$pg_title2 = ADMIN_MENU2_04;
if($code == "plist")		$pg_title2 = ADMIN_MENU2_05;
if($code == "pbanner_list")	$pg_title2 = ADMIN_MENU2_17; // (2021-02-15)
if($code == "pbanner_form")	$pg_title2 = ADMIN_MENU2_17; // (2021-02-15)
if($code == "pgoods_plan")	$pg_title2 = ADMIN_MENU2_18; // (2021-02-15)
if($code == "ppopup_list")	$pg_title2 = ADMIN_MENU2_19; // (2021-02-15)
if($code == "paylist")		$pg_title2 = ADMIN_MENU2_06;
if($code == "balancelist")	$pg_title2 = ADMIN_MENU2_07;
if($code == "payrun")		$pg_title2 = ADMIN_MENU2_08;
if($code == "payhistory")	$pg_title2 = ADMIN_MENU2_09;
if($code == "leave")		$pg_title2 = ADMIN_MENU2_10;
if($code == "tree")			$pg_title2 = ADMIN_MENU2_11;
if($code == "calculate")			$pg_title2 = ADMIN_MENU2_12;
//if($code == "pruoduct_sales")	$pg_title2 = ADMIN_MENU2_14;
if($code == "product_sales")	$pg_title2 = ADMIN_MENU2_14;
//if($code == "daliy")			$pg_title2 = ADMIN_MENU2_15;
if($code == "daily")			$pg_title2 = ADMIN_MENU2_15;
if($code == "daliy2")			$pg_title2 = ADMIN_MENU2_16;


include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/partner/pt_{$code}.php");
	?>
</div>

<?php 
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>
