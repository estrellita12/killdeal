<?php
if(!defined('_TUBEWEB_')) exit;

if(!$mb_id){
    alert("가맹점을 선택한 후 진행해주세요");
}

if($w == '') {
	$pl['pl_use'] = 1;
}else if($w == 'u') {
	$pl = sql_fetch("select * from shop_goods_plan where pl_no = '{$pl_no}' ");
	if(!$pl['pl_no'])
		alert('자료가 존재하지 않습니다.');
}


// 2021-10-28
$sb_date = ""; $ed_date = ""; $sb_ini = 0; $ed_ini = 0;
if($pl['pl_sb_date'] != '2000-01-01 00:00:00') $sb_date = explode(" ",$pl['pl_sb_date']);
else $sb_ini = 1;
if($pl['pl_ed_date'] != '3000-01-01 00:00:00') $ed_date = explode(" ",$pl['pl_ed_date']);
else $ed_ini = 1;


$frm_submit = '<div class="btn_confirm">
    <input type="submit" value="저장" class="btn_large" accesskey="s">
    <a href="./partner.php?code=pgoods_plan&mb_id='.$mb_id.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="./partner.php?code=pgoods_plan_form&mb_id='.$mb_id.'" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fregform" method="post" action="./partner/pt_pgoods_plan_form_update.php" onsubmit="return fboardform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="pl_no" value="<?php echo $pl_no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">기획전명</th>
		<td><input type="text" name="pl_name" value="<?php echo $pl['pl_name']; ?>" required itemname="기획전명" class="frm_input required" size="50"></td>
	</tr>

    <tr>
        <th scope="row">시작시간</th>
        <td>
            <input type="date" name="pl_sb_date" value="<?php echo $sb_date[0]; ?>" itemname="시작 날짜" class="frm_input">
            <input type="time" name="pl_sb_time" value="<?php echo isset($sb_date) ? $sb_date[1] : "00:00" ?>" itemname="시작 시간" class="frm_input">
            <span class="marl5 marr5">무제한</span><input type="checkbox" name="pl_sb_ini" <?php echo $sb_ini==1?"checked":"" ?>>
            <span class="fc_197 marl10">시작날짜의 시작시간부터 </span>
        </td>
    </tr>
    <tr>
        <th scope="row">종료시간</th>
        <td>
            <input type="date" name="pl_ed_date" value="<?php echo $ed_date[0]; ?>" itemname="종료 날짜" class="frm_input ">
            <input type="time" name="pl_ed_time" value="<?php echo isset($ed_date) ? $ed_date[1] : "00:00"; ?>" itemname="종료 시간" class="frm_input ">
            <span class="marl5 marr5">무제한</span><input type="checkbox" name="pl_ed_ini" <?php echo $ed_ini==1?"checked":"" ?>>
            <span class="fc_197 marl10">종료날짜의 종료시간 전까지</span>
        </td>
    </tr>


	<?php if($w == 'u') { ?>
	<tr>
		<th scope="row">기획전 URL</th>
		<td>
			<input type="text" value="/shop/planlist.php?pl_no=<?php echo $pl_no; ?>" class="frm_input" size="50" readonly style="background-color:#ddd;"> <a href="/shop/planlist.php?pl_no=<?php echo $pl_no; ?>" target="_blank" class="btn_small grey">기획전 바로가기</a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">노출여부</th>
		<td><input type="checkbox" name="pl_use" value="1" id="pl_use"<?php echo get_checked($pl['pl_use'], "1"); ?>> <label for="pl_use">노출함</label></td>
	</tr>
	<tr>
		<th scope="row">상세설명</th>
		<td>
			<?php echo editor_html('pl_memo', get_text(stripcslashes($pl['pl_memo']), 0)); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">관련상품코드</th>
		<td>
			<textarea name="pl_it_code" class="frm_input wfull" style="height:350px;resize:none;"><?php echo $pl['pl_it_code']; ?></textarea>
			<?php echo help('※ 엔터로 구분해서 입력해주세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">목록이미지</th>
		<td>
			<input type="file" name="pl_limg" id="pl_limg">
			<?php
			$bimg_str = "";
			$bimg = TB_DATA_PATH.'/plan/'.$pl['pl_limg'];
			if(is_file($bimg) && $pl['pl_limg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = rpc($bimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="pl_limg_del" value="'.$pl['pl_limg'].'" id="pl_limg_del"> <label for="pl_limg_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			echo help('사이즈(318픽셀 * 159픽셀)');
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">상단이미지</th>
		<td>
			<input type="file" name="pl_bimg" id="pl_bimg">
			<?php
			$bimg_str = "";
			$bimg = TB_DATA_PATH.'/plan/'.$pl['pl_bimg'];
			if(is_file($bimg) && $pl['pl_bimg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = rpc($bimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="pl_bimg_del" value="'.$pl['pl_bimg'].'" id="pl_bimg_del"> <label for="pl_bimg_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			echo help('사이즈(1000픽셀 * auto)');
			?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>
<script>
function fboardform_submit(f) {


	<?php echo get_editor_js('pl_memo'); ?>
	<?//php echo chk_editor_js('pl_memo'); ?>

    return true;
}
</script>
