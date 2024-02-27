<?php
if(!defined('_TUBEWEB_')) exit;

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
if(isset($sfl_1))             $qstr .="&sfl_1=$sfl_1";
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
    
    // (2021-01-05) 카테고리 검색 옵션 추가
    if($sca){
        $where[] = " ca_id like '$sca%' ";
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


    $sql_common $sql_search $sql_group_by $sql_order  limit $from_record, $rows ";
$result = sql_query($sql);
$result1 = sql_query($sql);
//var_dump($sql);
$tot_orderprice = 0; // 총주문액
$tot_orderproduct = 0; // 총 주문 수량

while($row=sql_fetch_array($result1)) {
    $tot_orderprice += $row['goods_price_sum'];
    $tot_orderproduct += $row['sum_qty'];
}


include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./partner/pt_product_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
    <table class="tablef">
    <colgroup>
        <col class="w100">
        <col>
        <col class="w100">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">검색어</th>
        <td colspan="3">
            <select name="sfl_1">
                <?php echo option_selected_1('gname', $sfl_1, '상품명'); ?>
                <?php echo option_selected('index_no', $sfl_1, '상품코드'); ?>
                <?php echo option_selected('mb_id', $sfl_1, '업체코드'); ?>
                <?php echo option_selected('maker', $sfl_1, '제조사'); ?>
                <?php echo option_selected('origin', $sfl_1, '원산지'); ?>
                <?php echo option_selected('model', $sfl_1, '모델명'); ?>
                <?php echo option_selected('explan', $sfl, '짧은설명'); ?>
            </select>
            <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
        </td>
    </tr>
    <tr>
        <th scope="row">카테고리</th>
        <td colspan="3">
            <?php echo get_category_select_1('sel_ca1', $sca); ?>
            <?php echo get_category_select_2('sel_ca2', $sca); ?>
            <?php echo get_category_select_3('sel_ca3', $sca); ?>
            <?php echo get_category_select_4('sel_ca4', $sca); ?>
            <?php echo get_category_select_5('sel_ca5', $sca); ?>

<script>
$(function() {
    $("#sel_ca1").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
    $("#sel_ca2").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
    $("#sel_ca3").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
    $("#sel_ca4").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
    $("#sel_ca5").multi_select_box("#sel_ca",5,"","=카테고리선택=");
});
</script>
        </td>
    </tr>
    <tr>
        <th scope="row">기간검색</th>
        <td colspan="3">
            <select name="sel_field" id="sel_field">
                <?php echo option_selected('receipt_time', $sel_field, "상품판매일"); ?>
                <?php echo option_selected('update_time', $sel_field, "최근수정일"); ?>
                <?php echo option_selected('reg_time', $sel_field, "최초등록일"); ?>
            </select>
            <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
        </td>
    </tr>
    <tr>
    <th scope="row">가맹점선택</th>
    <td>
        <select name="sfl">
            <option value='' selected="selected">전체</option>
            <option value='admin'>본사</option>
<?php
for($i = 0;$rowId = sql_fetch_array($result2);$i++){
    echo option_selected("{$rowId['mb_id']}", $sfl ,trans_pt_name($rowId['mb_id']));
}
?>
        </select>
    </td>
</tr>
<tr>
    <th scope="row">정렬선택</th>
    <td>
        <select name="order_short">
            <option value='goods_price_sum' selected="selected">전체</option>			
            <?php echo option_selected('goods_price_sum', $order_short, "판매총액높은순"); ?>
            <?php echo option_selected('sum_qty', $order_short, "판매수량높은순"); ?>
        </select>
    </td>
</tr>
    </tbody>
    </table>
</div>
<div class="btn_confirm">
    <input type="submit" value="검색" class="btn_medium">
    <input type="button" value="초기화" id="frmRest" class="btn_medium red">
</div>
</form>
