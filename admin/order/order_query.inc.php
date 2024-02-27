<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sfl && $stx){
    if($sfl =="pt_name"){
        //쿠팡 이라고 검색하면 코드로 변환
        $name =	trans_member_name($stx);				
        $sql_search .= " and pt_id = '$name' ";
    }else{
        $sql_search .= " and $sfl like '%$stx%' ";
    }
}

if($od_settle_case)
    $sql_search .= " and paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
    $sql_search .= " and dan = '$od_status' ";

//-------------------------------------------
$where = array();
$dan_array = array();

if(is_numeric($od_status0))     $sql_search .= " and  dan NOT IN ('0','1','2','3','4') ";
if(is_numeric($od_status_2))    array_push($dan_array,2);
if(is_numeric($od_status_3))    array_push($dan_array,3);
if(is_numeric($od_status_4))    array_push($dan_array,4);
if(is_numeric($od_status_5))    array_push($dan_array,5);
if(is_numeric($od_status_6))    array_push($dan_array,6);
if(is_numeric($od_status_7))    array_push($dan_array,7);
if(is_numeric($od_status_8))    array_push($dan_array,8);
if(is_numeric($od_status_9))    array_push($dan_array,9);
if(is_numeric($od_status_10))    array_push($dan_array,10);
if(is_numeric($od_status_11))    array_push($dan_array,11);
if(is_numeric($od_status_12))    array_push($dan_array,12);
if(is_numeric($od_status_13))    array_push($dan_array,13);
if($dan_array){
    foreach($dan_array as $val){
        $valEl .="'{$val}',";
    }
    $valEl = rtrim($valEl, ',');
    $sql_search  .= " and dan IN ({$valEl})";
}

if($calculate_yn){
    $sql_search .= " and calculate = '$calculate_yn' ";
}
//-----------------------------------------------------------
if(is_numeric($od_final))
    $sql_search .= " and  user_ok = '$od_final' ";

if($od_taxbill)
    $sql_search .= " and taxbill_yes = 'Y' ";

if($od_taxsave)
    $sql_search .= " and taxsave_yes IN ('Y','S') ";

if($od_memo)
    $sql_search .= " and memo <> '' ";

if($od_shop_memo)
    $sql_search .= " and shop_memo <> '' ";

if($od_receipt_point)
    $sql_search .= " and (use_point != 0 or use_point2 !=0) ";

if($od_coupon)
    $sql_search .= " and coupon_price != 0 ";

if($od_escrow)
    $sql_search .= " and od_escrow = 1 ";

if($q_pt_id)
    $sql_search .= " and pt_id = '$q_pt_id' ";

if($fr_date && $to_date)
    $sql_search .= " and left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
    $sql_search .= " and left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
    $sql_search .= " and left({$sel_field},10) between '$to_date' and '$to_date' ";
?>
