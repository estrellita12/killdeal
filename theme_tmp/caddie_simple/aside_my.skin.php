<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<div class="aside_hd wsetbg">
		<p class="eng">MY PAGE</p>
		<p class="kor">마이페이지</p>
	</div>
	<div class="aside_name"><?php echo get_text($member['name']); ?></div>
	<dl class="aside_my">
		<dt>주문현황</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">주문/배송조회</a></dd>
		<dt>관심상품</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/cart.php">장바구니</a></dd>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/wish.php">내가 찜한상품</a></dd>
    <!-- 
	<?php if($pt_id != 'golf' && $pt_id != 'imembers'){ //현대리바트,아이멤버스(20200806) 출력X_20200304 ?>
		<dt>회원정보</dt>
		<?php if($pt_id == 'golfpang'){ //골팡연동_20190920 ---->추후 현대리바트도 반영되어야함.  ?>
		      <dd><a href="http://www.golfpang.com/web/mymembers/mypage.do">회원정보수정</a></dd>
	    <?php }else if($pt_id == 'thegolfshow'){ //(2020-12-10)더골프쇼마켓  ?>
		      <dd><a href="https://www.thegolfshow.co.kr/modify.php">회원정보수정</a></dd>
	    <?php }else if($pt_id == 'honggolf'){ //(2020-12-10)홍골프  ?>
		      <dd><a href="https://www.honggolf.com/myPage">회원정보수정</a></dd>
		<?php }else{ ?>   
              <dd><a href="<?php echo TB_BBS_URL; ?>/member_confirm.php?url=register_form.php">회원정보수정</a></dd>
	       	  <dd class="marb5"><a href="<?php echo TB_BBS_URL; ?>/leave_form.php">회원탈퇴</a></dd>
		<?php } ?>
    <?php } //현대리바트 close  ?>
    -->
	</dl>
</aside>
<!-- } 좌측메뉴 끝 -->
