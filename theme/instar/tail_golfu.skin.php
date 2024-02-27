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
					<dd class="tel"><?php echo $config['company_tel']; ?></dd>
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

<style>

*{margin:0; padding:0; }

#footer_main{width:1400px; margin:0 auto; border-top:1px solid #888787; }
#footer_main a{text-decoration:none; color:#686868;}
#footer_main ul{margin-left:20px; margin-top:5px;}
#footer_main li{display:inline; margin:25px;}  

#fsub1{margin-bottom:15px;font-size:16px;}
#fsub2{margin-left:45px; color:#888787; float:left;}
#fsub3{float:right;margin-right:55px;}
#fsub3 img{margin-left:30px;}
</style>

<div id="footer_main">
	<div id="fsub1">
    	<ul>
			<li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/member/privacy_policy.aspx"><strong>개인정보취급방침</strong></a></li>
			<li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/member/member_terms.aspx">서비스약관</a></li>
			<li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/CustomerCenter/CustomerBoard.aspx?strGubun=ALLI">제휴문의</a></li>
            <li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/CustomerCenter/CustomerBoard.aspx?strGubun=ADVE">광고문의</a></li>
            <li><a style="text-decoration:none; color:#686868;" href="http://www.gdsi.co.kr" target="_blank">회사소개</a></li>
            <li><a href="javascript:MM_openBrWindow('http://www.golfu.net/member/EmailRejection.aspx','EmailRejection','scrollbars=yes,width=400,height=250,left=100,top=100');">이메일주소 무단수집 거부</a></li>
            <li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/CustomerCenter/CustomerCenter.aspx">고객센터</a></li>
            <li><a style="text-decoration:none; color:#686868;" href="http://www.golfu.net/member/MemberDel.aspx" >회원탈퇴</a></li>
            <li><a style="text-decoration:none; color:#686868;" href="http://golfunet.mymall24.biz" target="_blank" >꽃배달 인트라넷</a></li>
		</ul>
    </div>
    <div id="fsub2">
    	<p>주소:경기도 수원시 영통구 신원로 88 디지털엠파이어Ⅱ 101동 1303호 골프유닷넷(주)<br>
        대표이사:배병일 / 대표번호 : 1577-6030 /  사업자번호: 124-86-01686<br>
        통신판매업신고 : 2002-127호 / 개인정보 보호책임자(CPO) : 위성호<br>
        Copyright &copy; Golfu.net All rights reserved.</p>
    </div>
    <div id="fsub3">
    <img src="http://www.golfu.net/images/common/footer_info01.gif" alt="공정거래위원회 표준약관사용" />
	<img src="http://www.golfu.net/images/common/footer_info02.gif" alt="한국인터넷실명확인서비스" />
	<img src="http://www.golfu.net/images/common/footer_info03.gif" alt="KISIA" />
	<script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=shop.golfu.net&size=S&use_flash=YES&use_transparent=NO&lang=ko"></script>
	<img src="http://www.golfu.net/images/common/footer_info05.gif" alt="안전거래가맹점" />
    </div>
</div>

