<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$po_point = trim($_POST['po_point']);
$po_content = trim($_POST['po_content']);

$po_list = explode(",", trim($_POST['point_mb_id']));
$mb_cnt1 = count($po_list);
$mb_list = array();
for($i=0; $i<$mb_cnt1; $i++) {
	$row = get_member($po_list[$i], "id, point");
	$mb_list['id'][] = $row['id'];
	$mb_list['point'][] = $row['point'];
}

$mb_cnt2 = count($mb_list['id']);
for($i=0; $i<$mb_cnt2; $i++) {
	$mb_id = $mb_list['id'][$i];
	$mb_point = $mb_list['point'][$i];

	$isNum = false;
	if(($po_point < 0) && ($po_point * (-1) > $mb_point))
		$isNum = true;

	if(!$isNum)
		insert_point($mb_id, $po_point, $po_content, '@passive', $mb_id, $member['id'].'-'.uniqid(''));
}

alert("정상적으로 처리 되었습니다.", TB_ADMIN_URL.'/member.php?code=point_select_form');
?>