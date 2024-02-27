<?php
if(!defined('_TUBEWEB_')) exit;

// 5차카테고리
function mobile_tree_category($catecode)
{
	global $pt_id;

	$t_catecode = $catecode;

	$sql_common = " from shop_category ";
	$sql_where  = " where cateuse = '0' and find_in_set('$pt_id', catehide) = '0' ";
	$sql_order  = " order by caterank, catecode ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} and upcate = '$catecode' ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$sql = "select * {$sql_common} {$sql_where} and upcate = '$catecode' {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) {
			echo '<div id="sct_ct">'.PHP_EOL;
			echo "<ul>".PHP_EOL;
		}
		if($i%3==0){ //3의 배수이면
			echo "<li {$border}>\n";
		} 

		$addclass = "";
		if($t_catecode==$row['catecode'])
			$addclass = ' class="sct_here"';

		$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];

		echo "<a href=\"{$href}\"{$addclass}>{$row['catename']}</a>\n";

	}
		if($i%3==0){ //3의 배수이면
			echo "</li>\n";
		} 
	if($i > 0) {
		echo '</ul>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
}

// mobile_display_goods("영역", "출력수", "타이틀", "클래스명")
function mobile_display_goods($type, $rows, $mtxt, $li_css='')
{
	global $default, $pt_id;

	//echo "<h2 class=\"mtit\"><span>{$mtxt}</span></h2>\n";
	echo "<ul class=\"{$li_css}\">\n";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale">'.number_format($sett,0).'%</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

        $rv_sum_li = get_review_sum_v2($row['index_no']);
        $review_avg = $rv_sum_li['avg'];
        $item_use_count = $rv_sum_li['cnt'];
		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<dl>\n";
				echo "<dt><img src=\"{$it_imageurl}\" alt=\"상품이미지\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				echo "<dd class=\"price\">{$sale}&nbsp;{$it_sprice}{$it_price}</dd>\n";
				if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
					echo "<dd class=\"petc\">\n";
					if($row['gpoint'])
						echo "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
					if($is_free_baesong)
						echo "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
					if($is_free_baesong2)
						echo "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
					echo "</dd>\n";
				}
                echo "<dd class='review'>";
                if($review_avg > 0){
                    echo "<img src=\"/img/sub/comment_start.jpg\" ><span>{$review_avg} ({$item_use_count})</span>";
                }
                echo "</dd>";
			echo "</dl>\n";
		echo "</a>\n";
		//echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"".TB_MSHOP_URL."/listtype.php?type=$type\" class=\"btn_lsmall bx-white1 wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
}

// mobile_display_goods3("영역", "출력수", "타이틀", "클래스명") MWDEAL테마
function mobile_display_goods3($type, $rows, $mtxt, $li_css='')
{
    global $default, $pt_id;

    //echo "<h2 class=\"mtit\"><span>{$mtxt}</span></h2>\n";
    echo "<ul class=\"{$li_css}\">\n";

    $result = display_itemtype($pt_id, $type, $rows);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
        $it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
        $it_name = get_text($row['gname']);
        $it_price = mobile_price($row['index_no']);
        $it_amount = get_sale_price($row['index_no']);
        $it_point = display_point($row['gpoint']);

        $is_uncase = is_uncase($row['index_no']);
        $is_free_baesong = is_free_baesong($row);
        $is_free_baesong2 = is_free_baesong2($row);

        // (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
        $it_sprice = $sale = '';
        if($row['normal_price'] > $it_amount && !$is_uncase) {
            $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
            $sale = '<span class="sale">'.number_format($sett,0).'%</span>';
            $it_sprice = display_price2($row['normal_price']);
        }

        $rv_sum_li = get_review_sum_v2($row['index_no']);
        $review_avg = $rv_sum_li['avg'];
        $item_use_count = $rv_sum_li['cnt'];
        echo "<li>\n";
            echo "<a href=\"{$it_href}\">\n";
            echo "<dl>\n";
                echo "<dt><img src=\"{$it_imageurl}\" alt=\"상품이미지\"></dt>\n";
                echo "<dd class=\"pname\">{$it_name}</dd>\n";
                //echo "<dd class=\"price\">{$sale}&nbsp;{$it_sprice}{$it_price}</dd>\n";
                echo "<dd class=\"price\">{$sale}<span class=\"price_box\"> {$it_sprice}<br><span class=\"recommendation\">특별할인★</span>{$it_price}</span></dd>\n";
                if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
                    echo "<dd class=\"petc\">\n";
                    if($row['gpoint'])
                        echo "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
                    if($is_free_baesong)
                        echo "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
                    if($is_free_baesong2)
                        echo "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
                    echo "</dd>\n";
                }

                echo "<dd class='review'>";
                if($review_avg > 0){
                    echo "<img src=\"/img/sub/comment_start.jpg\" ><span>{$review_avg} ({$item_use_count})</span>";
                }
                echo "</dd>";
            echo "</dl>\n";
        echo "</a>\n";
        //echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
        echo "</li>\n";
    }
    echo "</ul>\n";
}


// mobile_display_goods("영역", "출력수", "타이틀", "클래스명") 캐디테마
function mobile_display_goods2($type, $rows, $mtxt, $li_css='')
{
	global $default, $pt_id;

	//echo "<h2 class=\"mtit\"><span>{$mtxt}</span></h2>\n";
	echo "<ul class=\"{$li_css}\">\n";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale">'.number_format($sett,0).'%</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<dl>\n";
				echo "<dt><img src=\"{$it_imageurl}\" alt=\"상품이미지\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				echo "<dd class=\"price\">{$sale}&nbsp;{$it_sprice}{$it_price}</dd>\n";
				if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
					echo "<dd class=\"petc\">\n";
					if($row['gpoint'])
						echo "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
					if($is_free_baesong)
						echo "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
					if($is_free_baesong2)
						echo "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
					echo "</dd>\n";
				}
			echo "</dl>\n";
		echo "</a>\n";
		//echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
}

