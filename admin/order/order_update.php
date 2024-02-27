<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "결제완료")  // (2021-03-04)
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 1) continue;
		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;

        // (2021-03-04)
        order_change_update( $_POST['od_id'][$k] , 'all' , $member['id'], $gw_status[$od['dan']], $gw_status[2] , ''  );
		change_order_status_ipgum($od_id);
		icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 3);
	}
	//dreamline_order_sms_send($od_id, 1);
	//dabonem_order_sms_send($od_id, 2);
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
            // (2021-03-04)
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

        // (2021-03-04)
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

		//현대리바트 주문상태변경(가맹점ID,상품코드)
		$od_id =  $_POST['od_id2'][$k];
        $pt_id = $_POST['pt_id2'][$k];
		$item_cd = $_POST['item_cd2'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 3) continue;

        // (2021-03-04)
        order_change_update( $_POST['od_id'][$k] , $_POST['od_no'][$k] , $member['id'], $gw_status[$od['dan']], $gw_status[4] , ''  );
		change_order_status_4($od_no, $delivery, $delivery_no);

		$od_sms_baesong[$od['od_id']] = $od['cellphone'];

		//현대리바트_건by건_주문상태변경_start_20200407
		if($pt_id == 'golf'){

			
			   //$stotal = get_order_spay($od_id);
               if($od['use_point2'] > 0){

				   $proc_dm = TB_TIME_YHS;//14자리 년월일시분초
			       $order_no = base64_encode($od_id);
                   $order_no2 = jsonfy2($order_no);
		           $hdata = array(
                      'ORDER_NO' => $order_no2,
	    	          'MEDIA_CD' => 'MW',
			  	      'PROC_STS' => '104',
				      'PROC_DM' => $proc_dm,
				      'ITEM_CD' => $item_cd
			       );
			       $url = "https://gift.e-hyundai.com/hb2efront_new/pointMagamProc.do?".http_build_query($hdata);
	               //echo("url:".$url."<br>");
						 
                   $ch = curl_init();
		           curl_setopt($ch, CURLOPT_URL, $url);
		           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		           curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	               curl_setopt($ch, CURLOPT_HEADER, 0);

		           $res = curl_exec($ch);
		           $load_string = simplexml_load_string($res);
		           $ca_result = $load_string->return_code;
				  
			       //주문상태변경 로그 쌓기
                   $value['c_type'] = "301";//주문상태변경_배송중
                   $value['url'] = $url; 
                   $value['return_code'] = $ca_result; //응답코드
                   $value['call_date'] = TB_TIME_YMDHIS;
                   insert("hwelfare_log", $value);//DB에 insert하기

		  	       curl_close($ch);


               }//기본금 >0 close
    
	    	

		}//pt_id=golf close

	}

	foreach($od_sms_baesong as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 4);
		//dreamline_order_sms_send($od_id, 2);
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
   
        //현대리바트 주문상태변경(가맹점ID,상품코드)
		$od_id =  $_POST['od_id2'][$k];
        $pt_id	 = $_POST['pt_id2'][$k];
		$item_cd = $_POST['item_cd2'][$k];


		$od = get_order($od_no);
		if($od['dan'] != 4) continue;

        // (2021-03-04)
        order_change_update( $_POST['od_id'][$k] , $_POST['od_no'][$k] , $member['id'], $gw_status[$od['dan']], $gw_status[5] , ''  );
		change_order_status_5($od_no, $delivery, $delivery_no);

		$od_sms_delivered[$od['od_id']] = $od['cellphone'];


		//현대리바트 주문상태변경호출(배송완료)
		//***아래의 로직을 자동배송완료 처리하는쪽에 추가 필요
		if($pt_id == 'golf'){

			   if($od['use_point2'] > 0){
			        $proc_dm = TB_TIME_YHS;//14자리 년월일시분초
			        $order_no = base64_encode($od_id);
                    $order_no2 = jsonfy2($order_no);
		            $hdata = array(
                         'ORDER_NO' => $order_no2,
	    	             'MEDIA_CD' => 'MW',
			  	         'PROC_STS' => '105',
				         'PROC_DM' => $proc_dm,
				         'ITEM_CD' => $item_cd
			        );
			        $url = "https://gift.e-hyundai.com/hb2efront_new/pointMagamProc.do?".http_build_query($hdata);
	                //echo("url:".$url."<br>");
						 
                    $ch = curl_init();
		            curl_setopt($ch, CURLOPT_URL, $url);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		            curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                curl_setopt($ch, CURLOPT_HEADER, 0);

		            $res = curl_exec($ch);
		            $load_string = simplexml_load_string($res);
		            $ca_result = $load_string->return_code;
				  
			        //주문상태변경 로그 쌓기
                    $value['c_type'] = "302";//주문상태변경_배송완료
                    $value['url'] = $url; 
                    $value['return_code'] = $ca_result; //응답코드
                    $value['call_date'] = TB_TIME_YMDHIS;
                    insert("hwelfare_log", $value);//DB에 insert하기

		  	        curl_close($ch);

			   } //포인트사용액 >0 close

		}
     	//현대리바트 주문상태변경호출(배송완료)_end_20190829
	}

	foreach($od_sms_delivered as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 6);
	}
}
else if($_POST['act_button'] == "구매확정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_status_final($od_no);
	}
}
else if($_POST['act_button'] == "구매확정취소")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_status_final_cancel($od_no);
	}
}
else if($_POST['act_button'] == "선택삭제")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if(!in_array($od['dan'], array(1,6)))
			alert('입금대기, 주문취소 상태의 상품만 삭제 가능합니다.');

		$sql = " select od_no from shop_order where od_id = '$od_id' order by index_no ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			order_delete($row['od_no'], $od_id); // 주문서 삭제
		}
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

        // (2021-03-04)
        order_change_update($_POST['od_id'][$k], $_POST['od_no'][$k], $member['id'], $gw_status[10], $gw_status[5] , '반품신청철회'  );
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

        // (2021-03-04)
        $dan_row = get_order( $_POST['od_no'][$k] ,"dan");
        order_change_update($_POST['od_id'][$k], $_POST['od_no'][$k], $member['id'], $gw_status[$dan_row['dan']], $gw_status[11] , ''  );
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

        // (2021-03-04)
        order_change_update($_POST['od_id'][$k], $_POST['od_no'][$k], $member['id'], $gw_status[12], $gw_status[5] , '교환신청철회'  );
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
     
        // (2021-03-04)
        $dan_row = get_order( $_POST['od_no'][$k] ,"dan");
        order_change_update($_POST['od_id'][$k], $_POST['od_no'][$k], $member['id'], $gw_status[$dan_row['dan']], $gw_status[13] , ''  );
		change_order_status_13($od_no, $delivery, $delivery_no);
	}
} else {
	alert();
}

goto_url(TB_ADMIN_URL."/order.php?$q1&page=$page");
?>
