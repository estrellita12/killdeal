<?php
if(!defined('_TUBEWEB_')) exit;
header( "Location: ../shop/list.php?ca_id=007" );
?>

<script>
//20200414 주석(현재사용하지않는기능)
// function CountDownTimer(dt, id)
// {
// 	var end = new Date(dt);

// 	var _second = 1000;
// 	var _minute = _second * 60;
// 	var _hour = _minute * 60;
// 	var _day = _hour * 24;
// 	var timer;

// 	function showRemaining() {
// 		var now = new Date();
// 		var distance = end - now;
// 		if (distance < 0) {
// 			clearInterval(timer);
// 			document.getElementById(id).innerHTML = 'EXPIRED!';
// 			return;
// 		}
// 		var days = Math.floor(distance / _day);
// 		var hours = Math.floor((distance % _day) / _hour);
// 		var minutes = Math.floor((distance % _hour) / _minute);
// 		var seconds = Math.floor((distance % _minute) / _second);
// 		var str = "";
// 		str += '<span class="num">'+days + '</span>일 ';
// 		str += '<span class="num">'+pad(hours,2) + '</span> :  ';
// 		str += '<span class="num">'+pad(minutes,2) + '</span> : ';
// 		str += '<span class="num">'+pad(seconds,2) + '</span>';
// 		document.getElementById(id).innerHTML = str;
// 	}

// 	timer = setInterval(showRemaining, 1000);
// }

// function pad(n, width) {
//   n = n + '';
//   return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
// }

// 웹접근성향상을 위한 속성 추가
$(document).ready(function(){
	$('.golf_mgz_wrap .plan_img img').attr('alt','골프매거진썸네일');
	$('.golfvideo .video_wrap .video_box iframe').attr('title','골프영상');
});
</script>
<?

	        //****RETURN_URL 인코딩/디코딩 테스트
            /*
            $url_ori = "https://jasmingolf.com/shop/planlist.php?pl_no=28";
			$url_en = urlencode($url_ori);
			echo("url_en:".$url_en."<br>");
            $url_de = urldecode($url_en);
			echo("url_de:".$url_de);
			*/

?>

