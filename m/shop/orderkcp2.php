<?php
include_once("./_common.php");


//현대리바트_복지_sitekey설정_20190826
$default['de_kcp_mid'] = "A8HRJ";
$default['de_kcp_site_key'] = '3Bbeo5luAlZqUwvsowTZ-y6__';


$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);

$stotal = get_order_spay_nosum($od_id); // 총계
$tot_price = get_session('tot_price'); // 결제금액

$order_action_url = TB_HTTPS_MSHOP_URL.'/orderformresult.php';
include_once(TB_MTHEME_PATH.'/orderkcp2.skin.php');

include_once("./_tail.php");
?>