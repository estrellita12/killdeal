<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

?>

<div class="mb_login">
    <div class="login_wrap" id="login_fld">
        <form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="url" value="<?php echo $login_url; ?>">
            <section class="login_fs">
                <p class="mart15">
                <label for="login_id" class="sound_only">회원아이디</label>
                <input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="아이디">
                </p>
                <p class="mart3">
                <label for="login_pw" class="sound_only">비밀번호</label>
                <input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="비밀번호">		
                </p>	
                <p class="mart10 tal">
                <input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
                <label for="login_auto_login" class="css-label">자동로그인</label>
                </p>
                <p class="mart10"><button type="submit" class="btn_medium wfull">로그인</button></p>
                <p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium wfull bx-white">회원가입</a></p>
                <p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">아이디/비밀번호 찾기</a></span></p>
            </section>
            <?php if($default['de_sns_login_use']) { ?>
            <p class="sns_btn">
            <?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
            <?php echo get_login_oauth('naver', 1); ?>
            <?php } ?>
            <?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
            <?php echo get_login_oauth('facebook', 1); ?>
            <?php } ?>
            <?php if($default['de_kakao_rest_apikey']) { ?>
            <?php echo get_login_oauth('kakao', 1); ?>
            <?php } ?>
            </p>
            <?php } ?>
            <div class="log_join_box"> 회원가입하시고 풍성한 혜택을 누리세요. </div>
            <input type="hidden" name="pt_id" value="<?php echo $pt_id?>">
        </form>
    </div>
    <div class="login_wrap" id="guest_buy_fld">
        <form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
            <section class="login_fs">
                <h3>비회원 구매</h3>
                <p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">비회원으로 구매하기</a></p>
            </section>
        </form>
    </div>

    <div class="login_wrap" id="guest_fld">
        <form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
            <section class="login_fs">
                <p class="mart15">
                <label for="od_id" class="sound_only">주문번호</label>
                <input type="text" name="od_id" id="od_id" placeholder="주문번호">			
                </p>
                <p class="mart3">
                <label for="od_pwd" class="sound_only">비밀번호</label>
                <input type="password" name="od_pwd" id="od_pwd" placeholder="비밀번호">		
                </p>
                <p class="mart10"><button type="submit" class="btn_medium wfull">확인</button></p>
                <div class="log_nologin_box"> 비회원으로 구매한 이력이 있는 경우에만 주문/배송 조회가 가능합니다. <br>주문/배송 이외의 서비스는 회원가입 후 이용이 가능합니다. </div>
            </section>
        </form>
    </div>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    if(!f.mb_id.value) {
        alert('아이디를 입력하세요.');
        f.mb_id.focus();
        return false;
    }
    if(!f.mb_password.value) {
        alert('비밀번호를 입력하세요.');
        f.mb_password.focus();
        return false;
    }

    return true;
}

function fguest_submit(f)
{
    if(!f.od_id.value) {
        alert('주문번호를 입력하세요.');
        f.od_id.focus();
        return false;
    }
    if(!f.od_pwd.value) {
        alert('비밀번호를 입력해주세요.');
        f.od_pwd.focus();
        return false;
    }

    return true;
}

$(document).ready(function(){
    $(".login_tab>li:eq(1)").addClass('active');
    $("#guest_buy_fld").addClass('active');

    $(".login_tab>li").click(function() {
        var activeTab = $(this).attr('data-tab');
        $(".login_tab>li").removeClass('active');
        $(".login_wrap").removeClass('active');
        $(this).addClass('active');
        $("#"+activeTab).addClass('active');
    });
});

</script>
