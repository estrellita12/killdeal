<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_order ";
$where = array();
$dan_array = array();


	if(is_numeric($od_status_0)){
		array_push($dan_array,0);
	}
	if(is_numeric($od_status_2)){
		 array_push($dan_array,2);
	}
	if(is_numeric($od_status_3)){
		array_push($dan_array,3);
	}
	if(is_numeric($od_status_4)){
		array_push($dan_array,4);}
	
	if(is_numeric($od_status_5)){
		array_push($dan_array,5);}
	
	if(is_numeric($od_status_6)){
		array_push($dan_array,6);}
	
	if(is_numeric($od_status_7)){
		array_push($dan_array,7);}
	
	if(is_numeric($od_status_8)){
		array_push($dan_array,8);}
	
	if(is_numeric($od_status_9)){
		array_push($dan_array,9);}
	
	if(is_numeric($od_status_10)){
		array_push($dan_array,10);}
	
	if(is_numeric($od_status_11)){
		array_push($dan_array,11);}
	
	if(is_numeric($od_status_12)){
		array_push($dan_array,12);}
	
	if(is_numeric($od_status_13)){
		array_push($dan_array,13);}

	

	if($dan_array){
		foreach($dan_array as $val){
			$valEl .="'{$val}',";
			
		}
		$valEl = rtrim($valEl, ',');
		
		$where[]  = "dan IN ({$valEl})";
	}			


	if($dan_array){
		foreach($dan_array as $val){			
			$valEl .="'{$val}',";			
		}
		
		$valEl = rtrim($valEl, ',');		
		$where[]  = "dan IN ({$valEl})";
	}
if($sfl){
	if($sfl !== ''){
		$where[] = " pt_id = '$sfl' ";
	}
}

else{
	if($member['id'] !== 'admin' && $member['id'] !== 'admin2'){
		$where[] = " pt_id = '{$member['id']}' ";
	}
}
 if($calculate_yn){
	 if($calculate_yn == 'Y'){
		 $where[] = " calculate = 'Y' ";
	 }
	 else if($calculate_yn == 'N'){
		 $where[] = " calculate = 'N' ";
	 }	
 }


if($fr_date && $to_date)
    $where[] = " left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$where[] = " left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " left({$sel_field},10) between '$to_date' and '$to_date' ";

	if($member['id'] !== 'admin' && $member['id'] !== 'admin2'){
	$where[] = " calculate = 'Y' ";
}
	

// 가맹점 월 단위 선택 쿼리
if($ptn_sel_field)
$where[] = "left(rcent_time,7) between '$ptn_sel_field' and '$ptn_sel_field' ";


if($where) {	
	$where[] = " dan != 0 ";
	
    $sql_search = ' where '.implode(' and ', $where);
}

$sql_order = " order by od_time desc, index_no asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);


$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_excel.sub.php');
?>