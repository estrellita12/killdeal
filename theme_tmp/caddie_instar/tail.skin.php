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
        <ul id="instafeed"></ul>
    </div>
<?php } //인스타그램  ?>

        <div class="ft_bottom">
            <div class="fgnb">
                <ul>
                    <li><a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></li>
                    <li><a href="<?php echo TB_BBS_URL; ?>/policy.php" id="policy">개인정보처리방침</a></li>
                    <!--<li><a href="https://pf.kakao.com/_qxdVKxd/chat">카톡 문의</a></li>-->
                    <li class="sns_wrap">
                        <?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo TB_THEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
                        <?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo TB_THEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
                        <?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo TB_THEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
                        <?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo TB_THEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
                        <?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo TB_THEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
                        <?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo TB_THEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
                        <?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo TB_THEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
                        <?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo TB_THEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
                    </li>
                </ul>
            </div>
            <!-- 가맹점마다 다른 테일 -->
            <!-- <div class="company"> -->
            <?php
                // (2021-02-04) 각 연동사별 head 스킨파일 분리
                $tail_skin_file = TB_THEME_PATH."/".$pt_id."_tail.skin.php";
                if( file_exists($tail_skin_file) ){
                    include_once($tail_skin_file);
                }else{
                    include_once(TB_THEME_PATH."/admin_tail.skin.php");
                }
            ?>
            <!-- </div> -->
        </div>
    </div>

    <?php if($default['de_pg_service'] == 'kcp') { ?>
    <form name="escrow_foot" method="post" autocomplete="off">
        <input type="hidden" name="site_cd" value="<?php echo $default['de_kcp_mid']; ?>">
    </form>
    <?php } ?>

    <script>
        function escrow_foot_check(){
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
<?php if(TB_DEVICE_BUTTON_DISPLAY && !TB_IS_MOBILE && is_mobile()) { ?>
        <a href="<?php echo TB_URL; ?>/index.php?device=mobile" id="device_change">모바일 버전으로 보기</a>
<?php } ?>

<script>
/*
$(document).ready(function(){
    //2022-01-27 인플루언서 가격 추가 스크립트    
    if(window.location.pathname != '/shop/orderform.php' && window.location.pathname != '/shop/cart.php'){        
        if(!$('.extra_sale_price').text() && !$('.timer').text()){
            $('.spr').after('</br><span class="recommendation">특별할인★</span>&nbsp;&nbsp;');
        }
    }

});   
*/ 
</script>