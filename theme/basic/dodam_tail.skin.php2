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
        template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /><' +
                '/a></li>',
        accessToken: "<?php echo $default['de_insta_access_token']; ?>"
    });
    userFeed.run();
</script>

<div class="insta">
    <h2 class="tac">
        <i class="fa fa-instagram"></i>
        INSTAGRAM<a
            href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>"
            target="_blank">@
            <?php echo $default['de_insta_url']; ?></a>
    </h2>
    <ul id="instafeed"></ul>
</div>
<?php } ?>

<?php if(defined('_INDEX_')) { ?>
<div class="ft_top">
	<div class="ft_cs">
		<div>
			<dl class="cswrap" style="vertical-align:baseline;">
				<dt class="tit">고객센터</dt>
				<dd class="tel"><?php echo $config['company_tel']; ?></dd>
				<dd>상담 :
					<?php echo $config['company_hours']; ?>
				</dd>
				<dd>점심 :
					<?php echo $config['company_lunch']; ?></dd>
				<dd>(<?php echo $config['company_close']; ?>)</dd>
			</dl>
			<dl class="bkwrap" style="vertical-align:baseline;">
				<dt class="tit">메일주소</dt>
				<?php $bank = unserialize($default['de_bank_account']); ?>
				<dd style="height:35px; font-size:18px; line-height: 1em; color: #222; margin:40px 0 0 0;">k.dealhelp@gmail.com</dd>
				<dd class="etc_btn">
					<?php if($config['partner_reg_yes']) { ?>
					<!-- <a href="<?php echo TB_BBS_URL; ?>/partner_reg.php" class="btn_lsmall">쇼핑몰
					분양신청</a> -->
					<?php } ?>
					<?php if($config['seller_reg_yes']) { ?>
					<!-- <a href="<?php echo TB_BBS_URL; ?>/seller_reg.php" class="btn_lsmall">온라인
					입점신청</a> -->
					<?php } ?>
				</dd>
			</dl>
			<dl class="notice" style="vertical-align:baseline;">
				<dt class="tit">공지사항
					<!-- <a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=13" class="bt_more">더보기
					<i class="fa fa-angle-right"></i></a> -->
				</dt>
				<?php echo board_latest(13, 100, 4, $pt_id); ?>
			</dl>
		</div>
	</div>
</div>
<?php } ?>

<style>

    * {
        margin: 0;
        padding: 0;
    }

    #footer_main {
        width: 1400px;
        margin: 0 auto;
    }
    #footer_main a {
        text-decoration: none;
        color: #686868;
    }
    #footer_main ul {
        margin-left: 20px;
        margin-top: 5px;
    }
    #footer_main li {
        display: inline;
        margin: 25px;
    }

    #fsub1 {
        margin-bottom: 15px;
        font-size: 16px;
    }
    #fsub2 {
        margin-left: 45px;
        color: #888787;
        float: left;
    }
    #fsub3 {
        float: right;
        margin-right: 55px;
    }
    #fsub3 img {
        margin-left: 30px;
    }
</style>

<!-- 하단영역 : 시작 -->
<div class="footer">
    <div class="link">
        <ul>
            <!-- <li><a href="/service/company">회사소개</a></li> <li><a
            href="/page/index?tpl=etc%2Fstore.html">매장소개</a></li> -->
            <li>
                <a href="https://www.dodamchon.co.kr/service/agreement">회원약관</a>
            </li>
            <li>
                <b><a href="https://www.dodamchon.co.kr/service/privacy">개인정보처리방침</a></b>
            </li>
            <li>
                <a href="https://www.dodamchon.co.kr/service/guide">이용안내</a>
            </li>
            <li>
                <a href="https://www.dodamchon.co.kr/board/?id=alliance">제휴문의</a>
            </li>
        </ul>
    </div>
    <div class="copyright">
        <!--<img src="" alt="LOGO" class="foot_logo" />-->
        <div class="txt">
            <!-- 이미지로 카피라이트를 이용하실 분들은 주석을 해제하여 사용하여 주십시요 -->
            <!-- <img src="../images/design/footer_txt.gif" /><br />-->
            회사명 : (주)동아애드넷
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>사업자등록번호 : 214-88-40517
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>주소 : 서울특별시 서대문구 충정로3가 120-1 충정로 제2빌딩 인촌빌딩 5층<br/>
            통신판매업 신고 : 제2011-서울서대문-0139호
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>연락처 : 070-4938-5588
            <!--{ ? config_basic.companyFax }-->
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>FAX : 02-393-4612
            <!--{ / }-->
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>개인정보보호 책임자 : 김우현
            <font color="cccccc">
                <b>
                    |
                </b>
            </font>대표자 : 전종현<br/>
            contact :
            <font color="990000">
                <b>k.dealhelp@gmail.com</b>
            </font>
            for more information

            <!--구매안전표기 -->
            <!--<div><div>{=escrow_mark(100)}</div></div> </div>-->
        </div>
    </div>
</div>