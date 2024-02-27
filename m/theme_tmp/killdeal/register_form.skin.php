<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 회원정보 입력/수정 시작 { -->
<script src="<?php echo TB_JS_URL; ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo TB_JS_URL; ?>/certify.js?v=<?php echo TB_JS_VER; ?>"></script>
<?php } ?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="agree" value="<?php echo $agree; ?>">
<input type="hidden" name="agree2" value="<?php echo $agree2; ?>">
<input type="hidden" name="mb_recommend" id="reg_mb_recommend" value="<?php echo $member['pt_id']; ?>">
<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
<input type="hidden" name="cert_no" value="">

<h3>사이트 이용정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_id">아이디</label></th>
		<td>
			<input type="text" name="mb_id" value="<?php echo $member['id'] ?>" id="reg_mb_id"<?php echo $required; ?><?php echo $readonly; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20">
			<span id="msg_mb_id"></span>
			<span class="frm_info">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">비밀번호</label></th>
		<td><input type="password" name="mb_password" id="reg_mb_password"<?php echo $required; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">비밀번호 확인</label></th>
		<td><input type="password" name="mb_password_re" id="reg_mb_password_re"<?php echo $required; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20"></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="mart30">개인정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_name">이름</label></th>
		<td>
			<input type="text" name="mb_name" value="<?php echo get_text($member['name']); ?>" id="reg_mb_name"<?php echo $required; ?><?php echo $readonly; ?> class="frm_input<?php echo $required; ?>" size="20">
			<?php
			$cert_str = '';
			if($config['cf_cert_use']) {
				if($config['cf_cert_ipin'])
					$cert_str .= '<button type="button" id="win_ipin_cert" class="btn_small">아이핀 본인인증</button>'.PHP_EOL;
				if($config['cf_cert_hp'])
					$cert_str .= '<button type="button" id="win_hp_cert" class="btn_small">휴대폰 본인인증</button>'.PHP_EOL;

				$cert_str .= '<noscript>본인인증을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
			}
			if($cert_str) echo '<div class="mart5">'.$cert_str.'</div>';
			if($config['cf_cert_use'] && $member['mb_certify']) {
				if($member['mb_certify'] == 'ipin')
					$mb_cert = '아이핀';
				else
					$mb_cert = '휴대폰';
			?>
			<div id="msg_certify">
				<strong><?php echo $mb_cert; ?> 본인인증</strong><?php if($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
			</div>
			<?php } ?>
			<?php if($config['cf_cert_use']) { ?>
			<span class="frm_info">아이핀 본인인증 후에는 이름이 자동 입력되고 휴대폰 본인인증 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
			<?php } ?>
		</td>
	</tr>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_tel">전화번호</label></th>
		<td><input type="text" name="mb_tel" value="<?php echo get_text($member['telephone']); ?>" id="reg_mb_tel"<?php echo $config['register_req_tel']?' required':''; ?> class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20" maxlength="20"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp'] || $config['cf_cert_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_hp">휴대폰번호</label></th>
		<td>
			<input type="text" name="mb_hp" value="<?php echo get_text($member['cellphone']); ?>" id="reg_mb_hp"<?php echo $config['register_req_hp']?' required':''; ?> class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20" maxlength="20">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_sms" value="Y"<?php echo ($w=='' || $member['smsser'] == 'Y')?' checked':''; ?>> 휴대폰 문자메세지를 받겠습니다.</label>
			</span>
			<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
			<input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['cellphone']); ?>">
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">E-mail</label></th>
		<td>
			<input type="hidden" name="old_email" value="<?php echo $member['email']; ?>">
			<input type="text" name="mb_email" value="<?php echo isset($member['email'])?$member['email']:''; ?>" id="reg_mb_email" required class="frm_input required" size="40" maxlength="100">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_mailling" value="Y" id="reg_mb_mailling"<?php echo ($w=='' || $member['mailser'] == 'Y')?' checked':''; ?>> 정보 메일을 받겠습니다.</label>
			</span>
		</td>
	</tr>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소</th>
		<td>
			<label for="reg_mb_zip" class="sound_only">우편번호</label>
			<input type="text" name="mb_zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" placeholder="우편번호">
			<button type="button" class="btn_small" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소검색</button><br>
			<input type="text" name="mb_addr1" value="<?php echo get_text($member['addr1']); ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> class="frm_input<?php echo $config['register_req_addr']?' required':''; ?> frm_address" size="60" placeholder="기본주소">
			<label for="reg_mb_addr1" class="sound_only">기본주소</label><br>
			<input type="text" name="mb_addr2" value="<?php echo get_text($member['addr2']); ?>" id="reg_mb_addr2" class="frm_input frm_address" size="60" placeholder="상세주소">
			<label for="reg_mb_addr2" class="sound_only">상세주소</label><br>
			<input type="text" name="mb_addr3" value="<?php echo get_text($member['addr3']); ?>" id="reg_mb_addr3" class="frm_input frm_address" size="60" placeholder="참고항목" readonly="readonly">
			<label for="reg_mb_addr3" class="sound_only">참고항목</label>
			<input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['addr_jibeon']); ?>">
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_medium bx-white">취소</a>
</div>
</form>

