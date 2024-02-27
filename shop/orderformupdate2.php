<?php

include_once("./_common.php");
include_once(TB_LIB_PATH.'/mailer.lib.php');

// 장바구니 상품 재고 검사
$error = "";
$sql = " select * from shop_cart where index_no IN ({$_POST['ss_cart_id']}) and ct_select = '0' ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {
    // 상품에 대한 현재고수량
    if($row['io_id']) {
        $it_stock_qty = (int)get_option_stock_qty($row['gs_id'], $row['io_id'], $row['io_type']);
    } else {
        $it_stock_qty = (int)get_it_stock_qty($row['gs_id']);
    }
    // 장바구니 수량이 재고수량보다 많다면 오류
    if($row['ct_qty'] > $it_stock_qty)
        $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
}

if($i == 0)
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', TB_SHOP_URL.'/cart.php');

if($error != "") {
    $error .= "다른 고객님께서 {$name}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";
    alert($error);
}

// 주문번호를 얻는다.
$od_id = get_session('ss_order_id');

if( !$od_id ){
    alert("주문번호가 없습니다.", TB_URL);
}

$dan = 0;
if($_POST['paymethod'] == '무통장')
	$dan = 1; // 주문접수 단계로 적용

if((int)$_POST['tot_price'] == 0) { // 총 결제금액이 0 이면
	$dan = 2; // 입금확인 단계로 적용

	// 포인트로 전액 결제시는 포인트결제로 값을 바꾼다.
	if($_POST['paymethod'] != '포인트' && (int)$_POST['org_price'] == (int)$_POST['use_point']) {
		$_POST['paymethod'] = '포인트';
	}
	//기본금->포인트
	if($_POST['paymethod'] != '포인트' && (int)$_POST['org_price'] == (int)$_POST['use_point2']) {
		$_POST['paymethod'] = '포인트';//추후 수정이 필요할듯
	}
	//1.기본금 전체결제라면 기본금 ,복지카드는 다른 kcp상점으로 분기한다.
}

set_session('tot_price', (int)$_POST['tot_price_']);//23,200 ->23이 저장_20190925 => 20191219 _ 수정 포인트 반영 결제 비요ㅕ
set_session('use_point', (int)$_POST['use_point']);
set_session('use_point2', (int)$_POST['use_point2']);//기본금

$baesong_price	= explode("|",$_POST['baesong_price']); // 상품별 배송비
$coupon_price	= explode("|",$_POST['coupon_price']); // 상품별 할인가
$coupon_lo_id	= explode("|",$_POST['coupon_lo_id']); // 상품별 쿠폰 shop_coupon_log (필드:lo_id)
$coupon_cp_id	= explode("|",$_POST['coupon_cp_id']); // 상품별 쿠폰 shop_coupon_log (필드:cp_id)
$ss_cart_id		= explode(",",$_POST['ss_cart_id']); // 장바구니 idx

$use_point = (int)$_POST['use_point']; // 포인트결제
$use_point2 = (int)$_POST['use_point2']; // 기본금결제(현대리바트)
$baesong_price2 = (int)$_POST['baesong_price2']; // 추가배송비

if($is_member)
    $od_pwd = $member['passwd'];
else
    $od_pwd = get_encrypt_string($_POST['od_pwd']);

