<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_field))		 $qstr .= "&sel_field=$sel_field";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($od_status))		 $qstr .= "&od_status=$od_status";
if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($sfl))             $qstr .="&sfl=$sfl";
if(isset($stx))             $qstr .="&stx=$stx";

if(isset($order_short))             $qstr .="&order_short=$order_short";


//if(isset($q_sidebanner))		$qstr .= "&q_sidebanner=$q_sidebanner";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " 
                from (
                		 select b.gs_id as gs_id ,b.sum_qty as sum_qty, a.gname as gname , b.od_id as od_id, 
						 a.simg1 as simg1, b.goods_price as goods_price , b.pt_id as pt_id, b.receipt_time AS receipt_time
						  from shop_goods as a, shop_order as b 
						  where a.index_no = b.gs_id
						  and b.dan in (2,3,4,5) )
                		 as A 
                 right join shop_goods as B 
                 on A.gs_id = B.index_no ";
                


$sql_common2 = " from shop_partner order by shop_name asc";
$sql_search = " where use_aff = 0 and shop_state = 0 ";
$sql_group_by = " GROUP BY B.index_no";
$where = array();
include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if($code =='pruoduct_sales' )
{

if($sfl){
	if($sfl !== ''){
		$where[] = " pt_id = '$sfl' ";
	}
}
else{
	if($member['id'] !== 'admin'){
		$where[] = " pt_id = '{$member['id']}' ";
		
	}
}





if($fr_date && $to_date)
    $where[] = " left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$where[] = " left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " left({$sel_field},10) between '$to_date' and '$to_date' ";


	if($stx){
		if($sfl_1 !== ''){
			$where[] = " B.$sfl_1 like  '%$stx%' ";
		}
	}else{
		$where[] = " A.goods_price !=0 ";
	}

if($where) {
		$sql_search = ' where '.implode(' and ', $where);
}
}




if(!$order_short) {
	
    $filed = "goods_price_sum";
    $sod = "desc";
} else {
	$filed = $order_short;
	$sod = "desc";
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql1 = " select * $sql_common $sql_search $sql_group_by ";

$row1 = sql_query($sql1);

// pt_id값 가져오기
$sql2 = " select mb_id {$sql_common2} ";
$result2 = sql_query($sql2);

$total_count = sql_num_rows($row1);


if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = "  select A.pt_id,sum(IFNULL(A.sum_qty,0)) as sum_qty, B.gname as gname ,B.index_no as index_no ,A.od_id as od_id,				  
             B.simg1 as simg1 , B.info_value as info_value , sum(A.goods_price) as goods_price_sum ,B.goods_price as goods_price
            ,B.ca_id as ca_id , B.ca_id2 as ca_id2 , B.ca_id3 as ca_id3
            ,B.use_aff as use_aff , B.shop_state as shop_state, B.readcount as readcount 


$sql_common $sql_search $sql_group_by $sql_order ";
$result = sql_query($sql);
$result1 = sql_query($sql);
//var_dump($sql);
$tot_orderprice = 0; // 총주문액
$tot_orderproduct = 0; // 총 주문 수량

while($row=sql_fetch_array($result1)) {
	$tot_orderprice += $row['goods_price_sum'];
	$tot_orderproduct += $row['sum_qty'];
}

$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_product_excel.sub.php');
?>