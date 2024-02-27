<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count)
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

	$ma_id = trim($_POST['ma_id'][$k]);

	$sql = " select ma_content from shop_mail where ma_id = '$ma_id' ";
    $row = sql_fetch($sql);

	// 에디터 이미지 삭제
	delete_editor_image($row['ma_content']);

	// 삭제
	sql_query(" delete from shop_mail where ma_id = '$ma_id' ");
}

goto_url(TB_ADMIN_URL."/member.php?$q1&page=$page");
?>