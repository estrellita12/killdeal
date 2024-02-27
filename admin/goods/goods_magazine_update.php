<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = "update shop_goods_magazine
				   set mgz_name = '{$_POST['mgz_name'][$k]}',
					   mgz_use = '{$_POST['mgz_use'][$k]}',
					   mgz_order = '{$_POST['mgz_order'][$k]}'
				 where mgz_no = '{$_POST['mgz_no'][$k]}'";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "선택삭제")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$mgz_no = trim($_POST['mgz_no'][$k]);

		$row = sql_fetch("select * from shop_goods_magazine where mgz_no = '$mgz_no'");
		@unlink(TB_DATA_PATH."/magazine/".$row['mgz_limg']);
		@unlink(TB_DATA_PATH."/magazine/".$row['mgz_bimg']);

		sql_query("delete from shop_goods_magazine where mgz_no='$mgz_no' ");
	}
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>