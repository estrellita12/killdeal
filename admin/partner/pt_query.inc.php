<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

// 참고
//$sql_common = " from shop_partner p, shop_member m ";
//$sql_search = " where p.mb_id = m.id ";
//$sql = " select p.*, m.name,m_pt_id,m.anew_date $sql_common  $sql_search  $sql_order  limit $from_record, $rows ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst)){
	$sql_search .= " and m.grade = '$sst' ";
}

if(isset($ste) && is_numeric($ste)){
	$sql_search .= " and p.state = '$ste' ";
}

if(isset($q_pt_id) && $q_pt_id){
	$sql_search .= " and p.mb_id = '$q_pt_id' ";
}

if($fr_date && $to_date)
    $sql_search .= " and left({$spt},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left({$spt},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left({$spt},10) between '$to_date' and '$to_date' ";

?>
