<?php
include_once("./_common.php");

$sql_where = " where pl_no = '$pl_no' ";
if($pt_id != 'admin') {
	$sql_where .= " and mb_id IN('admin','$pt_id') ";
}

$pl = sql_fetch("select * from shop_goods_plan {$sql_where} ");
if(!$pl['pl_no'])
	alert('자료가 없습니다.');

$tb['title'] = $pl['pl_name'];
include_once("./_head.php");


// 기획전 중 이벤트페이지로 이동시 분기처리
// 이벤트 진행중일때만 사용
// 이벤트없을때는 해당코드 주석
if($pl['pl_no'] == '123'){
//	if($pt_id == 'thegolfshow'){
//		include_once('./../../m_tgs_event_tgs.php');
//	}
//	else{
		include_once('./../../m_tgs_event.php');
//	}
}

$bimg_url = "";
$bimg = TB_DATA_PATH.'/plan/'.$pl['pl_bimg'];
if(is_file($bimg) && $pl['pl_bimg']) {
	$bimg_url = rpc($bimg, TB_PATH, TB_URL);
}

// 상품코드 \n -> , 변환
$pl_it_code = explode("\n", $pl['pl_it_code']);
$pl_it = mb_comma($pl_it_code);
if(!$pl_it) $pl_it = 'NULL';

$sql_search = " and index_no IN({$pl_it}) ";
$sql_common = sql_goods_list($sql_search);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, index_no desc ";
else
//	$sql_order = " order by index_no desc ";
	$sql_order = " order by FIELD (index_no,{$pl_it})  ";

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

include_once(TB_MTHEME_PATH.'/planlist.skin.php');

include_once("./_tail.php");
?>
