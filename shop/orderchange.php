<?php
include_once("./_common.php");

$sql = " select * from shop_order where gs_id = '$gs_id' and od_id = '$od_id' ";
$od = sql_fetch($sql);
if(!$od['od_id']) {
    alert_close("주문서가 존재하지 않습니다.");
}

if(!in_array($q, array('반품','교환'))) {
	alert_close("제대된 접근이 아닙니다.");
}

if(!($od['dan'] == 5 && is_null_time($od['user_date']))) {
    alert_close("{$q}신청하실 수 없는 상품입니다.");
}

$tb['title'] = $q.'신청';
include_once(TB_PATH."/head.sub.php");

$gs = get_goods($gs_id);

$form_action_url = TB_HTTPS_SHOP_URL.'/orderchange_update.php';
include_once(TB_THEME_PATH.'/orderchange.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>