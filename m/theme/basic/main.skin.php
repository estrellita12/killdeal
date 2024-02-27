<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<?php if($slider1 = mobile_slider(0, $pt_id)) { ?>
<!-- 메인배너 시작 { -->
<?php if( empty($main_banner_hidden) || $main_banner_hidden===false ){ ?>
<div id="main_bn">
	<?php echo $slider1; ?>
</div>
<script>
$(document).on('ready', function() {
	$('#main_bn').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		arrows: false,
		fade: true
	});
});
</script>
<?php } ?>
<!-- } 메인배너 끝 -->
<?php } ?>

<!-- 메인 상단 고정배너  시작 { -->
<?php if($banner = mobile_banner(9, $pt_id)) { ?>
<div class="ad"><?php echo $banner; ?></div>
<?php } ?>
<!-- } 메인 상단 고정배너 끝 -->

<!-- 이츠골프만 main_nav 보여줌(아코디언메뉴버튼이 어플리케이션 자체내장되어있어서 아코디언메뉴버튼을 메인에 추가해줆) -->
<?php if($pt_id == 'itsgolf' || $pt_id == 'refreshclub' || $pt_id == 'teeshot') { ?>
	<div class="main_nav">
		<ul>
			<?php
			$mod = 5;
			$res = sql_query_cgy('all');
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
				
				switch($i) {
					case '0' :
						$catecode = '골프클럽';
						break;
					case '1' :
						$catecode = '골프용품';
						break;
					case '2' :
						$catecode = '골프패션';
						break;
				}
			?>
				<li>
					<a href="<?php echo $href; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu'.$i.'.png'; ?>" alt="">
						<p><?php echo $catecode; ?></p>
					</a>
				</li>
			<?php
			}
			?>
			<!-- <li>
				<a href="<?php echo TB_MSHOP_URL.'/plan.php'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu3.png'; ?>" alt="">
					<p>기획전</p>
				</a>
			</li>
			<li style="border-right:0;">
				<a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=43'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu4.png'; ?>" alt="">
					<p>골프매거진</p>
				</a>
			</li>
			<li class="bg">
				<a href="<?php echo TB_MSHOP_URL.'/timesale.php'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu5.png?1234'; ?>" alt="">
					<p>킬딜특가</p>
				</a>
			</li>
			<li class="bg">
				<a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=42'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu6.png'; ?>" alt="">
					<p>골프영상</p>
				</a>
			</li> -->
			<li class="bg">
				<a href="<?php echo TB_MSHOP_URL.'/mypage.php'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu7.png'; ?>" alt="">
					<p>마이페이지</p>
				</a>
			</li>
			<!-- <li class="bg">
				<a href="<?php echo  TB_MSHOP_URL.'/cart.php'; ?>">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu8.png'; ?>" alt="">
					<p>장바구니</p>
				</a>
			</li> -->
			<li  class="bg" style="border-right:0;">
				<!-- <a href="<?php echo 'https://itsgolf.killdeal.co.kr/m/theme/basic/its.slideMenu.skin.php'; ?>" class="btn_sidem"> -->
				<a href="javascript:;" class="btn_sidem">
					<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu9.png?1'; ?>" alt="">
					<p>전체보기</p>
				</a>
			</li>
		</ul>
	</div>
<?php }?>

<!-- 타임세일 시작 {-->

<!-- } 타임세일 끝 -->

<!-- 킬딜특가 시간함수 {-->
<script>
// 골프매거진 img alt태그 / 골프비디오 iframe title태그 속성 삽입(접근성높임)
$(document).ready(function(){
	var imgEl = $(".mgz_wrap .plan_img img");
	var iframeEl = $(".video_box iframe");
	imgEl.attr('alt','골프매거진썸네일');
	iframeEl.attr('title','골프비디오');
	
});

</script>
<!-- 시간함수끝 } -->

