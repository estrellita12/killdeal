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

		$sql = " delete from shop_popular where pp_id = '{$_POST['pp_id'][$k]}' ";
		sql_query($sql);
	}
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>