<?php
if(!defined('_TUBEWEB_')) exit;
?>
<style>
.pr_desc li .price .recommendation {
    background: black;
    color: white;
    font-size: 12px;
    font-weight: normal;
}
.pr_desc li .price .mpr {
    padding-left:10px;
    font-weight: normal;
    color: #ed3636;
}
.pr_desc li .price .mpr span {
    font-weight: normal;
    margin-left: 2px;
    font-size: 80%;
}
.pr_desc li .price .sale {
    font-size: 20px;
    line-height: 20px;
    display: inline-block;
    color: #111;
    margin-right: 5px;
}

.bestca_tab1 {height:40px;margin:35px auto 25px !important;text-align:center;overflow:visible;clear:both; text-align:center;}
.bestca_tab1 li {font-size:15px; color:#333;display:inline-block; border-radius:30px; border:1px solid transparent; height: 35px; width: 120px; margin-right: 16px;
	/*
    background: -webkit-gradient(linear, 0 0, 0 100%, from(0.25turn), color-stop(0, #7607af),color-stop(0.5, #9508b2), color-stop(0.75, #cc2792), to(#dc1a5e));
	background: -webkit-linear-gradient(0.25turn,#7607af,#9508b2,#cc2792,#dc1a5e);
	background: -moz-linear-gradient(0.25turn,#7607af,#9508b2,#cc2792,#dc1a5e);
	background: -o-linear-gradient(0.25turn,#7607af,#9508b2,#cc2792,#dc1a5e);
	background: linear-gradient(0.25turn,#7607af,#9508b2,#cc2792,#dc1a5e); 
	*/
    filter:progid:DXimageTransform.Microsoft.gradient(GradientType=1,startColorstr='#7607af',endColorstr='#dc1a5e');
	text-align:center;cursor:pointer;position:relative;overflow:visible;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.bestca_tab1 li:last-child{margin-right:0;}
.bestca_tab1 li span {display:block; height: 33px; width: 118px; line-height:32px; background:#fff; border-radius:30px;}
.bestca_tab1 li.active span{background:none; color:#fff;}
.bestca_tab1 li span::after{
    content: "";
    display: inline-block;
    height: 25px;
    width: 20px;    
    margin: 0 0 0 8px;	
	vertical-align: bottom;
}
.bestca_tab1 li:nth-child(1) span::after{
	background: url(./instarimg/best_d_star.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(1).active span::after{
	background: url(./instarimg/best_w_star.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(2) span::after{
	background: url(./instarimg/best_d_club.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(2).active span::after{
	background: url(./instarimg/best_w_club.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(3) span::after{
	background: url(./instarimg/best_d_ball.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(3).active span::after{
	background: url(./instarimg/best_w_ball.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(4) span::after{
	background: url(./instarimg/best_d_shirt.png) no-repeat 0px 0px; 
}
.bestca_tab1 li:nth-child(4).active span::after{
	background: url(./instarimg/best_w_shirt.png) no-repeat 0px 0px; 
}
.bestca_tab1 li a{display:block; height: 32px; width: 118px; line-height:32px; background:#fff; border-radius:30px; text-decoration:none;}
.bestca_tab1 li.active a{background:none; color:#fff;}


</style>
<script>
// 웹접근성향상을 위한 속성 추가
$(document).ready(function(){
	$('.golf_mgz_wrap .plan_img img').attr('alt','골프매거진썸네일');
	$('.golfvideo .video_wrap .video_box iframe').attr('title','골프영상');
});
</script>
<?php if(isset($main_banner_hidden) && $main_banner_hidden == true){ echo "<div style='height:20px'></div>"; } ?>
<!-- 베스트상품 시작 {-->
<div class="cont_wrap marb20">
	<h2 class="mtit">
        <span>
            <!--<?php echo $default['de_pname_2']; ?>-->
            <?php echo "BEST20"; ?>
        </span>
        <!--
        <a id="cate_addr" href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=2">더보기 ></a>
        -->
    </h2>
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
		<div class="bestca pr_desc wli4 mart40">
			<?php echo get_listtype_cate2($list_best, '327', '327'); ?>
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
	<?php  } ?>
</div>
<!-- } 베스트상품  끝 -->

<!-- 추천상품 시작 { -->
<div class="cont_wrap marb20">
	<h2 class="mtit"><span>강력추천</span><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=5">더보기 ></a></h2>
	<?php echo get_listtype_skin2("5", '327', '327', '8', 'wli4 mart40'); ?>
</div>
<!-- } 추천상품 끝 -->

<!-- 신상품 시작 { -->
<div class="cont_wrap marb20">
	<h2 class="mtit"><span><?php echo $default['de_pname_3']; ?></span><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=3&page_rows=&sort=index_no&sortodr=desc">더보기 ></a></h2>
	<?php echo get_listtype_skin2("3", '327', '327', '8', 'wli4 mart40'); ?>
</div>
<!-- } 신상품 끝 -->
<div style="height:50px;">
</div>