<!-- <?php
if($default['de_maintype_best']) {
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);
?> -->
<!-- 카테고리별 베스트 시작 {-->
<!-- <div class="bscate mart30">
	<h2 class="mtit"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<div class="bscate_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<a><span><?php echo trim($list_best[$i]['subj']); ?></span></a>
		<?php } ?>
	</div>
	<div class="bscate_li">
		<?php echo mobile_listtype_cate($list_best); ?>
	</div>
	<script>
	$(document).ready(function(){
		$('.bscate_li').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			infinite: false,
			asNavFor: '.bscate_tab'
		});
		$(".bscate_tab").slick({
			autoplay: false,
			dots: false,
			infinite: false,
			centerMode: true,
			variableWidth: true,
			slidesToScroll: 1,
			asNavFor: '.bscate_li',
			focusOnSelect: true
		});
	});
	</script>
</div> -->
<!-- } 카테고리별 베스트 끝 -->
<!-- <?php } ?> -->

<!-- 베스트상품 시작 {-->
<div class="m_main_cont">
	<h2 class="mtit"><span><?php echo $default['de_pname_2']; ?></span></h2>
	<?php if($default['de_listing_best'] == '1') { ?>
		<!-- 베스트상품(수동) 카테고리별 베스트 시작 {-->
		<?php
		if($default['de_maintype_best']) {
			$list_best = unserialize(base64_decode($default['de_maintype_best']));
			$list_count = count($list_best);
			$tab_width = (float)(100 / $list_count);
		?>
		<!-- <h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2> -->
		<ul class="bestca_tab1 <?php if($pt_id == 'honggolf') echo 'hong_best_tab' ?>">
			<?php for($i=0; $i<$list_count; $i++) { 
					$j = $i;
					if($i == '2'){
						$j = '2';
					} else if ($i == '3') {
						$j = '3';
					}
			?>
			<!-- BEST100 , 골프클럽 , 골프용품, 골프패션 -->
			<li data-tab="bstab_c<?php echo $j; ?>"><span><?php echo trim($list_best[$i]['subj']); ?></span></li> 
			<?php } ?>
		</ul>
		<div class="pr_desc wli2 bestca">
			<?php echo m_get_listtype_cate($list_best, '160', '160');//숫자는 의미없음. ?> 
			
		</div>
		<script>
		$(document).ready(function(){
			$(".bestca_tab1>li:eq(0)").addClass('active');
			$("#bstab_c0").show();

			$(".bestca_tab1>li").click(function() {
				var activeTab = $(this).attr('data-tab');
				$(".bestca_tab1>li").removeClass('active');
				$(".bestca ul").hide();
				$(this).addClass('active');
				$("#"+activeTab).fadeIn(250);
			});
		});
		</script>
		<?php } ?>
		<!-- } 베스트상품(수동) 카테고리별 베스트 끝 -->
	<?php } else if ($default['de_listing_best'] == '0') { ?>

		<!-- 베스트상품(자동) 시작 {-->
		<?php
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);
		
		//$sql = " select a.index_no, a.simg1, a.gname, a.gpoint, a.ca_id, count(a.gcode) as qty_count from shop_goods a inner join shop_order b on (a.index_no = b.gs_id)  where b.od_time >= subdate(now(), interval 2 week) group by a.gcode desc limit 0, 4 ";

		$sql = "
			(SELECT a.index_no AS index_no, a.simg1 AS simg1, a.gname AS gname, a.gpoint AS gpoint, a.ca_id AS ca_id, count(gcode) as qty_count ,a.gcode AS gcode
				, a.normal_price AS normal_price
				from shop_goods a 
				inner join shop_order b on (a.index_no = b.gs_id)
				WHERE ca_id like '001%'
				group by a.gcode ORDER BY qty_count DESC LIMIT 4)
			UNION ALL
			 (SELECT a1.index_no AS index_no, a1.simg1 AS simg1, a1.gname AS gname, a1.gpoint AS gpoint, a1.ca_id AS ca_id, count(gcode) as qty_count ,a1.gcode AS gcode
			 	, a1.normal_price AS normal_price
				from shop_goods a1 
				inner join shop_order b1 ON (a1.index_no = b1.gs_id)
				WHERE ca_id like '002%'
				group BY a1.gcode ORDER BY qty_count DESC LIMIT 4) 
			 UNION ALL
			 ( SELECT a2.index_no AS index_no, a2.simg1 AS simg1, a2.gname AS gname, a2.gpoint AS gpoint, a2.ca_id AS ca_id, count(gcode) as qty_count ,a2.gcode AS gcode
			 	, a2.normal_price AS normal_price
				from shop_goods a2 
				inner join shop_order b2 ON (a2.index_no = b2.gs_id)
				WHERE ca_id like '003%'
				group BY a2.gcode  ORDER BY qty_count DESC LIMIT 4) ";			

		$result = sql_query($sql);
		?>
		<ul class="bestca_tab1 <?php if($pt_id == 'honggolf') echo 'hong_best_tab' ?>">
			<?php for($i=0; $i<$list_count; $i++) { 
				$j = $i;
				if($i == '2'){
					$j = '3';
				} else if ($i == '3') {
					$j = '2';
				}
			?>
			<li data-tab="bstab1_c<?php echo $j; ?>"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
			<?php } ?>
		</ul>
		<div class="pr_desc wli2" id="bestgoods">
			<ul>
			<?php
			//echo $sql;
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
				//$it_image = get_it_image($row['index_no'], $row['simg1'], 256, 256);
				$file = TB_DATA_PATH."/goods/".$row['simg1'];
				if(is_file($file)){
					$filepath = dirname($file);
					$filename = basename($file);
					if($filename) {
						$savepath = TB_DATA_PATH."/goods/";
					}
					$file_url = rpc($savepath, TB_PATH, TB_URL);

					$img = '<img src="'.$file_url.'/'.$filename.'">';

				} else {
					$img = TB_IMG_URL.'/noimage.gif';
				}
				$it_image = '<img src="'.$file_url.'/'.$filename.'">';
				$it_name = cut_str($row['gname'], 100);
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);
				$it_sum_qty = $row['qty_count'];
				$it_ca_id = substr($row['ca_id'], 2, 1);

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

			?>
				<li class="bstab1_c0 <?php echo 'bstab1_c'.$it_ca_id; ?>">			
					<div>
						<a href="<?php echo $it_href; ?>">
							<dl>
								<dt><?php echo $it_image; ?></dt>
								<dd class="pname"><?php echo $it_name; ?></dd>
								<dd class="price"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
							</dl>
						</a>
						<!-- 20191104 찜 주석처리
						<span class="ic_bx"><span onclick="javascript:itemlistwish('<?php //echo $row['index_no']; ?>');" id="<?php //echo $row['index_no']; ?>" class="<?php //echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php //echo $it_href; ?>" target="_blank" class="nwin"></a></span> -->

					</div>
				</li>
			<?php  } ?>
				<li class="non_item" style="display:none; text-align:center; width:100%; font-size:14px;">해당 카테고리의 상품이 없습니다. </li>
			</ul>
		</div>
