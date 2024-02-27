<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/magazinelist.php?mgz_no='.$mgz_no);
}

$sql_where = " where mgz_no = '$mgz_no' ";
if($pt_id != 'admin') {
	$sql_where .= " and mb_id IN('admin','$pt_id') ";
}

$pl = sql_fetch("select * from shop_goods_magazine {$sql_where} ");
if(!$pl['mgz_no'])
	alert('자료가 없습니다.');

$tb['title'] = $pl['mgz_name'];
include_once("./_head.php");

$bimg_url = "";
$bimg = TB_DATA_PATH.'/magazine/'.$pl['mgz_bimg'];
if(is_file($bimg) && $pl['mgz_bimg']) {
	$bimg_url = rpc($bimg, TB_PATH, TB_URL);
}

// 상품코드 \n -> , 변환
$mgz_it_code = explode("\n", $pl['mgz_it_code']);
$mgz_it = mb_comma($mgz_it_code);
if(!$mgz_it) $mgz_it = 'NULL';

$sql_search = " and index_no IN({$mgz_it}) ";
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

$mod = 4; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_THEME_PATH.'/magazinelist.skin.php');

include_once("./_tail.php");
?>