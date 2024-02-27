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
				echo '</div></div>'.PHP_EOL;
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
					<dd class="tel"><?php echo "070-4938-5588"//echo $config['company_tel']; ?></dd>
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
		<div style="width:1903px; margin: 0 auto; padding: 0;">
			<img src="/img/honggolf_footer.jpg" alt="" width="1903" height="220" usemap="#Map2">
			<map name="Map2">
				<area shape="rect" coords="922,23,944,43" href="#" target="_blank" alt="고객센터">
				<area shape="rect" coords="958,26,979,40" href="mailto:mynamegood@naver.com" target="_blank" alt="email">
				<area shape="rect" coords="811,59,864,73" href="https://www.honggolf.com/policy" alt="이용약관">
				<area shape="rect" coords="879,59,983,73" href="https://www.honggolf.com/privacy" target="_blank" alt="개인정보처리방침">
				<area shape="rect" coords="1000,59,1092,73" href="#" target="_blank" alt="사업자정보확인">
				<area shape="rect" coords="845,158,1058,184" href="https://mark.inicis.com/mark/popup_v1.php?mid=SIXhongolf" alt="안전구매서비스가맹점">
			</map>
		</div>

	<?php if($default['de_pg_service'] == 'kcp') { ?>
	<form name="escrow_foot" method="post" autocomplete="off">
	<input type="hidden" name="site_cd" value="<?php echo $default['de_kcp_mid']; ?>">
	</form>
	<?php } ?>

	<script>
	function escrow_foot_check()
	{
		<?php if($default['de_pg_service'] == 'inicis') { ?>
		var mid = "<?php echo $default['de_inicis_mid']; ?>";
		window.open("https://mark.inicis.com/mark/escrow_popup.php?mid="+mid, "escrow_foot_pop","scrollbars=yes,width=565,height=683,top=10,left=10");
		<?php } ?>
		<?php if($default['de_pg_service'] == 'lg') { ?>
		var mid = "<?php echo $default['de_lg_mid']; ?>";
		window.open("https://pgweb.uplus.co.kr/ms/escrow/s_escrowYn.do?mertid="+mid, "escrow_foot_pop","scrollbars=yes,width=465,height=530,top=10,left=10");
		<?php } ?>
		<?php if($default['de_pg_service'] == 'kcp') { ?>
		window.open("", "escrow_foot_pop", "width=500 height=450 menubar=no,scrollbars=no,resizable=no,status=no");

		document.escrow_foot.target = "escrow_foot_pop";
		document.escrow_foot.action = "http://admin.kcp.co.kr/Modules/escrow/kcp_pop.jsp";
		document.escrow_foot.submit();
		<?php } ?>
	}
	</script>
	<!-- } 카피라이터 끝 -->
</div>
<?php
if(TB_DEVICE_BUTTON_DISPLAY && !TB_IS_MOBILE && is_mobile()) { ?>
<a href="<?php echo TB_URL; ?>/index.php?device=mobile" id="device_change">모바일 버전으로 보기</a>
<?php } ?>