// mobile_slide_goods("영역", "출력수", "타이틀", "클래스명")
function mobile_slide_goods($type, $rows, $mtxt, $li_css='')
{
	global $default, $pt_id;

	echo "<h2><span>{$mtxt}</span></h2>\n";
	echo "<div class=\"{$li_css}\">\n";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

		echo "<dl>\n";
			echo "<a href=\"{$it_href}\">\n";
				echo "<dt><img src=\"{$it_imageurl}\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				echo "<dd class=\"price\">{$it_price}</dd>\n";
			echo "</a>\n";
		echo "</dl>\n";
	}
	echo "</div>\n";
}

// 메인 고객상품평 배열을 리턴
function mobile_review_rows($name, $rows)
{
	global $default, $pt_id;

	echo "<div class=\"main_post tline10\">\n";
	echo "<h2 class=\"m_tit\"><span class=\"mtxt\">$name</span></h2>\n";
	echo "<ul>\n";

	$sql_common = " from shop_goods_review ";
	$sql_search = " where (left(seller_id,3)='AP-' or seller_id = 'admin' or seller_id = '$pt_id') ";
	if($default['de_review_wr_use']) $sql_search .= " and pt_id = '$pt_id' ";
	$sql_order = " order by index_no desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id'], 'gname');
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		$it_name = get_text(cut_str($gs['gname'], 40));

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<p class=\"tit\">{$it_name}</p>\n";
			echo "<p>{$row['memo']}</p>\n";
			echo "</a>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=\"sct_noitem\">자료가 없습니다</li>\n";
	}

	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"".TB_MBBS_URL."/review.php\" class=\"btn_lsmall bx-white wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
	echo "</div>\n";
}

// 최근게시물 추출
function mobile_display_board($bo_table, $rows)
{
	global $default, $pt_id;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$sql = "select * from shop_board_{$bo_table} $sql_where order by wdate desc limit $rows ";
	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = get_text($row['subject']);
		$wdate	= date('Y.m.d',intval($row['wdate'],10));
		echo "<a href=\"".TB_MBBS_URL."/board_read.php?boardid=$bo_table&index_no=$row[index_no]\">$subject</a>";
	}

	if($i==0){ echo "게시물이 없습니다"; }
}

// 인기검색어 추출1
function mobile_popular($title, $rows, $pt_id)
{
	$str = "<h2>{$title}</h2>\n";
	$str.= "<ul id=\"ticker\">\n";

	$sql = " select pp_word, count(*) as cnt
			   from shop_popular
			  where pt_id = '$pt_id'
			    and TRIM(pp_word) <> ''
			  group by pp_word
			  order by cnt desc
			  limit $rows ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++){
		$word = get_text($row['pp_word']);
		$href = TB_MSHOP_URL.'/search.php?ss_tx='.$word;
		$rank = $i+1;

		$str.= "<li><a href=\"{$href}\"><span class=\"rkw_num\">{$rank}</span> {$word}</a></li>\n";
	}

	$str.= "</ul>\n";

	return $str;
}

// 인기검색어 추출2
function mobile_popular_rank($rows, $pt_id)
{
	$str = "<div class=\"m_rkw\">\n";
	$str.= "<ul>\n";

	$sql = " select pp_word, count(*) as cnt
			   from shop_popular
			  where pt_id = '$pt_id'
			    and TRIM(pp_word) <> ''
			  group by pp_word
			  order by cnt desc
			  limit $rows ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		$word = get_text($row['pp_word']);
		$href = TB_MSHOP_URL.'/search.php?ss_tx='.$word;
		$rank = $i+1;

		$str.= "<li><a href=\"{$href}\"><span class=\"rkw_num\">{$rank}</span> {$word}</a></li>\n";
	}

	$str.= "</ul>\n";
	$str.= "</div>\n";

	return $str;
}

// 쿠폰 : 상세내역
function mobile_cp_contents()
{
	global $row, $gw_usepart;

	$str = "";
	$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

	// 동시사용 여부
	$str .= "<div class='fc_197'>&#183; ";
	if(!$row['cp_dups']) {
		$str .= '동일한 주문건에 중복할인 가능';
	} else {
		$str .= '동일한 주문건에 중복할인 불가 (1회만 사용가능)';
	}
	$str .= "</div>";

	// 쿠폰유효 기간
	$str .= "<div>&#183; 쿠폰유효 기간 : ";
	if(!$row['cp_inv_type']) {
		// 날짜
		if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
		else $cp_inv_sdate = $row['cp_inv_sdate'];

		if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
		else $cp_inv_edate = $row['cp_inv_edate'];

		if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_sdate'] == '9999999999')
			$str .= '제한없음';
		else
			$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate;

		// 시간대
		$str .= "&nbsp;(시간대 : ";
		if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
		else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

		if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
		else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

		if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour1'] == '99')
			$str .= '제한없음';
		else
			$str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
		$str .= ")";
	} else {
		$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",strtotime($row['cp_wdate'])));
		$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
	}
	$str .= "</div>";

	// 혜택
	$str .= "<div>&#183; ";
	if($row['cp_sale_type'] == '0') {
		if($row['cp_sale_amt_max'] > 0)
			$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
		else
			$cp_sale_amt_max = "";

		$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
	} else {
		$str .= display_price($row['cp_sale_amt']). ' 할인';
	}
	$str .= "</div>";

	// 최대금액
	if($row['cp_low_amt'] > 0) {
		$str .= "<div>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
	}

	// 사용가능대상
	$str .= "<div>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

	return $str;
}

