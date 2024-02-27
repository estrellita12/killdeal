<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';


// *어드민 가맹점 정산리스트, 가맹점 판매정산리스트 쿼리*
// 변수값이 있다면 쿼리스트링변수에 할당
// 변수값 = 검색조건
if(isset($sel_field))		 $qstr .= "&sel_field=$sel_field";
if(isset($ptn_sel_field))    $qstr .= "&ptn_sel_field=$ptn_sel_field";
if(isset($od_status))		 $qstr .= "&od_status=$od_status";
if(isset($od_status_0))		 $qstr .= "&od_status_0=$od_status_0";
if(isset($od_status_2))		 $qstr .= "&od_status_2=$od_status_2";
if(isset($od_status_3))		 $qstr .= "&od_status_3=$od_status_3";
if(isset($od_status_4))		 $qstr .= "&od_status_4=$od_status_4";
if(isset($od_status_5))		 $qstr .= "&od_status_5=$od_status_5";
if(isset($od_status_6))		 $qstr .= "&od_status_6=$od_status_6";
if(isset($od_status_7))		 $qstr .= "&od_status_7=$od_status_7";
if(isset($od_status_8))		 $qstr .= "&od_status_8=$od_status_8";
if(isset($od_status_9))		 $qstr .= "&od_status_9=$od_status_9";
if(isset($od_status_10))		 $qstr .= "&od_status_10=$od_status_10";
if(isset($od_status_11))		 $qstr .= "&od_status_11=$od_status_11";
if(isset($od_status_12))		 $qstr .= "&od_status_12=$od_status_12";
if(isset($od_status_13))		 $qstr .= "&od_status_13=$od_status_13";
if(isset($od_id))		 $qstr .= "&od_id=$od_id";
if(isset($q_pt_id))		 $qstr .= "&q_pt_id=$q_pt_id";


// *최종처리일 업데이트 쿼리*
// 주문상태 dan = 0(주문하지않은상태)
// 해당쿼리는 최종처리일을 구함
// 최종처리일은 shop_order table에서 주문상태 변경시 바뀌는 필드값(입금일,배송일,환불일 등)을 비교
// 비교 후 가장 나중에 처리된 날짜를 최종처리일에 업데이트
$sql_1 =  "select * from shop_order where dan!=0";
$res = sql_query($sql_1);


while($row = sql_fetch_array($res)) {	
		
	// 첫번째 검사 입금일시 < 배송 배송일시 
		if($row['receipt_time'] < $row['delivery_date'] ){
			$time_zone = $row['delivery_date'];

		}else{
			$time_zone = $row['receipt_time']; 
		}		
		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['invoice_date'] ){
			$time_zone = $row['invoice_date'];
		}else{
			$time_zone = $time_zone;
		}


		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['return_date'] ){
			$time_zone = $row['return_date'];
		}else{
			$time_zone = $time_zone;
		}


		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['change_date'] ){
			$time_zone = $row['change_date'];
		}else{
			$time_zone = $time_zone;
		}


		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['refund_date'] ){
			$time_zone = $row['refund_date'];
		}else{
			$time_zone = $time_zone;
		}


		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['return_date2'] ){
			$time_zone = $row['return_date2'];
		}else{
			$time_zone = $time_zone;
		}


		// (입금일시 , 배송일시 ) < 배송완료
		if( $time_zone < $row['change_date2'] ){
			$time_zone = $row['change_date2'];
		}else{
			$time_zone = $time_zone;
		}
		
		if( $time_zone < $row['cancel_date'] ){
			$time_zone = $row['cancel_date'];
		}else{
			$time_zone = $time_zone;
		}
		

		if($time_zone > $row['rcent_time']) {
			$sql = "update shop_order set rcent_time='$time_zone' where od_id='$row[od_id]' and od_no='$row[od_no]' ";
			sql_query($sql);
		}		
	}

// 쿼리스트링 및 페이징
$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_common2 = " from shop_partner order by shop_name asc";
$where = array();
$dan_array = array();


//어드민 => 가맹점 정산 리스트, 가맹점페이지 => 상품정산리스트 일때 해당 쿼리 사용
if($code =='calculate' || $code == 'partner_order_calculate'){

	if(is_numeric($od_status0)){
		$where[] = " dan = '$od_status0' ";
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

	$where[] = " dan NOT IN ('0','1','2','3','4') ";

	if($dan_array){
		foreach($dan_array as $val){
			$valEl .="'{$val}',";
			
		}
		$valEl = rtrim($valEl, ',');
		
		$where[]  = "dan IN ({$valEl})";
	}			

} // 전체주문내역

else
	$where[] = " dan = '$code' ";

if($sfl){
	if($sfl !== ''){
		$where[] = " pt_id = '$sfl' ";
	}
}
else{
	// admin 이나 admin2 계정일때만 정산처리 N,Y값 다 보여줌
	// 가맹점계정은 가맹점 => 상품 정산리스트 에서 정산처리Y된것만 보여줌
	if($member['id'] !== 'admin' && $member['id'] !== 'admin2'){
		$where[] = " pt_id = '{$member['id']}' ";
		$where[] = " calculate = 'Y' ";
	}
}
if($q_pt_id){
	if($q_pt_id !== ''){
		$where[] = " pt_id = '$q_pt_id' ";
	}
}

if($od_id){
	if($od_id !== ''){
		$where[] = " od_id = '$od_id' ";
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

// 가맹점 월 단위 선택 쿼리
//if($ptn_sel_field)
//	$where[] = "left(rcent_time,7) between '$ptn_sel_field' and '$ptn_sel_field' ";

// 선택된 검색조건(쿼리스트링) where절 뒤에 추가
if($where) {
		$sql_search = ' where '.implode(' and ', $where);
}

// 가맹점 기간검색쿼리
//$sql_partner = " select distinct(left(rcent_time,7)) AS recent_time
//									from shop_order
//									where dan != 0 
//									and pt_id = '$pt_id' 
//									group by od_id, left(rcent_time,7) 
//									order by index_no desc ";
//$ptn_res = sql_query($sql_partner);


$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";
// 테이블의 전체 레코드수만 얻음
$sql = " select od_id  {$sql_common} {$sql_search} {$sql_group}  ";

$result = sql_query($sql);
$total_count = sql_num_rows($result);

// pt_id값 가져오기
// pt_id 값은 가맹점정산리스트에 select에 들어감
$sql2 = " select mb_id {$sql_common2} ";
$result2 = sql_query($sql2);
$total_count2 = sql_num_rows($result2);

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

// 페이징
$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$tot_orderprice = 0; // 총주문액
$tot_orderproduct = 0; // 총 주문 수량
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$amount = get_order_spay($row['od_id']);
	$tot_orderprice += $amount['buyprice'];
	$tot_orderproduct += $amount['qty'];
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
