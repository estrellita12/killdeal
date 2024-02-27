<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$pu_no = trim($_POST['pu_no'][$k]);

		// 삭제
		$sql = "select pu_img from shop_app_push where pu_no='$pu_no' ";
		$row = sql_fetch($sql);
		delete_editor_image($row['pu_img']);

		sql_query("delete from shop_app_push where pu_no='$pu_no'");	
	}
}

goto_url(TB_ADMIN_URL."/design.php?$q1&page=$page");
?>
