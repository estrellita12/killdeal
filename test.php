접속 테스트 화면입니다!!
<?php
error_reporting(E_ALL);

    ini_set("display_errors", 1);
	include_once('./common.php');
	//include_once(TB_LIB_PATH.'/dreamline.sms.lib.php');

	/*	
	function dreamline_order_sms_send($od_id, $fld)
	{

	$od = sms_get_order($od_id); // 주문정보
	
	for($i=0; $row=sql_fetch_array($od); $i++) {
		$name = $row['name'];
		$od_id = $row['od_id'];
		$use_price += $row['use_price'];
		$delivery_no = $row['delivery_no'];
		$b_cellphone = $row['b_cellphone'];
	}

	$recv_number = str_replace('-', '', $b_cellphone);
	$send_number = "07049385585";
	$sm = json_decode(file_get_contents(TB_LIB_PATH.'/sms_form.json'), true); //sms json 양식 불러오기
	// SMS BEGIN --------------------------------------------------------

	$sms_content = $sm["cf_cont{$fld}"];

	$sms_content = rpc($sms_content, "{이름}", $name);
	$sms_content = rpc($sms_content, "{주문번호}", $od_id);
	$sms_content = rpc($sms_content, "{금액}", $use_price);
	$sms_content = rpc($sms_content, "{송장번호}", $delivery_no);

	
	// SMS 전송
	include_once(TB_LIB_PATH.'/dreamline.sms.lib.php');

	$SMS = new DL_SMS; // SMS 연결
	
	$SMS->Check_Data($recv_number, $send_number, $send_number, $sms_content);

	$SMS->Send();
	
	// SMS END   --------------------------------------------------------
	}	
	
	function sms_get_order($od_id)
	{
		return sql_query(" select * from shop_order where od_id='$od_id'");
	}
	dreamline_order_sms_send('20011716402095', 3);
	*/
	
	/*
	$day_eight = 8;

if($day_eight > 0) {
	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($day_eight * 86400));
	$sql = "SELECT * FROM shop_order WHERE dan = '5' and pt_id = 'dodamgolf' and user_ok = '1' and left(invoice_date,10) = '2020-02-17'";
	echo $sql;
	$res = sql_query($sql, FALSE);
	for($i=0; $row=sql_fetch_array($res); $i++)
    {
		$memId = substr($row['mb_id'],3);
		$point = $row['sum_point'];
		echo $memId;
		echo $point;
		if($point != 0 )
		{
			$kc = gen_keycode();
			$em = $point;
			$md = "plus";
			$mm = "[적립]도담골프 주문(".$row['$od_id'].")에 의한 적립금 증가";
			
			echo $mm;
			dodam_point($memId, $kc, $em, $md, $mm);
		}
	}		
}
*/

if(true) {

    $tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_final_keep_term'] * 86400));
    /*
    $sql = " update shop_order
				set user_ok = '1'
				  , user_date = '".TB_TIME_YMDHIS."'
			  where left(invoice_date,10) < '$tmp_before_date'
		        and user_ok = '0'
			    and dan = '5' ";
    sql_query($sql, FALSE);
    */
    //20200325 구매확정 후 포인트 적립
    $sql = "SELECT * FROM shop_order WHERE dan = '5' AND delivery IS NOT NULL and user_ok = '1' and left(invoice_date,10) <= '$tmp_before_date'";
    echo $sql;

    $res = sql_query($sql, FALSE);

    for($i=0; $row=sql_fetch_array($res); $i++)
    {
        $od = get_order($row['od_no']);
        $gs = unserialize($od['od_goods']);
        echo "pt_id : ".$od['pt_id'];
        echo " od : ".$od;
        echo " gs : ".$gs;
        insert_sale_pay($od['pt_id'], $od, $gs);
        echo $i+1;
        echo "</br>";
    }
    echo "done";
}
?>

