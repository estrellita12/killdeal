<?php
if(!defined('_TUBEWEB_')) exit;

if(defined('_INDEX_')) { // index에서만 실행
	include_once(TB_LIB_PATH.'/popup.inc.php'); // 팝업레이어
}
?>

<? 
      if($pt_id == 'golfpang'){ //골팡 상단메뉴

		  if($is_member) { // 로그인

 ?>
          
		 <div style="background-image: url(https://mall.golfpang.com/img/alliance/bg_y.png); margin: 0; padding: 0; height: 111px;">
		<div style="width:980px; margin: 0 auto; padding: 0;">
		<img src="https://mall.golfpang.com/img/alliance/top-logout.png" alt="" width="980" height="111" usemap="#top_link1"/>
          <map name="top_link1">
			<area shape="rect" coords="864,48,977,110" href="http://mall.golfpang.com" alt="쇼핑몰 ">
			<area shape="rect" coords="749,48,862,110" href="http://www.golfpang.com/web/round/after_list.do" alt="커뮤니티 ">
			<area shape="rect" coords="635,48,749,110" href="http://www.golfpang.com/web/round/tour_outer_item_main.do" alt="해외투어ㅣ항공 ">
			<area shape="rect" coords="524,48,635,110" href="http://www.golfpang.com/web/round/tour_item_main.do" alt="국내투어 ">
			<area shape="rect" coords="408,48,523,110" href="http://www.golfpang.com/web/round/special_list.do" alt="특가">
			<area shape="rect" coords="294,48,409,110" href="http://www.golfpang.com/web/round/join_list.do" alt="조인">
			<area shape="rect" coords="187,48,294,110" href="http://www.golfpang.com/web/round/booking_list.do" alt="부킹">
            <area shape="rect" coords="-6,47,142,111" href="http://www.golfpang.com" alt="golfpang">
            <area shape="rect" coords="625,12,672,36" href="https://mall.golfpang.com/bbs/logout.php" alt="로그아웃"> <!-- 내부 로그아웃처리 후 골팡으로 이동 -->
			<area shape="rect" coords="770,10,846,38" href="http://www.golfpang.com/web/mymembers/inquiry_list.do" alt="마이골팡   ">
			<area shape="rect" coords="701,12,748,35" href="http://www.golfpang.com/web/mymembers/mypage.do" alt="정보변경  ">
			<area shape="rect" coords="849,10,914,37" href="http://www.golfpang.com/web/customer/notice.do" alt="고객센터 ">
			<area shape="rect" coords="929,10,975,37" href="http://www.golfpang.com/web/event/event.do" alt="이벤트 ">
          </map>
		</div>
	</div>

		

<?     } else { // 비로그인 ?>



	   <div style="background-image: url(https://mall.golfpang.com/img/alliance/bg_y.png); margin: 0; padding: 0; height: 111px;">
		<div style="width:980px; margin: 0 auto; padding: 0;">
			<img src="https://mall.golfpang.com/img/alliance/top-login.png" alt="" width="980" height="111" usemap="#top_link1"/>
	          <map name="top_link1">
			  <area shape="rect" coords="864,48,977,110" href="http://mall.golfpang.com" alt="쇼핑몰 ">
			  <area shape="rect" coords="749,48,862,110" href="http://www.golfpang.com/web/round/after_list.do" alt="커뮤니티 ">
			  <area shape="rect" coords="635,48,749,110" href="http://www.golfpang.com/web/round/tour_outer_item_main.do" alt="해외투어ㅣ항공 ">
			  <area shape="rect" coords="524,48,635,110" href="http://www.golfpang.com/web/round/tour_item_main.do" alt="국내투어 ">
			  <area shape="rect" coords="408,48,523,110" href="http://www.golfpang.com/web/round/special_list.do" alt="특가">
			  <area shape="rect" coords="294,48,409,110" href="http://www.golfpang.com/web/round/join_list.do" alt="조인">
			  <area shape="rect" coords="187,48,294,110" href="http://www.golfpang.com/web/round/booking_list.do" alt="부킹">
			  <area shape="rect" coords="-6,47,142,111" href="http://www.golfpang.com" alt="golfpang">
	          <area shape="rect" coords="703,12,761,38" href="http://www.golfpang.com/web/join/login.do" alt="로그인">
			  <area shape="rect" coords="769,12,827,38" href="http://www.golfpang.com/web/join/step1.do" alt="회원가입 ">
			  <area shape="rect" coords="849,12,907,38" href="http://www.golfpang.com/web/customer/notice.do" alt="고객센터">
			  <area shape="rect" coords="932,11,978,38" href="http://www.golfpang.com/web/event/event.do" alt="이벤트  ">
          </map>
		</div>
	</div>


 <?  }
 } ?>
  

