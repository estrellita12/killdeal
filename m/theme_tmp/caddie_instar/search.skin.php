<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

//$qstr1 = 'ss_tx='.$ss_tx.'&sort='.$sort.'&sortodr='.$sortodr;
//$qstr2 = 'ss_tx='.$ss_tx;
$qstr1 = 'ss_tx='.$ss_tx.'&sort='.$sort.'&sortodr='.$sortodr.'&ca_id='.$ca_id;
$qstr2 = 'ss_tx='.$ss_tx;
$qstr3 = 'ss_tx='.$ss_tx.'&sort='.$sort.'&sortodr='.$sortodr;


$sort_str = '';
for($i=0; $i<count($gw_msort); $i++) {
	list($tsort, $torder, $tname) = $gw_msort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	if($sort == $tsort && $sortodr == $torder)
		$sort_name = $tname;
	if($i==0 && !($sort && $sortodr))
		$sort_name = $tname;

	$sort_str .= '<li><a href="'.$sct_sort_href.'">'.$tname.'</a></li>'.PHP_EOL;
}
?>

<!-- 상품 정렬 선택 시작 { -->
<div id="sct_sort">
	<div class="count">전체 <strong><?php echo number_format($allCnt); ?></strong>개</div>
    <!--
    <span id="btn_cate">필터</span>
	<span id="btn_sort"><?php echo $sort_name; ?></span>
    -->
</div>
<!--
<div id="sort_li">
	<h2>상품 정렬</h2>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<span id="sort_close" class="ionicons ion-ios-close-empty"></span>
</div>
-->
<div id="filter_li">
    <h2>상품 정렬</h2>
    <ul>
        <?php echo $sort_str; // 탭메뉴 ?>
    </ul>
    <h2 id="cate_tit">카테고리<span class="bm active"></span></h2>
    <ul id="cate_list">
        <?php
            $ts = " select a.ca_id as ca_id, count(a.index_no) as cnt $sql_query group by a.ca_id order by a.ca_id ";
            $tr = sql_query($ts,false);
            for($i=0; $tmp=sql_fetch_array($tr); $i++) {
                $href = $_SERVER['SCRIPT_NAME']."?".$qstr3;
                if( $_REQUEST['ca_id'] == $tmp['ca_id'] ){
                    echo "<li><a href='".$href."' class='fc_red'>".adm_category_navi($tmp['ca_id'])." (".$tmp['cnt'].") <span class='ionicons ion-ios-close-empty'></span> </a></li>";
                }else{
                    echo "<li><a href='".$href."&ca_id=".$tmp['ca_id']."' >".adm_category_navi($tmp['ca_id'])." (".$tmp['cnt'].")</a></li>";
                }
            }
        ?>
    </ul>
    <h2>결과 내 재검색</h2>
    <form  name="fsearch2" id="fsearch2" action="<?php echo TB_MSHOP_URL; ?>/search.php" method="post">
    <input type="hidden" name="ss_tx_ori" value="<?php echo $ss_tx ?>">
    <input type="hidden" name="research" value="1">
    <ul  id="search_wrap">
        <li><input type="text" name="ss_tx" class="frm_input" placeholder="검색어 입력"><button type="submit" class="btn_medium" >검색</button></li>
    </ul>
    </form>
    <span id="sort_close" class="ionicons ion-ios-close-empty"></span>
</div>


<div id="sort_bg"></div>

<script>
$(function() {
	var mbheight = $(window).height();

    $('#btn_sort, #btn_cate').click(function(){
        $('#sort_bg').fadeIn(300);
         $("#filter_li").animate({width: "toggle"}, "fast");
    });

    $('#sort_bg, #sort_close').click(function(){
        $('#sort_bg').fadeOut(300);
         $("#filter_li").animate({right: "toggle"}, "fast");
    });

    $('#cate_tit').click(function(){
        if($('#cate_tit > .bm').hasClass('active')){
            $('#cate_tit > .bm').removeClass('active');
        } else {
            $('#cate_tit > .bm').addClass('active');
        }

        $("#cate_list").slideToggle();
    });

});
</script>
<!-- } 상품 정렬 선택 끝 -->

<div>
	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">자료가 없습니다.</p>";
	} else {
        echo "<div class=\"pr_desc wli2\">";        
		echo "<ul>";
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
			}else{
                $sale = '<p class="sale">10<span>%</span></p>';
                $it_sprice = display_price2(round($it_amount/0.9,-3));                
            }

			echo "<li>";
                echo "<div>";            
				echo "<a href=\"{$it_href}\">";
				echo "<dl>";
					echo "<dt><img src=\"{$it_imageurl}\" alt=\"상품이미지\"></dt>";
					echo "<dd class=\"pname\">{$it_name}</dd>\n";
					echo "<dd class=\"price\">{$sale}&nbsp;{$it_sprice} {$it_price}</dd>\n";
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
				echo "</dl>";
				echo "</a>";
				//echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
                echo "</div>";                  
			echo "</li>";
		}
		echo "</ul>";
        echo "</div>";                
	}

	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
	?>
</div>
