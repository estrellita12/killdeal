<?php
include_once("./_common.php");

check_demo();

//if(!$code)
//	alert("코드번호가 넘어오지 않았습니다.");

// 날짜선택(datepicker)
$sql_seah = "";
if($fr_date && $to_date)
    $sql_seah = "and left(od_time,10) between '$fr_date' and '$to_date' ";
else if(!$fr_date && $to_date)
    $sql_seah = "and left(od_time,10) <= '$to_date' ";
else if($fr_date && !$to_date)
    $sql_seah = "and left(od_time,10) >= '$fr_date' ";

$date_msg = "";
if(!$fr_date && !$to_date){
    $date_msg = "전체";
}else{
    $date_msg = $fr_date.' ~ '.$to_date;
}

$pt_sql = "select group_concat(mb_id) as pt_list from shop_partner";
$pt_res = sql_query($pt_sql);
$pt_row = sql_fetch_array($pt_res);
$pt_list = explode(",",$pt_row['pt_list']);

$sql = "select '$date_msg' as date_msg,pt_id,count(distinct od_id) as od_cnt,sum(goods_price+baesong_price) as buy_price,sum(sum_qty) as qty_cnt from shop_order where dan in ('2','3','4','5','8','12','13') $sql_seah group by pt_id order by buy_price desc";

$result = sql_query($sql);

$cnt = @sql_num_rows($result);

if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_daily_excel.sub.php');
?>