<script>		
	$(document).ready(function(){
		$(".bestca_tab1>li:eq(0)").addClass('active');
		$(".bstab1_c0").show();

		$(".bstab1_c1:eq(0)").hide();
		$(".bstab1_c1:eq(1)").hide();
		$(".bstab1_c1:eq(3)").hide();
		$(".bstab1_c1:eq(4)").hide();
		$(".bstab1_c1:eq(5)").hide();
		$(".bstab1_c1:eq(9)").hide();

		$(".bstab1_c2:eq(0)").hide();
		$(".bstab1_c2:eq(1)").hide();
		$(".bstab1_c2:eq(3)").hide();
		$(".bstab1_c2:eq(4)").hide();
		$(".bstab1_c2:eq(5)").hide();
		$(".bstab1_c2:eq(9)").hide();

		$(".bstab1_c3:eq(0)").hide();
		$(".bstab1_c3:eq(1)").hide();
		$(".bstab1_c3:eq(4)").hide();
		$(".bstab1_c3:eq(5)").hide();


		$(".bestca_tab1>li").click(function() {
			
			var activeTab = $(this).attr('data-tab');
			$(".bestca_tab1>li").removeClass('active');
			$("#bestgoods ul li").hide();
			$('.non_item').hide();
			$(this).addClass('active');
			$("."+activeTab).fadeIn(250);
				
			if(($(".bestca_tab1>li").eq(0)).hasClass("active")) { 	
					
				$(".bstab1_c1:eq(0)").hide();
				$(".bstab1_c1:eq(1)").hide();
				$(".bstab1_c1:eq(3)").hide();
				$(".bstab1_c1:eq(4)").hide();
				$(".bstab1_c1:eq(5)").hide();
				$(".bstab1_c1:eq(9)").hide();

				$(".bstab1_c2:eq(0)").hide();
				$(".bstab1_c2:eq(1)").hide();
				$(".bstab1_c2:eq(3)").hide();
				$(".bstab1_c2:eq(4)").hide();
				$(".bstab1_c2:eq(5)").hide();
				$(".bstab1_c2:eq(9)").hide();

				$(".bstab1_c3:eq(0)").hide();
				$(".bstab1_c3:eq(1)").hide();
				$(".bstab1_c3:eq(4)").hide();
				$(".bstab1_c3:eq(5)").hide();
			}

			if(!$("#bestgoods ul li").hasClass(activeTab)) {
				$('.non_item').fadeIn(250);
			}
		});
	});

		// $(document).ready(function(){
		// 	$(".bestca_tab1>li:eq(0)").addClass('active');
		// 	$(".bstab1_c0").show();

		// 	$(".bestca_tab1>li").click(function() {
		// 		var activeTab = $(this).attr('data-tab');
		// 		$(".bestca_tab1>li").removeClass('active');
		// 		$("#bestgoods ul li").hide();
		// 		$('.non_item').hide();
		// 		$(this).addClass('active');
		// 		$("."+activeTab).fadeIn(250);

		// 		if(!$("#bestgoods ul li").hasClass(activeTab)) {
		// 			$('.non_item').fadeIn(250);
		// 		}
		// 	});
		// });
		</script>
		<!-- } 베스트상품(자동)  끝 -->
	<?php  } ?>

	<p class="sct_btn"><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=2" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>