for($i=0; $i<count($gs_id); $i++) {

	// 주문 일련번호
	$od_no = $cart_id[$i];

	if($i==0) {
		$t_point = $use_point;  // 포인트 결제금액
		for($k=0; $k<count($gs_id); $k++) {
			if($k == 0 && $baesong_price2 > 0) {
				$baesong_price[$k] = (int)$baesong_price[$k] + $baesong_price2; // 배송비 + 추가배송비
			}

			$t_baesong = (int)$baesong_price[$k]; // 배송비 결제금액
			$t_price = (int)$gs_price[$k] - (int)$coupon_price[$k]; // 상품 판매가 - 쿠폰 할인가
			if($t_point > 0) {
				if(($t_price+$t_baesong) >= $t_point) {

					$t_point2 =round(((int)$gs_price[$k] * $t_point)/((int)get_session('tot_price') + $t_point));//상품금액비율 포인트 쪼개기
					$i_use_price[$k] = ($t_price+$t_baesong)-$t_point2;
					$i_use_point[$k] = $t_point2;
					//$t_point = 0;

				} else if(($t_price+$t_baesong) < $t_point) {
					$i_use_price[$k] = 0;
					$i_use_point[$k] = $t_price+$t_baesong;
					$t_point = $t_point-($t_price+$t_baesong);
				}

			} else {
				$t_point = 0;
				$i_use_point[$k] = 0;
				$i_use_price[$k] = $t_price+$t_baesong;
			}
			

			
            			

		}//for close
	} else {
		$baesong_price2 = 0;
	}

	$sql = "insert into shop_order
			   set od_id				= '{$od_id}'
			     , od_no				= '{$od_no}'
				 , mb_id				= '{$member['id']}'
				 , name					= '{$_POST['name']}'
				 , cellphone			= '{$_POST['cellphone']}'
				 , telephone			= '{$_POST['telephone']}'
				 , email				= '{$_POST['email']}'
				 , zip					= '{$_POST['zip']}'
				 , addr1				= '{$_POST['addr1']}'
				 , addr2				= '{$_POST['addr2']}'
				 , addr3				= '{$_POST['addr3']}'
				 , addr_jibeon			= '{$_POST['addr_jibeon']}'
				 , b_name				= '{$_POST['b_name']}'
				 , b_cellphone			= '{$_POST['b_cellphone']}'
				 , b_telephone			= '{$_POST['b_telephone']}'
				 , b_zip				= '{$_POST['b_zip']}'
				 , b_addr1				= '{$_POST['b_addr1']}'
				 , b_addr2				= '{$_POST['b_addr2']}'
				 , b_addr3				= '{$_POST['b_addr3']}'
				 , b_addr_jibeon		= '{$_POST['b_addr_jibeon']}'
				 , gs_id				= '{$gs_id[$i]}'
				 , gs_notax				= '{$gs_notax[$i]}'
				 , seller_id			= '{$seller_id[$i]}'
				 , goods_price			= '{$gs_price[$i]}'
				 , supply_price			= '{$supply_price[$i]}'
				 , sum_point			= '{$sum_point[$i]}'
				 , sum_qty				= '{$sum_qty[$i]}'
				 , coupon_price			= '{$coupon_price[$i]}'
				 , coupon_lo_id			= '{$coupon_lo_id[$i]}'
				 , coupon_cp_id			= '{$coupon_cp_id[$i]}'
				 , use_price			= '{$i_use_price[$i]}'
				 , use_point			= '{$i_use_point[$i]}'
				 , use_point2			= '{$i_use_point2[$i]}'
				 , baesong_price		= '{$baesong_price[$i]}'
				 , baesong_price2		= '{$baesong_price2}'
				 , paymethod			= '{$_POST['paymethod']}'
				 , bank					= '{$_POST['bank']}'
				 , deposit_name			= '{$_POST['deposit_name']}'
				 , dan					= '{$dan}'
				 , memo					= '{$_POST['memo']}'
				 , taxsave_yes			= '{$_POST['taxsave_yes']}'
				 , taxbill_yes			= '{$_POST['taxbill_yes']}'
				 , company_saupja_no	= '{$_POST['company_saupja_no']}'
				 , company_name			= '{$_POST['company_name']}'
				 , company_owner		= '{$_POST['company_owner']}'
				 , company_addr			= '{$_POST['company_addr']}'
				 , company_item			= '{$_POST['company_item']}'
				 , company_service		= '{$_POST['company_service']}'
				 , tax_hp				= '{$_POST['tax_hp']}'
				 , tax_saupja_no		= '{$_POST['tax_saupja_no']}'
				 , od_time				= '".TB_TIME_YMDHIS."'
				 , od_pwd				= '{$od_pwd}'
				 , od_ip				= '{$_SERVER['REMOTE_ADDR']}'
				 , od_test				= '{$default['de_card_test']}'
				 , od_tax_flag			= '{$default['de_tax_flag_use']}'
				 , od_settle_pid		= '{$pt_settle_pid}'
				 , pt_id				= '{$_POST['pt_id']}'
				 , shop_id				= '{$_POST['shop_id']}' ";
	sql_query($sql, FALSE);
	$insert_id = sql_insert_id();


	

	// 고객이 주문/배송조회를 위해 보관해 둔다.
	save_goods_data($gs_id[$i], $insert_id, $od_id);

	// 쿠폰 사용함으로 변경 (무통장, 포인트결제일 경우만)
	if($coupon_lo_id[$i] && $is_member && in_array($_POST['paymethod'],array('무통장','포인트'))) {
		sql_query("update shop_coupon_log set mb_use='1',od_no='$od_no',cp_udate='".TB_TIME_YMDHIS."' where lo_id='$coupon_lo_id[$i]'");
	}

	// 쿠폰 주문건수 증가
	if($coupon_cp_id[$i] && $is_member) {
		sql_query("update shop_coupon set cp_odr_cnt=(cp_odr_cnt + 1) where cp_id='$coupon_cp_id[$i]'");
	}

	// 주문완료 후 쿠폰발행
	$gs = get_goods($gs_id[$i], 'use_aff');
	if(!$gs['use_aff'] && $config['coupon_yes'] && $is_member) {
		$cp_used = is_used_coupon('1', $gs_id[$i]);
		if($cp_used) {
			$cp_id = explode(",", $cp_used);
			for($g=0; $g<count($cp_id); $g++) {
				if($cp_id[$g]) {
					$cp = sql_fetch("select * from shop_coupon where cp_id='$cp_id[$g]'");
					insert_used_coupon($member['id'], $member['name'], $cp);
				}
			}
		}
	}
}

