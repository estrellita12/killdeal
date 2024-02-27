<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/orderinquiryview.php?od_id='.$od_id);
}

if(!$is_member) {
    if(get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 주문서 조회가 불가합니다.\\n\\n주문조회 화면을 통하여 조회하시기 바랍니다.", TB_URL);
}

$od = sql_fetch("select * from shop_order where od_id = '$od_id'");
if(!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 주문서가 없습니다.");
}

$tb['title'] = '주문상세내역';
include_once("./_head.php");

// LG 현금영수증 JS
if($od['od_pg'] == 'lg') {
    if($default['de_card_test']) {
    echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    }
}

$stotal = get_order_spay($od_id); // 총계

// 결제정보처리
$app_no_subj = '';
$disp_bank = true;
$disp_receipt = false;
$easy_pay_name = '';
if($od['paymethod'] == '신용카드' || $od['paymethod'] == 'KAKAOPAY') {
	$app_no_subj = '승인번호';
	$app_no = $od['od_app_no'];
	$disp_bank = false;
	$disp_receipt = true;
} else if($od['paymethod'] == '간편결제') {
	$app_no_subj = '승인번호';
	$app_no = $od['od_app_no'];
	$disp_bank = false;
	switch($od['od_pg']) {
		case 'lg':
			$easy_pay_name = 'PAYNOW';
			break;
		case 'inicis':
			$easy_pay_name = 'KPAY';
			break;
		case 'kcp':
			$easy_pay_name = 'PAYCO';
			break;
		default:
			break;
	}
} else if($od['paymethod'] == '휴대폰') {
	$app_no_subj = '휴대폰번호';
	$app_no = $od['bank'];
	$disp_bank = false;
	$disp_receipt = true;
} else if($od['paymethod'] == '가상계좌' || $od['paymethod'] == '계좌이체') {
	$app_no_subj = '거래번호';
	$app_no = $od['od_tno'];
}

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

//현대리바트_start(이페이지는 현대리바트에서만 호출되는 페이지: pt_id=golf 무의미함.)
//***proc_code는 부분취소가 될수 있으므로 for문안에 위치해야 할듯...
if($_GET['way'] == "cancel") { 

     $proc_code = base64_encode("300");//포인트 취소
	 //마감호출시 사용되는 proc_code적용
}else{ //사용

     $proc_code = base64_encode("200");//포인트 사용

}

   $mem_no = base64_encode(get_session("mem_no"));
   $mem_no2 = jsonfy2($mem_no);
  
   $shopevent_no = base64_encode(get_session("shopevent_no"));
   $shopevent_no2 = jsonfy2($shopevent_no);
   $shop_no = "6831A9DA1B37FA0E34799E99601BB6FE";//**추후 세션에 저장했다가 불러오는 방식 교체 필요
  
   $proc_code2 = jsonfy2($proc_code);

   $mem_nm = iconv("UTF-8","EUC-kr",get_session("mem_nm"));//charset변환 
   $mem_nm2 = base64_encode($mem_nm);
   $mem_nm3 = jsonfy2($mem_nm2);

   $u_point = base64_encode($stotal['usepoint2']);//*******일단 취소는 일괄취소 방식으로 작업(반드시 부분취소도 반영되어야 할것임.)
   $u_point2 = jsonfy2($u_point);

   $order_no = base64_encode($od_id); //$od_id
   $order_no2 = jsonfy2($order_no);

   $tax_gb = base64_encode("1");//과세여부 gs_notax
   $tax_gb2 = jsonfy2($tax_gb);

   //현대리바트 마감 호출 start
   

   //현대리바트 마감 호출 end


   include_once(TB_THEME_PATH.'/orderinquiryview2.skin.php');

 

   include_once("./_tail.php");
?>