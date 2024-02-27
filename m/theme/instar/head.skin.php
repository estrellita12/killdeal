<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

include_once(TB_MTHEME_PATH.'/slideMenu.skin.php');
?>

<div id="wrapper">
	<header id="header">
		<div id="m_gnb">
            <!--로고-->
			<h1 class="logo"><?php echo mobile_display_logo(); ?></h1>
            <div class="btn_sidem">
				<span class="btn_line line_no1"></span>
				<span class="btn_line line_no2"></span>
				<span class="btn_line line_no3"></span>
			</div>
            <!--장바구니-->
<!--            <span><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" class="btn_order"><i></i><span class="ic_num"></a></span>            -->
			<span><a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><i></i><span class="ic_num"><?php echo get_cart_count(); ?></span></a></span>	
            <?php if ($_SERVER['PHP_SELF'] != '/m/bbs/login.php' && $_SERVER['PHP_SELF'] != '/m/shop/cart.php'){?>
            <!--검색바-->
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
		    </div>    
            <?php } ?>       
		</div>

		<nav id="gnb">
			<ul>
			</ul>
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
		<!--	
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
        -->
		<?php } ?>
