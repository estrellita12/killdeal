<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

// *일일 매출 통계페이지 (daliy2)*
// daliy2에서 받아온 결과값을 도출하는 쿼리

// 쿼리스트링
$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common2 = " from shop_partner ";
$where = array();
$dan_array = array();


if($sfl){
	if($sfl !== ''){
		$where[] = " pt_id = '$sfl' ";
	} else {
    $where[] = "";
  }
}

//날짜선택
if($fr_date && $to_date)
$seah[] = "where 1 = 1 and left(od_time,10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
$seah[] = "where 1 = 1 and left(od_time,10) between '$fr_date' and '$to_date' ";
else if(!$fr_date && $to_date)
$seah[] = "where 1 = 1 and left(od_time,10) between '$fr_date' and '$to_date' ";
else if(!$fr_date && !$to_date)
$seah[] = "where 1 = 1 and left(od_time,10) between 0 and 0 "; 

if($seah) {
	$sql_seah = implode(' and ', $seah);
}

if($where) {
		$sql_search = ' where '.implode(' and ', $where);
}

// 일일별 매출의 합을 가져오는 쿼리

$sql = " select A1.od_time as od_time, ifnull(sum(A2.goods_price + A2.baesong_price),0) as goods_price_sum ,ifnull(A2.pt_id,'$sfl') as pt_id ,ifnull(sum(A2.sum_qty),0) as sum_qty
from (SELECT a.od_time FROM ( 
	SELECT CURDATE( ) - INTERVAL( a.a + ( 10 * b.a ) + ( 100 * c.a ) ) DAY AS od_time 
	FROM ( 
		SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
				UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS a
						CROSS JOIN 
				( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
										UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS b
		CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
					UNION ALL SELECT  6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS c ) AS a 
		$sql_seah
						ORDER BY a.od_time desc ) as A1
left outer join (
					select * from shop_order where pt_id = '$sfl' and dan IN ('2','3','4','5','8','12','13')                          
											) as A2
		on A1.od_time = left(A2.od_time,10)
		group by A1.od_time ";

$result = sql_query($sql);
$total_count = sql_num_rows($result); // 총 주문수량


$sql2 = " select mb_id {$sql_common2} ";
$result2 = sql_query($sql2);
$total_count2 = sql_num_rows($result2);


include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>