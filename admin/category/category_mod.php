<?php
include_once("./_common.php");

include_once(TB_ADMIN_PATH."/admin_head.php");

$srcfile = TB_DATA_PATH.'/category';
$upload_file = new upload_files($srcfile);
$ca_no = $_REQUEST['index_no'];

if(!is_dir($srcfile)) {
	@mkdir($srcfile, TB_DIR_PERMISSION);
	@chmod($srcfile, TB_DIR_PERMISSION);
}

if($_POST['mod_type'] == 'u') {
	check_demo();

	$ca = sql_fetch("select * from shop_category where index_no='$ca_no'");

	$sql_commend = '';

	if($cateimg1_del) {
		$upload_file->del($cateimg1_del);
		$sql_commend .= " , cateimg1 = '' ";
	}
	if($cateimg2_del) {
		$upload_file->del($cateimg2_del);
		$sql_commend .= " , cateimg2 = '' ";
	}
	if($headimg_del) {
		$upload_file->del($headimg_del);
		$sql_commend .= " , headimg = '' ";
	}
	if($m_headimg_del) {
		$upload_file->del($m_headimg_del);
		$sql_commend .= " , m_headimg = '' ";
	}
	if($_FILES['cateimg1']['name']) {
		$upload_file->del($ca['cateimg1']);
		$cateimg1 = $upload_file->upload($_FILES['cateimg1']);
		$sql_commend .= " , cateimg1 = '$cateimg1' ";
	}
	if($_FILES['cateimg2']['name']) {
		$upload_file->del($ca['cateimg2']);
		$cateimg2 = $upload_file->upload($_FILES['cateimg2']);
		$sql_commend .= " , cateimg2 = '$cateimg2' ";
	}
	if($_FILES['headimg']['name']) {
		$upload_file->del($ca['headimg']);
		$headimg = $upload_file->upload($_FILES['headimg']);
		$sql_commend .= " , headimg = '$headimg' ";
	}
	if($_FILES['m_headimg']['name']) {
		$upload_file->del($ca['m_headimg']);
		$m_headimg = $upload_file->upload($_FILES['m_headimg']);
		$sql_commend .= " , m_headimg = '$m_headimg' ";
	}

	$len = strlen($ca['catecode']);
	$sql_where = " where SUBSTRING(catecode,1,$len) = '{$ca['catecode']}' ";

	// 카테고리 숨김
	$sql = "update shop_category set cateuse = '$cateuse' {$sql_where} ";
	sql_query($sql);

	$sql = "update shop_category
			   set catename='".trim($catename)."',
				   headimgurl = '".trim($headimgurl)."',
				   m_headimgurl = '".trim($m_headimgurl)."'
			      {$sql_commend}
			 where index_no='$ca_no' ";
	sql_query($sql);

	goto_url(TB_ADMIN_URL."/category/category_mod.php?index_no=$ca_no");
}

$ca = sql_fetch("select * from shop_category where index_no='$ca_no'");
?>

<form name="fcgyform" method="post" action="./category_mod.php" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mod_type" value="u">
<input type="hidden" name="index_no" value="<?php echo $ca_no; ?>">
<input type="hidden" name="upcate" value="<?php echo $ca['catecode']; ?>">