$od_pg = $default['de_pg_service'];
if($_POST['paymethod'] == 'KAKAOPAY')
    $od_pg = 'KAKAOPAY';

// 복합과세 금액
if($default['de_tax_flag_use']) {
	$info = comm_tax_flag($od_id);
	$od_tax_mny  = $info['comm_tax_mny'];
	$od_vat_mny  = $info['comm_vat_mny'];
	$od_free_mny = $info['comm_free_mny'];
} else {
	$od_tax_mny  = round($_POST['tot_price'] / 1.1);
	$od_vat_mny  = $_POST['tot_price'] - $od_tax_mny;
	$od_free_mny = 0;
}

// 주문서에 UPDATE
$sql = " update shop_order
            set od_pg		 = '$od_pg'
			  , od_tax_mny	 = '$od_tax_mny'
			  , od_vat_mny	 = '$od_vat_mny'
			  , od_free_mny	 = '$od_free_mny'
		  where od_id = '$od_id'";
sql_query($sql, false);

if(in_array($_POST['paymethod'],array('무통장','포인트','기본금'))) {
	$cart_select = " , ct_select = '1' ";
}

// 장바구니 주문완료 처리 (무통장, 포인트결제,기본금)
$sql = " update shop_cart set od_id = '$od_id' {$cart_select} where index_no IN ({$_POST['ss_cart_id']}) ";
sql_query($sql);

// 재고수량 감소
for($i=0; $i<count($ss_cart_id); $i++) {
	$ct = get_cart_id($ss_cart_id[$i]);

	if($ct['io_id']) { // 옵션 : 재고수량 감소
		$sql = " update shop_goods_option
					set io_stock_qty = io_stock_qty - '{$ct['ct_qty']}'
				  where io_id = '{$ct['io_id']}'
					and gs_id = '{$ct['gs_id']}'
					and io_type = '{$ct['io_type']}'
					and io_stock_qty <> '999999999' ";
		sql_query($sql, FALSE);
	} else { // 상품 : 재고수량 감소
		$sql = " update shop_goods
					set stock_qty = stock_qty - '{$ct['ct_qty']}'
				  where index_no = '{$ct['gs_id']}'
					and stock_mod = '1' ";
		sql_query($sql, FALSE);
	}
}

if(in_array($_POST['paymethod'],array('무통장','포인트'))) {
	// 회원이면서 포인트를 사용했다면 테이블에 사용을 추가
	if($is_member && $use_point) {
		insert_point($member['id'], (-1) * $use_point, "주문번호 $od_id 결제");
	}
    //현대복지포인트_추후 필요여부 판단후 적용할것...
	/*
	if($is_member && $use_point2) {
		insert_point($member['id'], (-1) * $use_point2, "주문번호 $od_id 결제");
	}
	*/

	// 쿠폰사용내역기록
	if($is_member) {
		$sql = "select * from shop_order where od_id='$od_id'";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			if($row['coupon_price']) {
				$sql = "update shop_coupon_log
						   set mb_use = '1',
							   od_no = '$row[od_no]',
							   cp_udate	= '".TB_TIME_YMDHIS."'
						 where lo_id = '$row[coupon_lo_id]' ";
				sql_query($sql);
			}
		}
	}

	$od = sql_fetch("select * from shop_order where od_id='$od_id'");

	// 주문완료 문자전송
	icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 2);

	// 무통장 입금 때 고객에게 계좌정보 보냄
	if($_POST['paymethod'] == '무통장' && (int)$_POST['tot_price'] > 0) {
		$sms_content = $od['name']."님의 입금계좌입니다.\n금액:".number_format($_POST['tot_price'])."원\n계좌:".$od['bank']."\n".$config['company_name'];
		icode_direct_sms_send($od['pt_id'], $od['cellphone'], $sms_content);
	}

	// 메일발송
	if($od['email']) {
		$subject1 = get_text($od['name'])."님 주문이 정상적으로 처리되었습니다.";
		$subject2 = get_text($od['name'])." 고객님께서 신규주문을 신청하셨습니다.";

		ob_start();
		include_once(TB_SHOP_PATH.'/orderformupdate_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		// 주문자에게 메일발송
		mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

		// 관리자에게 메일발송
		if($super['email'] != $od['email']) {
			mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
		}
	}
}