//  상품 상세페이지 : 배송비
function mobile_sendcost_amt()
{
	global $gs, $config, $sr;

	// 공통설정
	if($gs['sc_type']=='0') {
		if($gs['mb_id'] == 'admin') {
			$delivery_method  = $config['delivery_method'];
			$delivery_price	  = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price   = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
				$str = "무료배송";
				break;
			case '2':
				$str = "상품수령시 결제(착불)";
				break;
			case '3':
				$str = display_price($delivery_price);
				break;
			case '4':
				$str = "무료~".display_price($delivery_price2)."&nbsp;(조건부무료)";
				break;
		}

		// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1)
				$str = '상품수령시 결제(착불)';
			else if($gs['sc_method'] == 2) {
				$str = "<select name=\"ct_send_cost\" style=\"width:100%\">
							<option value=\"0\">주문시 결제(선결제)</option>
							<option value=\"1\">상품수령시 결제(착불)</option>
						</select>";
			}
		}
	} else if($gs['sc_type']=='1') {
		$str = "무료배송";
	} else if($gs['sc_type']=='2') {
		$str = "무료~".display_price($gs['sc_amt'])."&nbsp;(조건부무료)";
	} else if($gs['sc_type']=='3') {
		$str = display_price($gs['sc_amt']);
	}

	// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1)
			$str = '상품수령시 결제(착불)';
		else if($gs['sc_method'] == 2) {
            /* (2021-03-26)
			$str = "<select name=\"ct_send_cost\" style=\"width:100%\">
						<option value=\"0\">주문시 결제(선결제)</option>
						<option value=\"1\">상품수령시 결제(착불)</option>
					</select>";
            */
			$str = "<select name=\"ct_send_cost\" style=\"width:100%\">
						<option value=\"0\">주문시 결제(선결제)</option>
					</select>";
		}
	}

	return $str;
}

// 상품 가격정보의 배열을 리턴
function mobile_price($gs_id, $msg='<span>원</span>')
{
	global $member, $is_member,$pt_id;

	$gs = get_goods($gs_id, 'index_no, price_msg, buy_level, isopen, buy_only');

	$price = get_sale_price($gs_id);

	// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
	if(is_soldout($gs['index_no'])) {
        // (2021-03-31)
        if($gs['isopen'] == 4){
            $str = "<span class=\"soldout\">중지</span>";
        }else{
            $str = "<span class=\"soldout\">품절</span>";
        }

	} else {
		if($gs['price_msg']) {
			$str = $gs['price_msg'];
		} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
			$str = "";
		} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
			if(!$is_member)
				$str = "<span class=\"memopen\">회원공개</span>";
			else
				$str = "<span class=\"mpr\">".number_format($price).$msg."</span>";
		} else {
			$str = "<span class=\"mpr\">".number_format($price).$msg."</span>";
		}
	}
	if((!$is_member && $pt_id == 'maniamall')||(!$is_member && $pt_id == 'honggolf')){ //2020623 마니아몰 비회원 가격비공개
		$str = "<span class=\"memopen\">회원공개</span>";
	}

	return $str;
}

