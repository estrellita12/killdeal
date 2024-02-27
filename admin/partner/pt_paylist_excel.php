<?php
include_once("./_common.php");

check_demo();

$sql_common = " from shop_member ";
$sql_search = " where grade between 2 and 6 ";

include_once(TB_ADMIN_PATH.'/member/member_query.inc.php');

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = " select * $sql_common $sql_search $sql_order ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

// 가맹점 목록 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_paylist_excel.sub.php');
?>
