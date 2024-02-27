<?php
if(!defined('_TUBEWEB_')) exit;
 include_once(TB_ADMIN_PATH.'/order/order_get.inc.php');

$sql_common = " from shop_order ";
if($code == 'list' || $code =='list2' || $code =='calculate') // 주문리스트(전체),주문정산
    $sql_search = " where dan != 0 ";
else
    $sql_search = " where dan = '$code' ";

include_once(TB_ADMIN_PATH.'/order/order_query.inc.php');

$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$tot_orderprice = 0; // 총주문액
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$amount = get_order_spay($row['od_id']);
	$tot_orderprice += $amount['buyprice'];
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="#" id="frmOrderPrint" class="btn_lsmall white"><i class="fa fa-print"></i> 주문서출력</a>
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./order/order_list_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
EOF;

?>
