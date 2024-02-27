<?php
if(!defined('_TUBEWEB_')) exit;

// *최종처리일 업데이트 쿼리*
// 주문상태 dan = 0(주문하지않은상태)
// 해당쿼리는 최종처리일을 구함
// 최종처리일은 shop_order table에서 주문상태 변경시 바뀌는 필드값(입금일,배송일,환불일 등)을 비교
// 비교 후 가장 나중에 처리된 날짜를 최종처리일에 업데이트
$sql_1 =  "select * from shop_order where dan!=0";
$res = sql_query($sql_1);


while($row = sql_fetch_array($res)) {	

    // 첫번째 검사 입금일시 < 배송 배송일시 
    if($row['receipt_time'] < $row['delivery_date'] ){
        $time_zone = $row['delivery_date'];

    }else{
        $time_zone = $row['receipt_time']; 
    }		
    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['invoice_date'] ){
        $time_zone = $row['invoice_date'];
    }else{
        $time_zone = $time_zone;
    }


    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['return_date'] ){
        $time_zone = $row['return_date'];
    }else{
        $time_zone = $time_zone;
    }


    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['change_date'] ){
        $time_zone = $row['change_date'];
    }else{
        $time_zone = $time_zone;
    }


    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['refund_date'] ){
        $time_zone = $row['refund_date'];
    }else{
        $time_zone = $time_zone;
    }


    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['return_date2'] ){
        $time_zone = $row['return_date2'];
    }else{
        $time_zone = $time_zone;
    }


    // (입금일시 , 배송일시 ) < 배송완료
    if( $time_zone < $row['change_date2'] ){
        $time_zone = $row['change_date2'];
    }else{
        $time_zone = $time_zone;
    }

    if( $time_zone < $row['cancel_date'] ){
        $time_zone = $row['cancel_date'];
    }else{
        $time_zone = $time_zone;
    }


    if($time_zone > $row['rcent_time']) {
        $sql = "update shop_order set rcent_time='$time_zone' where od_id='$row[od_id]' and od_no='$row[od_no]' ";
        sql_query($sql);
    }		
}

?>
