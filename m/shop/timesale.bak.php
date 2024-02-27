<?php
include_once("./_common.php");

$tb['title'] = $default['de_pname_8'];
include_once("./_head.php");
$Date = date("Y-m-d");

$YY = date("Y", strtotime($Date));
$MM = date("m", strtotime($Date));
$DD = date("d", strtotime($Date));
$Day = date("w", strtotime($Date));

$this_week_start = date("Y-m-d", strtotime($YY."-".$MM."-".$DD." -".$Day." day"));
$this_week_end = date("Y-m-d", strtotime($this_week_start." +6 day"));

$sql_search = " and sb_date = '".$this_week_start."' and eb_date = '".$this_week_end."' ";
$sql_common = sql_goods_list($sql_search);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, eb_date asc ";
else
	$sql_order = " order by eb_date asc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_MTHEME_PATH.'/timesale.skin.php');

include_once("./_tail.php");
?>