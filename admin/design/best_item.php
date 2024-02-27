<?php
if(!defined('_TUBEWEB_')) exit;

if($default['de_maintype_title'] == '') {
	$default['de_listing_best']  = 1;
} 
?>

<form name="fregform" method="post" action="./design/best_item_update.php">
<input type="hidden" name="token" value="">

<h2><?php echo $default['de_maintype_title']; ?></h2>
<div style="margin-bottom:10px; text-align:right; margin-top: -35px;">
	<input type="checkbox" name="de_listing_best" value="1" id="de_listing_best"<?php echo get_checked($default['de_listing_best'], "1"); ?>> <label for="de_listing_best">수동진열</label><br>비선택시 베스트상품 자동진열
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="80px">
		<col width="230px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">구분</th>
		<th scope="col">명칭</th>
		<th scope="col">상품코드</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="list1">타이틀명</td>
		<td><input type="text" name="de_maintype_title" value="<?php echo $default['de_maintype_title']; ?>" required itemname="최상위 타이틀명" class="required frm_input wfull" placeholder="최상위 타이틀명"></td>
		<td></td>
	</tr>
	<?php
	$list = unserialize(base64_decode($default['de_maintype_best']));
	for($i=0; $i<10; $i++) {
	?>
	<tr>
		<td class="list1">탭메뉴 <?php echo ($i+1); ?></td>
		<td><input type="text" name="maintype_subj[]" value="<?php echo $list[$i]['subj']; ?>" class="frm_input wfull" placeholder="탭메뉴명"></td>
		<td><input type="text" name="maintype_code[]" value="<?php echo $list[$i]['code']; ?>" class="frm_input wfull" placeholder="상품코드 입력 콤마(,)로 구분하세요."></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>
