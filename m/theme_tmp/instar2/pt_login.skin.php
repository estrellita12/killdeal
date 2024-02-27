<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

?>

<div class="mb_login">
    <?php if( $pt_link_data['non_mem_allow'] ) { ?>
        <?php if(preg_match("/orderform.php/", $url)) { ?>
        <ul class="login_tab">
            <li data-tab="login_fld"><span>회원 로그인</span></li>
            <li data-tab="guest_buy_fld"><span>비회원 구매</span></li>
        </ul>
        <?php }else { ?>
        <ul class="login_tab">
            <li data-tab="login_fld"><span>회원 로그인</span></li>
            <li data-tab="guest_fld"><span>비회원 주문조회</span></li>
        </ul>
        <?php } ?>
    <?php } ?>
    <div class="login_wrap" id="login_fld">
        <form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="url" value="<?php echo $login_url; ?>">
            <section class="login_fs">
                <p><b><?php echo trans_pt_name($pt_id) ?></b> 에서 로그인 하시기 바랍니다.</p>
                <?php if($pt_link_data['mlogin_url'] != ""){ ?>
                <p class="mart10"><button type="submit" class="btn_medium wfull" onclick='location.herf="<?php echo $pt_link_data['mlogin_url'];?>"'>로그인</button></p>
                <?php } ?>
            </section>
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

</script>
