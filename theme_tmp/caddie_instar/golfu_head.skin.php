<?php
if(!defined('_TUBEWEB_')) exit;
?>

<style>
    *{margin:0; padding:0; }
    #golfuheader{width:1400px; margin:0 auto; font-size:12px;}
    #golfuheader a{text-decoration:none; color:#686868;}
    #golfuheader ul{float:right; margin-top:2px;}
    #golfuheader li{display:inline; margin:4px;}

</style>
<div id="golfuheader">
    <ul>
        <!-- 로그인과 관련된 부분 Start -->
        <?php if($is_member) { ?>

            <li><a href="https://shopping.golfu.net/bbs/logout.php"  target="_parent">로그아웃</a></li>
            <li><a href="https://www.golfu.net/Member/MemberEdit.aspx" target="_parent">정보수정</a></li>
        <?php }else { ?>

            <li><a href="https://www.golfu.net/member/Login.aspx?strPrevUrl=https://shopping.golfu.net"  target="_parent">로그인</a></li>
            <li><a href="https://www.golfu.net/Member/MemberAgree.aspx" target="_parent">회원가입</a></li>
        <?php } ?>
        <!-- 로그인과 관련된 부분 End -->
        <li><a href="https://www.golfu.net/Reservation/ReserveClubList.aspx" target="_parent">부킹</a></li>
        <li><a href="https://www.golfu.net/SNC/SNCContents.aspx" target="_parent">소셜커머스</a></li>
        <li><a href="https://www.golfu.net/Package/PackageMain.aspx" target="_parent">국내패키지</a></li>
        <li><a href="https://www.golfu.net/Tour/Tour_Alliance.aspx" target="_parent">해외투어</a></li>
        <li><a href="https://www.golfu.net/Media/MagazineMain.aspx" target="_parent">뉴스/매거진</a></li>
        <li><a href="https://www.golfu.net/Flower/FlowerMain.aspx" target="_parent">Golfu플라워</a></li>
        <li><a href="https://www.golfu.net/MemberShips/GolfMembers.aspx" target="_parent">회원권</a></li>
        <li><a href="https://www.golfu.net/GolfInfo/GolfCourseInfo/Default.aspx" target="_parent">골프정보</a></li>
        <li><a href="https://www.golfu.net/community/NoticeBoard.aspx" target="_parent">커뮤니티</a></li>
    </ul>
</div>
<br><br>
<!-- 상단부 영역 시작 { -->
<div id="hd_inner">
    <div class="hd_bnr">
        <span><?php echo display_banner(2, $pt_id); ?></span>
    </div>
    <ul class="fl tnb_log">
        <?php
        $tnb = array();
        if($is_admin)
            $tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
        if($is_member) {
            //$tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">로그아웃</a></li>';
        } else {
            //$tnb[] = '<li><a href="https://www.golfu.net/member/Login.aspx?strPrevUrl=http://shopping.golfu.net">로그인</a></li>';
            //$tnb[] = '<li><a href="'.TB_BBS_URL.'/register.php">회원가입</a></li>';
        }
        $tnb_str = implode(PHP_EOL, $tnb);
        echo $tnb_str;
        ?>
    </ul>

    <h1 class="hd_logo">
        <?php echo display_logo(); ?>
    </h1>

    <!-- 20191017 class명 fr tnb_member 수정후 top 20px 추가-->
    <ul class="tnb_member">
        <?php
        $tnb = array();
        $tnb[] = '<li class="cart"><a href="'.TB_SHOP_URL.'/cart.php"><i></i>장바구니</a></li>';
        $tnb[] = '<li class="order"><a href="'.TB_SHOP_URL.'/orderinquiry.php"><i></i>주문/배송조회</a></li>';
        $tnb_str = implode(PHP_EOL, $tnb);
        echo $tnb_str;
        ?>
    </ul>
</div>
