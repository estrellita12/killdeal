<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$gubun = trim($_POST['gubun']);
$new_ca_id = trim($_POST['new_ca_id']);
$act = trim($_POST['act']);

if($gubun == '1') // 선택한 상품만 적용
{
	for($i=0; $i<count($_POST['chk']); $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs_id = $_POST['gs_id'][$k];

		$sql_fld = "";

		if($act == 1) { // 분류연결
			$gs = get_goods($gs_id, 'ca_id, ca_id2, ca_id3');

			if(!$gs['ca_id'])
				$sql_fld = " ca_id  = '$new_ca_id' ";
			else if(!$gs['ca_id2'])
				$sql_fld = " ca_id2 = '$new_ca_id' ";
			else if(!$gs['ca_id3'])
				$sql_fld = " ca_id3 = '$new_ca_id' ";
		}
		else if($act == 2) // 분류이동
			$sql_fld = " ca_id = '$new_ca_id', ca_id2 = '', ca_id3 = '' ";
		else if($act == 3) // 모든 분류를 연결해제
			$sql_fld = " ca_id = '', ca_id2 = '', ca_id3 = '' ";
		else if($act == 4) // 추가 분류만 연결해제
			$sql_fld = " ca_id2 = '', ca_id3 = '' ";

		if($sql_fld) {
			sql_query(" update shop_goods set $sql_fld where index_no = '$gs_id' ");
		}
	}
}
else if($gubun == '2') // 검색된 상품에 적용
{
	foreach(explode("&", $q1) as $arr_param) {
		$param = explode("=", $arr_param);
		$$param[0] = $param[1];
	}

	if($sel_ca1) $sca = $sel_ca1;
	if($sel_ca2) $sca = $sel_ca2;
	if($sel_ca3) $sca = $sel_ca3;
	if($sel_ca4) $sca = $sel_ca4;
	if($sel_ca5) $sca = $sel_ca5;

	$sql_common = " from shop_goods ";
	$sql_search = " where use_aff = 0 and shop_state = 0 ";
	$sql_order  = " order by index_no desc ";

	include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

	$sql = " select index_no, ca_id, ca_id2, ca_id3 $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$gs_id = $row['index_no'];

		$sql_fld = "";

		if($act == 1) { // 분류연결
			if(!$row['ca_id'])
				$sql_fld = " ca_id  = '$new_ca_id' ";
			else if(!$row['ca_id2'])
				$sql_fld = " ca_id2 = '$new_ca_id' ";
			else if(!$row['ca_id3'])
				$sql_fld = " ca_id3 = '$new_ca_id' ";
		}
		else if($act == 2) // 분류이동
			$sql_fld = " ca_id = '$new_ca_id', ca_id2 = '', ca_id3 = '' ";
		else if($act == 3) // 모든 분류를 연결해제
			$sql_fld = " ca_id = '', ca_id2 = '', ca_id3 = '' ";
		else if($act == 4) // 추가 분류만 연결해제
			$sql_fld = " ca_id2 = '', ca_id3 = '' ";

		if($sql_fld) {
			sql_query(" update shop_goods set $sql_fld where index_no = '$gs_id' ");
		}
	}
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>