//  상품 상세페이지 : 구매하기, 장바구니, 찜 버튼
function mobile_buy_button($msg, $gs_id)
{
	global $gs, $pt_id;

	$ui_btn   = array("1"=>"장바구니","2"=>"구매하기","3"=>"찜하기");
	$ui_class = array("1"=>"btn_medium wset","2"=>"wset btn_medium","3"=>"btn_medium bx-white wish_btn");

	$str = "<div class=\"sp_btn\">";
	for($i=1; $i<=3; $i++) {
		switch($i){
			case '1':
				$sw_direct = "cart";
				$sw_direct2 = "cartback";
				$sw_position = "fbuyform_btn";
				$sw = "fbuyform_submit('".$sw_direct."');";
				break;
			case '2':
				$sw_direct = "buy";
				$sw_position = "";
				$sw = "fbuyform_submit('".$sw_direct."');";
				break;
			case '3':
				$sw_direct = "wish";
				$sw_position = "";
				$sw = "";
				break;
		}

		if($msg) {
			if($sw_direct == "buy") {
				/*
                // (2021-02-25)
                if($pt_id == 'golfya')
				{
					$str .= "<p><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'_golfya>$ui_btn[$i]</button></p>";
				}
				else if($pt_id == 'golfjam')
				{
					$str .= "<p><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'_golfjam>$ui_btn[$i]</button></p>";
				}
				else
				{*/
					$str .= "<p><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
				//}
			} else {
				$str .= "<span><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			}
		} else {
			if($sw_direct == "wish") {
				$str .= "<span><button type=\"button\" onclick=\"ajax_item_wish();\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			} else if($sw_direct == "buy") {
				/*if($pt_id == 'golfya')
				{
					$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]btn_medium_golfya'>$ui_btn[$i]</button></p>";
				}
				else if($pt_id == 'golfjam')
				{
					$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]btn_medium_golfjam'>$ui_btn[$i]</button></p>";
				}
				else
				{*/
					$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
				//}
				//$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
			} else {
				//$str .= "<span><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
				// $str .= "<span class=\"".$sw_position."\"><a href=\"javascript:".$sw."\" class=\"btn_large".$sw_css."\">".$ui_btn[$i]."</a>";
				$str .= "<div class=\"".$sw_position."\"><button type=\"button\" class=\"btn_medium open_popup".$sw_css."\">".$ui_btn[$i]."</button>";
				$str .= "	<div class=\"fbuyform_pop\" style=\"display:none;\">";
				$str .= "		<div>";
				$str .= "			<div class=\"fbuyform_pop_txt\">장바구니에 상품을 담았습니다.</div>";
				$str .= "			<div class=\"btn_box\">";	
				$str .= "				<span><a href=\"javascript:fbuyform_submit('".$sw_direct2."');\" class=\"bx-white back\">계속쇼핑</a></span>";
				$str .= "				<span><a href=\"javascript:fbuyform_submit('".$sw_direct."');\" class=\"grey\">장바구니로</a></span>";
				$str .= "			</div>";
				// $str .= "			<a href=\"javascript:;\" class=\"close\"></a>";
				$str .= "		</div>";
				$str .= "	</div>";
				$str .= "</div>";
			}
		}
	}

	$str .= "</div>";

	return $str;
}

// 상품 상세페이지 : 고객상품평
function mobile_goods_review($name, $cnt, $gs_id, $rows=10)
{
	global $member, $gw_star, $pt_id, $default;

	$sql_common = " from shop_goods_review ";
	$sql_search = " where gs_id = '$gs_id' ";
	if($default['de_review_wr_use']) {
		$sql_search .= " and pt_id = '$pt_id' ";
	}

	$sql_order  = " order by index_no desc limit $rows ";

	echo "<div class=sp_vbox_mr>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			echo "<li class='trst'><a href=\"javascript:window.open('".TB_MSHOP_URL."/view_user.php?gs_id=$gs_id');\">더보기</a><span class='im im_arr'></span></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$tmp_date  = substr($row['reg_time'],0,10);
		$tmp_score = $gw_star[$row['score']];

		$len = strlen($row['mb_id']);
		$str = substr($row['mb_id'],0,3);
		$tmp_name = $str.str_repeat("*",$len - 3);

		$hash = md5($row['index_no'].$row['reg_time'].$row['mb_id']);

		echo "<li class='lst'><span class=lst_post>$row[memo]</span>";
		echo "<span class='lst_h'><span class='fc_255'>$tmp_score</span> ";
		echo "<span class='fc_999'> / $tmp_name / $tmp_date";
		if(is_admin() || ($member['id'] == $row['mb_id'])) {
			echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:window.open('".TB_MSHOP_URL."/orderreview.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"".TB_MSHOP_URL."/orderreview_update.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=d&hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a>";
		}
		echo "</span></span>";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

//  상품 상세페이지 : Q&A
function mobile_goods_qa($name, $cnt, $gs_id)
{
	global $member;

	echo "<div class=sp_vbox_qa>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			//echo "<li class='trst'><a href=\"javascript:window.open('".TB_MSHOP_URL."/qaform.php?gs_id=$gs_id','_self');\" class='btn_lsmall black'>상품문의 작성</a></li>\n";
			echo "<li class='trst'><a href='".TB_MSHOP_URL."/qaform.php?gs_id=$gs_id' target='_self' class='btn_lsmall black'>상품문의 작성</a></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * from shop_goods_qa where gs_id='$gs_id' order by iq_time desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$iq_time = substr($row['iq_time'],0,10);

		$is_secret = false;
		if($row['iq_secret']) {
			$icon_secret = "<img src='".TB_IMG_URL."/icon/icon_secret.jpg' class='vam' alt='비밀글'>";

			if(is_admin() || $member['id' ] == $row['mb_id']) {
				$iq_answer = $row['iq_answer'];
			} else {
				$iq_answer = "";
				$is_secret = true;
			}
		} else {
			$icon_secret = "";
			$iq_answer = $row['iq_answer'];
		}

		if($row['iq_answer'])
			$icon_answer = "<span class='fc_7d6'>답변완료</span>&nbsp;&nbsp;";
		else
			$icon_answer = "<span class='fc_999'>답변대기</span>&nbsp;&nbsp;";

		$iq_subject = "";
		if(!$is_secret) { $iq_subject .= "<a href='javascript:void(0);' onclick=\"qna('".$i."')\">"; }
		$iq_subject .= "<span class=lst_post>".$icon_answer.$row['iq_subject']."</span>";

		$len = strlen($row['mb_id']);
		$str = substr($row['mb_id'],0,3);
		$mb_id = $str.str_repeat("*",$len - 3);

		$hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

		echo "<li class='lst'>\n$iq_subject";
			echo "<span class='lst_h'><span class='fc_255'>$row[iq_ty]</span> ";
			echo "<span class='fc_999'> / $mb_id / $iq_time $icon_secret </span></span>";
			if(!$is_secret) { echo "</a>"; }

			echo "<div class='faq' id='qna".$i."' style='display:none;'>\n";
				echo "<table class='faqbody'>\n";
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td class='mi_dt'><img src='".TB_IMG_URL."/sub/FAQ_Q.gif'></td>\n";
					echo "<td class='mi_bt fc_125'>\n".nl2br($row['iq_question']);

					if(is_admin() || $member['id' ] == $row['mb_id'] && !$iq_answer) {
						echo "<div class='padt10'><a href=\"javascript:window.open('".TB_MSHOP_URL."/qaform.php?gs_id=$row[gs_id]&iq_id=$row[iq_id]&w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"".TB_MSHOP_URL."/qaform_update.php?gs_id=$row[gs_id]&iq_id=$row[iq_id]&w=d&hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a></div>\n";
					}
					echo "</td>\n";
				echo "</tr>\n";

				if($iq_answer) {
					echo "<tr>\n";
						echo "<td class='mi_dt padt20'><img src='".TB_IMG_URL."/sub/FAQ_A.gif'></td>\n";
						echo "<td class='mi_bt padt20 fc_7d6'>".nl2br($iq_answer)."</td>\n";
					echo "</tr>\n";
				}
				echo "</tbody>\n";
				echo "</table>\n";
			echo "</div>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

// 상품 선택옵션
function mobile_item_options($gs_id, $subject, $event='')
{
	if(!$gs_id || !$subject)
		return '';

	$amt = get_sale_price($gs_id);

	$sql = " select * from shop_goods_option where io_type = '0' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';
	$subj = explode(',', $subject);
	$subj_count = count($subj);

	if($subj_count > 1) {
		$options = array();

		// 옵션항목 배열에 저장
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$opt_id = explode(chr(30), $row['io_id']);

			for($k=0; $k<$subj_count; $k++) {
				if(!is_array($options[$k]))
					$options[$k] = array();

				if($opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
					$options[$k][] = $opt_id[$k];
			}
		}

		// 옵션선택목록 만들기
		for($i=0; $i<$subj_count; $i++) {
			$opt = $options[$i];
			$opt_count = count($opt);
			$disabled = '';
			if($opt_count) {
				$seq = $i + 1;
				if($i > 0)
					$disabled = ' disabled="disabled"';
				$str .= '<div class=sp_obox>'.PHP_EOL;
				$str .= '<ul>'.PHP_EOL;
				$str .= '<li class=tlst><label for="it_option_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

				$select  = '<select id="it_option_'.$seq.'" class="it_option"'.$disabled.' '.$event.'>'.PHP_EOL;
				$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
				for($k=0; $k<$opt_count; $k++) {
					$opt_val = $opt[$k];
					if($opt_val) {
						$select .= '<option value="'.$opt_val.'">'.$opt_val.'</option>'.PHP_EOL;
					}
				}
				$select .= '</select>'.PHP_EOL;

				$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
				$str .= '</ul>'.PHP_EOL;
				$str .= '</div>'.PHP_EOL;
			}
		}
	} else {
		$str .= '<div class=sp_obox>'.PHP_EOL;
		$str .= '<ul>'.PHP_EOL;
		$str .= '<li class=tlst><label for="it_option_1">'.$subj[0].'</label></li>'.PHP_EOL;

		$select  = '<select id="it_option_1" class="it_option" '.$event.'>'.PHP_EOL;
		$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';

			if(!$row['io_stock_qty'])
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$select .= '<option value="'.$row['io_id'].','.$row['io_price'].','.$row['io_stock_qty'].','.$amt.'">'.$row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
		}
		$select .= '</select>'.PHP_EOL;

		$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
		$str .= '</ul>'.PHP_EOL;
		$str .= '</div>'.PHP_EOL;
	}

	return $str;
}

// 상품 추가옵션
function mobile_item_supply($gs_id, $subject, $event='')
{
	if(!$gs_id || !$subject)
		return '';

	$sql = " select * from shop_goods_option where io_type = '1' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';

	$subj = explode(',', $subject);
	$subj_count = count($subj);
	$options = array();

	// 옵션항목 배열에 저장
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = explode(chr(30), $row['io_id']);

		if($opt_id[0] && !array_key_exists($opt_id[0], $options))
			$options[$opt_id[0]] = array();

		if($opt_id[1]) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';
			$io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($io_stock_qty < 1)
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$options[$opt_id[0]][] = '<option value="'.$opt_id[1].','.$row['io_price'].','.$io_stock_qty.',0">'.$opt_id[1].$price.$soldout.'</option>';
		}
	}

	// 옵션항목 만들기
	for($i=0; $i<$subj_count; $i++) {
		$opt = $options[$subj[$i]];
		$opt_count = count($opt);
		if($opt_count) {
			$seq = $i + 1;
			$str .= '<div class=sp_obox>'.PHP_EOL;
			$str .= '<ul>'.PHP_EOL;
			$str .= '<li class=tlst><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

			$select = '<select id="it_supply_'.$seq.'" class="it_supply" '.$event.'>'.PHP_EOL;
			$select .= '<option value="">선택안함</option>'.PHP_EOL;
			for($k=0; $k<$opt_count; $k++) {
				$opt_val = $opt[$k];
				if($opt_val) {
					$select .= $opt_val.PHP_EOL;
				}
			}
			$select .= '</select>'.PHP_EOL;

			$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
			$str .= '</ul>'.PHP_EOL;
			$str .= '</div>'.PHP_EOL;
		}
	}

	return $str;
}

// 장바구니 옵션호출
function mobile_print_item_options($gs_id, $set_cart_id)
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where gs_id='$gs_id' and ct_direct='$set_cart_id' and ct_select='0' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

        $price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';
		// 20200506 주석(상품옵션 맨뒤에 갯수 제거)
		// $str .= "<li>{$row['ct_option']} ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		$str .= "<li>{$row['ct_option']} (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 주문완료 옵션호출
function mobile_print_complete_options($gs_id, $od_id)
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		$str .= "<li class=\"fc_999\">{$row['ct_option']} ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 계좌정보를 select 박스 형식으로 얻는다
function mobile_bank_account($name, $selected='')
{
	global $default;

	$str  = '<select id="'.$name.'" name="'.$name.'" style="width:100%">'.PHP_EOL;
	$str .= '<option value="">선택하십시오</option>'.PHP_EOL;

	$bank = unserialize($default['de_bank_account']);
	for($i=0; $i<5; $i++) {
		$bank_account = $bank[$i]['name'].' '.$bank[$i]['account'].' '.$bank[$i]['holder'];
		if(trim($bank_account)) {
			$str .= option_selected($bank_account, $selected, $bank_account);
		}
	}
	$str .= '</select>'.PHP_EOL;

	return $str;
}

// 로고
function mobile_display_logo($fld='mobile_logo')
{
	global $pt_id;

	$row = sql_fetch("select $fld from shop_logo where mb_id='$pt_id'");
	if(!$row[$fld] && $pt_id != 'admin') {
		$row = sql_fetch("select $fld from shop_logo where mb_id='admin'");
	}

	$file = TB_DATA_PATH.'/banner/'.$row[$fld];
	if(is_file($file) && $row[$fld]) {
		$file = rpc($file, TB_PATH, TB_URL);

        // 2021-11-18
        if( $pt_id=='golfpang' ){
            if( stristr($_SERVER['HTTP_USER_AGENT'],"ipad") || stristr($_SERVER['HTTP_USER_AGENT'],"iphone") ){
                return '<a href="https://m.golfpang.com"><img src="'.$file.'" class="lg_wh" alt="로고"></a>';
            }
        }

		return '<a href="'.TB_URL.'/m/"><img src="'.$file.'" class="lg_wh" alt="로고"></a>';
        /*
		if($pt_id == 'admin'){
			return '<a href="'.TB_URL.'/m/"><img src="'.$file.'" class="lg_wh adm_logo" alt="로고"></a>';
		}
		else if($pt_id == 'maniamall'){
			return '<a href="'.TB_URL.'/m/" class="mania_logo"><img src="'.$file.'" class="lg_wh" alt="로고"></a>';
		}
        else if($pt_id == 'honggolf'){
			return '<a href="https://honggolf.com/" class="mania_logo"><img src="'.$file.'" class="lg_wh" alt="로고"></a>';
		}
		else{
			return '<a href="'.TB_URL.'/m/"><img src="'.$file.'" class="lg_wh" alt="로고"></a>';
		}
        */
	} else {
		return '';
	}
}

// mobile_listtype_cate('설정값')
function mobile_listtype_cate($list_best)
{
	global $default;

	$mod = 3;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= 3) break;

			$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
			$it_name = get_text($row['gname']);
			$it_price = mobile_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}

			$str .= "<li>\n";
			$str .=		"<a href=\"{$it_href}\">\n";
			$str .=		"<dl>\n";
			$str .=			"<dd class=\"pname\">{$it_name}</dd>\n";
			$str .=			"<dd class=\"pimg\"><img src=\"{$it_imageurl}\"></dd>\n";
			$str .=			"<dd class=\"price\">{$it_price}</dd>\n";
			$str .=		"</dl>\n";
			$str .=		"</a>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class='empty_list'>자료가 없습니다.</li>\n";

		$ul_str .= "<ul>\n{$str}</ul>\n";
	}

	return $ul_str;
}