</div>
<!-- } 베스트상품  끝 -->

<!-- 킬딜특가시작 -->
<div class="m_main_count">
    <?php
    $sql_search = " and 1!=1 ";
    $ts = sql_fetch("select * from shop_goods_timesale where ts_sb_date <= NOW() and ts_ed_date >= NOW() ");
    if( isset($ts) ){
        $sb_date = $ts['ts_sb_date'];
        $ed_date = $ts['ts_ed_date'];
        $ts_list_code = explode(",", $ts[ts_it_code]); // 배열을 만들고
        $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
        $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
        $ts_list_code = implode(",",$ts_list_code );
        $sql_search = " and index_no in ( $ts_list_code )";
        $sql_order = " order by field ( index_no, $ts_list_code ) ";
    }

    $sql_common = sql_goods_list($sql_search);

    // 테이블의 전체 레코드수만 얻음
    $sql = " select count(*) as cnt $sql_common ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $sql = " select * $sql_common $sql_order limit 2";
    $result = sql_query($sql);
    if( $total_count > 0 ) {
    ?>
    <h2 class="mtit marb10"><span</span></h2>
    <div class="gradient"><img src="/img/timesale_top_banner2.png" style="width:100%"></div>
    <ul class="pr_desc wli2">
    <?php for($i=0; $row=sql_fetch_array($result); $i++) {
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
            //$review_avg = get_review_avg_v2($row['index_no']);
            $rv_sum_li = get_review_sum_v2($row['index_no']);
            $review_avg = $rv_sum_li['avg'];
            $item_use_count = $rv_sum_li['cnt'];
        ?>
            <li style="position:relative">
                <!--<a href="<?php echo TB_MSHOP_URL; ?>/timesale.php">-->
                <a href="<?php echo $it_href ?>">
                <?php if(strpos($it_price,"품절")){ ?>
                    <div class="soldout_layer"></div>
                <?php } ?>
                <dl>
                    <dt><img src="<?php echo $it_imageurl; ?>" alt="상품이미지"></dt>
                    <dd class="pname lineclamp2"><?php echo $it_name; ?></dd>
                    <dd class="price"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
                    <dd class="review">
                    <?php if($review_avg > 0){ ?>
                        <img src="/img/sub/comment_start.jpg"><span><?php echo $review_avg;?> (<?php echo number_format($item_use_count) ?>)</span>
                    <?php } ?>
                    </dd>
                </dl>
                </a>
            </li>
        <?php
            } // for문
        echo "</ul>";
        ?>
</div>
<p class="sct_btn"><a href="<?php echo TB_MSHOP_URL; ?>/timesale.php" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>
<?php  } ?>
<!-- 킬딜특가 끝 -->

