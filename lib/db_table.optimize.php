<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

// 실행일 비교
if(isset($default['de_optimize_date']) && $default['de_optimize_date'] >= TB_TIME_YMD)
    return;

// 실행일 기록
if(isset($default['de_optimize_date'])) {
    sql_query(" update shop_default set de_optimize_date = '".TB_TIME_YMD."' ");
}


// 설정일이 지난 장바구니 상품 삭제
if($default['de_cart_keep_term'] > 0) {
	//$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_cart_keep_term'] * 86400));
	//$sql = " delete from shop_cart where left(ct_time,10) < '$tmp_before_date' and ct_select='0' and od_id='' ";
	$sql = " delete from shop_cart where date_add(ct_time, INTERVAL 7 DAY)  <= now() and ct_select='0' and od_id='' ";
	sql_query($sql, FALSE);
}

// 설정일이 지난 찜상품 삭제
if($default['de_wish_keep_term'] > 0) {
	//$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_wish_keep_term'] * 86400));
	//$sql = " delete from shop_wish where left(wi_time,10) < '$tmp_before_date' ";
	$sql = " delete from shop_wish where date_add(wi_time, INTERVAL 7 DAY)  <= now() ";
	sql_query($sql, FALSE);
}


// 설정일이 지난 배송완료상품 구매확정
if($default['de_final_keep_term'] > 0) {
/*	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_final_keep_term'] * 86400));
	$sql = " update shop_order
				set user_ok = '1'
				  , user_date = '".TB_TIME_YMDHIS."'
			  where left(invoice_date,10) < '$tmp_before_date'
		        and user_ok = '0'
			    and dan = '5' ";
*/
	$sql = " update shop_order
			 set user_ok = '1'
			   , user_date = '".TB_TIME_YMDHIS."'
		   where date_add(invoice_date, INTERVAL 3 DAY)  <= now()
	         and user_ok = '0'
			 and dan = '5' ";
	sql_query($sql, FALSE);

	//20201029 구매확정 후 판매수수료부여
	$sql = "SELECT * FROM shop_order WHERE dan = '5' and user_ok = '1' and user_date like ".TB_TIME_YMD."%";

	$res = sql_query($sql, FALSE);

	for($i=0; $row=sql_fetch_array($res); $i++)
    {
		$od = get_order($row['od_no']);
		$gs = unserialize($od['od_goods']);
		insert_sale_pay($od['pt_id'], $od, $gs);
	}


}

/*
//20200221 도담골프 포인트 적립 부분 8일후 설정 배송시작후 7일후 8일째 되는날 구매 확정이 되므로
$day_eight = 8;

if($day_eight > 0) {
	//$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($day_eight * 86400));
	//$sql = "SELECT * FROM shop_order WHERE dan = '5' and pt_id = 'dodamgolf' and user_ok = '1' and left(invoice_date,10) = '$tmp_before_date'";
	$sql = "SELECT * FROM shop_order WHERE dan = '5' and pt_id = 'dodamgolf' and user_ok = '1' and  date_add(invoice_date, INTERVAL 8 DAY)  <= now() and user_ok_yn='N' ";

	$res = sql_query($sql, FALSE);
	for($i=0; $row=sql_fetch_array($res); $i++)
    {
		$memId = substr($row['mb_id'],3);
		$point = $row['sum_point'];

		if($point != 0 )
		{
			$kc = gen_keycode();
			$em = $point;
			$md = "plus";
			$mm = "[적립]도담골프 주문(".$row['od_id'].")에 의한 적립금 증가";

			dodam_point($memId, $kc, $em, $md, $mm);
			//도담 적립 포인트 적립 유뮤 체크  20200715 추가
		 $sql = " update shop_order
		 set user_ok_yn = 'Y'
			 where dan = '5' and pt_id = 'dodamgolf'
			 and user_ok = '1'
			 and  date_add(invoice_date, INTERVAL 8 DAY)  <= now()
			 and  od_id= '$row[od_id]'
			 and user_ok_yn ='N' ";

			sql_query($sql);
		}
	}
}
*/

// 설정일이 지난 미입금된 주문내역 자동취소
if($default['de_misu_keep_term'] > 0) {
/*	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_misu_keep_term'] * 86400));
	$sql = " select *
			   from shop_order
			  where left(od_time,10) < '$tmp_before_date'
				and dan = '1'
			  order by index_no ";
*/
	$sql = " select *
 		    from shop_order
	    	where date_add(od_time, INTERVAL 4 DAY)  <= now()
			and dan = '1'
	   		order by index_no ";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		change_order_status_6($row['od_no']);

		// 메모남김
		/* $sql = " update shop_order
					set shop_memo = CONCAT(shop_memo,\"\\n미입금 자동 주문취소 - ".TB_TIME_YMDHIS." (취소이유 : {$default['de_misu_keep_term']}일경과)\")
				  where od_no = '{$row['od_no']}' "; */

		$sql = " update shop_order
					set shop_memo = CONCAT(shop_memo,\"\\n미입금 자동 주문취소 - ".TB_TIME_YMDHIS." (취소이유 : 3일경과)\")
				  where od_no = '{$row['od_no']}' ";
		sql_query($sql);

        // (2021-03-04)
        order_change_update($row['od_id'], $row['od_no'], 'system', '입금대기', '취소완료' , '미입금 자동 주문 취소'  );
	}
}

// 실행일 기록
if(isset($default['de_optimize_date'])) {
    sql_query(" update shop_default set de_optimize_date = '".TB_TIME_YMD."' ");
}

unset($tmp_before_date);
?>
