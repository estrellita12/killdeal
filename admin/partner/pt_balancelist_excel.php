<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_partner_pay a, shop_member b ";
$sql_search = " where a.mb_id = b.id ";

if($sfl && $stx) {
	$sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and b.grade = '$sst' ";

if($fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "balance";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_group = " group by a.mb_id HAVING SUM(a.pp_pay) > 0 ";
$sql_order = " order by {$filed} {$sod} ";

$sql = " select a.*, 	
			    SUM(a.pp_pay) as balance,
				b.name,
				b.grade,
				b.term_date,
                b.homepage,
                b.id
           {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 내역이 없습니다.");

// 가맹점 목록 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_balancelist_excel.sub.php');

?>