// 주문번호제거
set_session('ss_order_id', '');

// 장바구니 session 삭제
set_session('ss_cart_id', '');

// orderinquiryview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id.TB_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
set_session('ss_orderview_uid', $uid);


//**기본금으로만 결제시 호출되어야 한다.

if($_POST['paymethod'] == '포인트')
{
   if($pt_id == "golf")
   {
       $stotal = get_order_spay($od_id); // 총계

       if($stotal['usepoint2'] > 0) //기본금 사용액이 있니?
       {
           $mem_no = base64_encode(get_session("mem_no"));
           $mem_no2 = jsonfy2($mem_no);
  
           $shopevent_no = base64_encode(get_session("shopevent_no"));
           $shopevent_no2 = jsonfy2($shopevent_no);
           $shop_no = "6831A9DA1B37FA0E34799E99601BB6FE";//**추후 세션에 저장했다가 불러오는 방식 교체 필요
   
           $proc_code = base64_encode("200");//포인트 사용
           $proc_code2 = jsonfy2($proc_code);

           $mem_nm = iconv("UTF-8","EUC-kr",get_session("mem_nm"));//charset변환 
           $mem_nm2 = base64_encode($mem_nm);
           $mem_nm3 = jsonfy2($mem_nm2);

           $u_point = base64_encode($stotal['usepoint2']);
           $u_point2 = jsonfy2($u_point);

           $order_no = base64_encode($od_id); //$od_id
           $order_no2 = jsonfy2($order_no);
    
	       $hdata = array(
              'mem_id' => $mem_no2,
	    	  'shopevent_no' => $shopevent_no2,
			  'proc_code' => $proc_code2,
			  'chk_data' => $mem_nm3,
			  'point' => $u_point2,
			  'order_no' => $order_no2,
			  'media_cd' => 'MW'
	       );
	       $url = "https://giftdev.e-hyundai.com/hb2efront_new/pointOpenAPI.do?".http_build_query($hdata);
	       //echo("url:".$url."<br>");
		     
           $ch = curl_init();
	       curl_setopt($ch, CURLOPT_URL, $url);
	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	       curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	       curl_setopt($ch, CURLOPT_HEADER, 0);
           $res = curl_exec($ch);

           $load_string = simplexml_load_string($res);
		   $ma_result = $load_string->return_code;
           $value['c_type'] = "101";//포인트사용
           $value['url'] = $url; 
           $value['return_code'] = $ma_result; //응답코드
           $value['call_date'] = TB_TIME_YMDHIS;
           insert("hwelfare_log", $value);//DB에 insert하기



	       curl_close($ch);

       } //기본금 사용액 > 0 close

   } //pt_id = golf close

} //기본금 전액결제 close



if(in_array($_POST['paymethod'],array('무통장','포인트'))) {
	goto_url(TB_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
} else if($_POST['paymethod'] == '기본금') { //기본금->포인트로 수정_현재필요없음.
	goto_url(TB_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
	
}else if($_POST['paymethod'] == '복지카드') {
	//KCP 복지상점코드로 이동_20190803
	goto_url(TB_SHOP_URL.'/orderkcp2.php?od_id='.$od_id);
}  else if($_POST['paymethod'] == 'KAKAOPAY') {
	goto_url(TB_SHOP_URL.'/orderkakaopay.php?od_id='.$od_id);
} else {
	if($default['de_pg_service'] == 'kcp')
		//KCP 일반상점코드로 이동
		goto_url(TB_SHOP_URL.'/orderkcp.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'inicis')
		goto_url(TB_SHOP_URL.'/orderinicis.php?od_id='.$od_id);
	else if($default['de_pg_service'] == 'lg')
		goto_url(TB_SHOP_URL.'/orderlg.php?od_id='.$od_id);
}


?>

<html>
    <head>
        <title>주문정보 기록</title>
        <script>
            // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
            function noRefresh()
            {
                /* CTRL + N키 막음. */
                if ((event.keyCode == 78) && (event.ctrlKey == true))
                {
                    event.keyCode = 0;
                    return false;
                }
                /* F5 번키 막음. */
                if(event.keyCode == 116)
                {
                    event.keyCode = 0;
                    return false;
                }
            }

            document.onkeydown = noRefresh ;
        </script>
    </head>
</html>