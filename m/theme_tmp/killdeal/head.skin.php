<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

include_once(TB_MTHEME_PATH.'/slideMenu.skin.php');
?>

<div id="wrapper">
	<div onclick="history.go(-1);" class="page_cover"><span class="sm_close"></span></div>
	<?php if($banner1 = mobile_banner(1, $pt_id)) { // 상단 큰배너 ?>
	<div class="top_ad"><?php echo $banner1; ?></div>
	<?php } ?>
	<header id="header">
		<div id="m_gnb">
			<h1 class="logo"><?php echo mobile_display_logo(); ?></h1>
			<span class="btn_sidem fa fa-navicon"></span>
			<span class="btn_search fa fa-search"></span>
			<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><span class="ic_num"><?php echo get_cart_count(); ?></span></a>
		</div>
		<div id="hd_sch">
			<section>
				<form name="fsearch" id="fsearch" method="get" action="<?php echo TB_MSHOP_URL; ?>/search.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
				<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
				<input type="search" name="ss_tx" value="<?php echo $ss_tx; ?>" class="search_inp" maxlength="20">
				<input type="submit" value="&#xf002;" id="sch_submit">
				</form>
				<script>
				function fsearch_submit(f){
					if(!f.ss_tx.value){
						alert('검색어를 입력하세요.');
						return false;
					}
					return true;
				}
				</script>
			</section>
			<script>
			$(function(){
				// 상단의 검색버튼 누르면 검색창 보이고 끄기
				$('.btn_search').click(function(){
					if($("#hd_sch").css('display') == 'none'){
						$("#hd_sch").slideDown('fast');
						$(this).attr('class','btn_search ionicons ion-android-close');
					} else {
						$("#hd_sch").slideUp('fast');
						$(this).attr('class','btn_search fa fa-search');
					}
				});
			});
			</script>

			<div class="m_rkw_se" id="rkw_open">
				<?php echo mobile_popular("인기검색어", 6, $pt_id); ?>
				<button type="button" class="btn_open"></button>
			</div>
			<div class="m_rkw_se" id="rkw_close" style="display:none;">
				<h2>인기검색어 순위</h2>
				<button type="button" class="btn_close"></button>
			</div>
			<div class="m_rkw_bg" style="display:none;">
				<?php echo mobile_popular_rank(10, $pt_id); ?>
			</div>

			<script>
			// 인기검색어 펼침
			$(".m_rkw_se .btn_open").click(function(){
				$("#rkw_open").hide();
				$("#rkw_close").show();
				$(".m_rkw_bg").show();
			});

			// 인기검색어 닫음
			$(".m_rkw_se .btn_close").click(function(){
				$("#rkw_open").show();
				$("#rkw_close").hide();
				$(".m_rkw_bg").hide();
			});

			// 인기검색어 롤링
			function tick(){
				$('#ticker li:first').slideUp( function () {
					$(this).appendTo($('#ticker')).slideDown();
				});
			}
			setInterval(function(){ tick () }, 4000);
			</script>
		</div>
	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<nav id="gnb">
			<ul>
			<?php
			for($i=0; $i<count($gw_menu); $i++) {
				$seq = ($i+1);
				$page_url = TB_MURL.$gw_menu[$i][1];
				if(!$default['de_pname_use_'.$seq] || !$default['de_pname_'.$seq])
					continue;

				echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;
			}
			?>
			</ul>
		</nav>
		<script>
		//상단 슬라이드 메뉴
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
		<?php } ?>