/*************************************************************************
**
**  쇼핑몰 배너관련 함수 모음
**
*************************************************************************/

// 메인배너 출력
function mobile_slider($code, $mb_id)
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$a1 = $a2 = '';
		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$file = rpc($file, TB_PATH, TB_URL);
			$str .= "{$a1}<img src=\"{$file}\" alt=\"상품슬라이더이미지\">{$a2}\n";
		}
	}

	return $str;
}

// 배너 자체만 리턴
function mobile_banner($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$file = rpc($file, TB_PATH, TB_URL);
		$str = "{$a1}<img src=\"{$file}\">{$a2}";
	}

	return $str;
}

// 배너 (동일한 배너코드가 부여될경우 세로로 계속하여 출력)
function mobile_banner_rows($code, $mb_id)
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$a1 = $a2 = $bg = '';

		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$file = rpc($file, TB_PATH, TB_URL);
			$str .= "<li>{$a1}<img src=\"{$file}\">{$a2}</li>\n";
		}
	}

	if($i > 0)
		$str = "<ul>\n{$str}</ul>\n";

	return $str;
}

// 킬딜특가 가져오기
function m_get_killdeal(){
    /*
	$tb['title'] = $default['de_pname_8'];
	$Date = date("Y-m-d");

	$YY = date("Y", strtotime($Date));
	$MM = date("m", strtotime($Date));
	$DD = date("d", strtotime($Date));
	$Day = date("w", strtotime($Date));

	$this_week_start = date("Y-m-d", strtotime($YY."-".$MM."-".$DD." -".$Day." day"));
	$this_week_end = date("Y-m-d", strtotime($this_week_start." +6 day"));

	$sql_search = " and sb_date = '".$this_week_start."' and eb_date = '".$this_week_end."' ";
	$sql_common = sql_goods_list($sql_search);
	
	return $sql_common;
    */

    // 2021-08-11
    $ts = sql_fetch("select * from shop_goods_timesale where ts_sb_date <= NOW() and ts_ed_date >= NOW() ");
    if( isset($ts) ){
        $sb_date = $ts['ts_sb_date'];
        $ed_date = $ts['ts_ed_date'];
        $is_timesale    = true;
        $sql_search = " and index_no in ( $ts[ts_it_code] )";
    }
    $sql_common = sql_goods_list($sql_search);

    return $sql_common;
}

