<?php
if(!defined('_TUBEWEB_')) exit;

// 20210707
if(isset($limit_num) && $limit_num!=0) $qstr .= "&limit_num=$limit_num";

include_once(TB_ADMIN_PATH.'/goods/goods_get.inc.php');

$sql_common = " from shop_goods as a right join shop_order as b on a.index_no = b.gs_id";
//$sql_search = " where a.use_aff = 0 and a.shop_state = 0  and b.dan!=0 ";
$sql_search = " where a.shop_state = 0  and b.dan>1 ";

include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if($q_pt_id){	
    $sql_search .= " and pt_id = '$q_pt_id' ";
}

if(!$orderby) {
    $filed = "buy_cnt";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 20210707
if( isset($limit_num) && $limit_num!=0 ){
    $from_record = 0;
    $rows = $limit_num;
    $num = $limit_num;
    $total_count = $limit_num;
}else{

// 테이블의 전체 레코드수만 얻음
$sql = " select count(c.gs_id) as cnt from (select b.gs_id as gs_id $sql_common $sql_search  group by b.gs_id ) as c ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

} //else

$sql = "select a.*, sum(b.sum_qty) as total_sum_qty, sum(if(b.dan = 2 or b.dan = 3 or b.dan=4 or b.dan=5, b.sum_qty,0)) as buy_cnt, sum(if(b.dan = 6,b.sum_qty,0)) as cancel_cnt, sum(if(b.dan = 10 or b.dan = 11 or b.dan=7,b.sum_qty,0)) as return_cnt, sum(if(b.dan = 9,b.sum_qty,0)) as refund_cnt, sum(if(b.dan=12 or b.dan = 13 or b.dan = 8,b.sum_qty,0)) as change_cnt , sum(if(b.dan = 0,b.sum_qty,0)) as dan6, count(b.od_id) as total_od_id, sum(b.goods_price+b.baesong_price) as total_use_price, b.pt_id as pt_id, b.od_time as od_time $sql_common $sql_search  group by b.gs_id $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="#" id="frmPtProductExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./partner/pt_product_sales_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
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
			<select name="sfl">
				<?php echo option_selected_1('gname', $sfl, '상품명'); ?>
				<?php echo option_selected('gs_id', $sfl, '상품코드'); ?>
				<?php echo option_selected('mb_id', $sfl, '업체코드'); ?>
				<?php echo option_selected('maker', $sfl, '제조사'); ?>
				<?php echo option_selected('origin', $sfl, '원산지'); ?>
				<?php echo option_selected('model', $sfl, '모델명'); ?>
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
			<select name="q_date_field" id="q_date_field">
				<?php echo option_selected('od_time', $q_date_field, "상품주문일"); ?>
				<?php echo option_selected('update_time', $q_date_field, "최근수정일"); ?>
				<?php echo option_selected('reg_time', $q_date_field, "최초등록일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
    <tr>
        <th scope="row">판매여부</th>
        <td colspan="3">
            <?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '1', '진열'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '2', '품절'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '3', '단종'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '4', '중지'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">가맹점명</th>
        <td colspan="3">
            <?php echo get_search_partner('q_pt_id', $q_pt_id); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">출력갯수</th>
        <td colspan="3">
			<select name="limit_num" id="limit_num">
				<?php echo option_selected('0', $limit_num, "전체"); ?>
				<?php echo option_selected('10', $limit_num, "10개"); ?>
				<?php echo option_selected('20', $limit_num, "20개"); ?>
				<?php echo option_selected('30', $limit_num, "30개"); ?>
				<?php echo option_selected('50', $limit_num, "50개"); ?>
				<?php echo option_selected('100', $limit_num, "100개"); ?>
			</select>
        </td>
        
    </tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

 <h2><?php if($q_pt_id){echo trans_pt_name($q_pt_id);}else{echo "전체";} ?></h2>
<div class="local_ov">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<form name="forderlist" id="forderlist" method="post" >

<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
    <table id="sodr_list">
    <colgroup>
        <col class="w20"> <!-- 체크박스 -->
        <col class="w20"> <!-- 상품번호 -->
        <col class="w20"> <!-- 상품번호 -->
        <col class="w80">  <!-- 상품이미지 -->
        <col > <!-- 상품명 -->
        <col class="w80">  <!-- 진열 -->
        <col class="w80">  <!-- 소비자가 -->
        <col class="w80">  <!-- 판매가 -->
        <col class="w80">  <!-- 조회수 -->
        <col class="w80">  <!-- 총주문건 -->
        <col class="w80">  <!-- 총주문수량 -->
        <col class="w80">  <!-- 판매수량 -->
        <col class="w80">  <!-- 취소수량 -->
        <col class="w80">  <!-- 환불수량 -->
        <col class="w80">  <!-- 반품수량 -->
        <col class="w80">  <!-- 교환수량 -->
        <col class="w80">  <!-- 판매총액 -->
    </colgroup>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
        <th scope="col">상품코드</th>
        <th scope="col">상세내역</th>
        <th scope="col" colspan="2">상품명</th>
        <th scope="col"><?php echo subject_sort_link('isopen',$q2); ?>진열</a></th>
        <th scope="col"><?php echo subject_sort_link('normal_price',$q2); ?> 소비자가</th>
        <th scope="col"><?php echo subject_sort_link('goods_price',$q2); ?> 판매가</th>
        <th scope="col"><?php echo subject_sort_link('readcount',$q2); ?> 전체조회수</th>
        <th scope="col"><?php echo subject_sort_link('total_od_id',$q2); ?> 총주문건</th>
        <th scope="col"><?php echo subject_sort_link('total_sum_qty',$q2); ?> 총주문수량</th>
        <th scope="col"><?php echo subject_sort_link('buy_cnt',$q2); ?>판매수량</th>
        <th scope="col"><?php echo subject_sort_link('cancel_cnt',$q2); ?>취소수량</th>
        <th scope="col"><?php echo subject_sort_link('refund_cnt',$q2); ?>환불수량</th>
        <th scope="col"><?php echo subject_sort_link('return_cnt',$q2); ?>반품수량</th>
        <th scope="col"><?php echo subject_sort_link('change_cnt',$q2); ?>교환수량</th>
        <th scope="col"><?php echo subject_sort_link('total_use_price',$q2); ?>판매총액</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $gs_id = $row['index_no'];
        $bg = 'list'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td>
            <input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
        </td>
        <td>  <!-- 상품번호-->
            <a href="<?php echo TB_ADMIN_URL; ?>/pt_product_sales_pop.php?gs_id=<?php echo $gs_id."&".$q1; ?>" onclick="win_open(this,'pop_product_sales','800','800','yes');return false;" class="tu" title="각 가맹점별 판매 내역"><?php echo $gs_id ?></a>
        </td>
        <td>
            <a href="<?php echo TB_ADMIN_URL; ?>/pt_product_sales_pop2.php?gs_id=<?php echo $gs_id."&".$q1; ?>" onclick="win_open(this,'pop_product_sales','1000','800','yes');return false;" class="tu" title="상품 주문 내역">주문내역</a>
        </td>
        <td class="td_img">  <!-- 상품이미지 -->
        <a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank" title="상품 페이지"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a>
        </td>
        <td class="td_itname">  <!-- 상품명-->
            <a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $gs_id; ?>" target="_blank" title="상품 관리 페이지"><?php echo get_text($row['gname']); ?></a>
        </td>
        <td><?php echo $gw_isopen[$row['isopen']]; ?></td>
        <td>  <!-- 소비자가-->
        <?php echo number_format($row['normal_price']); ?>
        </td>
        <td>  <!-- 판매가-->
        <?php echo number_format($row['goods_price']); ?>
        </td>
        <td>  <!-- 조회수-->
            <?php echo number_format($row['readcount']); ?>
        </td>
        <td>  <!-- 총주문건 -->
            <?php echo number_format($row['total_od_id']); ?>
        </td>
        <td>  <!-- 총주문수량 -->
            <?php echo number_format($row['total_sum_qty']); ?>
        </td>
        <td>  <!-- 판매수량 -->
            <?php echo number_format($row['buy_cnt']); ?>
        </td>
        <td>  <!-- 취소수량 -->
            <?php echo number_format($row['cancel_cnt']); ?>
        </td>
        <td>  <!-- 환불수량 -->
            <?php echo number_format($row['refund_cnt']); ?>
        </td>
        <td>  <!-- 반품수량 -->
            <?php echo number_format($row['return_cnt']); ?>
        </td>
        <td>  <!-- 교환수량 -->
            <?php echo number_format($row['change_cnt']); ?>
        </td>
        <td>  <!-- 판매총액 -->
            <?php echo number_format($row['total_use_price']); ?>
        </td>
    </tr>
<?php
}	
?>
    </tbody>
    </table>
</div>
</form>


<?php

echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>

$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
    $("#frmPtProductExcel").on("click", function() {
        var type = $(this).attr("id");
        var gs_chk = new Array();
        var gs_id = "";
        var $el_chk = $("input[name='chk[]']");

        $el_chk.each(function(index) {
            if($(this).is(":checked")) {
                gs_chk.push($("input[name='gs_id["+index+"]']").val());
            }
        });

        if(gs_chk.length > 0) {
            gs_id = gs_chk.join();
        }
        if(gs_id == "") {
            alert("처리할 자료를 하나 이상 선택해 주십시오.");
            return false;
        } else {
            this.href = "./partner/pt_product_sales_excel2.php?gs_id="+gs_id;
            return true;
        }
    });


});

</script>