<!-- 베스트상품 시작 {-->
<div class="cont_wrap marb20">
	<h2 class="mtit"><span><?php echo $default['de_pname_2']; ?></span><a id="cate_addr" href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=2">더보기 ></a></h2>
	<?php if($default['de_listing_best'] == '1') { ?>
		<!-- 베스트상품(수동) 카테고리별 베스트 시작 {-->
		<?php
		if($default['de_maintype_best']) {
			$list_best = unserialize(base64_decode($default['de_maintype_best']));

			$list_count = count($list_best);
			$tab_width = (float)(100 / $list_count);
		?>
		<!-- <h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2> -->
		<ul class="bestca_tab1">
			<?php for($i=0; $i<$list_count; $i++) {
					$j = $i;
					if($i == '2'){
						$j = '2';
					} else if ($i == '3') {
						$j = '3';
					}
			?>
			<!-- <li onclick="cate_list('<?php echo trim($list_best[$i]['subj']); ?>')" data-tab="bstab_c<?php echo $j; ?>"><span ><?php echo trim($list_best[$i]['subj']); ?></span></li> -->
			<li onclick="cate_list('<?php echo $i; ?>')" data-tab="bstab_c<?php echo $j; ?>"><span ><?php echo trim($list_best[$i]['subj']); ?></span></li>
			<?php } ?>
		</ul>
		<div class="bestca pr_desc wli5 mart5">
			<?php echo get_listtype_cate($list_best, '256', '256'); ?>
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

			<? if($pt_id == 'golfpang')
			   {
			?>
			       //alert(get_cookie('gp_id'));
		    <? } ?>
		});

		//20200520 더 보기가 인기상품으로 가는 문제점 유지보수
        /*
		function cate_list(cate_name){
			console.log("11");
			var cate = '';
			if(cate_name == "인기상품"){
				cate = '';
				$("#cate_addr").attr("href", "<?php echo TB_SHOP_URL; ?>/listtype.php?type=2&cat="+cate);

			}else if(cate_name =="골프클럽" ){
				cate = '001';
				$("#cate_addr").attr("href", "<?php echo TB_SHOP_URL; ?>/listtype.php?type=2&cat="+cate);

			}else if(cate_name =="골프용품" ){
				cate = '003';
				$("#cate_addr").attr("href", "<?php echo TB_SHOP_URL; ?>/listtype.php?type=2&cat="+cate);

			}else if(cate_name =="골프패션" ){
				cate = '002';
				$("#cate_addr").attr("href", "<?php echo TB_SHOP_URL; ?>/listtype.php?type=2&cat="+cate);
			}
		}
        */

        function cate_list(idx){
            var cate = "00"+idx;
            $("#cate_addr").attr("href", "<?php echo TB_SHOP_URL; ?>/listtype.php?type=2&cat="+cate);
        }

		</script>
		<?php } ?>
		<!-- } 베스트상품(수동) 카테고리별 베스트 끝 -->
	<?php } else if ($default['de_listing_best'] == '0') { ?>

		<!-- 베스트상품(자동) 시작 {-->
		<?php
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);

		//$sql = " select a.index_no, a.simg1, a.gname, a.gpoint, a.ca_id, count(a.gcode) as qty_count from shop_goods a inner join shop_order b on (a.index_no = b.gs_id)  where b.od_time >= subdate(now(), interval 2 week) group by a.gcode desc limit 0, 10 ";
		$sql = "
				(SELECT a.index_no AS index_no, a.simg1 AS simg1, a.gname AS gname, a.gpoint AS gpoint, a.ca_id AS ca_id, count(gcode) as qty_count ,a.gcode AS gcode
				, a.normal_price AS normal_price
					from shop_goods a 
					inner join shop_order b on (a.index_no = b.gs_id)
					WHERE ca_id like '001%'
					group by a.gcode ORDER BY qty_count DESC LIMIT 10)
				 UNION ALL
			 (SELECT a1.index_no AS index_no, a1.simg1 AS simg1, a1.gname AS gname, a1.gpoint AS gpoint, a1.ca_id AS ca_id, count(gcode) as qty_count ,a1.gcode AS gcode
			 , a1.normal_price AS normal_price
					from shop_goods a1 
					inner join shop_order b1 ON (a1.index_no = b1.gs_id)
					WHERE ca_id like '002%'
					group BY a1.gcode  ORDER BY qty_count DESC LIMIT 10) 
				 UNION ALL
			 ( SELECT a2.index_no AS index_no, a2.simg1 AS simg1, a2.gname AS gname, a2.gpoint AS gpoint, a2.ca_id AS ca_id, count(gcode) as qty_count ,a2.gcode AS gcode
			 , a2.normal_price AS normal_price
					from shop_goods a2 
					inner join shop_order b2 ON (a2.index_no = b2.gs_id)
					WHERE ca_id like '003%'
					group BY a2.gcode  ORDER BY qty_count DESC LIMIT 10 ) ";					

		$result = sql_query($sql);

		?>
		<ul class="bestca_tab1">
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
		<div class="pr_desc wli5 mart5" id="bestgoods">
			<ul>
			<?php
			//echo $sql;
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
				$it_image = get_it_image($row['index_no'], $row['simg1'], 256, 256);
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
						<span class="ic_bx"><span onclick="javascript:itemlistwish('<?php //echo $row['index_no']; ?>');" id="<?php //echo $row['index_no']; ?>" class="<?php //echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php //echo $it_href; ?>" target="_blank" class="nwin"></a></span>
						-->
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
				$(".bstab1_c1:eq(2)").hide();
				$(".bstab1_c1:eq(3)").hide();
				$(".bstab1_c1:eq(4)").hide();
				$(".bstab1_c1:eq(5)").hide();
				$(".bstab1_c1:eq(9)").hide();
				$(".bstab1_c1:eq(6)").css("margin-left","0");

				$(".bstab1_c2:eq(0)").hide();
				$(".bstab1_c2:eq(1)").hide();
				$(".bstab1_c2:eq(2)").hide();
				$(".bstab1_c2:eq(3)").hide();
				$(".bstab1_c2:eq(4)").hide();
				$(".bstab1_c2:eq(5)").hide();
				$(".bstab1_c2:eq(9)").hide();
				$(".bstab1_c2:eq(8)").css("margin-left","0");

				$(".bstab1_c3:eq(0)").hide();
				$(".bstab1_c3:eq(1)").hide();
				$(".bstab1_c3:eq(2)").hide();
				$(".bstab1_c3:eq(3)").hide();
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
					$(".bstab1_c1:eq(2)").hide();
					$(".bstab1_c1:eq(3)").hide();
					$(".bstab1_c1:eq(4)").hide();
					$(".bstab1_c1:eq(5)").hide();
					$(".bstab1_c1:eq(9)").hide();
					$(".bstab1_c1:eq(6)").css("margin-left","0");

					$(".bstab1_c2:eq(0)").hide();
					$(".bstab1_c2:eq(1)").hide();
					$(".bstab1_c2:eq(2)").hide();
					$(".bstab1_c2:eq(3)").hide();
					$(".bstab1_c2:eq(4)").hide();
					$(".bstab1_c2:eq(5)").hide();
					$(".bstab1_c2:eq(9)").hide();
					$(".bstab1_c2:eq(8)").css("margin-left","0");

					$(".bstab1_c3:eq(0)").hide();
					$(".bstab1_c3:eq(1)").hide();
					$(".bstab1_c3:eq(2)").hide();
					$(".bstab1_c3:eq(3)").hide();
					$(".bstab1_c3:eq(4)").hide();
					$(".bstab1_c3:eq(5)").hide();

				} else if(!($(".bestca_tab1>li").eq(0)).hasClass("active")) {
					$(".bstab1_c1:eq(6)").css("margin-left","19px");
					$(".bstab1_c2:eq(8)").css("margin-left","19px");
				}
				if(!$("#bestgoods ul li").hasClass(activeTab)) {
					$('.non_item').fadeIn(250);
				}
			   });
		});
		</script>
		<!-- } 베스트상품(자동)  끝 -->
	<?php  } ?>
