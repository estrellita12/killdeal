<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "API 등록";
include_once("./admin_head.sub.php");

if(!$partner['de_maintype_title'])
	$partner['de_maintype_title'] = $default['de_maintype_title'];

if(!$partner['de_maintype_best'])
    $partner['de_maintype_best'] = $default['de_maintype_best'];
    

//가맹점 상품 등록

$sql_pt = $member['id'];

$res = sql_fetch("select gs_id from recommend_good where pt_id = '$member[id]'");
?>

<form name="fregform" method="post" action="./partner_recommend_item_update.php">
<input type="hidden" name="token" value="">

<h2><?php echo $partner['de_maintype_title']; ?></h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="80px">
		<col width="90%">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">분류</th>
		<th scope="col">상품코드</th>
	</tr>
	</thead>
	<tbody>
	<?php
	//print_r( $res );
	$list = unserialize(base64_decode($res['gs_id']));
	for($i=0; $i<10; $i++) {
	?>
	<tr>
		<td><input type="text" name="maintype_subj[]" value="<?php echo $list[$i]['subj']; ?>" class="frm_input wfull" placeholder="분류명"></td>
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

<?php
include_once("./admin_tail.sub.php");
?>