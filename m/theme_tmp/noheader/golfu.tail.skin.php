<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

	</div>
	<span class="btn_top fa fa-chevron-up"></span>
	<span class="btn_bottom fa fa-chevron-down"></span>

	<?php
	if($default['de_insta_access_token']) { // 인스타그램
	   $userId = explode(".", $default['de_insta_access_token']);
	?>
	<script src="<?php echo TB_JS_URL; ?>/instafeed.min.js"></script>
	<script>
		var userFeed = new Instafeed({
			get: 'user',
			userId: "<?php echo $userId[0]; ?>",
			limit: 6,
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

	
	<footer id="ft">
		<ul class="ft_menu">
			<?php if(TB_DEVICE_BUTTON_DISPLAY && TB_IS_MOBILE) { ?>
			
			<?php } ?>
			<li><a href="<?php echo TB_URL; ?>/index.php?device=pc">PC버전</a></li>
			<?php if($config['partner_reg_yes']) { ?>
			<!-- <li><a href="<?php echo TB_MBBS_URL; ?>/partner_reg.php">쇼핑몰분양신청</a></li> -->
			<?php } ?>
			<?php if($config['seller_reg_yes']) { ?>
			<!-- <li><a href="<?php echo TB_MBBS_URL; ?>/seller_reg.php">온라인입점신청</a></li> -->
			<?php } ?>
			<li><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">고객센터</a></li>
			<li><a href="javascript:saupjaonopen('1248601686');">사업자정보확인</a></li>
		</ul>
		<!-- <dl class="ft_cs">
			<dt>고객센터 / 계좌안내</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			<?php $bank = unserialize($default['de_bank_account']); ?>
			<dd><?php echo $bank[0]['name']; ?> <span class="bank_num"><?php echo $bank[0]['account']; ?></span> 예금주 : <?php echo $bank[0]['holder']; ?></dd>
		</dl> -->
		<dl class="ft_address">
			<dd>(주)골프유닷넷 <span class="g_hl"></span> <span class="">대표자 : 배병일</span></dd>
			<dd>주소: 경기도 수원시 영통구 신원로 88 디지털엠파이어Ⅱ 101동 1303호 </dd>
			<dd>전화 : 1577-6030  </dd>
			<dd>통신판매업신고 : 2002-경기수원-0127호</dd>
			<dd>사업자등록번호 : 124-86-01686</dd>
			<!-- <dd>개인정보보호책임자 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd> -->
		</dl>
		<p class="ft_crt">COPYRIGHT © (주)골프유닷넷 ALL RIGHTS RESERVED.</p>
	</footer>
</div>

<script>
$(function() {
	// 상위로이동
	$(".btn_top").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 300);
	});
	// 하위로이동
    $(".btn_bottom").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 300);
    });

	$(window).scroll(function () {
		if($(this).scrollTop() > 0) {
			$(".btn_top, .btn_bottom").fadeIn(300);
		} else {
			$(".btn_top, .btn_bottom").fadeOut(300);
		}
	});

	// 상단메뉴 스크롤시 fixed
	var adheight = $(".top_ad").height() + $("#gnb").height();
	$(window).scroll(function () {
		if($(this).scrollTop() > adheight) {
			$("#header").addClass('active');
			$("#container").addClass('padt45');
		} else {
			$("#header").removeClass('active');
			$("#container").removeClass('padt45');
		}
	});
});
</script>
