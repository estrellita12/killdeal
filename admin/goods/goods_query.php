<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/goods/goods_get.inc.php');

$sql_common = " from shop_goods ";
$sql_search = " where use_aff = 0 ";
if(isset($use_aff) && $use_aff){
    $sql_search = " where use_aff = 1 ";
}
if(isset($shop_state) && $shop_state>=0){
    $sql_search .= " and shop_state = $shop_state ";
}


include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
    $page_rows = $_SESSION['ss_page_rows'];
else
    $page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select *,(sum_qty*goods_price) as sum_price $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
