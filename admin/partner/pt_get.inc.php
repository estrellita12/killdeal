<?php
if(!defined('_TUBEWEB_')) exit;

//if(isset($stx))         $qstr .= "&stx=$stx";               // 검색어
//if(isset($sfl))         $qstr .= "&sfl=$sfl";               // 검색어
//if(isset($sst))         $qstr .= "&sst=$sst";               // 레벨
//if(isset($spt))         $qstr .= "&spt=$spt";               // 날짜 조건
//if(isset($fr_date))     $qstr .= "&fr_date=$fr_date";       // 시작 날짜
//if(isset($to_date))     $qstr .= "&to_date=$to_date";       // 종료 날짜
if(isset($q_pt_id) && $q_pt_id)         $qstr .= "&q_pt_id=$q_pt_id";       // 가맹점ID
if(isset($ste) && $ste)                 $qstr .= "&ste=$ste";               // 승인상태

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

?>
