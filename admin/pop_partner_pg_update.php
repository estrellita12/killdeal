<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/register.lib.php");

check_demo();

check_admin_token();

$mb_id = trim($_POST['mb_id']);

$mb = get_member($mb_id);
if(!$mb['id']) {
    alert('존재하지 않는 회원자료입니다.');
}

if($mb_id == 'admin') {
    alert('최고관리자는 수정하실 수 없습니다.');
}

if($member['id'] != 'admin' && $mb['grade'] <= $member['grade']) {
    alert('자신보다 레벨이 높거나 같은 회원은 수정할 수 없습니다.');
}

if($mb_id == $member['id'] && $mb_grade != $mb['grade']) {
    alert($mb_id.' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');
}

// 인증정보처리
if($_POST['mb_certify_case'] && $_POST['mb_certify']) {
    $mb_certify = $_POST['mb_certify_case'];
    $mb_adult = $_POST['mb_adult'];
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

unset($pfrm);

$pfrm['de_bank_use']		    = $_POST['de_bank_use'];
$pfrm['de_card_use']		    = $_POST['de_card_use'];
$pfrm['de_iche_use']		    = $_POST['de_iche_use'];
$pfrm['de_vbank_use']		    = $_POST['de_vbank_use'];
$pfrm['de_hp_use']		    = $_POST['de_hp_use'];
$pfrm['de_card_test']		    = $_POST['de_card_test'];
$pfrm['de_pg_service']		    = $_POST['de_pg_service'];
$pfrm['de_tax_flag_use']		    = $_POST['de_tax_flag_use'];
$pfrm['de_taxsave_use']		    = $_POST['de_taxsave_use'];
$pfrm['de_card_noint_use']		    = $_POST['de_card_noint_use'];
$pfrm['de_easy_pay_use']		    = $_POST['de_easy_pay_use'];
$pfrm['de_easy_pay_services']   = implode(",",$_POST['de_easy_pay_services']); // PG사 간편결제 서비스
$pfrm['de_kcp_mid']		    = $_POST['de_kcp_mid'];
$pfrm['de_kcp_site_key']		    = $_POST['de_kcp_site_key'];
$pfrm['de_lg_mid']		    = $_POST['de_lg_mid'];
$pfrm['de_lg_mert_key']		    = $_POST['de_lg_mert_key'];
$pfrm['de_inicis_mid']		    = $_POST['de_inicis_mid'];
$pfrm['de_inicis_admin_key']		    = $_POST['de_inicis_admin_key'];
$pfrm['de_inicis_sign_key']		    = $_POST['de_inicis_sign_key'];
$pfrm['de_samsung_pay_use']		    = $_POST['de_samsung_pay_use'];
$pfrm['de_escrow_use']		    = $_POST['de_escrow_use'];

$adm_bank = array();
for($i=0; $i<count($_POST['bank_name']); $i++) {
    if(!trim($_POST['bank_name'][$i])) continue;

    $adm_bank[$i]['name'] = trim($_POST['bank_name'][$i]);
    $adm_bank[$i]['account'] = trim($_POST['bank_account'][$i]);
    $adm_bank[$i]['holder'] = trim($_POST['bank_holder'][$i]);
}

$pfrm['de_bank_account']       = serialize($adm_bank);

//$pfrm['update_time']		= TB_TIME_YMDHIS;
update("shop_partner", $pfrm," where mb_id='$mb_id'");

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id,$pageName,'가맹점 전자 결제 수정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_pg.php?mb_id='.$mb_id);

?>
