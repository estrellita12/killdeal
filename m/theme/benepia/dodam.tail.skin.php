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

	<!-- <div class="sns_wrap">
		<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
		<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
		<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
		<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
		<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
		<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
		<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
		<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
	</div> -->

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
			<li><a href="javascript:saupjaonopen('214-88-40517');">사업자정보확인</a></li>
		</ul>
		<!-- <dl class="ft_cs">
			<dt>고객센터 / 계좌안내</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			<?php $bank = unserialize($default['de_bank_account']); ?>
			<dd><?php echo $bank[0]['name']; ?> <span class="bank_num"><?php echo $bank[0]['account']; ?></span> 예금주 : <?php echo $bank[0]['holder']; ?></dd>
		</dl> -->

<link rel="stylesheet" type="text/css" href="https://dodamgolf.co.kr/m/theme/basic/dodam/css/layout.css" />
<link rel="stylesheet" type="text/css" href="https://dodamgolf.co.kr/m/theme/basic/dodam/css/common.css" />
<link rel="stylesheet" type="text/css" href="https://dodamgolf.co.kr/m/theme/basic/dodam/css/quick_design.css" />

<!-- 하단영역 : 시작 -->
<div id="layout_footer">
	<h2 class="hide">하단 메뉴</h2>
	<ul class="fnav fcp">
	<!--
		<li><a href="/service/company">회사소개</a></li>
		<li><span>|</span><a href="/page/index?tpl=etc%2Fstore.html">매장소개</a></li>
	-->
		<li><a href="https://m.dodamchon.co.kr/service/agreement">이용약관</a></li>
		<li><span>|</span><b><a href="https://m.dodamchon.co.kr/service/privacy">개인정보처리방침</a></b></li>
		<li><span>|</span><a href="https://m.dodamchon.co.kr/service/guide">이용안내</a></li>
		<li><span>|</span><a href="https://m.dodamchon.co.kr/board/?id=alliance">제휴문의</a></li>
	</ul>
	<h2 class="hide">쇼핑몰 정보</h2>
<div style="display:table; width:100%;">
    <ul class="fcp" style="display:table-cell; vertical-align:top">
            <li><!--span class="hide"-->회사명 : <!--/span--><b>(주)동아애드넷</b></li>
            <li>대표 : 전종현</li>
            <li>고객센터 : <a href="tel:{config_basic.companyPhone}">070-4938-5588</a></li>
            <li>사업자등록번호 : 214-88-40517</li>
            <li>주소 : 서울특별시 서대문구 충정로 35-17 (충정로 제2빌딩) 인촌빌</li>
            <li>통신판매업 신고 : 제2011-서울서대문-0139호</li>
            <li>contact : <b>k.dealhelp@gmail.com</b> for more information</li>
        </ul>
    </div>
</div>
<!--{?preg_match('/goods\/view/',_SERVER.REQUEST_URI)}-->
	<!--{? navercheckout_tpl }-->
<div style="height:117px;">&nbsp;</div>
	<!--{ / }-->
<div style="height:80px;">&nbsp;</div>
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
