<?php
include_once("./_common.php");

$sql = " select *
		   from shop_category
		  where catecode = '$ca_id'
		    and cateuse = '0'
			and find_in_set('$pt_id', catehide) = '0' ";
$ca = sql_fetch($sql);
if(!$ca['catecode'])
    alert('등록된 분류가 없습니다.');

$tb['title'] = $ca['catename'];
include_once("./_head.php");

$sql_search = " and (ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%') ";
$sql_common = sql_goods_list($sql_search);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, rank desc, index_no desc ";
else
	//20191107 기존 정렬 주석처리 기본정렬 인기순
	//$sql_order = " order by rank desc, index_no desc ";
	// $sql_order = " order by readcount desc, rank desc, index_no desc ";
	//20200519 기존 정렬 주석처리 기본정렬 신상품순
	//$sql_order = " order by index_no desc ";
	$sql_order = " order by sum_qty desc, readcount desc ";

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

include_once(TB_MTHEME_PATH.'/list.skin.php');

include_once("./_tail.php");
?>
