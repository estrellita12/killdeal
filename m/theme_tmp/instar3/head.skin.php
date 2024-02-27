<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가


include_once(TB_MTHEME_PATH.'/slideMenu.skin.php');

?>

<div id="wrapper">
	<?php if($banner1 = mobile_banner(1, $pt_id)) { // 상단 큰배너 ?>
	<!--<div class="top_ad"><?php //echo $banner1; ?></div><br>-->
	<?php } ?>
	<header id="header">
		<div id="m_gnb">
			<h1 class="logo fl marl10"><?php echo mobile_display_logo(); ?></h1>
            <a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><i></i><span class="ic_num"><?php echo get_cart_count(); ?></span></a>
            <a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" class="btn_order fa fa-shopping-order"><i></i></a>
		</div>  
        <nav id="gnb">
            <ul></ul>
        </nav>
	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<script>
		//상단 슬라이드 메뉴
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		// 페이지 접속시 해당gnb메뉴 폰트스타일변경
		$(document).ready(function() {
			$(".gnb_active a").css("font-weight", "700");
			$(".gnb_active a").css("color", "#000");
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
        <!--
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
        -->
		<?php } ?>
