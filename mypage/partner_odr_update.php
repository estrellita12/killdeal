<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "결제완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 1) continue;
		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;

        // 2021-06-29
        order_change_update( $_POST['od_id'][$k] , 'all' , $member['id'], $gw_status[$od['dan']], $gw_status[2] , ''  );
		change_order_status_ipgum($od_id);
		icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 3);
	}
	dreamline_order_sms_send($od_id, 1);
}
else if($_POST['act_button'] == "주문취소")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 1) continue;
		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;

		$sql = " select od_no from shop_order where od_id = '$od_id' order by index_no ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
            // 2021-06-29
            order_change_update( $_POST['od_id'][$k] , $row['od_no'] , $member['id'], $gw_status[$od['dan']], $gw_status[6] , ''  );
			change_order_status_6($row['od_no']);
		}

		icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 5);
	}
}
else if($_POST['act_button'] == "배송준비")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 2) continue;

        // 2021-06-29
         order_change_update( $_POST['od_id'][$k] , $_POST['od_no'][$k] , $member['id'], $gw_status[$od['dan']], $gw_status[3] , ''  );
		change_order_status_3($od_no);
	}
}
else if($_POST['act_button'] == "배송중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 3) continue;

        // 2021-06-29
        order_change_update( $_POST['od_id'][$k] , $_POST['od_no'][$k] , $member['id'], $gw_status[$od['dan']], $gw_status[4] , ''  );
		change_order_status_4($od_no, $delivery, $delivery_no);

		$od_sms_baesong[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_baesong as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 4);
        //dabonem_order_sms_send($od_id, 3);
	}
}
else if($_POST['act_button'] == "배송완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 4) continue;

        // 2021-06-29
        order_change_update( $_POST['od_id'][$k] , $_POST['od_no'][$k] , $member['id'], $gw_status[$od['dan']], $gw_status[5] , ''  );
		change_order_status_5($od_no, $delivery, $delivery_no);

		$od_sms_delivered[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_delivered as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 6);
	}
}
else if($_POST['act_button'] == "운송장번호수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set delivery	= '{$_POST['delivery'][$k]}'
					  , delivery_no = '{$_POST['delivery_no'][$k]}'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "반품신청철회")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_order_return_cancel($od_no);
	}
}
else if($_POST['act_button'] == "반품중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		change_order_status_11($od_no, $delivery, $delivery_no);
	}
}
else if($_POST['act_button'] == "교환신청철회")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_order_change_cancel($od_no);
	}
}
else if($_POST['act_button'] == "교환중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		change_order_status_13($od_no, $delivery, $delivery_no);
	}
} else {
	alert();
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>
