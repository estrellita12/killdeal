<?php
include_once("./_common.php");

$tb['title'] = '카테고리 설정';
include_once(TB_ADMIN_PATH."/admin_head.php");

$sql_order = " order by caterank, catecode ";
?>

<h1 class="newp_tit"><?php echo $tb['title']; ?></h1>
<div class="new_win_body">
	<div class="sho_cate_bx">
		<div class="local_frm02">
			<a href="./pt_category.php?mb_id=<?php echo $mb_id; ?>" class="btn_lsmall bx-blue">처음으로</a>
		</div>
		<ul>
		<?php
		$sql = "select * from shop_category where length(catecode)='3' $sql_order ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$count1 = sel_count("shop_category", "where upcate='{$row['catecode']}' $sql_order");
			$href1 = "./pt_category.php?mb_id=$mb_id&sel_ca1={$row['catecode']}";

			$count_yes = false;
			if(in_array($mb_id, explode(",", $row['catehide']))) {
				$count_yes = true;
			}

			echo "<li>\n";
		?>
			<div>
				<img src="<?php echo TB_IMG_URL; ?>/icon/no_01_over.gif" class="vam" alt="1차">
				<b><?php echo $row['catecode']; ?></b>
				<input type="checkbox" name="catehide" value="1"<?php echo ($count_yes)?" checked='checked'":""; ?> onclick="check_sub('<?php echo $row['index_no']; ?>','<?php echo $mb_id; ?>');"> <b class="fc_00f">감춤</b>
				<a href="<?php echo $href1; ?>"><b><?php echo $row['catename']; ?></b></a> <b class="fc_255">(<?php echo $count1; ?>)</b>
			</div>
		<?php
		if($sel_ca1 && $sel_ca1==$row['catecode']) { // 2차
			echo "<dl class=\"cate2_bx\">\n";
			$sql2 = "select * from shop_category where upcate='$sel_ca1' $sql_order ";
			$res2 = sql_query($sql2);
			while($row2=sql_fetch_array($res2)) {
				$count2 = sel_count("shop_category", "where upcate='{$row2['catecode']}' $sql_order");
				$href2 = "{$href1}&sel_ca2={$row2['catecode']}";

				$count_yes = false;
				if(in_array($mb_id, explode(",", $row2['catehide']))) {
					$count_yes = true;
				}
		?>
			<dt>
				<img src="<?php echo TB_IMG_URL; ?>/icon/no_02.gif" class="vam" alt="2차">
				<b><?php echo $row2['catecode']; ?></b>
				<input type="checkbox" name="catehide" value="1"<?php echo ($count_yes)?" checked='checked'":""; ?> onclick="check_sub('<?php echo $row2['index_no']; ?>','<?php echo $mb_id; ?>');"> <b class="fc_00f">감춤</b>
				<a href="<?php echo $href2; ?>"><b><?php echo $row2['catename']; ?></b></a> <b class="fc_255">(<?php echo $count2; ?>)</b>
			</dt>
		<?php
		if($sel_ca2 && $sel_ca2==$row2['catecode']) { // 3차
			echo "<dd>\n<dl class=\"cate3_bx\">\n";
			$sql3 = "select * from shop_category where upcate='$sel_ca2' $sql_order";
			$res3 = sql_query($sql3);
			while($row3=sql_fetch_array($res3)) {
				$count3 = sel_count("shop_category", "where upcate='{$row3['catecode']}' $sql_order");
				$href3 = "{$href2}&sel_ca3={$row3['catecode']}";

				$count_yes = false;
				if(in_array($mb_id, explode(",", $row3['catehide']))) {
					$count_yes = true;
				}
		?>
			<dd>
				<img src="<?php echo TB_IMG_URL; ?>/icon/no_03.gif" class="vam" alt="3차">
				<b><?php echo $row3['catecode']; ?></b>
				<input type="checkbox" name="catehide" value="1"<?php echo ($count_yes)?" checked='checked'":""; ?> onclick="check_sub('<?php echo $row3['index_no']; ?>','<?php echo $mb_id; ?>');"> <b class="fc_00f">감춤</b>
				<a href="<?php echo $href3; ?>"><b><?php echo $row3['catename']; ?></b></a> <b class="fc_255">(<?php echo $count3; ?>)</b>
			</dd>
		<?php
		if($sel_ca3 && $sel_ca3==$row3['catecode']) { // 4차
			echo "<dd>\n<dl class=\"cate4_bx\">\n";
			$sql4 = "select * from shop_category where upcate='$sel_ca3' $sql_order";
			$res4 = sql_query($sql4);
			while($row4=sql_fetch_array($res4)) {
				$count4 = sel_count("shop_category", "where upcate='{$row4['catecode']}' $sql_order");
				$href4 = "{$href3}&sel_ca4={$row4['catecode']}";

				$count_yes = false;
				if(in_array($mb_id, explode(",", $row4['catehide']))) {
					$count_yes = true;
				}
		?>
			<dd>
				<img src="<?php echo TB_IMG_URL; ?>/icon/no_04.gif" class="vam" alt="4차">
				<b><?php echo $row4['catecode']; ?></b>
				<input type="checkbox" name="catehide" value="1"<?php echo ($count_yes)?" checked='checked'":""; ?> onclick="check_sub('<?php echo $row4['index_no']; ?>','<?php echo $mb_id; ?>');"> <b class="fc_00f">감춤</b>
				<a href="<?php echo $href4; ?>"><b><?php echo $row4['catename']; ?></b></a> <b class="fc_255">(<?php echo $count4; ?>)</b>
			</dd>
		<?php
		if($sel_ca4 && $sel_ca4==$row4['catecode']) { // 5차
			echo "<dd>\n<dl class=\"cate5_bx\">\n";
			$sql5 = "select * from shop_category where upcate='$sel_ca4' $sql_order";
			$res5 = sql_query($sql5);
			while($row5=sql_fetch_array($res5)) {
				$count_yes = false;
				if(in_array($mb_id, explode(",", $row5['catehide']))) {
					$count_yes = true;
				}
		?>
			<dd>
				<img src="<?php echo TB_IMG_URL; ?>/icon/no_05.gif" class="vam" alt="5차">
				<b><?php echo $row5['catecode']; ?></b>
				<input type="checkbox" name="catehide" value="1"<?php echo ($count_yes)?" checked='checked'":""; ?> onclick="check_sub('<?php echo $row5['index_no']; ?>','<?php echo $mb_id; ?>');"> <b class="fc_00f">감춤</b>
				<b><?php echo $row5['catename']; ?></b>
			</dd>
		<?php
										} //while 5
										echo "</dl>\n</dd>\n";
									} //if
								} //while 4
								echo "</dl>\n</dd>\n";
							} //if
						} //while 3
						echo "</dl>\n</dd>\n";
					} //if
				} //while 2
				echo "</dl>\n";
			} //if
			echo "</li>\n";
		} //while 1
		?>
		</ul>
	</div>
	<div class="btn_confirm">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
</div>

<script>
function check_sub(ca_no, mb_id) {
	var error = "";
	var token = get_ajax_token();
	if(!token) {
		alert("토큰 정보가 올바르지 않습니다.");
		return false;
	}

	$.ajax({
		url: "./pt_category_update.php",
		type: "POST",
		data: { "mb_id": mb_id, "ca_no": ca_no, "token": token },
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			error = data.error;
		}
	});

	if(error) {
		alert(error);
	}

	location.reload();
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>