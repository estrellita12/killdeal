<?php
include_once('./_common.php');

$sql = " select * from shop_order where gs_id = '$gs_id' and od_id = '$od_id' ";
$od = sql_fetch($sql);
if(!$od['od_id']) {
    alert("주문서가 존재하지 않습니다.");
}

if(!in_array($q, array('반품','교환'))) {
	alert("제대된 접근이 아닙니다.");
}

if(!($od['dan'] == 5 && is_null_time($od['user_date']))) {
    alert("{$q}신청하실 수 없는 상품입니다.");
}

if($q == '반품') {
	change_order_status_10($od['od_no'], $od['od_id'], $change_memo);
} else {
	change_order_status_12($od['od_no'], $od['od_id'], $change_memo);
}

alert("정상적으로 {$q}신청 되었습니다.", "replace");
?>