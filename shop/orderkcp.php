<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);




//$stotal = get_order_spay($od_id); // 총계
//20200525 모든 토탈값이 더해지는 오류 발생 오류 수정
$stotal = get_order_spay_nosum($od_id); // 총계
$tot_price = get_session('tot_price'); // 결제금액

// 20200109 도담골프 멤버별 포인트 처리
switch(get_session('ss_mb_gd'))
{
	//브론즈
	case '1':
	$stotal['point'] = 0;
	break;
	//실버
	case '3':
	$stotal['point'] = floor($stotal['useprice'] * 0.01);
	break;
	//골드
	case '4':
	$stotal['point'] = floor($stotal['useprice'] * 0.03);
	break;
	//VIP
	case '5':
	$stotal['point'] = floor($stotal['useprice'] * 0.05);
	break;
	//임직원
	case '2':
	$stotal['point'] = floor($stotal['useprice'] * 0.05);
	break;
	//센터장
	case '6':
	$stotal['point'] = floor($stotal['useprice'] * 0.05);
	break;
}

$order_action_url = TB_HTTPS_SHOP_URL.'/orderformresult.php';
include_once(TB_THEME_PATH.'/orderkcp.skin.php');

include_once("./_tail.php");
?>