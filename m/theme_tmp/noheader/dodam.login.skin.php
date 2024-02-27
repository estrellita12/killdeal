<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script>
function login_dodam_member()
{
	window.location.replace('https://dodamchon.co.kr/member/login');
}

</script>

<div class="mb_login">
	<?php if($pt_id != 'thegolfshow') {
	?>
	<section class="login_fs">
		<p class="mart10"><button class="btn_medium wfull" onClick="login_dodam_member()">로그인</button></p>
		<p class="mart3"><a href="https://dodamchon.co.kr/member/agreement" class="btn_medium wfull bx-white">회원가입</a></p>
	</section>
	<?php
	} else if($pt_id == 'thegolfshow')
	{
	?>
	회원가입은 앱을 더골프쇼 어플에서 접속하시기 바랍니다.
	<?php } ?>
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
	

	<?php if(preg_match("/orderform.php/", $url)) { ?>
	<section class="mb_login_od" style="border-top:0px">
		<h3>비회원 구매</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">비회원으로 구매하기</a></p>
	</section>
	<?php } else if(preg_match("/orderinquiry.php$/", $url)) { ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>비회원 주문조회</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">주문번호</label>
            <input type="text" name="od_id" id="od_id" placeholder="주문번호">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">비밀번호</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="비밀번호">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">확인</button></p>
	</section>
	</form>
	<?php } ?>
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
</script>
