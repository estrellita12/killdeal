<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/timesale.php');
}

$tb['title'] = $default['de_pname_8'];
include_once("./_head.php");

/*
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

$mod = 3; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
//echo $sql;
$result = sql_query($sql);
*/


// 2021-08-11
$sql_search = " and 1!=1 ";
$ts = sql_fetch("select * from shop_goods_timesale where ts_sb_date <= NOW() and ts_ed_date >= NOW() ");
if( isset($ts) ){
    $sb_date = $ts['ts_sb_date'];
    $ed_date = $ts['ts_ed_date'];
    $is_timesale    = true;
    $ts_list_code = explode(",", $ts[ts_it_code]); // 배열을 만들고
    $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
    $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
    $ts_list_code = implode(",",$ts_list_code );
    $sql_search = " and index_no in ( $ts_list_code )";
    $sql_order = " order by field ( index_no, $ts_list_code ) ";
}

//$sql_common = sql_goods_list($sql_search);
$sql_common = " from shop_goods where shop_state = '0' ".$sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$sql = " select * $sql_common $sql_order";
$result = sql_query($sql);

include_once(TB_THEME_PATH.'/timesale.skin.php');

include_once("./_tail.php");
?>
