<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2 class="pg_tit">
	<span><?php echo $tb['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i><?php echo $tb['title']; ?></p>
</h2>

<form name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<section id="fregister_term">
	<h2>회원가입 약관 (필수)</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">회원가입 약관 내용에 동의합니다.</label>
	</fieldset>
</section>

<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_large wset">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>
</form>

<script>
function fregister_submit(f)
{

	//문구변경이 필요할수도 있음.(회원가입 ->쇼핑몰 이용)
	if(!f.agree.checked) {
		alert("회원가입 약관 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree.focus();
		return false;
	}

    /*
	if(!f.agree2.checked) {
		alert("개인정보 수집 및 이용 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree2.focus();
		return false;
	}
	*/

	return true;
}

jQuery(function($){
	// 모두선택
	$("input[name=chk_all]").click(function() {
		if ($(this).prop('checked')) {
			$("input[name^=agree]").prop('checked', true);
		} else {
			$("input[name^=agree]").prop("checked", false);
		}
	});

	$("input[name^=agree]").click(function() {
		$("input[name=chk_all]").prop("checked", false);
	});
});
</script>
