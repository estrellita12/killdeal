<?php
if(!defined('_TUBEWEB_')) exit;

if($w == '') {
	$pl['mgz_use'] = 1;
	$pl['mgz_order']  = 0;
} else if($w == 'u') {
	$pl = sql_fetch("select * from shop_goods_magazine where mgz_no = '{$mgz_no}' ");
	if(!$pl['mgz_no'])
		alert('자료가 존재하지 않습니다.');
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./goods.php?code=magazine'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="./goods.php?code=magazine_form" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fregform" method="post" action="./goods/goods_magazine_form_update.php" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="mgz_no" value="<?php echo $mgz_no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">매거진명</th>
		<td><input type="text" name="mgz_name" value="<?php echo $pl['mgz_name']; ?>" required itemname="매거진명" class="frm_input required" size="50"></td>
	</tr>
	<?php if($w == 'u') { ?>
	<tr>
		<th scope="row">매거진 URL</th>
		<td>
			<input type="text" value="/shop/magazinelist.php?mgz_no=<?php echo $mgz_no; ?>" class="frm_input" size="50" readonly style="background-color:#ddd;"> <a href="/shop/magazinelist.php?mgz_no=<?php echo $mgz_no; ?>" target="_blank" class="btn_small grey">매거진 바로가기</a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">노출여부</th>
		<td><input type="checkbox" name="mgz_use" value="1" id="mgz_use"<?php echo get_checked($pl['mgz_use'], "1"); ?>> <label for="mgz_use">노출함</label></td>
	</tr>
	<tr>
		<th scope="row">노출순서</th>
		<td><input type="text" name="mgz_order" value="<?php echo $pl['mgz_order']; ?> " class="frm_input"> 숫자가 작을수록 우선 순위로 노출 됩니다.</td>
	</tr>
	<tr>
		<th scope="row">관련상품코드</th>
		<td>
			<textarea name="mgz_it_code" class="frm_input wfull" style="height:350px;resize:none;"><?php echo $pl['mgz_it_code']; ?></textarea>
			<?php echo help('※ 엔터로 구분해서 입력해주세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">목록이미지</th>
		<td>
			<input type="file" name="mgz_limg" id="mgz_limg">
			<?php
			$bimg_str = "";
			$bimg = TB_DATA_PATH.'/magazine/'.$pl['mgz_limg'];
			if(is_file($bimg) && $pl['mgz_limg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = rpc($bimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="mgz_limg_del" value="'.$pl['mgz_limg'].'" id="mgz_limg_del"> <label for="mgz_limg_del">삭제</label>';
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
			<input type="file" name="mgz_bimg" id="mgz_bimg">
			<?php
			$bimg_str = "";
			$bimg = TB_DATA_PATH.'/magazine/'.$pl['mgz_bimg'];
			if(is_file($bimg) && $pl['mgz_bimg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = rpc($bimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="mgz_bimg_del" value="'.$pl['mgz_bimg'].'" id="mgz_bimg_del"> <label for="mgz_bimg_del">삭제</label>';
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
