<?php
include_once("./_common.php");

check_demo();

if(!$gs_id)
    alert("상품번호가 넘어오지 않았습니다.");


$sql = " select *  from shop_goods where index_no IN ({$gs_id}) order by index_no desc ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/goods/goods_excel.sub.php');

?>
