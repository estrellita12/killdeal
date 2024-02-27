<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

// (2020-12-10) 동일 스킨 파일에 슬라이드 메뉴만 구별시킴
if($pt_id == 'itsgolf'){
    include_once(TB_MTHEME_PATH.'/its.slideMenu.skin.php');
}
else if($pt_id=='refreshclub'){
?>
    <script type="text/x-javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
<?php
    include_once(TB_MTHEME_PATH.'/refreshclub_slideMenu.skin.php');
}
?>

<div id="wrapper">
	
	<?php if($banner1 = mobile_banner(1, $pt_id)) { // 상단 큰배너 ?>
	<!--<div class="top_ad"><?php //echo $banner1; ?></div><br>-->
	<?php } ?>
	<!-- 20200403주석 active걸려있으면 gnb가 첫화면에 안보임(header.active시 fixed되어있기때문) -->
	<!-- <header id="header" style="height:60px" class="active"> -->
	<header id="header" style="height:60px">
		<div id="m_gnb" style="background-color:rgba(255, 255, 255, 0.1);">
		<div id="its_hd_sch">
			<section style="width:80%; margin-left:19%">
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
					if($("#its_hd_sch").css('display') == 'none'){
						$("#its_hd_sch").slideDown('fast');
						$(this).attr('class','btn_search ionicons ion-android-close');
					} else {
						$("#its_hd_sch").slideUp('fast');
						$(this).attr('class','btn_search fa fa-search');
					}
				});
			});
			
			flg = 0;
			
			//$("input[name=ss_tx]").focus() == false
			$("input[name=ss_tx]").focus(function() {
			   flg = 1;
			  //alert( "Handler for .focus() called." );
			});
			$("input[name=ss_tx]").blur(function() {
			   flg = 0;
			  //alert( "Handler for .focus() called." );
			});
			jQuery(window).load(function() {
     
			  // 처음 시작시 화면의 사이즈 값을 가진다.
			  var originalSize = jQuery(window).width() + jQuery(window).height();
			  
			  // 창의 사이즈 변화가 일어났을 경우 실행된다.
			  jQuery(window).resize(function() {
				// 처음 사이즈와 현재 사이즈가 변경된 경우
				// 키보드가 올라온 경우
				if(jQuery(window).width() + jQuery(window).height() != originalSize &&  flg == 0) {
				   $("#header").css('display','none');
				   $("#daum_juso_rayerzip").css('height','45%');
				  //alert("가상 키보드가 오픈 되었습니다."); 
				}
			   
				// 처음 사이즈와 현재 사이즈가 동일한 경우
				// 키보드가 다시 내려간 경우
				else {
				  $("#header").css('display', 'block');
				  $("#daum_juso_rayerzip").css('height','75%');
				  //alert("가상 키보드의 사용지 종료되었습니다.");
				}
			  });
			});

			function back_btn()
			{
				if(document.referrer)
				{
					//alert(document.referrer);
					if(location.pathname == '/m/shop/orderinquiryview.php')
					{
						location.href="/m/";
					}
					else
					{
						history.back(-1);
					}
				}
				else
				{
					window.parent.postMessage({'message':'main'}, '*' )
				}
			}
			</script>

			<!-- <div class="m_rkw_se" id="rkw_open">
				<?php echo mobile_popular("인기검색어", 6, $pt_id); ?>
				<button type="button" class="btn_open"></button>
			</div>
			<div class="m_rkw_se" id="rkw_close" style="display:none;">
				<h2>인기검색어 순위</h2>
				<button type="button" class="btn_close"></button>
			</div>
			<div class="m_rkw_bg" style="display:none;">
				<?php echo mobile_popular_rank(10, $pt_id); ?>
			</div> -->

			<!-- <script>
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
			</script> -->
		</div>
			<!-- <span class="btn_search fa fa-search"></span> -->
			<div id="floatdiv"><a href="javascript:back_btn();"><img src="/img//btn/btn_back.png?111"></a></div>
		</div>
	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<nav id="gnb">
			<ul>
				<!-- 20200414 주석 (head.skin.php와 동일한코드로 대체) -->
				<!-- <li><a href="<?php  echo TB_URL.'/m/ '; ?>">홈</a></li> -->
				<!-- <?php
				for($i=0; $i<count($gw_menu); $i++) {
					$seq = ($i+1);
					$page_url = TB_MURL.$gw_menu[$i][1];
					if(!$default['de_pname_use_'.$seq] || !$default['de_pname_'.$seq])
						continue;
					echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;
				}
				?> -->
					<!-- <li><a href="<?php echo TB_MSHOP_URL.'/listtype.php?type=2'; ?>">BEST100</a></li>
					<li><a href="<?php echo TB_MSHOP_URL.'/plan.php'; ?>">기획전</a></li>
					<li><a href="<?php echo TB_MSHOP_URL.'/listtype.php?type=3'; ?>">신상품</a></li> -->
					<!-- <li><a href="<?php //echo TB_MBBS_URL.'/board_list.php?boardid=43'; ?> ">골프매거진</a></li> -->
					<!-- <li><a href="<?php echo TB_MSHOP_URL.'/timesale.php'; ?>">타임세일</a></li> -->
				<?php
					$m_url = substr($_SERVER['REQUEST_URI'],2);
					if($m_url[3] == null )
					{
						echo '<li class="gnb_active"><a href="'.TB_URL.'/m/">홈</a></li>';
					}
					else
					{
						echo '<li><a href="'.TB_URL.'/m/">홈</a></li>';
					}

					for($i=0; $i<count($gw_menu); $i++) {
						$seq = ($i+1);
						$page_url = TB_MURL.$gw_menu[$i][1];

						if(!$default['de_pname_use_'.$seq] || !$default['de_pname_'.$seq] || $gw_menu[$i][1] == '/shop/timesale.php')
							continue;

						$gw_menu[$i][1];
										
						if($m_url ==  $gw_menu[$i][1])
						{
							echo '<li class="gnb_active"><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;
						}
						else
						{
							echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;	
						}	
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

		$(document).ready(function() {
			$(".gnb_active a").css("font-weight", "700");
			$(".gnb_active a").css("color", "#000");
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
		<?php } ?>
