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
$pt_list = get_partner_list();
$pt_del = array("dukhomall","dodamgolf","honggolf","srixon","moonsgolf");
foreach($pt_list as $k => $v){
    foreach($pt_del as $v2){
        if($v == $v2){
            unset($pt_list[$k]);
        }
    }
}


$sql = "select date_msg ";
foreach($pt_list as $pt){
    $sql .= ", ifnull( MAX( case when pt_id='{$pt}' then buy_price end) ,0 ) as {$pt}_buy_price ";
    $sql .= ", ifnull( MAX( case when pt_id='{$pt}' then qty_cnt end) ,0 ) as {$pt}_qty_cnt ";
    $sql .= ", ifnull( MAX( case when pt_id='{$pt}' then od_cnt end) ,0 ) as {$pt}_od_cnt ";
}
$sql .= "from ( select pt_id, date_format(od_time,'%Y-%m-%d') as date_msg, count(distinct od_id) as od_cnt, sum(goods_price+baesong_price) as buy_price, sum(sum_qty) as qty_cnt from shop_order where dan in ('2','3','4','5','8','12','13') $sql_seah  group by date_msg, pt_id order by date_msg ) as res ";
$sql .= " group by date_msg;";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);

if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_daily2_excel.sub.php');
?>
