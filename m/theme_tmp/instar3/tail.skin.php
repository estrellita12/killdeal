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
    <ul id="instafeed"></ul>
</div>
<?php } // 인스타그램 ?>

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
            <li><a href="https://pf.kakao.com/_qxdVKxd/chat">카톡 문의</a></li>
            <li><a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');">사업자정보확인</a></li>
        </ul>
        <dl class="ft_address">
            <dd class="tel"> <?php if($pt_id=='admin'){ echo "전화";}else{ echo "쇼핑 문의";} ?> : <?php echo $config['company_tel']; ?></dd>
            <dd>이메일 : <?php echo $super['email']; ?></dd>
            <dd><?php echo $config['company_name']; ?> <span class="g_hl"></span> <span class="">대표자 : <?php echo $config['company_owner']; ?></span></dd>
            <?php if($pt_id == 'maniamall') { ?><dd><span class="g_h1"></span>제휴사 : (주)마케팅큐브&nbsp;&nbsp;&nbsp;&nbsp;대표 : 진병두</dd><?php } ?>
            <dd>주소: <?php echo $config['company_addr']; ?></dd>
            <dd>통신판매업신고 : <?php echo $config['tongsin_no']; ?></dd>
            <dd>사업자등록번호 : <?php echo $config['company_saupja_no']; ?></dd>
            <!-- <dd>개인정보보호책임자 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd> -->
        </dl>
        <p class="ft_crt">COPYRIGHT © <?php echo $config['company_name']; ?> ALL RIGHTS RESERVED.</p>
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
