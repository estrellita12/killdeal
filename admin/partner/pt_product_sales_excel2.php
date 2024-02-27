<?php
include_once("./_common.php");

check_demo();


if(!$gs_id)
    alert("상품번호가 넘어오지 않았습니다.");

// 주문서 query 공통
$sql_common = " from shop_goods as a right join shop_order as b on a.index_no = b.gs_id";
$sql_search = " where a.index_no IN ({$gs_id}) ";

if(!$orderby) {
    $filed = "buy_cnt";
    $sod = "desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = "select a.*, sum(b.sum_qty) as total_sum_qty, sum(if(b.dan = 2 or b.dan = 3 or b.dan=4 or b.dan=5, b.sum_qty,0)) as buy_cnt, sum(if(b.dan = 6,b.sum_qty,0)) as cancel_cnt, sum(if(b.dan = 10 or b.dan = 11 or b.dan=7,b.sum_qty,0)) as return_cnt, sum(if(b.dan = 9,b.sum_qty,0)) as refund_cnt, sum(if(b.dan=12 or b.dan = 13 or b.dan = 8,b.sum_qty,0)) as change_cnt , sum(if(b.dan = 0,b.sum_qty,0)) as dan6, count(b.od_id) as total_od_id, sum(b.goods_price+b.baesong_price) as total_use_price, b.pt_id as pt_id, b.od_time as od_time $sql_common $sql_search  group by b.gs_id $sql_order";

$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_product_sales_excel.sub.php');

?>