</div>
<!-- } 베스트상품  끝 -->


<!-- 큰 배너 배경 및 문구 시작 { -->
<?php echo mask_banner(7, $pt_id); ?>
<!-- } 큰 배너 배경 및 문구 끝 -->

<!-- 인기상품 시작 { -->
<!-- <div class="cont_wrap mart60 marb99">
	<h2 class="mtit"><span><?php echo $default['de_pname_4']; ?></span></h2>
	<?php echo get_listtype_skin("4", '235', '235', '12', 'wli4 mart5'); ?>
</div> -->
<!-- } 인기상품 끝 -->

<!-- 타임특가 시작 { -->
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

$sql = " select * $sql_common $sql_order limit 4";
$result = sql_query($sql);

if( $total_count > 0 ) {
?>
<div class="cont_wrap marb20">
    <div class="pr_desc wli4 mart10">
    <div class="gradient marb10" style="position:relative"><img src="/img/timesale_top_banner2.png">
    <a style="position:absolute; top:0px; right:0px; z-index:100; line-height: 25px; font-size: 14px; text-decoration: none; font-weight: 600;" href="    <?php echo TB_SHOP_URL; ?>/timesale.php">더보기 ></a>
    </div>
        <ul>
        <?php
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $it_image = get_it_image($row['index_no'], $row['simg1'], 327,327);
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

    ?>
            <li>
                <a href="<?php echo TB_SHOP_URL; ?>/timesale.php">
                <?php if(strpos($it_price,"품절") || strpos($it_price,"중지")){ ?>
                    <div class="soldout_layer"></div>
                <?php } ?>
                    <dl>
                        <dt><?php echo $it_image;?></dt>
                        <dd class="pname"><?php echo $it_name; ?></dd>
                        <dd class="price"><?php echo $sale; ?> &nbsp;<span class="price_box"><?php echo $it_sprice; ?><span class="memopen" style="background:rgb(211,21,21)">회원공개</span></span></dd>
                    </dl>
                </a>
            </li>
    <?php } ?>
        </ul>
    </div>
</div>
<?php } // if  ?>
<!-- } 타임특가 끝 -->

