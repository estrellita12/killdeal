<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

$qstr1 = 'ca_id='.$ca_id.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ca_id='.$ca_id;

$sort_str = '';
for($i=0; $i<count($gw_msort); $i++) {
	list($tsort, $torder, $tname) = $gw_msort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	if($sort == $tsort && $sortodr == $torder)
		$sort_name = $tname;
	if($i==0 && !($sort && $sortodr))
		$sort_name = $tname;

	$sort_str .= '<li><a href="'.$sct_sort_href.'" >'.$tname.'</a></li>'.PHP_EOL;
}
?>


<?php echo mobile_tree_category($ca_id);?>

<?php
$cgy = get_category_head_image_m($ca_id);
?>

<?php echo $cgy['m_headimg']; // 분류별 상단이미지 ?>


<!-- 상품 정렬 선택 시작 { -->
<div id="sct_sort">
	<div class="count">전체 (<strong><?php echo number_format($total_count); ?></strong>개)</div>
	<span id="btn_sort"><?php echo $sort_name; ?></span>
</div>
<div id="sort_li">
	<h2>상품 정렬</h2>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<span id="sort_close" class="ionicons ion-ios-close-empty"></span>
</div>
<div id="sort_bg"></div>

<script>
$(function() {
	var mbheight = $(window).height();

	$('#btn_sort').click(function(){
		$('#sort_bg').fadeIn(300);
		$('#sort_li').slideDown('fast');
		$('html').css({'height':mbheight+'px', 'overflow':'hidden'});
	});

	$('#sort_bg, #sort_close').click(function(){
		$('#sort_bg').fadeOut(300);
		$('#sort_li').slideUp('fast');
		$('html').css({'height':'100%', 'overflow':'scroll'});
	});
});
</script>
<!-- } 상품 정렬 선택 끝 -->

<div>
	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">자료가 없습니다.</p>";
	} else {
		echo "<ul class=\"pr_desc wli2\">";
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
			$it_name = cut_str($row['gname'], 50);
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], 400, 400);
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

			echo "<li>";
				echo "<a href=\"{$it_href}\">";
				echo "<dl>";
					echo "<dt><img src=\"{$it_imageurl}\" alt=\"상품이미지\"></dt>";
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
                    $review_avg = get_review_avg_v2($row['index_no']);
                    echo "<dd class='review'>";
                    if($review_avg > 0){
                        echo "<img src=\"/img/sub/comment_start.jpg\" ><span>{$review_avg} 리뷰</span>";
                    }
                    echo "</dd>";


				echo "</dl>";
				echo "</a>";
				//echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
			echo "</li>";
		}
		echo "</ul>";
	}

	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
	?>
</div>
