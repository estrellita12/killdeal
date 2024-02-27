<?php
include_once("./_common.php");

check_demo();

$sql_common = " from shop_goods ";

if(isset($use_aff) && $use_aff){
    $sql_search = " where use_aff = 1 ";
}else{
    $sql_search = " where use_aff = 0 ";
}

if(isset($shop_state) && $shop_state){
    $sql_search .= " and shop_state = $shop_state ";
}else{
    $sql_search .= " and shop_state = 0 ";
}

include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = " select * $sql_common $sql_search $sql_order ";
//echo $sql;
//exit;
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/goods/goods_excel.sub.php');

?>
