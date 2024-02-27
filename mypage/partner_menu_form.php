<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "메뉴 관리";
include_once("./admin_head.sub.php");

$pname_run = 0;
for($i=0; $i<count($gw_menu); $i++) {
	$seq = ($i+1);
	//if(!$partner['de_pname_'.$seq]) {
	//	$pname_run++;
	//}
	if( $partner['de_pname_'.$seq] ) {
		$pname_run++;
	}
}

if(!$pname_run) {
	for($i=0; $i<count($gw_menu); $i++) {
		$seq = ($i+1);
		$partner['de_pname_use_'.$seq] = $default['de_pname_use_'.$seq];
		$partner['de_pname_'.$seq]	   = $default['de_pname_'.$seq];
	}
}
?>

<form name="fregform" method="post" action="./partner_menu_form_update.php">
<input type="hidden" name="token" value="">

<h2>메뉴 설정</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="80px">
		<col width="50px">
		<col width="200px">
		<col width="200px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th>구분</th>
		<th>노출</th>
		<th>메뉴</th>
		<th>기본</th>
		<th>URL</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $i<count($gw_menu); $i++) {
		$seq = ($i+1);
	?>
	<tr>
		<td class="list1">메뉴<?php echo $seq; ?></td>
		<!-- <td><input type="checkbox" name="de_pname_use_<?php echo $seq; ?>" value="1"<?php echo $partner['de_pname_use_'.$seq]?' checked':''; ?>></td> -->
        <td>
            <select name="de_pname_use_<?php echo $seq; ?>" >
                 <?php echo option_selected(0, $partner['de_pname_use_'.$seq], '표시안함'); ?>
                 <?php echo option_selected(1, $partner['de_pname_use_'.$seq], '둘다표시'); ?>
                 <?php echo option_selected(2, $partner['de_pname_use_'.$seq], 'PC만표시'); ?>
                 <?php echo option_selected(3, $partner['de_pname_use_'.$seq], 'Mobile만표시'); ?>
            </select>
        </td>
		<td><input type="text" name="de_pname_<?php echo $seq; ?>" value="<?php echo $partner['de_pname_'.$seq]; ?>" class="frm_input" placeholder="메뉴명"></td>
		<td class="tal"><?php echo $gw_menu[$i][0]; ?></td>
		<td class="tal"><?php echo $admin_shop_url.$gw_menu[$i][1]; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<?php
include_once("./admin_tail.sub.php");
?>