// 비디오게시판 리스트 가져오기
function m_board_video_latest($boardid, $len, $rows, $pt_id)
{
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	$sql = " select * from shop_board_{$boardid} where main_use = '1' {$sql_search2} order by index_no desc limit $rows ";

	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = date('Y-m-d',intval($row['wdate'],10));
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
		$memo = $row['memo'];
		preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);

		$str .= "<div>\n";
		$str .= "	<a href=\"https://www.youtube.com/channel/UCUXDKiEsZH9Kcad-hksAK7g\" class=\"honggolf_link_btn\">홍인규골프채널바로가기</a>\n";
		$str .= "	<a href=\"https://www.youtube.com/channel/UC1vCF_oF13DLh7l5TAQGHMQ/featured\" class=\"youtube_link_btn\">마마골프채널바로가기</a>\n";
		$str .= "	<div class='video_box'>\n";
		$str .= "		{$RESULT[0][0]}\n";
		$str .= "	</div>\n";
		$str .= "</div>\n";
	}

	return $str;
}


// 비디오게시판 리스트 가져오기 캐디테마
function m_board_video_latest3($boardid, $len, $rows, $pt_id){
    /*
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	$sql = " select * from shop_board_{$boardid} where main_use = '1' {$sql_search2} order by index_no desc limit $rows ";
    */

    global $default;

    $sql_where = "";
    if($pt_id=='baksajang' || $pt_id=='teeluv' || $pt_id=='maniamall' || $pt_id=='dukhomall'){
        $sql_search = " and pt_id = '$pt_id' ";
    }else{
        $sql_search = " and pt_id = 'admin' ";
    }

    $str = '';
    $sql = " select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N'  {$sql_search} order by main_order,index_no desc limit $rows ";    

	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		console_log($row);
		$subject = cut_str($row['subject'],$len);
		$wdate = substr(date('Y/m/d',intval($row['wdate'],10)),2);
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
		$memo = $row['memo'];
		preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);
        $href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
        $url_th = "https://i.ytimg.com/vi/{$row['fileurl1']}/maxresdefault.jpg";

		$str .= "<div>\n";
		$str .= "	<div class='video_box'>\n";
        $str .= "       <a href=\"{$href}\" class=\"video_play\">\n";
        $str .= "           <img src=\"${url_th}\" style=\"width:100%;\">\n";
        $str .= "       </a>\n";
		$str .= "	</div>\n";
		$str .= "	<div class='video_c_title'>\n";
		$str .= "		{$subject}\n";
		$str .= "	</div>\n";		
		$str .= "	<div class='video_c_reg'>\n";
		$str .= "		{$wdate}\n";
		$str .= "	</div>\n";				
		$str .= "</div>\n";
	}

	return $str;	
}