<div class="tbl_frm02 mart10">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">카테고리 소속</th>
		<td class="bold"><?php echo adm_category_navi($ca['catecode']); ?></td>
	</tr>
	<tr>
		<th scope="row">카테고리명</th>
		<td>
			<input type="text" name="catename" value="<?php echo $ca['catename']; ?>" required itemname="카테고리명" class="frm_input required" size="50">
			<input type="checkbox" name="cateuse" value="1" id="cateuse"<?php echo ($ca['cateuse'])?" checked='checked'":""; ?>> <label for="cateuse">카테고리 숨김</label>
		</td>
	</tr>
	<?php /* ?>
	<tr>
		<th scope="row">카테고리 아이콘</th>
		<td>
			<input type="file" name="cateimg1">
			<?php
			$mimg_str = "";
			$mimg = $srcfile.'/'.$ca['cateimg1'];
			if(is_file($mimg) && $ca['cateimg1']) {
				$size = @getimagesize($mimg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$mimg = rpc($mimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="cateimg1_del" value="'.$ca['cateimg1'].'" id="cateimg1_del"> <label for="cateimg1_del">삭제</label>';
				$mimg_str = '<img src="'.$mimg.'" width="'.$width.'">';
			}
			if($mimg_str) {
				echo '<div class="banner_or_img">'.$mimg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 아이콘 (ON)</th>
		<td>
			<input type="file" name="cateimg2">
			<?php
			$timg_str = "";
			$timg = $srcfile.'/'.$ca['cateimg2'];
			if(is_file($timg) && $ca['cateimg2']) {
				$size = @getimagesize($timg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$timg = rpc($timg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="cateimg2_del" value="'.$ca['cateimg2'].'" id="cateimg2_del"> <label for="cateimg2_del">삭제</label>';
				$timg_str = '<img src="'.$timg.'" width="'.$width.'">';
			}
			if($timg_str) {
				echo '<div class="banner_or_img">'.$timg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<?php */ ?>
	<?php if(strlen($ca['catecode']) == 3) { ?>
	<tr>
		<th scope="row">카테고리 상단배너</th>
		<td>
			<input type="file" name="headimg">
			<?php
			$himg_str = "";
			$himg = $srcfile.'/'.$ca['headimg'];
			if(is_file($himg) && $ca['headimg']) {
				$size = @getimagesize($himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$himg = rpc($himg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="headimg_del" value="'.$ca['headimg'].'" id="headimg_del"> <label for="headimg_del">삭제</label>';
				$himg_str = '<img src="'.$himg.'" width="'.$width.'">';
			}
			if($himg_str) {
				echo '<div class="banner_or_img">'.$himg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 상단배너 링크</th>
		<td><input type="text" name="headimgurl" value="<?php echo $ca['headimgurl']; ?>" class="frm_input" size="50"></td>
	</tr>

	<tr>
		<th scope="row">카테고리 모바일 상단배너</th>
		<td>
			<input type="file" name="m_headimg">
			<?php
			$m_himg_str = "";
			$m_himg = $srcfile.'/'.$ca['m_headimg'];
			if(is_file($m_himg) && $ca['m_headimg']) {
				$size = @getimagesize($m_himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$m_himg = rpc($m_himg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="m_headimg_del" value="'.$ca['m_headimg'].'" id="m_headimg_del"> <label for="m_headimg_del">삭제</label>';
				$m_himg_str = '<img src="'.$m_himg.'" width="'.$width.'">';
			}
			if($m_himg_str) {
				echo '<div class="banner_or_img">'.$m_himg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 모바일 상단배너 링크</th>
		<td><input type="text" name="m_headimgurl" value="<?php echo $ca['m_headimgurl']; ?>" class="frm_input" size="50"></td>
	</tr>
	<?php } ?>
	
	<?php if(strlen($ca['catecode']) == 6) { ?>
	<tr>
		<th scope="row">카테고리 상단배너</th>
		<td>
			<input type="file" name="headimg">
			<?php
			$himg_str = "";
			$himg = $srcfile.'/'.$ca['headimg'];
			if(is_file($himg) && $ca['headimg']) {
				$size = @getimagesize($himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$himg = rpc($himg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="headimg_del" value="'.$ca['headimg'].'" id="headimg_del"> <label for="headimg_del">삭제</label>';
				$himg_str = '<img src="'.$himg.'" width="'.$width.'">';
			}
			if($himg_str) {
				echo '<div class="banner_or_img">'.$himg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 상단배너 링크</th>
		<td><input type="text" name="headimgurl" value="<?php echo $ca['headimgurl']; ?>" class="frm_input" size="50"></td>
	</tr>

	<tr>
		<th scope="row">카테고리 모바일 상단배너</th>
		<td>
			<input type="file" name="m_headimg">
			<?php
			$m_himg_str = "";
			$m_himg = $srcfile.'/'.$ca['m_headimg'];
			if(is_file($m_himg) && $ca['m_headimg']) {
				$size = @getimagesize($m_himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$m_himg = rpc($m_himg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="m_headimg_del" value="'.$ca['m_headimg'].'" id="m_headimg_del"> <label for="m_headimg_del">삭제</label>';
				$m_himg_str = '<img src="'.$m_himg.'" width="'.$width.'">';
			}
			if($m_himg_str) {
				echo '<div class="banner_or_img">'.$m_himg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 모바일 상단배너 링크</th>
		<td><input type="text" name="m_headimgurl" value="<?php echo $ca['m_headimgurl']; ?>" class="frm_input" size="50"></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_lsmall">
	<button type="button" onClick="cancel('<?php echo $ca_no; ?>')" class="btn_lsmall bx-white">닫기</button>
</div>
</form>

<script>
function cancel(no){
	parent.document.all['co'+no].style.display='none';
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>