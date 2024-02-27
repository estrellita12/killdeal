<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($sst) {
	$sql_search .= " and grade = '$sst' ";
}

if($q_pt_id) {
	$sql_search .= " and pt_id = '$q_pt_id' ";
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and left({$spt},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left({$spt},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left({$spt},10) between '$to_date' and '$to_date' ";

?>
