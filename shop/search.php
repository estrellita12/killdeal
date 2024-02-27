<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/search.php?ss_tx='.$ss_tx);
}

if( isset($research) && $research==1 ){
    $ss_tx = $ss_tx_ori." ".$ss_tx;
}


$ss_tx = trim(strip_tags($ss_tx));
if(!$ss_tx) {
	alert('검색어가 넘어오지 않았습니다.');
}

$tb['title'] = '상품 검색 결과';
include_once("./_head.php");

$concat = array();
$concat[] = "upper(a.gname)";
$concat[] = "a.explan";
$concat[] = "a.gcode";
$concat[] = "a.brand_nm"; // 2021-11-25
$concat[] = "a.index_no"; // (2021-02-26) 

$concat_fields = "concat(".implode(",' ',",$concat).")";

// 인기검색어
if($_POST['hash_token'] && TB_HASH_TOKEN == $_POST['hash_token']) {
	insert_popular($pt_id, $ss_tx);
}

// 2021-11-25
$arr_ss_tx = explode(" ",$ss_tx);
//print_r($arr_ss_tx);
$sql_search = "";
foreach($arr_ss_tx as $tx){
    $tx = trim($tx);
    $tx = preg_replace('/(\xE2\x80\x8B|&#8203;)/', '', $tx); // 2023-04-11
    $sql_search .= " and ";
    //$sql_search .= " (  $concat_fields like upper('%$tx%') or find_in_set('$tx', a.keywords) >= 1 ) ";
    $sql_search .= " (  $concat_fields like upper('%$tx%') ) ";
}
//$sql_search = " and ( $concat_fields like upper('%$ss_tx%') or find_in_set('$ss_tx', a.keywords) >= 1 ) ";
//$sql_search += " and a.index_no NOT IN ('5312','5311','5304','5310','5309','5308','5307','5306','5305','5322','5321','5320','5319','5318','5317','5323','5316','5315','5314','5313') "; 
$sql_common = sql_goods_search($sql_search);
$sql_query = $sql_common;   // 2021-11-26 카테고리 탭을 만들기 위한 조건문

if( isset($ca_id) ){
    $sql_common .= " and a.ca_id like ('{$ca_id}%') ";
}


// 상품 정렬
if($sort && $sortodr){
    // (2021-01-11) 할인율순 정렬 추가
    if($sort=="dis"){
        $sql_order = " order by {$sort} {$sortodr}, a.index_no desc ";
    }else{
	    $sql_order = " order by a.{$sort} {$sortodr}, a.index_no desc ";
    }
}else{
	$sql_order = " order by a.index_no desc ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(a.index_no) as cnt $sql_query ";
$row = sql_fetch($sql);
$allCnt = $row['cnt'];

// 조건이 설정된  테이블의 전체 레코드수만 얻음
$sql = " select count(a.index_no) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 4; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,((normal_price-goods_price)/normal_price) as dis $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql,false);

include_once(TB_THEME_PATH.'/search.skin.php');

include_once("./_tail.php");
?>
