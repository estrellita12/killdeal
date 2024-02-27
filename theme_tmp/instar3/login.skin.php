<?php
if(!defined('_TUBEWEB_')) exit;

?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 로그인</p>
<h2 class="stit">LOGIN</h2>
<?php if(preg_match("/orderform.php/", $url) && $pt_link_data['non_mem_allow']) { ?>
<?php goto_url( TB_SHOP_URL."/orderform.php" ); ?>
<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url; ?>">
    <div class="login_wrap active" id="login_fld">
        <dl class="log_inner">
            <dd><a href="<?php echo TB_SHOP_URL; ?>/orderform.php" class="btn_large wset wfull">비회원으로 구매하기</a></dd>
        </dl>
    </div>
</form>
<?php }else{ ?>
<form name="forderinquiry" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" autocomplete="off">
    <div class="login_wrap active" id="guest_fld">	
        <dl class="log_inner">
            <dt>비회원 주문조회</dt>
            <dd class="stxt">
            결제 완료 후 안내해드린 주문번호와 주문 결제 시에 작성한 비밀번호를 입력해주세요.
            </dd>
            <dd>
            <label for="od_id" class="sound_only">주문번호</label>
            <input type="text" name="od_id" id="od_id" class="frm_input" placeholder="주문번호">		
            </dd>
            <dd>
            <label for="od_pwd" class="sound_only">비밀번호</label>
            <input type="password" name="od_pwd" id="od_pwd" class="frm_input" placeholder="비밀번호">		
            </dd>
            <dd><button type="submit" class="btn_large">확인</button></dd>
        </dl>	
    </div>
</form>
<?php } ?>
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
/*
$(document).ready(function(){
    $(".login_tab>li:eq(0)").addClass('active');
    $("#login_fld").addClass('active');

    $(".login_tab>li").click(function() {
        var activeTab = $(this).attr('data-tab');
        $(".login_tab>li").removeClass('active');
        $(".login_wrap").removeClass('active');
        $(this).addClass('active');
        $("#"+activeTab).addClass('active');
    });
});
*/
</script>
