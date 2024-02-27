<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/register.lib.php');
include_once(TB_LIB_PATH.'/mailer.lib.php');

check_demo();

if(!($w == '' || $w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if($_SESSION['ss_hash_token'] != TB_HASH_TOKEN) {
    alert('잘못된 접근입니다.', TB_URL);
}

if($w == 'u')
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
else if($w == '')
    $mb_id = trim($_POST['mb_id']);
else
    alert('잘못된 접근입니다.', TB_URL);

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_name        = trim($_POST['mb_name']);
$mb_email       = trim($_POST['mb_email']);
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip			= isset($_POST['mb_zip'])           ? trim($_POST['mb_zip'])		 : "";
$mb_addr1       = isset($_POST['mb_addr1'])         ? trim($_POST['mb_addr1'])       : "";
$mb_addr2       = isset($_POST['mb_addr2'])         ? trim($_POST['mb_addr2'])       : "";
$mb_addr3       = isset($_POST['mb_addr3'])         ? trim($_POST['mb_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon'])   ? trim($_POST['mb_addr_jibeon']) : "";
$mb_recommend   = isset($_POST['mb_recommend'])     ? trim($_POST['mb_recommend'])   : "";
$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "";
$mb_sms         = isset($_POST['mb_sms'])           ? trim($_POST['mb_sms'])         : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip			= preg_replace('/[^0-9]/', '', $mb_zip);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

if($w == '' || $w == 'u') {

    if($msg = empty_mb_id($mb_id))	alert($msg);
    if($msg = valid_mb_id($mb_id))	alert($msg);
    if($msg = count_mb_id($mb_id))	alert($msg);

    // 이름에 utf-8 이외의 문자가 포함됐다면 오류
    // 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if($tmp_mb_name != $mb_name) {
        alert('이름을 올바르게 입력해 주십시오.');
    }

    if($w == '' && !$mb_password)
        alert('비밀번호가 넘어오지 않았습니다.');
    if($w == '' && $mb_password != $mb_password_re)
        alert('비밀번호가 일치하지 않습니다.');

    if($msg = empty_mb_name($mb_name))		alert($msg);
    if($msg = empty_mb_email($mb_email))	alert($msg);
    if($msg = reserve_mb_id($mb_id))		alert($msg);
    // 이름에 한글명 체크를 하지 않는다.
    //if($msg = valid_mb_name($mb_name))	alert($msg);
    if($msg = valid_mb_email($mb_email))	alert($msg);
    if($msg = prohibit_mb_email($mb_email))	alert($msg);

    // 휴대폰 필수입력일 경우 휴대폰번호 유효성 체크
    if(($config['register_use_hp'] || $config['cf_cert_hp']) && $config['register_req_hp']) {
        if($msg = valid_mb_hp($mb_hp))		alert($msg);
    }

    if($w == '') {
        if($msg = exist_mb_id($mb_id))		alert($msg);

        if(get_session('ss_check_mb_id') != $mb_id || get_session('ss_check_mb_email') != $mb_email) {
            set_session('ss_check_mb_id', '');
            set_session('ss_check_mb_email', '');

            alert('올바른 방법으로 이용해 주십시오.');
        }

        // 본인확인 체크
        if($config['cf_cert_use'] && $config['cf_cert_req']) {
            if(trim($_POST['cert_no']) != $_SESSION['ss_cert_no'] || !$_SESSION['ss_cert_no'])
                alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        }

        if($mb_recommend) {
            if(!exist_mb_id($mb_recommend))
                alert("추천인이 존재하지 않습니다.");
        }

        if(strtolower($mb_id) == strtolower($mb_recommend)) {
            alert('본인을 추천할 수 없습니다.');
        }
    } else {
		// 자바스크립트로 정보변경이 가능한 버그 수정
		// 회원정보의 메일을 이전 메일로 옮기고 아래에서 비교함
		$old_email = $member['email'];
	}

    // (2021-02-18) 이메일 중복 확인 제거
    //if($msg = exist_mb_email($mb_email, $mb_id))   alert($msg);
    // (2021-04-26)
    if($msg = exist_pt_mb_email($pt_id, $mb_email, $mb_id))   alert($msg);
}

//===============================================================
//  본인확인
//---------------------------------------------------------------
$mb_hp = hyphen_hp_number($mb_hp);
if($config['cf_cert_use'] && $_SESSION['ss_cert_type'] && $_SESSION['ss_cert_dupinfo']) {
    // 중복체크
    $sql = " select id from shop_member where id <> '{$member['id']}' and mb_dupinfo = '{$_SESSION['ss_cert_dupinfo']}' ";
    $row = sql_fetch($sql);
    if($row['id']) {
        alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : ".$row['id']);
    }
}

unset($value);
$md5_cert_no = $_SESSION['ss_cert_no'];
$cert_type = $_SESSION['ss_cert_type'];
if($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
    // 해시값이 같은 경우에만 본인확인 값을 저장한다.
    if($_SESSION['ss_cert_hash'] == md5($mb_name.$cert_type.$_SESSION['ss_cert_birth'].$md5_cert_no)) {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= $cert_type;
        $value['mb_adult']		= $_SESSION['ss_cert_adult'];
        $value['mb_birth']		= $_SESSION['ss_cert_birth'];
        $value['gender']		= $_SESSION['ss_cert_sex'];
        $value['mb_dupinfo']	= $_SESSION['ss_cert_dupinfo'];
		$value['age']			= get_birth_age($_SESSION['ss_cert_birth']);
        if($w == 'u')
			$value['name'] = $mb_name;
    } else {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= '';
        $value['mb_adult']		= '0';
        $value['mb_birth']		= '';
        $value['gender']		= '';
		$value['age']			= '';
    }
} else {
    if(get_session("ss_reg_mb_name") != $mb_name || get_session("ss_reg_mb_hp") != $mb_hp) {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= '';
        $value['mb_adult']		= '0';
        $value['mb_birth']		= '';
        $value['gender']		= '';
		$value['age']			= '';
    }
}
//===============================================================

$msg = "";

if($w == '') {
	$value['id']			= $mb_id; //회원아이디
	$value['passwd']		= $mb_password; //비밀번호
	$value['name']			= $mb_name; //이름
	$value['email']			= $mb_email; //이메일
	$value['telephone']		= $mb_tel;	 //전화번호
	$value['zip']			= $mb_zip; //우편번호
	$value['addr1']			= $mb_addr1; //주소
	$value['addr2']			= $mb_addr2; //상세주소
	$value['addr3']			= $mb_addr3; //참고항목
	$value['addr_jibeon']	= $mb_addr_jibeon; //지번주소
	$value['today_login']	= TB_TIME_YMDHIS; //최근 로그인일시
	$value['reg_time']		= TB_TIME_YMDHIS; //가입일시
	$value['mb_ip']			= $_SERVER['REMOTE_ADDR']; //IP
	$value['grade']			= '9'; //레벨
	$value['pt_id']			= $mb_recommend; //추천인아이디
	$value['login_ip']		= $_SERVER['REMOTE_ADDR']; //최근 로그인IP
	$value['mailser']		= $mb_mailling ? $mb_mailling : 'N'; //E-Mail을 수신
	$value['smsser']		= $mb_sms ? $mb_sms : 'N'; //SMS를 수신

    // 관리자인증을 사용하지 않는다면 인증으로 간주함.
    if(!$config['cert_admin_yes'])
        $value['use_app']	= '1';

	insert("shop_member", $value);
	$mb_no = sql_insert_id();

    // 회원가입 포인트 부여
    insert_point($mb_id, $config['register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');

    // 추천인에게 포인트 부여
	insert_point($mb_recommend, $config['partner_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');

	// 회원님께 메일 발송
	$subject = '['.$config['company_name'].'] 회원가입을 축하드립니다.';

	ob_start();
	include_once(TB_BBS_PATH.'/register_form_update_mail1.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($config['company_name'], $super['email'], $mb_email, $subject, $content, 1);


	// 최고관리자님께 메일 발송
	$subject = '['.$config['company_name'].'] '.$mb_name .'님께서 회원으로 가입하셨습니다.';

	ob_start();
	include_once(TB_BBS_PATH.'/register_form_update_mail2.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($mb_name, $mb_email, $super['email'], $subject, $content, 1);

	// 회원가입 문자발송
	icode_register_sms_send($mb_recommend, $mb_id);

    // 관리자인증을 사용하지 않는 경우에만 로그인
	if($config['cert_admin_yes'])
		$msg = "회원가입이 완료 되었으며 승인 처리 이후 로그인 가능합니다";
	else
		set_session('ss_mb_id', $mb_id);

	set_session('ss_mb_reg', $mb_id);

} else if($w == 'u') {
    if(!trim($_SESSION['ss_mb_id']))
        alert('로그인 되어 있지 않습니다.');

    if(trim($_POST['mb_id']) != $mb_id)
        alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");

    if($mb_password)
        $value['passwd']	= $mb_password; //비밀번호

	$value['name']			= $mb_name; //이름 (2021-02-17) 소셜 로그인의 경우 이름이 이상하게 저장됨
	$value['email']			= $mb_email; //이메일
	$value['telephone']		= $mb_tel;	 //전화번호
	$value['zip']			= $mb_zip; //우편번호
	$value['addr1']			= $mb_addr1; //주소
	$value['addr2']			= $mb_addr2; //상세주소
	$value['addr3']			= $mb_addr3; //참고항목
	$value['addr_jibeon']	= $mb_addr_jibeon; //지번주소
	$value['mailser']		= $mb_mailling ? $mb_mailling : 'N'; //E-Mail을 수신
	$value['smsser']		= $mb_sms ? $mb_sms : 'N'; //SMS를 수신
	update("shop_member", $value, " where id = '{$member['id']}' ");
}

// 신규회원가입 쿠폰발급
if($w == '' && $config['coupon_yes']) {
	$cp_used = false;
	$cp = sql_fetch("select * from shop_coupon where cp_type = '5'");
	if($cp['cp_id'] && $cp['cp_use']) {
		if(($cp['cp_pub_sdate'] <= TB_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
		   ($cp['cp_pub_edate'] >= TB_TIME_YMD || $cp['cp_pub_edate'] == '9999999999'))
			$cp_used = true;

		if($cp_used)
			insert_used_coupon($mb_id, $mb_name, $cp);
	}
}

unset($_SESSION['ss_cert_type']);
unset($_SESSION['ss_cert_no']);
unset($_SESSION['ss_cert_hash']);
unset($_SESSION['ss_cert_birth']);
unset($_SESSION['ss_cert_adult']);
unset($_SESSION['ss_hash_token']);

if($msg)
    echo '<script>alert(\''.$msg.'\');</script>';

if($w == '') {
    goto_url(TB_BBS_URL.'/register_result.php');
} else if($w == 'u') {
    $row = sql_fetch(" select passwd from shop_member where id = '{$member['id']}' ");
    $tmp_password = $row['passwd'];

	echo '
	<!doctype html>
	<html lang="ko">
	<head>
	<meta charset="utf-8">
	<title>회원정보수정</title>
	<body>
	<form name="fregisterupdate" method="post" action="'.TB_BBS_URL.'/register_form.php">
	<input type="hidden" name="w" value="u">
	<input type="hidden" name="mb_id" value="'.$mb_id.'">
	<input type="hidden" name="mb_password" value="'.$tmp_password.'">
	<input type="hidden" name="is_update" value="1">
	</form>
	<script>
	alert("회원 정보가 수정 되었습니다.");
	document.fregisterupdate.submit();
	</script>
	</body>
	</html>';
}
?>