<!-- <?php if($banner = mobile_banner(5, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?> -->

<!-- <?php if($banner = mobile_banner(6, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?> -->

<!-- <?php if($banner = mobile_banner(7, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?> -->

<!-- 인기상품 시작 { -->
<!-- <div class="mart50">
	<?php echo mobile_display_goods('4', '6', $default['de_pname_4'], 'pr_desc wli2'); ?>
</div> -->
<!-- } 인기상품 끝 -->


<!-- <?php if($banner = mobile_banner(8, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?> -->

<!-- 쇼핑이벤트 배너영역 시작 { -->

<!-- } 쇼핑이벤트 배너영역 끝 -->

<!-- 추천상품 시작 { -->
<div class="m_main_cont main_mdpick">
	<h2 class="mtit"><span>강력추천</span></h2>
	<?php echo mobile_display_goods('5', '4', $default['de_pname_5'], 'pr_desc wli2'); ?>

</div>
<!-- } 추천상품 끝 -->
<!-- 20191101 모바일 강력추천 더보기 추가 -->
<p class="sct_btn"><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=5" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>


<!-- 골프비디오 시작 { -->
<? // 리프레쉬에서 아이폰 앱에서는 골프비디오가 정상작동 하지 않는다는 문제점 이슈로 인해 리프레쉬 아이폰은 숨김 처리--- 20200622 ?>
<?
$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
if( ( $pt_id == "refreshclub" && $Android ) || ($pt_id !== 'refreshclub' ) ) { ?>

<!-- 골프매거진 시작 {-->
    <!--
<div class="m_main_cont">
	<h2 class="mtit"><span>마니아타임즈</span></h2>
	<div class="pr_desc mgz_wrap">
		<ul>
			<?php echo m_board_news_latest(45, 100, 2, $pt_id); ?>
		</ul>
    </div>
	<p class="sct_btn"><a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=45" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>
</div>
-->
<!-- } 골프매거진 끝 -->

<!-- 골프매거진 시작 {-->
<div class="m_main_cont">
	<h2 class="mtit"><span>골프매거진</span></h2>
	<div class="pr_desc mgz_wrap">
		<ul>
			<?php echo m_board_mgz_latest(43, 100, 2, $pt_id); ?>
		</ul>
    </div>
	<p class="sct_btn"><a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=43" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>
</div>
<!-- } 골프매거진 끝 -->

<!-- (2021-01-12) 골프 비디오 변경 -->
<!-- 골프비디오 시작 { -->
<div class="m_main_cont">
    <div class="golfvideo_wrap">
        <div class="cont_wrap">
            <h2 class="mtit"><span>골프영상</span></h2>
            <div class="video_wrap">
                <div class='video_box'>
                    <?php echo m_board_video_latest2(42, 100, 1, $pt_id); ?>
                </div>
            </div>
        </div>
        <p class="sct_btn"><a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=42" class="btn_lsmall bx-white1 wfull">더보기 <i class="fa fa-angle-right marl3"></i></a></p>
    </div>
</div>
<!-- 20191021 모바일 골프영상 더보기 추가-->
<!-- } 골프비디오 끝 -->

<!-- 신상품 시작 { -->
<div class="m_main_cont">
	<h2 class="mtit"><span><?php echo $default['de_pname_3']; ?></span></h2>
	<?php echo mobile_display_goods('3', '4', $default['de_pname_3'], 'pr_desc wli2'); ?>
</div>
<!-- } 신상품 끝 -->

<?php } ?>


<br>