<!-- 추천상품 시작 { -->
<div class="cont_wrap marb20">
	<h2 class="mtit"><span>강력추천</span><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=5">더보기 ></a></h2>
	<?php echo get_listtype_skin("5", '327', '327', '8', 'wli4 mart40'); ?>
</div>
<!-- } 추천상품 끝 -->

<!--  미디어컨텐츠 시작  -->
<div class="cont_wrap marb20">
	<h2 class="mtit media_cont"><span>미디어 콘텐츠</span></h2>
    <!-- 골프비디오 시작 { -->

	<!-- 골프매거진 시작 {-->
	<div class="golf_mgz_wrap">
		<a class="btn_link" href="<?php echo "bbs"; ?>/list.php?boardid=45">마니아타임즈 더보기 ></a>
		<ul>
			<?php echo board_news_latest(45, 100, 2, $pt_id); ?>
		</ul>
	</div>
    <!-- } 골프매거진 끝 -->

	<!-- 골프매거진 시작 {-->
	<div class="golf_mgz_wrap">
		<a class="btn_link" href="<?php echo "bbs"; ?>/list.php?boardid=43">매거진 더보기 ></a>
		<ul>
			<?php echo board_mgz_latest(43, 100, 2, $pt_id); ?>
		</ul>
	</div>
    <!-- } 골프매거진 끝 -->
    <br>
    <!-- 골프비디오 수정 20191014 더보기 버튼 추가, 골프비디오 게시판 연결 -->
    <!-- (2021-01-12) 골프비디오 수정 -->
    <div class="golfvideo">
        <a class="btn_link" href="<?php echo "bbs"; ?>/list.php?boardid=42">영상 더보기 ></a>
        <div class="video_wrap">
            <?php echo board_video_latest2(42, 100, 2, $pt_id); ?>
        </div>
    </div>
    <!-- } 골프비디오 끝 -->
</div>

<!--  미디어컨텐츠 끝  -->




<!-- 신상품 시작 { -->
<div class="cont_wrap marb20">
	<h2 class="mtit"><span><?php echo $default['de_pname_3']; ?></span><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=3&page_rows=&sort=index_no&sortodr=desc">더보기 ></a></h2>
	<?php echo get_listtype_skin("3", '327', '327', '8', 'wli4 mart40'); ?>
</div>
<!-- } 신상품 끝 -->

<!-- 하단 배너영역 시작 { -->
<!-- <?php echo display_banner(12, $pt_id); ?> -->
<?php
	$sql = sql_banner_rows(12, $pt_id);
	$res = sql_query($sql);
	$mbn_rows = sql_num_rows($res);
	if($mbn_rows) {
?>
<div id="mb_b_wrap"> <!-- style="height: 600px;" -->
	<?php
	$txt_w = (100 / $mbn_rows);
	$txt_arr = array();
	for($i=0; $row=sql_fetch_array($res); $i++)
	{
		if($row['bn_text'])
			$txt_arr[] = $row['bn_text'];

		$a1 = $a2 = $bg = '';
		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
			if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

			$file = rpc($file, TB_PATH, TB_URL);
			echo "<div class=\"mbn_img\" style=\"background:{$bg}url('{$file}') no-repeat top center;\">{$a1}{$a2}</div>\n";
		}
	}
	?>
</div>
<script>
$(document).on('ready', function() {
	<?php if(count($txt_arr) > 0) { ?>
	var txt_arr = <?php echo json_encode($txt_arr); ?>;

	$('#mb_b_wrap').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: false,
		fade: false,
		customPaging: function(slider, i) {
			return "<span>"+txt_arr[i]+"</span>";
		}
	});
	$('#mb_b_wrap .slick-dots li').css('width', '<?php echo $txt_w; ?>%');

	<?php } else { ?>
	$('#mb_b_wrap').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: false,
		fade: false
	});

	<?php } ?>
});
</script>
<?php
}
?>
<!-- } 하단 배너영역 끝 -->


