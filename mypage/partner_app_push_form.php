<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "통합 배너관리";
include_once("./admin_head.sub.php");


if($w == "") {

} else if($w == "u") {
	$row = sql_fetch("select * from shop_app_push where pu_no='$pu_no'");
    if(!$row['pu_no'])
        alert("팝업이 존재하지 않습니다.");
}
?>

<form name="fregform" method="post" action="./parnter_app_push_form_update.php" onsubmit="return fregform_submit(this);"  enctype="MULTIPART/FORM-DATA" >
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="pu_no" value="<?php echo $pu_no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">발송 날짜</th>
		<td>
            <?php if( isset($row['sdate']) && $row['sdate']){ echo $row['sdate'];}else{ echo "미발송";} ?>
		</td>
	</tr>
	<tr>
		<th scope="row">발송 대상</th>
		<td>
            <select name="pu_to">
                <option value="plan">plan</option>
            </select>
		</td>
	</tr>
	<tr>
		<th scope="row">제목</th>
		<td>
			<input type="text" name="pu_title" value="<?php echo $row['pu_title'] ?>" required itemname="푸시제목" class="required frm_input w300"></td>
		</td>
	</tr>
	<tr>
		<th scope="row">내용</th>
		<td>
			<input type="text" name="pu_body" value="<?php echo $row['pu_body'] ?>" itemname="푸시내용" class="frm_input w300"></td>
		</td>
	</tr>
	<tr>
		<th scope="row">이미지</th>
		<td>
			<input type="file" name="pu_img" required itemname="푸시이미지" class="required frm_input"></td>
		</td>
	</tr>
	<tr>
		<th scope="row">링크</th>
		<td>
			<input type="text" name="pu_link" value="<?php echo $row['pu_link'] ?>" itemname="푸시링크" class="frm_input w300"></td>
		</td>
	</tr>

	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./page.php?code=partner_app_push_list<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('memo'); ?>

    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#begin_date,#end_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
});
</script>
<?php
include_once("./admin_tail.sub.php");
?>