<script>
$(function() {
	<?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
	// 아이핀인증
	$("#win_ipin_cert").click(function() {
		if(!cert_confirm())
			return false;

		var url = "<?php echo TB_OKNAME_URL; ?>/ipin1.php";
		certify_win_open('kcb-ipin', url);
		return;
	});

	<?php } ?>
	<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
	// 휴대폰인증
	$("#win_hp_cert").click(function() {
		if(!cert_confirm())
			return false;

		<?php
		switch($config['cf_cert_hp']) {
			case 'kcb':
				$cert_url = TB_OKNAME_URL.'/hpcert1.php';
				$cert_type = 'kcb-hp';
				break;
			case 'kcp':
				$cert_url = TB_KCPCERT_URL.'/kcpcert_form.php';
				$cert_type = 'kcp-hp';
				break;
			case 'lg':
				$cert_url = TB_LGXPAY_URL.'/AuthOnlyReq.php';
				$cert_type = 'lg-hp';
				break;
			default:
				echo 'alert("기본환경설정에서 휴대폰 본인인증 설정을 해주십시오");';
				echo 'return false;';
				break;
		}
		?>

		certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
		return;
	});
	<?php } ?>
});

function fregisterform_submit(f)
{
	// 회원아이디 검사
	if(f.w.value == "") {
		var msg = reg_mb_id_check();
		if(msg) {
			alert(msg);
			f.mb_id.select();
			return false;
		}
	}

	if(f.w.value == "") {
		if(f.mb_password.value.length < 4) {
			alert("비밀번호를 4글자 이상 입력하십시오.");
			f.mb_password.focus();
			return false;
		}
	}

	if(f.mb_password.value != f.mb_password_re.value) {
		alert("비밀번호가 같지 않습니다.");
		f.mb_password_re.focus();
		return false;
	}

	if(f.mb_password.value.length > 0) {
		if(f.mb_password_re.value.length < 4) {
			alert("비밀번호를 4글자 이상 입력하십시오.");
			f.mb_password_re.focus();
			return false;
		}
	}

	// 이름 검사
	if(f.w.value=="") {
		if(f.mb_name.value.length < 1) {
			alert("이름을 입력하십시오.");
			f.mb_name.focus();
			return false;
		}

		/*
		var pattern = /([^가-힣\x20])/i;
		if(pattern.test(f.mb_name.value)) {
			alert("이름은 한글로 입력하십시오.");
			f.mb_name.select();
			return false;
		}
		*/
	}

	<?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
	// 본인인증 체크
	if(f.cert_no.value=="") {
		alert("회원가입을 위해서는 본인인증을 해주셔야 합니다.");
		return false;
	}
	<?php } ?>

	// E-mail 검사
	if((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
		var msg = reg_mb_email_check();
		if(msg) {
			alert(msg);
			f.reg_mb_email.select();
			return false;
		}
	}

	<?php if(($config['register_use_hp'] || $config['cf_cert_hp']) && $config['register_req_hp']) { ?>
	// 휴대폰번호 체크
	var msg = reg_mb_hp_check();
	if(msg) {
		alert(msg);
		f.reg_mb_hp.select();
		return false;
	}
	<?php } ?>

	if(typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
		if(f.mb_id.value == f.mb_recommend.value) {
			alert("본인을 추천할 수 없습니다.");
			f.mb_recommend.focus();
			return false;
		}

		var msg = reg_mb_recommend_check();
		if(msg) {
			alert(msg);
			f.mb_recommend.select();
			return false;
		}
	}

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}
</script>
<!-- } 회원정보 입력/수정 끝 -->
