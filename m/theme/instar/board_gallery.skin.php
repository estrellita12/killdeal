<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo TB_MJS_URL; ?>/masonry.pkgd.js"></script>
<script src="<?php echo TB_MJS_URL; ?>/imagesloaded.pkgd.min.js"></script>

<?php if($board['fileurl1']) { ?>
<div class="m_bo_hd"><img src="<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></div>
<?php } ?>
<div class="m_gall">
	<?php if($board['use_category']) { ?>
	<select name="faq_type" class="faq_sch" onchange="location=this.value;">
		<option value="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>">전체보기</option>
		<?php
		for($i=0; $i<count($usecate); $i++) {
			$selected = "";
			if($usecate[$i]==$ca_name) {
				$selected = ' selected';
			}
		?>
		<option value="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>&ca_name=<?php echo $usecate[$i]; ?>"<?php echo $selected; ?>><?php echo $usecate[$i]; ?></option>
		<?php } ?>
	</select>
	<?php } ?>

	<?php
	$li_run = 0;
	$li_str = '';

	$sql = " select * from shop_board_{$boardid} where btype = '1' {$add_search} order by fid desc ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$bo_href = TB_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;
		$bo_subj = '<strong class="fc_eb7">[공지]</strong> '.get_text($row['subject']);
		$bo_date = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

		if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
			$bo_subj .= " <img src='{$bo_imgurl}/img/iconY.gif' class='marl3'>";
		}

		$thumb = get_list_thumbnail($boardid, $row, 400, 0);
		if(!$thumb['src']) {
			$thumb['src'] = TB_IMG_URL.'/noimage.gif';
		}

		$li_str .= '<li class="gall_item">'.PHP_EOL;
		$li_str .= '	<a href="'.$bo_href.'">'.PHP_EOL;
		$li_str .= '	<dl>'.PHP_EOL;
		$li_str .= '		<dt><img src="'.$thumb['src'].'" class="lazyload"></dt>'.PHP_EOL;
		$li_str .= '		<dd class="subj">'.$bo_subj.'</dd>'.PHP_EOL;
		$li_str .= '		<dd class="date">'.$bo_date.'</dd>'.PHP_EOL;
		$li_str .= '	</dl>'.PHP_EOL;
		$li_str .= '	</a>'.PHP_EOL;
		$li_str .= '</li>'.PHP_EOL;

		$li_run++;
	}

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bo_href = TB_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;
		$bo_subj = '';
		$spacer = strlen($row['thread'] != 'A');
		if($spacer>$bo_reply_limit) {
			$spacer = $bo_reply_limit;
		}

		for($g=0; $g<$spacer; $g++) {
			$bo_subj = "<img src='{$bo_imgurl}/img/icon_reply.gif'> ";
		}

		$bo_subj = $bo_subj .get_text($row['subject']);
		$bo_date = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

		if($row['issecret'] == 'Y') {
			$bo_subj .= " <img src='{$bo_imgurl}/img/icon_secret.gif'>";
		}

		if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
			$bo_subj .= " <img src='{$bo_imgurl}/img/iconY.gif'>";
		}

		$thumb = get_list_thumbnail($boardid, $row, 400, 0);
		if(!$thumb['src']) {
			$thumb['src'] = TB_IMG_URL.'/noimage.gif';
		}

		$li_str .= '<li class="gall_item">'.PHP_EOL;
		$li_str .= '	<a href="'.$bo_href.'">'.PHP_EOL;
		$li_str .= '	<dl>'.PHP_EOL;
		$li_str .= '		<dt><img src="'.$thumb['src'].'" class="lazyload"></dt>'.PHP_EOL;
		$li_str .= '		<dd class="subj">'.$bo_subj.'</dd>'.PHP_EOL;
		$li_str .= '		<dd class="date">'.$bo_date.'</dd>'.PHP_EOL;
		$li_str .= '	</dl>'.PHP_EOL;
		$li_str .= '	</a>'.PHP_EOL;
		$li_str .= '</li>'.PHP_EOL;

		$li_run++;
	}

	if($li_run > 0)
		echo "<ul id=\"m_gall_ul\">\n{$li_str}</ul>\n";
	else
		echo "<p class=\"empty_list\">게시글이 없습니다.</p>\n";
	?>

	<?php
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?boardid='.$boardid.'&page=');
	?>

	<?php if($member['grade'] <= $board['write_priv']) { ?>
	<div class="btn_confirm">
		<a href="<?php echo TB_MBBS_URL; ?>/board_write.php?boardid=<?php echo $boardid; ?>" class="btn_medium">글쓰기</a>
	</div>
	<?php } ?>

	<form name="searchform" method="get">
	<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
	<div class="bottom_sch">
		<select name="sfl">
		<?php
		for($i=0;$i<sizeof($gw_search_value);$i++) {
			echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $sfl).">{$gw_search_text[$i]}</option>\n";
		}
		?>
		</select>
		<input type="text" name="stx" class="frm_input" value="<?php echo $stx; ?>">
		<input type="submit" value="검색" class="btn_small grey">
	</div>
	</form>
</div>

<script>
/* Mobile 브라우져 체크 함수 */
function mobile_chk(){
	var user_device = navigator.userAgent.toLowerCase();
	var mobile_device = new Array('iphone','ipad', 'firefox', 'android');
	for(var i=0;i<mobile_device.length;i++){
		if(user_device.indexOf(mobile_device[i]) != -1)	return true;
	}
	return false;
}

var $masgall = $('#m_gall_ul').masonry({
	itemSelector: '.gall_item',
	percentPosition: true,
	columnWidth: '.gall_item'
});
// layout Masonry after each image loads
$masgall.imagesLoaded().progress( function() {
	$masgall.masonry();
});
</script>
