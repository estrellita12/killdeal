<?php
if(!defined('_TUBEWEB_')) exit;

?>
<style>
.wsetbg { background:#D33B22; color:#fff; }
.wsetfc { color:#D33B22; }
.wsetbd{ border:1px solid #D33B22; }

.wseta:hover{ color:#D33B22; }
.wseta:after{ background-color:#D33B22; }

.wset { background:#D33B22; border:1px solid #D33B22; color:#fff; !important }
.wset:hover { background:#D33B22; border:1px solid #D33B22; color:#fff; !important }

.bestca_tab1 li{
    background: -webkit-linear-gradient(to right, #92140c,  #D33B22 ) !important;
    background: -moz-linear-gradient(to right,  #92140c,  #D33B22 ) !important;
    background: -o-linear-gradient(to right,  #92140c,  #D33B22 ) !important;
    background: linear-gradient(to right,  #92140c,  #D33B22 ) !important;
}

.gradient{
    background: -webkit-linear-gradient(to right,  #92140c,  #D33B22 ) !important;
    background: -moz-linear-gradient(to right,  #92140c,  #D33B22 ) !important;
    background: -o-linear-gradient(to right,  #92140c,  #D33B22 ) !important;
    background: linear-gradient(to right, #92140c,  #D33B22 ) !important;
}



</style>
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
        if($is_member) {
            if(! $pt_link_data['db_link_yes'] ){
                $tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">로그아웃</a></li>';
            }
            if(!$is_admin){ // 관리자는 회원정보수정 금지
                if(! $pt_link_data['db_link_yes'] ){
                    $tnb[] = '<li><a href="'.TB_BBS_URL.'/member_confirm.php?url=register_form.php">회원정보수정</a></li>';
                }
            }
        } else {
            $tnb[] = '<li><a class="pt_wset" href="'.TB_BBS_URL.'/login.php?url='.$urlencode.'">로그인</a></li>';
            if(! $pt_link_data['db_link_yes'] ){
                $tnb[] = '<li><a class="pt_wset" href="'.TB_BBS_URL.'/register.php">회원가입</a></li>';
            }
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
