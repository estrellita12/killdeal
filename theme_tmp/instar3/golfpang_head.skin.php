<?php

if(!defined('_TUBEWEB_')) exit;

?>
<?php 

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

<?php } else { // 비로그인 ?>
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
<?php  } ?>
<div id="hd_inner2">
    <div class="hd_bnr">
		<span><?php echo display_banner(2, $pt_id); ?></span>
	</div>
	<ul class="fl tnb_log">
	<?php
        // 이 파트는 쓸모없는것아닌가....
		$tnb = array();
		if($is_admin){
			$tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
		}
		$tnb_str = implode(PHP_EOL, $tnb);
		//echo $tnb_str;
	?>
	</ul>
</div>
