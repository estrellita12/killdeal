<?php
include_once("./_common.php");

check_demo();

if(!$help_no)
	alert("게시글 번호가 넘어오지 않았습니다.");

$sql = " select * from shop_goods_qa where iq_id IN ({$help_no}) order by iq_id asc ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/goods/goods_qa_excel.sub.php');
?>
