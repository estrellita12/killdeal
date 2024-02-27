<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div id="hd_inner">
    <div class="hd_bnr">
        <span><?php echo display_banner(2, $pt_id); ?></span>
    </div>
    <ul class="fl tnb_log">
        <?php
        $tnb = array();
        if($is_admin){
            $tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
        }
        $tnb_str = implode(PHP_EOL, $tnb);
        echo $tnb_str;
        ?>
    </ul>

    <h1 class="hd_logo"></h1>

    <div class="hong_imgmap">
        <img src="/img/honggolf_header_20200824.png" alt="홍골프헤더이미지맵" usemap="#hd_link" height="100" width="1100" >
        <map name="hd_link">
            <area shape="rect" coords="0,19,160,79" href="https://www.honggolf.com/" alt="홍골프링크">
            <area shape="rect" coords="253,40,335,60" href="https://honggolf.killdeal.co.kr/" alt="홍골프샵">
            <area shape="rect" coords="403,40,508,60" href="https://www.honggolf.com/shop" alt="구독자상품">
            <area shape="rect" coords="572,40,620,60" href="https://www.honggolf.com/lesson" alt="레슨">
            <area shape="rect" coords="683,40,729,60" href="https://www.honggolf.com/war" alt="대회">
            <area shape="rect" coords="795,40,864,60" href="https://www.honggolf.com/event" alt="이벤트">
            <area shape="rect" coords="925,40,1010,60" href="https://www.honggolf.com/untitled-1/" alt="사용후기">
        </map>
    </div>
    <ul class="tnb_member">
        <?php
        $tnb = array();
        $tnb[] = '<li class="mypage"><a href="'.TB_SHOP_URL.'/mypage.php"><i></i>마이페이지</a></li>';
        $tnb[] = '<li class="cart"><a href="'.TB_SHOP_URL.'/cart.php"><i></i>장바구니</a></li>';
        $tnb[] = '<li class="order"><a href="'.TB_SHOP_URL.'/orderinquiry.php"><i></i>주문/배송조회</a></li>';
        $tnb_str = implode(PHP_EOL, $tnb);
        echo $tnb_str;
        ?>
    </ul>
</div>
