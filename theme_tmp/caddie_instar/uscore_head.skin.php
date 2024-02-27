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
        if($is_member) { // 로그인 상태라면
            if(!$is_admin){
                $tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">로그아웃</a></li>';
            }
        } else { // 로그아웃 상태라면
            $tnb[] = '<li><a href="https://www.uscore.co.kr/app/login.php?https://shop.uscore.co.kr ">로그인</a></li>';
        }
        $tnb_str = implode(PHP_EOL, $tnb);
        echo $tnb_str;
        ?>
    </ul>

    <h1 class="hd_logo">
        <?php echo display_logo(); ?>
    </h1>

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
