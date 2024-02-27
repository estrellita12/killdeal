<?php
include_once("./_common.php");

check_demo();

// 주문서 query 공통
$sql_common = " from shop_order ";
$sql_search = " where dan NOT IN ('0','1') and  NOT (dan=6 and paymethod like '가상계좌%' and receipt_time='0000-00-00 00:00:00') ";

include_once(TB_ADMIN_PATH.'/order/order_query.inc.php');

$sql_order = " order by od_time desc, index_no asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";

$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_calculate_excel.sub.php');
?>
