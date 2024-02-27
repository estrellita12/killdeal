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
			<h1 class="logo"><?php echo mobile_display_logo(); ?></h1>
			<div class="btn_sidem">
				<span class="btn_line line_no1"></span>
				<span class="btn_line line_no2"></span>
				<span class="btn_line line_no3"></span>
			</div>
			<!-- <span class="btn_sidem fa fa-navicon"><i></i></span> -->
			<!-- <span class="btn_search fa fa-search"></span> -->
			<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><i></i><span class="ic_num"><?php echo get_cart_count(); ?></span></a>
		</div>
		<div id="hd_sch">
			<section>
				<h2 class="hd_sch_tit">검색바</h2>
				<form name="fsearch" id="fsearch" method="get" action="<?php echo TB_MSHOP_URL; ?>/search.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
				<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
				<label><input type="text" name="ss_tx" value="<?php echo $ss_tx; ?>" class="search_inp" maxlength="20"></label>
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
		<nav id="gnb">
			<ul>
			<?php
            // 2021-12-31 위치 이동
			$m_url = substr($_SERVER['REQUEST_URI'],2);
			if($m_url[3] == null )
			{ 
					echo '<li class="gnb_active"><a href="'.TB_URL.'/m/">홈</a></li>';				
			}
			else
			{
					echo '<li><a href="'.TB_URL.'/m/">홈</a></li>';
			}
        
            // 2021-08-13
            $gw_menu_list = array(2, 5, 6, 7, 1, 3,4,9 ,8 );
            foreach( $gw_menu_list as $i ){
                $seq = ($i-1);
                $page_url = TB_URL.$gw_menu[$seq][1];
                if( ($default['de_pname_use_'.$i] != 1 && $default['de_pname_use_'.$i] != 3)  || !$default['de_pname_'.$i])
                    continue;
                $img="";
                if($i==8){$img = "<img src='/img/clock.png' style='width:18px'>";}
                echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$i].$img.'</a></li>'.PHP_EOL;
            }
			?>
			</ul>
		</nav>

	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
        <!--
		<nav id="gnb">
			<ul>
			<?php
            /*
			$m_url = substr($_SERVER['REQUEST_URI'],2);
			if($m_url[3] == null )
			{ 
					echo '<li class="gnb_active"><a href="'.TB_URL.'/m/">홈</a></li>';				
			}
			else
			{
					echo '<li><a href="'.TB_URL.'/m/">홈</a></li>';
			}
        
            // 2021-08-13
            $gw_menu_list = array(2, 5, 6, 7, 1, 3,4,9 ,8 );
            foreach( $gw_menu_list as $i ){
                $seq = ($i-1);
                $page_url = TB_URL.$gw_menu[$seq][1];
                if( ($default['de_pname_use_'.$i] != 1 && $default['de_pname_use_'.$i] != 3)  || !$default['de_pname_'.$i])
                    continue;
                $img="";
                if($i==8){$img = "<img src='/img/clock.png' style='width:18px'>";}
                echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$i].$img.'</a></li>'.PHP_EOL;
            }
            */
			?>
			</ul>
		</nav>
        -->
		<script>
		//상단 슬라이드 메뉴
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		$(document).ready(function() {
            // 페이지 접속시 해당gnb메뉴 폰트스타일변경            
			$(".gnb_active a").css("font-weight", "700");
			$(".gnb_active a").css("color", "#000");

            //아이폰, 안드로이드 구분 후 확대 metatag 적용
            var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
            if ( varUA.indexOf('android') > -1) { // 안드로이드
                return "android";
            } else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) { // 아이폰
                $('#header').after('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>');                
                return "ios";
            } else { //아이폰, 안드로이드 외
                return "other";
            }
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
		<?php } ?>
