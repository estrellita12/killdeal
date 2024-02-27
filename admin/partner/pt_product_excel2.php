<?php
include_once("./_common.php");

check_demo();

if(!$index_no)
	alert("주문번호가 넘어오지 않았습니다.");

$sql = " 

select A.pt_id,sum(IFNULL(A.sum_qty,0)) as sum_qty, B.gname as gname ,B.index_no as index_no
        ,A.od_id as od_id, B.simg1 as simg1 , B.info_value as info_value , sum(A.goods_price) as goods_price_sum 
        ,B.goods_price as goods_price ,B.ca_id as ca_id , B.ca_id2 as ca_id2 , B.ca_id3 as ca_id3 ,B.use_aff as use_aff 
        , B.shop_state as shop_state , B.readcount as readcount  from ( select b.gs_id as gs_id ,b.sum_qty as sum_qty, a.gname as gname , b.od_id as od_id,
        a.simg1 as simg1, b.goods_price as goods_price , b.pt_id as pt_id from shop_goods as a, shop_order as b where a.index_no = b.gs_id
        and b.dan in (2,3,4,5) ) as A right join shop_goods as B on A.gs_id = B.index_no 
        where B.gname like '%%'
        and B.index_no IN ({$index_no})
 GROUP BY B.index_no order by goods_price_sum desc  ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_product_excel.sub.php');
?>