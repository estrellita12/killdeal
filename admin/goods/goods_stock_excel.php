<?php
include_once("./_common.php");

check_demo();

//$sql_common = "from ( select a.*,sum(b.io_stock_qty) as op from shop_goods a, shop_goods_option b where a.index_no=b.gs_id  group by a.index_no )memo ";
//$sql_search = " where use_aff = 0 and shop_state = 0 ";
//$sql_search .= " and ( (stock_qty <= 1  and stock_mod = 1) or (op <= 1 and stock_mod = 0) ) ";

$sql_common = " from shop_goods ";
$sql_search = " where use_aff = 0 ";
$sql_search .= " and shop_state = 0 ";
$sql_search .= " and stock_qty <= 1 ";

include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

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

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/goods/goods_stock_excel.sub.php');

?>
