<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>
<?php if($board['fileurl1']) { ?>
<div class="m_bo_hd"><img src="<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></div>
<?php } ?>
<div class="m_bo_bg">
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
	//echo $sql;
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$bo_href = TB_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;
		$bo_subj = '<strong class="fc_eb7">[공지]</strong> '.get_text($row['subject']);
		$bo_date = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

		if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
			//$bo_subj .= " <img src='{$bo_imgurl}/img/iconY.gif' class='marl3'>";
		}

		$li_str .= '<li class="list">'.PHP_EOL;
		$li_str .= '	<a href="'.$bo_href.'">'.PHP_EOL;
		$li_str .= '	<p class="subj">'.$bo_subj.'</p>'.PHP_EOL;
		$li_str .= '	<p class="date">'.$bo_date.'</p>'.PHP_EOL;
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

		if($board['use_category'] == '1'  && $row['ca_name']) {
			$bo_subj .= '<strong>['.$row['ca_name'].']</strong> ';
		}

		$bo_subj = $bo_subj .get_text($row['subject']);
		$bo_date = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

		if($row['issecret'] == 'Y') {
			//$bo_subj .= " <img src='{$bo_imgurl}/img/icon_secret.gif'>";
		}

		if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
			//$bo_subj .= " <img src='{$bo_imgurl}/img/iconY.gif'>";
		}
		
		// 20191021 모바일 페이지 이미지 썸네일 추가
		//썸네일 주석처리 20191029 
		// 20191106 매거진 비디오 나눠서 처리 
		if($boardid == 43){
            $thumb = get_list_thumbnail($boardid, $row, 120, 0);
            $bo_imgurl = $thumb['src'];
            if(!$thumb['src']) {
			    $url = split_mgz_url($row['memo']);
			    $bo_imgurl = $url[0];
                if(!$bo_imgurl){	
                    $bo_imgurl = TB_IMG_URL.'/noimage.gif';
                }
            }

		}else if($boardid == 42){
			$bo_imgurl = "http://i.ytimg.com/vi/{$row['fileurl1']}/mqdefault.jpg";
		}else if($boardid == 45){
			$bo_imgurl = "{$row['fileurl1']}";
		}else {
            // 20210618
            $thumb = get_list_thumbnail($boardid, $row, 120, 0);
            if(!$thumb['src']) {
                $bo_imgurl = TB_IMG_URL.'/noimage.gif';
            }else{
                $bo_imgurl =  $thumb['src'];
            }
		    //	$tag = split_youtube_tag($row['memo']);
		    //	$bo_imgurl = merge_thumnail_url($tag[0]);
		}
		
		/* 20191106 모바일 매거진 기존 코드 주석처리
		$li_str .= '<li class="list">'.PHP_EOL;
		$li_str .= '	<a href="'.$bo_href.'">'.PHP_EOL;
		//$li_str .= '	<img src="'.$bo_imgurl.'">'.PHP_EOL;
		$li_str .= '	<p class="subj">'.$bo_subj.'</p>'.PHP_EOL;
		$li_str .= '	<p class="date">'.$bo_date.'</p>'.PHP_EOL;
		$li_str .= '	</a>'.PHP_EOL;
		$li_str .= '</li>'.PHP_EOL;
		*/
		$li_str .= '<li class="list">';
		$li_str .= '<div>';
		//if($boardid == 42) {
		//	$li_str .= ' <a href="https://www.youtube.com/watch/'.$tag[0].'" target=\"_blank\"> ';
		//} else {
			$li_str .= ' <a href="'.$bo_href.'">';
		//}
		if($boardid != 13){
			$li_str .= '    <dt>';
			$li_str .= '	<img src="'.$bo_imgurl.'" alt="골프매거진썸네일">';
			$li_str .= '    </dt>';
		}
		$li_str .= '    <dd>';
		$li_str .= '	<p class="subj">'.$bo_subj.'</p>';
		$li_str .= '	<p class="date">'.date("y/m/d",$row['wdate']).'</p>'.PHP_EOL;
		$li_str .= '    </dd>';
		$li_str .= '	</a>';
		$li_str .= '</div>';
		$li_str .= '</li>'.PHP_EOL;
		$li_run++;
	}

	if($li_run > 0)
		echo "<ul>\n{$li_str}</ul>\n";
	else
		echo "<p class=\"empty_list\">게시글이 없습니다.</p>\n";
	?>

	<?php if($member['grade'] <= $board['write_priv']) { ?>
	<div class="btn_confirm">
		<a href="<?php echo TB_MBBS_URL; ?>/board_write.php?boardid=<?php echo $boardid; ?>" class="btn_medium">글쓰기</a>
	</div>
	<?php } ?>

	<?php
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?boardid='.$boardid.'&page=');
	?>

	<form name="searchform" method="get">
	<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
	<div class="bottom_sch">
		<label>
			<select name="sfl">
			<?php
			for($i=0;$i<sizeof($gw_search_value);$i++) {
				echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $sfl).">{$gw_search_text[$i]}</option>\n";
			}
			?>
			</select>
		</label>
		<label><input type="text" name="stx" class="frm_input" value="<?php echo $stx; ?>"></label>
		<input type="submit" value="검색" class="btn_small grey">
	</div>
	</form>
</div>
