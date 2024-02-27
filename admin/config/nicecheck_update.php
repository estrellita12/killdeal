<?php
include_once("./_common.php");

check_demo();

check_admin_token();

// 본인확인을 사용할 경우 아이핀, 휴대폰인증 중 하나는 선택되어야 함
if($_POST['cf_cert_use'] && !$_POST['cf_cert_ipin'] && !$_POST['cf_cert_hp'])
    alert('본인확인을 위해 아이핀 또는 휴대폰 본인학인 서비스를 하나이상 선택해 주십시오');

if(!$_POST['cf_cert_use']) {
    $_POST['cf_cert_ipin'] = '';
    $_POST['cf_cert_hp'] = '';
}

unset($value);
$value['cf_cert_use']		= $_POST['cf_cert_use'];
$value['cf_cert_ipin']		= $_POST['cf_cert_ipin'];
$value['cf_cert_hp']		= $_POST['cf_cert_hp'];
$value['cf_cert_kcb_cd']	= $_POST['cf_cert_kcb_cd'];
$value['cf_cert_kcp_cd']	= $_POST['cf_cert_kcp_cd'];
$value['cf_lg_mid']			= $_POST['cf_lg_mid'];
$value['cf_lg_mert_key']	= $_POST['cf_lg_mert_key'];
$value['cf_cert_limit']		= $_POST['cf_cert_limit'];
$value['cf_cert_req']		= $_POST['cf_cert_req'];
update("shop_config", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=nicecheck');
?>