// 골프비디오 썸네일 가져오기 20200729
function m_video_thumb($boardid, $len, $rows, $pt_id){
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

    if($_SESSION["ss_mb_id"] != 'admin')
    {
	    $sql = " select * from shop_board_{$boardid} where main_use = '1' {$sql_search2} and issecret = 'N' order by main_order asc limit $rows ";
    }
    else
    {
        $sql = " select * from shop_board_{$boardid} where main_use = '1' {$sql_search2} order by main_order asc limit $rows ";
    }

	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = date('Y-m-d',intval($row['wdate'],10));
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
		$u_url = split_youtube_tag($row['memo']);
		$url_th = main_merge_thumnail_url($u_url[0]);

		$str .= "	<div class='video_box'>\n";
		$str .= " <a href=\"https://www.youtube.com/watch/$u_url[0]\" class=\"video_play\" target=\"_blank\">\n";
		$str .= " <img src=$url_th style=\"width:100%;\">\n";
		$str .= " </a>\n";
		$str .= "	</div>\n";
	}

	return $str;
}

// 골프매거진 리스트 가져오기
function m_board_mgz_latest($boardid, $len, $rows, $pt_id)
{
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	if($_SESSION["ss_mb_id"] != 'admin')
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N' order by main_order,index_no desc limit $rows";
    }
	else
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1'  order by main_order,index_no desc limit $rows";
    }
	$res = sql_query($sql);

	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = date('Y-m-d',intval($row['wdate'],10));
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
		$memo = $row['memo'];
		$hit = $row['readcount'];
		$main_use = $row['main_use'];
		$main_order = $row['main_order'];

        // 20210618
        if($row['fileurl1']) {
            $filepath = TB_DATA_URL.'/board/43/'.$row['fileurl1'];
            $bo_imgurl = "<img src='".$filepath."'>";
        }else{
            //preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);
            preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $memo, $matches);
            $bo_imgurl = $matches[0][0];
        }

		if($main_use !== '0'){
			$str .= "<li>\n";
			$str .= "	<a href=\"{$href}\">\n";
			$str .= "		<p class=\"plan_img\">{$bo_imgurl}</p>\n";
			$str .= "		<span class=\"plan_txt\">\n";
			$str .= "			<span class=\"plan_tit\">{$subject}</span>\n";
			//$str .= "		<span class=\"plan_hit\">{$hit} 읽음</span>\n";
			$str .= "		</span>\n";
			$str .= "	</a>\n";
			$str .= "</li>\n";
		}
	}
	return $str;
}

// 골프매거진 리스트 가져오기 캐디테마
function m_board_mgz_latest2($boardid, $len, $rows, $pt_id)
{
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	if($_SESSION["ss_mb_id"] != 'admin')
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N' order by main_order,index_no desc limit $rows";
    }
	else
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1'  order by main_order,index_no desc limit $rows";
    }
	$res = sql_query($sql);

	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = substr(date('Y/m/d',intval($row['wdate'],10)),2);
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
		$memo = $row['memo'];
		$hit = $row['readcount'];
		$main_use = $row['main_use'];
		$main_order = $row['main_order'];

        // 20210618
        if($row['fileurl1']) {
            $filepath = TB_DATA_URL.'/board/43/'.$row['fileurl1'];
            $bo_imgurl = "<img src='".$filepath."'>";
        }else{
            //preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);
            preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $memo, $matches);
            $bo_imgurl = $matches[0][0];
        }

		if($main_use !== '0'){
			$str .= "<li>\n";
			$str .= "	<a href=\"{$href}\">\n";
			$str .= "		<p class=\"plan_img\">{$bo_imgurl}</p>\n";
			$str .= "		<span class=\"plan_txt\">\n";
			$str .= "			<span class=\"plan_tit\">{$subject}</span>\n";
			$str .= "			<span class=\"plan_reg\">{$wdate}</span>\n";
			//$str .= "		<span class=\"plan_hit\">{$hit} 읽음</span>\n";
			$str .= "		</span>\n";
			$str .= "	</a>\n";
			$str .= "</li>\n";
		}
	}
	return $str;
}

// 베스트상품(수동)
function m_get_listtype_cate($list_best, $width, $height)
{
	$mod = 4;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where index_no = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= $mod) break;

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		    //$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$file = TB_DATA_PATH."/goods/".$row['simg1'];
			if(is_file($file)){
					$filepath = dirname($file);
					$filename = basename($file);
					if($filename) {
						$savepath = TB_DATA_PATH."/goods/";
					}
					$file_url = rpc($savepath, TB_PATH, TB_URL);

					$img = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';

			} else {
					$img = TB_IMG_URL.'/noimage.gif';
			}
			$it_image = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';


            

			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}

            $rv_sum_li = get_review_sum_v2($row['index_no']);
            $review_avg = $rv_sum_li['avg'];
            $item_use_count = $rv_sum_li['cnt'];

			$str .= "<li class=\"{$gcode}\">\n";
				$str .= "<div>\n";
					$str .= "<a href=\"{$it_href}\">\n";
						$str .= "<dl>\n";
							$str .= "<dt>{$it_image}</dt>\n";
							$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
							$str .= "<dd class=\"price\">{$sale}&nbsp;<span class=\"price_box\">{$it_sprice}{$it_price}</span></dd>\n";
                            $str .= "<dd class='review'>";
                            if($review_avg > 0){
                                $str .="<img src=\"/img/sub/comment_start.jpg\" ><span>{$review_avg} ({$item_use_count})</span>";
                            }
                            $str .= "</dd>";
						$str .= "</dl>\n";
					$str .= "</a>\n";
					//20191104 찜 주석처리
					//$str .= "<span class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class='nwin'></a></span>\n";
				$str .= "</div>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";

		$ul_str .= "<ul id=\"bstab_c{$i}\">\n{$str}</ul>\n";
	}

	return $ul_str;
}

