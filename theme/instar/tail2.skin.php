<?php
if(!defined('_TUBEWEB_')) exit;
?>
		<?php
		if(!defined('_INDEX_')) { // index가 아니면 실행
			$gp_string = $_SERVER['REQUEST_URI'];
			$gp_find = "?";
			$pos = strpos($gp_string, $gp_find);

			$gp_string_val = substr($gp_string, 0, $pos);

			if('/shop/list.php' != $gp_string_val && '/shop/listtype.php?'  != $gp_string_val && '/shop/cart.php' != $_SERVER['REQUEST_URI'] && '/shop/orderform.php' != $_SERVER['REQUEST_URI']) {
				echo '</div>'.PHP_EOL;
			} else {
				echo '</div></div>'.PHP_EOL;
			}
		}

		?>
	</div>

	<!-- 카피라이터 시작 { -->
	<div id="ft" style="<?php if(defined('_INDEX_')) { echo 'margin-top:0;'; } ?>">
		<?php
		if($default['de_insta_access_token']) { // 인스타그램
		   $userId = explode(".", $default['de_insta_access_token']);
		?>
		<script src="<?php echo TB_JS_URL; ?>/instafeed.min.js"></script>
		<script>
			var userFeed = new Instafeed({
				get: 'user',
				userId: "<?php echo $userId[0]; ?>",
				limit: 8,
				template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
				accessToken: "<?php echo $default['de_insta_access_token']; ?>"
			});
			userFeed.run();
		</script>

		<div class="insta">
			<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
			<ul id="instafeed">
			</ul>
		</div>
		<?php } ?>

		<?php if(defined('_INDEX_')) { ?>
		<div class="ft_top">
			<div class="ft_cs">
				<dl class="cswrap">
					<dt class="tit">고객센터</dt>
					<dd class="tel"><?php echo "070-4938-5588"/* $config['company_tel']; */ ?></dd>
					<dd>상담 : <?php echo $config['company_hours']; ?> </dd>
					<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
					<dd>(<?php echo $config['company_close']; ?>)</dd>
				</dl>
				<dl class="bkwrap">
					<!-- 20191227 기존 계좌안내 주석처리
					<dt class="tit">계좌안내</dt>
					<?php $bank = unserialize($default['de_bank_account']); ?>
					<dd class="bknum"><?php echo $bank[0]['account']; ?></dd>
					<dd>은행명 : <?php echo $bank[0]['name']; ?></dd>
					<dd>예금주 : <?php echo $bank[0]['holder']; ?></dd>
					<dd class="etc_btn">
						<?php if($config['partner_reg_yes']) { ?>
					--> 
						 <!-- <a href="<?php echo TB_BBS_URL; ?>/partner_reg.php" class="btn_lsmall">쇼핑몰 분양신청</a> -->
						 <!--
						<?php } ?>
						<?php if($config['seller_reg_yes']) { ?>
						-->
						 <!-- <a href="<?php echo TB_BBS_URL; ?>/seller_reg.php" class="btn_lsmall">온라인 입점신청</a> -->
						<!--<?php } ?>
					</dd>
					-->
					<dt class="tit">메일주소</dt>
					<dd style="height:35px; font-size:18px; line-height: 1em; color: #222; margin:40px 0 0 0;">k.dealhelp@gmail.com</dd>
				</dl>
				<dl class="notice">
					<dt class="tit">공지사항 
						<!-- <a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=13" class="bt_more">더보기 <i class="fa fa-angle-right"></i></a> -->
					</dt>
					<?php echo board_latest(13, 100, 4, $pt_id); ?>
				</dl>
			</div>
		</div>
		<?php } ?>
<div style="background-image: url(https://mall.golfpang.com/img/alliance/bg_b.png); margin: 0; padding: 0; height: 329px;">
	<div style="width:980px; margin: 0 auto; padding: 0;"><img src="https://mall.golfpang.com/img/alliance/footer_s.png"  alt="" width="980" usemap="#Map"/>
		<map name="Map">
		<area shape="rect" coords="555,141,629,178" href="http://www.ayutthayagolf.com/" target="_blank" alt="아유타야골프 " >
		<area shape="rect" coords="478,141,547,179" href="http://cafe.naver.com/golfpang" target="_blank" alt="언론보도 " >
		<area shape="rect" coords="404,141,473,179" href="http://www.golfpang.com/web/customer/newsArticle_list.do" alt="언론보도 " >
		<area shape="rect" coords="298,140,398,179" href="http://www.golfpang.com/web/customer/pr.do" alt="회원약관 " >
		<area shape="rect" coords="228,139,289,179" href="http://www.golfpang.com/web/customer/tnc.do" alt="회원약관 " >
		<area shape="rect" coords="154,139,215,179" href="http://www.golfpang.com/web/customer/notice_view.do?idx=56" alt="채용안내  " >
		<area shape="rect" coords="61,139,146,180" href="http://www.golfpang.com/web/info/members.do" alt="찾아오시는길 " >
		<area shape="rect" coords="798,50,933,96" href="https://itunes.apple.com/kr/app/id1012616785?mt=8" target="_blank" alt="애플스토어  ">
		<area shape="rect" coords="646,50,781,96" href="https://play.google.com/store/apps/details?id=app.hybirds.golfpang" target="_blank" alt="구글플레이 ">
		<area shape="rect" coords="-2,140,52,180" href="http://www.golfpang.com/web/info/members.do" alt="회사소개 " >
		</map>
	</div>
</div>
