<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/listtype.php?type='.$type);
}

$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
if($type == 1)      $tb['title'] = $default['de_pname_1']; // 쇼핑특가
else if($type == 2) $tb['title'] = $default['de_pname_2']; // 베스트셀러
else if($type == 3) $tb['title'] = $default['de_pname_3']; // 신상품
else if($type == 4) $tb['title'] = $default['de_pname_4']; // 인기상품
else if($type == 5) $tb['title'] = $default['de_pname_5']; // 추천상품
else
    alert('상품유형이 아닙니다.', TB_URL);

include_once("./_head.php");

$sql_search = "";

// 상품 정렬
if($sort && $sortodr){
    // (2021-01-11) 할인율순 정렬 추가
     if($sort=="dis"){
        $sql_order = " order by {$sort} {$sortodr}, a.index_no desc ";
    }else{
	    $sql_order = " order by a.{$sort} {$sortodr}, a.index_no desc ";
    }
}else{
	//20191107 기본정렬 주석처리 인기상품 기준 정렬
	// $sql_order = " order by readcount desc, rank desc, index_no desc ";
	//20200420 기본정렬 주석처리 인기상품 기준 정렬 => 신상품 기준 정렬
	$sql_order = " order by a.index_no desc ";
}
$res = query_itemtype($pt_id, $type, $sql_search, $sql_order);
$total_count = sql_num_rows($res);

$mod = 4; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$result = query_itemtype($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");

if($type== '2') {
	if($default['de_listing_best'] == '1') {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		for($i=0; $i<count($list_best); $i++) {
			$str = '';

			$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
			$list_code = array_unique($list_code); //중복된 아이디 제거
			$list_code = array_filter($list_code); // 빈 배열 요소를 제거
			$list_code = array_values($list_code); // index 값 주기

			$succ_count = 0;

			for($g=0; $g<count($list_code); $g++) {
				$gcode = trim($list_code[$g]);

				$sql_gcode = " and index_no = {$gcode}";
			}

		}	
		//print_r($sql_gcode);
		//print_r($list_best);
		
		/* 20191106 베스트 100 기존 정렬 주석처리
		$res = bestsell_itemtype2($pt_id, $type, $sql_search, $sql_order);
		$total_count = sql_num_rows($res);
		*/
		
		$ncat = substr($cat, 2, 1);
		if($cat == '000') {
			$ncat = '0';
		} else if ($cat == '001'){
			$ncat = '1';
		} else if ($cat == '002'){
			$ncat = '2';
		} else if ($cat == '003') {
			$ncat = '3';
		} else if (!$cat) {
			$ncat = '0';
		}

		//echo $list_best[$ncat][code];
		$list_best = $list_best[$ncat][code];
		//echo $list_best;
		$sql_search = "where index_no in ({$list_best})";
		//echo $sql_search;
		//기존 베스트 100 정렬 주석처리 
		//$result = bestsell_itemtype2($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");
		$result = bestsell_itemtype2($pt_id, $type, $sql_search, $list_best);
		$total_count = sql_num_rows($result);

	} else {
		$res = bestsell_itemtype($pt_id, $type, $sql_search, $sql_order);
		$total_count = sql_num_rows($res);

		$sql_search = "where ca_id like '{$cat}%'";

		$result = bestsell_itemtype($pt_id, $type, $sql_search, $sql_order." limit $from_record, $rows ");
		$total_count = sql_num_rows($result);
		//echo $sql_search;
	}
}


include_once(TB_THEME_PATH.'/listtype.skin.php');

include_once("./_tail.php");
?>
