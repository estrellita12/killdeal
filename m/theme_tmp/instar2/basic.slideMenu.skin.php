<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="slideMenu">
	<div onclick="history.go(-1);" class="page_cover"><span class="btn_close"></span></div>
	<dl class="top_btn" style="height:30px">
		<dd>
		
		</dd>
		<dd>

		</dd>
	</dl>
	<!-- <ul class="sm_icbt">
		<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php"><i class="ionicons ion-android-cart"></i> 장바구니</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php"><i class="ionicons ion-ios-list-outline"></i> 주문/배송</a></li>
		<li><a href="<?php echo TB_MBBS_URL; ?>/review.php"><i class="ionicons ion-android-camera"></i> 구매후기</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/mypage.php"><i class="ionicons ion-android-contact"></i> 마이페이지</a></li>
	</ul> -->
 
<?  if($pt_id != "golf" && $pt_id != "golfpang" ){ ?>

	<!-- <div class="main_nav_wrap">
		<div class="main_nav">
			<ul>
				<?php
				$mod = 5;
				$res = sql_query_cgy('all');
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
					
					switch($i) {
						case '0' :
							$catecode = '골프클럽';
							break;
						case '1' :
							$catecode = '골프용품';
							break;
						case '2' :
							$catecode = '골프패션';
							break;
					}
				?>
					<li>
						<a href="<?php echo $href; ?>">
							<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu'.$i.'.png'; ?>" alt="">
							<p><?php echo $catecode; ?></p>
						</a>
					</li>
				<?php
				}
				?>
				<li>
					<a href="<?php echo TB_MSHOP_URL.'/plan.php'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu3.png'; ?>" alt="">
						<p>기획전</p>
					</a>
				</li>
				<li style="border-right:0;">
					<a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=43'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu4.png'; ?>" alt="">
						<p>골프매거진</p>
					</a>
				</li>
				<li class="bg">
					<a href="<?php echo TB_MSHOP_URL.'/timesale.php'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu5.png?1234'; ?>" alt="">
						<p>킬딜특가</p>
					</a>
				</li>
				<li class="bg">
					<a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=42'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu6.png'; ?>" alt="">
						<p>골프영상</p>
					</a>
				</li>
				
				<li class="bg">
					<a href="<?php echo TB_MSHOP_URL.'/mypage.php'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu7.png'; ?>" alt="">
						<p>마이페이지</p>
					</a>
				</li>
				
				<li class="bg">
					<a href="<?php echo  TB_MSHOP_URL.'/cart.php'; ?>">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu8.png'; ?>" alt="">
						<p>장바구니</p>
					</a>
				</li>
				<li  class="bg" style="border-right:0;">
					<a href="javascript:;" class="btn_sidem">
						<img src="<?php echo TB_IMG_URL.'/icon/icon_m_menu9.png?1'; ?>" alt="">
						<p>전체보기</p>
					</a>
				</li>
			</ul>
		</div>
	</div> -->

