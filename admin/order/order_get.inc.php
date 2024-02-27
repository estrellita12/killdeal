<?php
 if(!defined('_TUBEWEB_')) exit;

if($sel_field)        $qstr .= "&sel_field=$sel_field";
if($od_settle_case)   $qstr .= "&od_settle_case=".urlencode($od_settle_case);
if($od_status)        $qstr .= "&od_status=$od_status";
if($od_final)         $qstr .= "&od_final=$od_final";
if($od_taxbill)       $qstr .= "&od_taxbill=$od_taxbill";
if($od_taxsave)       $qstr .= "&od_taxsave=$od_taxsave";
if($od_memo)          $qstr .= "&od_memo=$od_memo";
if($od_shop_memo)     $qstr .= "&od_shop_memo=$od_shop_memo";
if($od_receipt_point) $qstr .= "&od_receipt_point=$od_receipt_point";
if($od_coupon)        $qstr .= "&od_coupon=$od_coupon";
if($od_escrow)         $qstr .= "&od_escrow=$od_escrow";
if($od_escrow)              $qstr .= "&q_pt_id=$q_pt_id";

if(isset($ptn_sel_field) && $ptn_sel_field)    $qstr .= "&ptn_sel_field=$ptn_sel_field";
if(isset($od_status_0) && is_numeric($od_status_0))      $qstr .= "&od_status_0=$od_status_0";
if(isset($od_status_2) && is_numeric($od_status_2))      $qstr .= "&od_status_2=$od_status_2";
if(isset($od_status_3) && is_numeric($od_status_3))      $qstr .= "&od_status_3=$od_status_3";
if(isset($od_status_4) && is_numeric($od_status_4))      $qstr .= "&od_status_4=$od_status_4";
if(isset($od_status_5) && is_numeric($od_status_5))      $qstr .= "&od_status_5=$od_status_5";
if(isset($od_status_6) && is_numeric($od_status_6))      $qstr .= "&od_status_6=$od_status_6";
if(isset($od_status_7) && is_numeric($od_status_7))      $qstr .= "&od_status_7=$od_status_7";
if(isset($od_status_8) && is_numeric($od_status_8))      $qstr .= "&od_status_8=$od_status_8";
if(isset($od_status_9) && is_numeric($od_status_9))      $qstr .= "&od_status_9=$od_status_9";
if(isset($od_status_10) && is_numeric($od_status_10))        $qstr .= "&od_status_10=$od_status_10";
if(isset($od_status_11) && is_numeric($od_status_11))        $qstr .= "&od_status_11=$od_status_11";
if(isset($od_status_12) && is_numeric($od_status_12))        $qstr .= "&od_status_12=$od_status_12";
if(isset($od_status_13) && is_numeric($od_status_13))       $qstr .= "&od_status_13=$od_status_13";
if(isset($od_id) && $od_id)                                 $qstr .= "&od_id=$od_id";
if(isset($q_pt_id) && $q_pt_id)                             $qstr .= "&q_pt_id=$q_pt_id";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

?>


