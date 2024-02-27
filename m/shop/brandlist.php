<?php
include_once("./_common.php");

$tb['title'] = $default['de_pname_6'];
include_once("./_head.php");
if($br_name) {
	if($br_name == "마제스티") {
		$br_name = "마루망";
	}
	$br = sql_fetch("select * from shop_brand where br_name = '$br_name'");
	$sql_search = " and brand_uid = '$br_name' ";
} else {
	$sql_search = " and brand_uid <> '' ";
	$br['br_name'] = $default['de_pname_6'];
}

$bimg = TB_DATA_PATH.'/brand/'.$br['br_logo'];
if(is_file($bimg) && $br['br_logo'])
	$br_logo = rpc($bimg, TB_PATH, TB_URL);
else
	$br_logo = TB_IMG_URL.'/brlogo_sam.jpg';

$sql_common = sql_goods_list($sql_search);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, index_no desc ";
else
	$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// (2021-01-11) 할인율순 정렬 추가
$sql = " select *,((normal_price-goods_price)/normal_price) as dis $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql,false);

include_once(TB_MTHEME_PATH.'/brandlist.skin.php');

include_once("./_tail.php");
?>
