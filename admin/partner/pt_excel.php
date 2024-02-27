<?php
include_once("./_common.php");

check_demo();

$sql_common = " from shop_order ";
$sql_search = " where dan NOT IN ('0','1','2','3','4')  ";

include_once(TB_ADMIN_PATH.'/order/order_query.inc.php');

$sql_order = " order by od_time desc, index_no asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";

$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_excel.sub.php');
?>
