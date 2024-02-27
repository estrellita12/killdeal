<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="slideMenu">
	<div onclick="history.go(-1);" class="page_cover"><span class="btn_close"></span></div>
    <dl class="top_btn gradient">
    <?php
        // (2020-12-08) 각 연동사 별로 slideMenu.skin 파일을 따로 설정
        $slideMenu_skin_file = TB_MTHEME_PATH."/".$pt_id."_slideMenu.skin.php";
        if( file_exists($slideMenu_skin_file) ){
                include_once($slideMenu_skin_file);
        }else{
               include_once(TB_MTHEME_PATH."/admin_slideMenu.skin.php");
        }
    ?>
    </dl>
	<div class="main_nav_wrap">
		<div class="smtab_wrap">
			<div class="top_tab_wrap">
				<ul class="smtab">
					<li data-tab="shop_cate">카테고리</li>
					<li data-tab="mypage">마이페이지</li>
					<li data-tab="custom">고객센터</li>
					<li data-tab="media">골프미디어</li>
				</ul>
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
					<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php">주문/배송조회</a></li>
				 	<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php">장바구니</a></li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/wish.php">찜한상품</a></li>
					<li><a href="<?php echo TB_MSHOP_URL; ?>/today.php">최근 본 상품</a></li>
                    <?php if( ($config['usepoint_yes']) || ($config['coupon_yes']) ){ //(2020-12-10)본사정책에 따라 탭이 아예 보이지 않게 설정 ?>
						<li class="bm">쿠폰/포인트</li>
						<li class="subm">
							<ul>
								<?php if($config['usepoint_yes']) { ?>
								<li><a href="<?php echo TB_MSHOP_URL; ?>/point.php">포인트조회</a></li>
								<?php } ?>
								<?php if($config['gift_yes']) { ?>
								<li><a href="<?php echo TB_MSHOP_URL; ?>/gift.php">쿠폰인증</a></li>
								<?php } ?>
								<?php if($config['coupon_yes']) { ?>
								<li><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">쿠폰관리</a></li>
								<?php } ?>
							</ul>
						</li>
					 <? } ?>
				</ul>
			</div>
			<div id="custom" class="sm_body">
				<ul>
					<?php
                    // (2021-03-17)
					$sql = " select * from shop_board_conf where gr_id='gr_cs' order by index_no asc ";
					$res = sql_query($sql);
					for($i=0; $row=sql_fetch_array($res); $i++) { 
					?>
					<li><a href="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
					<?php } ?>
					<!-- <li><a href="<?php echo TB_MBBS_URL; ?>/review.php">구매후기</a></li> -->
					<li><a href="<?php echo TB_MBBS_URL; ?>/qna_list.php">1:1 상담문의</a></li>
					<li><a href="<?php echo TB_MBBS_URL; ?>/faq.php">자주묻는 질문</a></li>
				</ul>
			</div>
			<div id="media" class="sm_body">
				<ul>
	
                <?php 
                    //$sql = " select * from shop_board_conf where gr_id='gr_media' order by index_no asc ";
                    $sql = " select * from shop_board_conf where gr_id='gr_media' and list_skin!='news' order by index_no asc ";
                    $res = sql_query($sql);
                    for($i=0; $row=sql_fetch_array($res); $i++) {
                    ?>
                    <li><a href="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
                    <?php } ?>

				</ul>
			</div>
	
		</div>
	</div>
	<div class="main_nav_wrap">
		<div class="smtab_wrap padb0">
            
			<span class="sm_cs_tit">주요서비스</span>
			<ul class="sm_cs">
                <?php 
                // (2021-03-16)
                for($i=0; $i<count($gw_menu); $i++) {
                    if($gw_menu[$i][0] == '신상품' || $gw_menu[$i][0] == '추천상품'){
                        $seq = ($i+1);
                        $page_url = TB_MURL.$gw_menu[$i][1];
        
                        if(!$default['de_pname_use_'.$seq] && !$default['de_pname_'.$seq] )
                            continue;
                        echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a class="fa fa-angle-right marl3"></li>'.PHP_EOL;
                    }
                }
                ?>
			</ul>
            
            <span class="sm_cs_tit">고객센터</span>
			<ul class="sm_cs">
			    <li><a href="tel:070-4938-5588<?php //echo $config['company_tel']; ?>" class="btn_medium cs_call wfull">전화연결</a></li>
			    <li><a href="https://pf.kakao.com/_qxdVKxd" class="btn_medium cs_call wfull" target="_blank">카톡상담</a></li>
            </ul>
		</div>
	</div>
</div>


<script>
$(function(){
	// 왼쪽 슬라이드메뉴의 서브메뉴 동작
	$('#slideMenu .subm').hide();
	$('#slideMenu .bm').click(function(){
		if($(this).hasClass('active')){
			$(this).next().slideUp(400);
			$(this).removeClass('active');
		} else {
			$('#slideMenu .bm').removeClass('active');
			$('#slideMenu .subm').slideUp(300);
			$(this).addClass('active');
			$(this).next().slideDown(400);
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
