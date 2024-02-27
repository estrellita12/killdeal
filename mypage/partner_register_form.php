<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "신규 회원등록";
include_once("./admin_head.sub.php");
?>

<script src="<?php echo TB_JS_URL; ?>/jquery.register_form.js"></script>

<form name="fregisterform" id="fregisterform" action="./partner_register_form_update.php" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="token" value="">

<h2>사이트 이용정보 입력</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_id">아이디</label></th>
		<td>
			<input type="text" name="mb_id" id="reg_mb_id" required class="frm_input required" size="20" maxlength="20">
			<span id="msg_mb_id"></span>
			<?php echo help('영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">비밀번호</label></th>
		<td><input type="password" name="mb_password" id="reg_mb_password" required class="frm_input required" size="20" maxlength="20"> 4자 이상의 영문 및 숫자</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">비밀번호 확인</label></th>
		<td><input type="password" name="mb_password_re" id="reg_mb_password_re" required class="frm_input required" size="20" maxlength="20"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2 class="mart30">개인정보 입력</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_name">이름(실명)</label></th>
		<td><input type="text" name="mb_name" id="reg_mb_name" required class="frm_input required" size="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_tel">전화번호</label></th>
		<td><input type="text" name="mb_tel" id="reg_mb_tel" class="frm_input" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_hp">휴대폰번호</label></th>
		<td>
			<input type="text" name="mb_hp" id="reg_mb_hp" class="frm_input" size="20" maxlength="20">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_sms" value="Y" checked="checked"> 휴대폰 문자메세지를 받겠습니다.</label>
			</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_email">E-mail</label></th>
		<td>
			<input type="text" name="mb_email" id="reg_mb_email" required class="frm_input required" size="40" maxlength="100">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_mailling" value="Y" id="reg_mb_mailling" checked="checked"> 정보 메일을 받겠습니다.</label>
			</span>
		</td>
	</tr>
    <tr>
        <th scope="row">본인확인방법</th>
        <td>
            <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin">
            <label for="mb_certify_ipin">아이핀</label>
            <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp">
            <label for="mb_certify_hp">휴대폰</label>
        </td>
    </tr>
    <tr>
        <th scope="row">본인확인</th>
        <td>
            <input type="radio" name="mb_certify" value="1" id="mb_certify_yes">
            <label for="mb_certify_yes">예</label>
            <input type="radio" name="mb_certify" value="" id="mb_certify_no" checked="checked">
            <label for="mb_certify_no">아니오</label>
        </td>
	</tr>
    <tr>
        <th scope="row">성인인증</th>
        <td>
            <input type="radio" name="mb_adult" value="1" id="mb_adult_yes">
            <label for="mb_adult_yes">예</label>
            <input type="radio" name="mb_adult" value="0" id="mb_adult_no" checked="checked">
            <label for="mb_adult_no">아니오</label>
        </td>
    </tr>
	<tr>
		<th scope="row">주소</th>
		<td>
			<label for="reg_mb_zip" class="sound_only">우편번호</label>
			<input type="text" name="mb_zip" id="reg_mb_zip" class="frm_input" size="8" maxlength="5">
			<button type="button" class="btn_small" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소검색</button><br>
			<input type="text" name="mb_addr1" id="reg_mb_addr1" class="frm_input frm_address" size="60">
			<label for="reg_mb_addr1">기본주소</label><br>
			<input type="text" name="mb_addr2" id="reg_mb_addr2" class="frm_input frm_address" size="60">
			<label for="reg_mb_addr2">상세주소</label><br>
			<input type="text" name="mb_addr3" id="reg_mb_addr3" class="frm_input frm_address" size="60" readonly="readonly">
			<label for="reg_mb_addr3">참고항목</label>
			<input type="hidden" name="mb_addr_jibeon" value="">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_recommend">추천인</label></th>
		<td><input type="text" name="mb_recommend" value="<?php echo $member['id']; ?>" id="reg_mb_recommend" required class="frm_input required"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregisterform_submit(f)
{
	// 회원아이디 검사
	var msg = reg_mb_id_check();
	if(msg) {
		alert(msg);
		f.mb_id.select();
		return false;
	}

	if(f.mb_password.value.length < 4) {
		alert("비밀번호를 4글자 이상 입력하십시오.");
		f.mb_password.focus();
		return false;
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

	// E-mail 검사
	var msg = reg_mb_email_check();
	if(msg) {
		alert(msg);
		f.reg_mb_email.select();
		return false;
	}

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

<?php
include_once("./admin_tail.sub.php");
?>