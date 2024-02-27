<?php
include_once("./_common.php");

ajax_admin_token();

$mb = get_member($mb_id, 'id');
if(!$mb['id']) {
	die("{\"error\":\"회원아이디가 존재하지 않습니다.\"}");
}

$cat = sql_fetch("select * from shop_category where index_no = '$ca_no' ");
$cat['catehide'] = preg_replace('/\s+/', '', $cat['catehide']);

$count_yes = false;
if(in_array($mb_id, explode(",", $cat['catehide']))) {
	$count_yes = true;
}

$s_value = "";

if(!$count_yes) {
	$list_data = explode(",", $cat['catehide']);
	$list_data = array_diff($list_data, array($mb_id)); // 적용 아이디 모두제거
	array_push($list_data, $mb_id);  // 적용아이디 배열에 추가
	$list_data = array_unique($list_data); //중복된 아이디 제거
	$list_data = array_filter($list_data); // 빈 배열 요소를 제거
	$list_data = array_values($list_data); // index 값 주기
	$s_value = implode(",", $list_data);
} else {
	$list_data = explode(",", $cat['catehide']);
	$list_data = array_diff($list_data, array($mb_id)); // 적용 아이디 모두제거
	$list_data = array_unique($list_data); //중복된 아이디 제거
	$list_data = array_filter($list_data); // 빈 배열 요소를 제거
	$list_data = array_values($list_data); // index 값 주기
	$s_value = implode(",", $list_data);
}

$len = strlen($cat['catecode']);
$sql = "update shop_category set catehide = '$s_value' where SUBSTRING(catecode,1,$len) = '{$cat['catecode']}' ";
$result = sql_query($sql);

if($result)
	die("{\"error\":\"\"}"); // 정상
else
	die("{\"error\":\"일시적인 오류\"}");
?>