<? } ?>


	<div class="main_nav_wrap">
		<div class="smtab_wrap">
			<div class="top_tab_wrap">
				<ul class="smtab">
					<li data-tab="shop_cate">카테고리</li>
					<li data-tab="mypage">마이페이지</li>
					<li data-tab="custom">고객센터</li>
				</ul>
				<a class="btn_best_link" href="<?php echo TB_MSHOP_URL.'/listtype.php?type=2'; ?>">BEST100</a>
			</div>
			<div id="shop_cate" class="sm_body">
				<?php
				$r = sql_query_cgy('all', 'COUNT');
				if($r['cnt'] > 0){
				?>
				<ul>
					<?php
					$res = sql_query_cgy('all');
					while($row = sql_fetch_array($res)) {
						$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
					?>
					<li class="bm"><?php echo $row['catename'];?></li>
					<li class="subm">
						<ul>
							<li><a href="<?php echo $href;?>">전체</a></li>
							<?php
							$res2 = sql_query_cgy($row['catecode']);
							while($row2 = sql_fetch_array($res2)) {
								$href2 = TB_MSHOP_URL.'/list.php?ca_id='.$row2['catecode'];
							?>
							<li><a href="<?php echo $href2;?>"><?php echo $row2['catename']; ?></a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
				</ul>
				<?php } else { ?>
				<p class="sct_noitem">등록된 분류가 없습니다.</p>
				<?php } ?>
			</div>
			<div id="mypage" class="sm_body">
				<ul>
					<li class="bm">쿠폰/포인트</li>
					<li class="subm">
						<ul>
							<li><a href="<?php echo TB_MSHOP_URL; ?>/point.php">포인트조회</a></li>
							<?php if($config['gift_yes']) { ?>
							<li><a href="<?php echo TB_MSHOP_URL; ?>/gift.php">쿠폰인증</a></li>
							<?php } ?>
							<?php if($config['coupon_yes']) { ?>
							<li><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">쿠폰관리</a></li>
							<?php } ?>
						</ul>
					</li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php">주문/배송조회</a></li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php">장바구니</a></li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/wish.php">찜한상품</a></li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/today.php">최근 본 상품</a></li>
				</ul>
			</div>
			<div id="custom" class="sm_body">
				<ul>
					<?php
					$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
					$res = sql_query($sql);
					for($i=0; $row=sql_fetch_array($res); $i++) { 
						// 20191024 골프영상, 매거진 모바일 비노출
						if($row['index_no'] == 42 ||  $row['index_no'] == 43)
						{
							continue;
						}
					?>
					<li><a href="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
					<?php } ?>
					<li><a href="<?php echo TB_MBBS_URL; ?>/review.php">구매후기</a></li>
					<li><a href="<?php echo TB_MBBS_URL; ?>/qna_list.php">1:1 상담문의</a></li>
					<li><a href="<?php echo TB_MBBS_URL; ?>/faq.php">자주묻는 질문</a></li>
				</ul>
			</div>

		</div>
	</div>
	<div class="main_nav_wrap">
		<div class="smtab_wrap padb0">
			<p class="sm_cs_tit">주요서비스</p>
			<ul class="sm_cs">
				<li><a href="<?php echo TB_MSHOP_URL.'/timesale.php'; ?>">킬딜특가<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MSHOP_URL.'/listtype.php?type=5'; ?>">강력추천<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MSHOP_URL.'/listtype.php?type=3'; ?>">신상품<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MSHOP_URL.'/plan.php'; ?>">기획전<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=43'; ?>">골프매거진<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MBBS_URL.'/board_list.php?boardid=42'; ?>">골프영상<i class="fa fa-angle-right marl3"></i></a></li>
				<li><a href="<?php echo TB_MSHOP_URL.'/brand.php'; ?>">브랜드관<i class="fa fa-angle-right marl3"></i></a></li>
			</ul>
			<!-- <dl class="sm_cs">
				<dt>고객센터</dt>
				<dd class="cs_tel"><?php echo $config['company_tel']; ?></dd>
				<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
				<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			</dl>
			<dl class="sm_cs">
				<dt>입금계좌안내</dt>
				<?php $bank = unserialize($default['de_bank_account']); ?>
				<dd><?php echo $bank[0]['name']; ?> <b><?php echo $bank[0]['account']; ?></b></dd>
				<dd>예금주명 : <?php echo $bank[0]['holder']; ?></dd>
			</dl> -->
			<p class="mart10"><a href="tel:070-4938-5588<?php //echo $config['company_tel']; ?>" class="btn_medium cs_call wfull">고객센터 전화연결</a></p>
		</div>
	</div>
</div>
<script>
$(function(){
	// 왼쪽 슬라이드메뉴의 서브메뉴 동작
	$('#slideMenu .subm').hide();
	$('#slideMenu .bm').click(function(){
		if($(this).hasClass('active')){
			$(this).next().slideUp(250);
			$(this).removeClass('active');
		} else {
			$('#slideMenu .bm').removeClass('active');
			$('#slideMenu .subm').slideUp(250);
			$(this).addClass('active');
			$(this).next().slideDown(250);
		}
	});

	// 상단 메뉴버튼 클릭시 메뉴페이지 슬라이드
	$(".btn_sidem").click(function () {
		$("#slideMenu, #wrapper, .page_cover, html").addClass("m_open");
		window.location.hash = "#Menu";
		$("#wrapper, html").css({
			height: $(window).height()
		});
	});
	window.onhashchange = function () {
		if(location.hash != "#Menu") {
			$("#slideMenu, #wrapper, .page_cover, html").removeClass("m_open");
			$("#wrapper, html").css({
				height:'100%'
			});
		}
	};

	//탭기능
	$(document).ready(function(){
		$(".smtab>li:eq(0)").addClass('active');
		$("#shop_cate").addClass('active');

		$(".smtab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".smtab>li").removeClass('active');
			$(".sm_body").removeClass('active');
			$(this).addClass('active');
			$("#"+activeTab).addClass('active');
		});
	});
});
</script>