// 베스트상품(자동)
function m_get_listtype_cate2($list_best, $width, $height)
{
	$mod = 4;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= $mod) break;

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
			$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}

			$str .= "<li class=\"{$gcode}\">\n";
				$str .= "<div>\n";
					$str .= "<a href=\"{$it_href}\">\n";
						$str .= "<dl>\n";
							$str .= "<dt>{$it_image}</dt>\n";
							$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
							$str .= "<dd class=\"price\"><span class=\"price_box\">{$it_sprice}{$it_price}</span></dd>\n";
						$str .= "</dl>\n";
					$str .= "</a>\n";
					//20191104 찜 주석처리
					//$str .= "<span class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class='nwin'></a></span>\n";
				$str .= "</div>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";

		$ul_str .= "<ul id=\"bstab_c{$i}\">\n{$str}</ul>\n";
	}

	return $ul_str;
}

//상품리스트 (인플루언서 몰)
function m_get_listtype_cate3($list_best, $width, $height, $limit=null)
{
	$mod = $limit ? $limit : 30;
    $mod = 4;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where index_no = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= $mod) break;

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		    //$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$file = TB_DATA_PATH."/goods/".$row['simg1'];
			if(is_file($file)){
					$filepath = dirname($file);
					$filename = basename($file);
					if($filename) {
						$savepath = TB_DATA_PATH."/goods/";
					}
					$file_url = rpc($savepath, TB_PATH, TB_URL);

					$img = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';

			} else {
					$img = TB_IMG_URL.'/noimage.gif';
			}
			$it_image = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';


            

			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
                //$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'
				$sale = $sett >= 1 ? '<p class="sale">'.number_format($sett,0).'<span>%</span></p>' : '<p class="sale"><span></span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}else{
                $sale = '<p class="sale">10<span>%</span></p>';
                $it_sprice = display_price2(round($it_amount/0.9,-3));                
            }

            $rv_sum_li = get_review_sum_v2($row['index_no']);
            $review_avg = $rv_sum_li['avg'];
            $item_use_count = $rv_sum_li['cnt'];

			$str .= "<li class=\"{$gcode}\">\n";
				$str .= "<div>\n";
					$str .= "<a href=\"{$it_href}\">\n";
						$str .= "<dl>\n";
							$str .= "<dt>{$it_image}</dt>\n";
							$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
							$str .= "<dd class=\"price\">{$sale}<span class=\"price_box\"> {$it_sprice}<br><span class=\"recommendation\">특별할인★</span>{$it_price}</span></dd>\n";
                            $str .= "<dd class='review'>";
                            if($review_avg > 0){
                                $str .="<img src=\"/img/sub/comment_start.jpg\" ><span>{$review_avg} ({$item_use_count})</span>";
                            }
                            $str .= "</dd>";
						$str .= "</dl>\n";
					$str .= "</a>\n";
					//20191104 찜 주석처리
					//$str .= "<span class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class='nwin'></a></span>\n";
				$str .= "</div>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";

		$ul_str .= "<ul id=\"bstab_c{$i}\">\n{$str}</ul>\n";
	}

	return $ul_str;
}

//상품리스트 (인플루언서 캐디 몰)
function m_get_listtype_cate4($list_best, $width, $height, $limit=null)
{
	$mod = $limit ? $limit : 30;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where index_no = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= $mod) break;

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		    //$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$file = TB_DATA_PATH."/goods/".$row['simg1'];
			if(is_file($file)){
					$filepath = dirname($file);
					$filename = basename($file);
					if($filename) {
						$savepath = TB_DATA_PATH."/goods/";
					}
					$file_url = rpc($savepath, TB_PATH, TB_URL);

					$img = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';

			} else {
					$img = TB_IMG_URL.'/noimage.gif';
			}
			$it_image = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';


            

			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
                //$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'
				$sale = $sett >= 1 ? '<p class="sale">'.number_format($sett,0).'<span>%</span></p>' : '<p class="sale"><span></span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}else{
                $sale = '<p class="sale">10<span>%</span></p>';
                $it_sprice = display_price2(round($it_amount/0.9,-3));                
            }

			$str .= "<li class=\"{$gcode}\">\n";
				$str .= "<div>\n";
					$str .= "<a href=\"{$it_href}\">\n";
						$str .= "<dl>\n";
							$str .= "<dt>{$it_image}</dt>\n";
							$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
							$str .= "<dd class=\"price\">{$sale}<span class=\"price_box\"> {$it_sprice}<span class=\"recommendation\"></span>{$it_price}</span></dd>\n";
						$str .= "</dl>\n";
					$str .= "</a>\n";
					//20191104 찜 주석처리
					//$str .= "<span class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class='nwin'></a></span>\n";
				$str .= "</div>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";

		$ul_str .= "<ul id=\"bstab_c{$i}\">\n{$str}</ul>\n";
	}

	return $ul_str;
}

// 분류별 상단배너2
function get_category_head_image_m($ca_id)
{
	$cgy = array();

	$sql = "select * from shop_category where catecode = '".substr($ca_id,0,6)."' limit 1 ";
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/category/'.$row['m_headimg'];

	if(is_file($file) && $row['m_headimg']) {
		if($row['m_headimgurl']) {
			$a1 = '<a href="'.$row['m_headimgurl'].'">';
			$a2 = '</a>';
		}

		$stillimgSize = getimagesize($file);
		$stillimgWidth = $stillimgSize[0];
		$stillimgHeight = $stillimgSize[1];

		$imgpadding = floor(($stillimgHeight / $stillimgWidth)*100);

		$file = rpc($file, TB_PATH, TB_URL);
		$cgy['m_headimg'] = '<div id="sub_bn_wrap">'.$a1.'<div class="sub_bn_box" style="background:url('.$file.') no-repeat center; background-size: cover; padding-bottom:'.$imgpadding.'%;"></div>'.$a2.'</div>';
	}

	return $cgy;
}
?>