<div id="wrapper">
	<div id="header">
		<?php if(!get_cookie("ck_hd_banner")) { // 상단 큰배너 ?>
		<div id="hd_banner">
			<?php if($banner1 = display_banner_bg(1, $pt_id)) { // 배너가 있나? ?>
			<!-- <?php echo $banner1; ?> -->

			<?php
			$sql = sql_banner_rows(1, $pt_id);
			$res = sql_query($sql);
			$mbn_rows = sql_num_rows($res); //20190727_외부에 노출 일단 숨김처리함_메이저월드
			if($mbn_rows) {
			?>
			<div id="hd_banner_wrap"> 
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
						echo "<div class=\"mbn_img\" style=\"background:{$bg}url('{$file}') no-repeat top center;height:{$row['bn_height']}px;\">{$a1}{$a2}</div>\n";
					}
				}
				?>
			</div>
			<script>
			$(document).on('ready', function() {
				<?php if(count($txt_arr) > 0) { ?>
				var txt_arr = <?php echo json_encode($txt_arr); ?>;
			
				$('#hd_banner_wrap').slick({
					autoplay: true,
					autoplaySpeed: 4000,
					dots: false,
					fade: false,
					vertical: true,
					verticalSwiping: true,
					customPaging: function(slider, i) {
						return "<span>"+txt_arr[i]+"</span>";
					}
				});
				$('#hd_banner_wrap .slick-dots li').css('width', '<?php echo $txt_w; ?>%');
				
				<?php } else { ?>
				$('#hd_banner_wrap').slick({
					autoplay: true,
					autoplaySpeed: 4000,
					vertical: true,
					verticalSwiping: true,
					dots: false,
					fade: false
				});
			
				<?php } ?>
			});
			</script>
			<!-- } 메인 슬라이드배너 끝 -->
			<img src="<?php echo TB_IMG_URL; ?>/bt_close.gif" id="hd_close">

			<?php 
			}
			?>
			<?php } // banner end ?>
		</div>
		<?php } // cookie end ?>
		<!-- <div id="tnb">
			<div id="tnb_inner">
				<ul class="fr">
					<?php
					$tnb = array();
					if($is_admin)
						$tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
					if($is_member) {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">로그아웃</a></li>';
					} else {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/login.php?url='.$urlencode.'">로그인</a></li>';
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/register.php">회원가입</a></li>';
					}
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/mypage.php">마이페이지</a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/cart.php">장바구니<span class="ic_num">'. get_cart_count().'</span></a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/orderinquiry.php">주문/배송조회</a></li>';
					$tnb[] = '<li><a href="'.TB_BBS_URL.'/faq.php?faqcate=1">고객센터</a></li>';
					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div> -->

		<div id="hd">
			<!-- 상단부 영역 시작 { -->
			<div id="hd_inner2">
				<div class="hd_bnr">
					<span><?php echo display_banner(2, $pt_id); ?></span>
				</div>
			
				
				<ul class="fl tnb_log">
					<?php
                   
			               

					$tnb = array();
					if($is_admin)
						$tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
					
					$tnb_str = implode(PHP_EOL, $tnb);
					//echo $tnb_str;
					?>
				</ul>
		
			
				
			</div>
			<div id="gnb">
				<div id="gnb_inner">
					<div class="all_cate">
					   <!-- 전체카테고리 -->
						<span class="allc_bt"><i class="fa fa-bars"></i> 전체카테고리</span>
						
					</div>
					<div class="gnb_li">
						<ul>
						<?php
						$mod = 5;
						$res = sql_query_cgy('all');
						for($i=0; $row=sql_fetch_array($res); $i++) {
							$href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];
						?>
							<li class="main_gnb">
								<a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
							</li>
						<?php
						}
						?>
						<?php
						for($i=0; $i<count($gw_menu); $i++) {
							$seq = ($i+1);
							$page_url = TB_URL.$gw_menu[$i][1];
							if(!$default['de_pname_use_'.$seq] || !$default['de_pname_'.$seq])
								continue;

							echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;
						}
						?>
						</ul>
					</div>
					<div id="hd_sch">
						<fieldset class="sch_frm">
							<legend>사이트 내 전체검색</legend>
							<form name="fsearch" id="fsearch" method="post" action="<?php echo TB_SHOP_URL; ?>/search.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
							<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
							<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="">
							<button type="submit" class="sch_submit fa fa-search" value="검색"></button>
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
						</fieldset>
					</div>
				</div>
				<div class="con_bx">
						<ul>
						<?php
						$mod = 5;
						$res = sql_query_cgy('all');
						for($i=0; $row=sql_fetch_array($res); $i++) {
							$href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

							if($i && $i%$mod == 0) echo "</ul>\n<ul>\n";
						?>
							<li class="c_box">
								<a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
								<?php
								$r = sql_query_cgy($row['catecode'], 'COUNT');
								if($r['cnt'] > 0) {
								?>
								<ul>
									<?php
									$res2 = sql_query_cgy($row['catecode']);
									while($row2 = sql_fetch_array($res2)) {
										$href2 = TB_SHOP_URL.'/list.php?ca_id='.$row2['catecode'];
									?>
									<li><a href="<?php echo $href2; ?>"><?php echo $row2['catename']; ?></a></li>
									<?php } ?>
								</ul>
								<?php } ?>
							</li>
						<?php
						}
						$li_cnt = ($i%$mod);
						if($li_cnt) { // 나머지 li
							for($i=$li_cnt; $i<$mod; $i++)
								echo "<li></li>\n";
						}
						?>
						</ul>
					</div>
					<script>
					$(function(){
						$('.all_cate .allc_bt').click(function(){
							if($('.con_bx').css('display') == 'none'){
								$('.con_bx').show();
								$(this).html('<i class="ionicons ion-ios-close-empty"></i> 전체카테고리');
							} else {
								$('.con_bx').hide();
								$(this).html('<i class="fa fa-bars"></i> 전체카테고리');
							}
						});
					});
					</script>
			</div>
			<!-- } 상단부 영역 끝 -->
			<script>
			$(function(){
				// 상단메뉴 따라다니기
				var elem1 = $("#hd_banner").height() + $("#tnb").height() + $("#hd_inner").height();
				var elem2 = $("#hd_banner").height() + $("#tnb").height() + $("#hd").height();
				var elem3 = $("#gnb").height();
				$(window).scroll(function () {
					if($(this).scrollTop() > elem1) {
						$("#gnb").addClass('gnd_fixed');
						$("#hd").css({'padding-bottom':elem3})
					} else if($(this).scrollTop() < elem2) {
						$("#gnb").removeClass('gnd_fixed');
						$("#hd").css({'padding-bottom':'0'})
					}
				});
			});
			</script>
		</div>
		
		<?php
		if(defined('_INDEX_')) { // index에서만 실행
			$sql = sql_banner_rows(0, $pt_id);
			$res = sql_query($sql);
			$mbn_rows = sql_num_rows($res);
			if($mbn_rows) {
		?>
		<!-- 메인 슬라이드배너 시작 { -->

 <? if(!($pt_id == "srixon")) //스릭슨 접속시 메인배너 작동X_20190727
    {
 ?>
		<div id="mbn_wrap"> <!-- style="height: 600px;" -->
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

			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 4000,
				dots: true,
				fade: true,
				customPaging: function(slider, i) {
					return "<span>"+txt_arr[i]+"</span>";
				}
			});
			$('#mbn_wrap .slick-dots li').css('width', '<?php echo $txt_w; ?>%');
			
			<?php } else { ?>
			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 4000,
				dots: true,
				fade: true
			});
            
			<?php } ?>
		});
		</script>
		<!-- } 메인 슬라이드배너 끝 -->
		<?php }
		}
		?>
	</div>
<? }// srixon close ?>
</div>

	<div id="container">
		<?php
		if(!is_mobile()) { // 모바일접속이 아닐때만 노출

			if($pt_id == 'srixon')//스릭슨 서브페이지만 퀵메뉴 출력_20190727
		    {
                if(!defined('_INDEX_')) {
					  include_once(TB_THEME_PATH.'/quick.skin.php'); // 퀵메뉴
				}
			}
			else
	    	{
		    	include_once(TB_THEME_PATH.'/quick.skin.php'); // 퀵메뉴
			}
		}

		if(!defined('_INDEX_')) { // index가 아니면 실행
			$gp_string = $_SERVER['REQUEST_URI'];
			$gp_find = "?";
			$pos = strpos($gp_string, $gp_find);

			$gp_string_val = substr($gp_string, 0, $pos);

			if('/shop/list.php' != $gp_string_val && '/shop/listtype.php?'  != $gp_string_val && '/shop/cart.php' != $_SERVER['REQUEST_URI'] && '/shop/orderform.php' != $_SERVER['REQUEST_URI']) {
				echo '<div class="cont_inner">'.PHP_EOL;
			} else {
				echo '<div class="list_sub"><div class="cont_inner">'.PHP_EOL;
			}
		}
		?>
