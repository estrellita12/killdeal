<?php
if(!defined('_TUBEWEB_')) exit;
// $dan = "";

// 주문서 query 공통
include_once(TB_ADMIN_PATH.'/order/order_get.inc.php');

// 최종처리일 데이터 입력
include_once(TB_ADMIN_PATH.'/partner/rcent_time_query.php');

$sql_common = " from shop_order ";
$sql_search = " where dan NOT IN ('0','1') and NOT (dan=6 and paymethod like '가상계좌%' and receipt_time='0000-00-00 00:00:00')  ";

include_once(TB_ADMIN_PATH.'/order/order_query.inc.php');

$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select od_id  {$sql_common} {$sql_search} {$sql_group}  ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);


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


//include_once(TB_ADMIN_PATH.'/partner/pt_query.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택정산" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="#" id="frmPtCalcExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./partner/pt_calculate_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
EOF;
?>

<!-- 가맹점별 주문값을 가져와서 정산처리  -->
<!-- 최종처리일 기준으로 정산을 진행  -->
<!-- 정산처리를 완료한 주문건은 가맹점=>상품정산리스트 에서 확인 할 수 있음 -->
<h2>정산리스트</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
    <table>
        <colgroup>
            <col class="w100">
            <col>
        </colgroup>
        <tbody>
            <tr>
    <tr>
        <th scope="row">검색어</th>
        <td>
            <select name="sfl" id="find">
                <?php echo option_selected('od_id', $sfl, '주문번호'); ?>
                <?php echo option_selected('od_no', $sfl, '일련번호'); ?>
                <?php echo option_selected('gs_id', $sfl, '상품번호'); ?>
                <?php echo option_selected("mb_id", $sfl, '회원아이디'); ?>
                <?php echo option_selected('name', $sfl, '주문자명'); ?>
                <?php echo option_selected('cellphone', $sfl, '주문자휴대폰'); ?>
                <?php echo option_selected('deposit_name', $sfl, '입금자명'); ?>
                <?php echo option_selected('bank', $sfl, '입금계좌'); ?>
                <?php echo option_selected('b_name', $sfl, '수령자명'); ?>
                <?php echo option_selected('b_cellphone', $sfl, '수령자핸드폰'); ?>
                <?php echo option_selected('delivery_no', $sfl, '운송장번호'); ?>
                <?php echo option_selected('seller_id', $sfl, '판매자ID'); ?>
                <?php echo option_selected('pt_id', $sfl, '가맹점ID'); ?>
                <?php echo option_selected('pt_name', $sfl, '가맹점명'); ?>
            </select>
            <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
        </td>
    </tr>

            <tr>
                <th scope="row">날짜/처리기준</th>
                <td>
<!-- 				<input type="hidden" value="rcent_time" name="sel_field"> -->
                    <select name="sel_field" onChange="dan_change();">
                        <?php echo option_selected('od_time', $sel_field, "주문일"); ?>
                        <?php echo option_selected('cancel_date', $sel_field, "주문취소일"); ?>
                        <?php echo option_selected('rcent_time', $sel_field, "최종처리일"); ?>
                    </select>
                    <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">가맹점 선택</th>
                <td>
                    <?php echo get_search_partner('q_pt_id', $q_pt_id); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">주문상태</th>
                    <td>
                        <?php echo check_checked_multi('od_status_0', $od_status_0, '','전체'); ?>
 						<?php echo check_checked_multi('od_status_2', $od_status_2, '2', $gw_status[2]); ?>
 						<?php echo check_checked_multi('od_status_3', $od_status_3, '3', $gw_status[3]); ?> 
 						<?php echo check_checked_multi('od_status_4', $od_status_4, '4', $gw_status[4]); ?> 
                        <?php echo check_checked_multi('od_status_5', $od_status_5, '5', $gw_status[5]); ?>
                        <?php echo check_checked_multi('od_status_12', $od_status_12, '12', $gw_status[12]); ?>
                        <?php echo check_checked_multi('od_status_13', $od_status_13, '13', $gw_status[13]); ?>
                        <?php echo check_checked_multi('od_status_8', $od_status_8, '8', $gw_status[8]); ?>
                        <?php echo check_checked_multi('od_status_6', $od_status_6, '6', $gw_status[6]); ?>
                        <?php echo check_checked_multi('od_status_9', $od_status_9, '9', $gw_status[9]); ?>
                        <?php echo check_checked_multi('od_status_10', $od_status_10, '10', $gw_status[10]); ?>
                        <?php echo check_checked_multi('od_status_11', $od_status_11, '11', $gw_status[11]); ?>
                        <?php echo check_checked_multi('od_status_7', $od_status_7, '7', $gw_status[7]); ?>
                    </td>
            </tr>
            <tr>
                <th scope="row">처리상태</th>
                <td>
                    <select name="calculate_yn">
                        <?php echo option_selected('', $calculate_yn, "전체"); ?>
                        <?php echo option_selected('N', $calculate_yn, "정산처리 전"); ?>
                        <?php echo option_selected('Y', $calculate_yn, "정산처리 후"); ?>
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

<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
<!-- 	<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong> -->
<!-- 	<strong class="ov_a">총주문 수량 : <?php echo number_format($tot_orderproduct); ?>개</strong> -->
</div>

<form name="forderlist" id="forderlist" action="./partner/pt_calculate_update.php" method="post" onsubmit="return fcalculatelist_submit(this.value);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
    <table id="sodr_list">
    <colgroup>
        <col class="w20"> <!-- 체크박스 -->
        <col class="w100">  <!-- 가맹점(pt_id) -->
        <col class="w150">  <!-- 주문번호 -->
        <col class="w50">  <!-- 상품명 -->
        <col> <!-- 상품이미지 -->
        <col class="w30">  <!--수량-->
        <col class="w80">  <!-- 판매가 -->
        <col class="w30">  <!-- 포인트 -->
        <col class="w80">  <!-- 실결제금액 -->
        <col class="w80">  <!-- 총주문금액 -->
        <col class="w80">  <!-- 결제수단 -->
        <col class="w80">  <!-- 주문자 -->
        <col class="w100">  <!-- 주문일 -->
        <col class="w70">  <!-- 주문상태 -->
        <col class="w100">	<!-- 최종처리일 -->  
        <col class="w50">	<!-- 정산완료 -->
    </colgroup>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
        <th scope="col">가맹점</th>
        <th scope="col">주문번호</th>
        <th scope="col" colspan="2">상품명</th>
        <th scope="col">수량</th>
        <th scope="col">판매총액</th>
        <th scope="col">포인트</th>
        <th scope="col">실결제금액</th>
        <th scope="col">총주문금액</th>
        <th scope="col">결제수단</th>
        <th scope="col">주문자</th>
        <th scope="col">주문일</th>
        <th scope="col">주문상태</th>
        <th scope="col">최종처리일</th>
        <th scope="col">정산처리</th>
    </tr>
    </thead>
    <tbody>
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
    $bg = 'list'.($i%2);

    $amount = get_order_spay($row['od_id']);
    $sodr = get_order_list($row, $amount);

    $sql = " select * {$sql_common} {$sql_search} and od_id = '{$row['od_id']}' order by index_no ";
    $res = sql_query($sql);
    $rowspan = sql_num_rows($res);
    for($k=0; $row2=sql_fetch_array($res); $k++) {
        $gs = unserialize($row2['od_goods']);
?>
    <tr class="<?php echo $bg; ?>">
        <?php if($k == 0) { ?>
        <td rowspan="<?php echo $rowspan; ?>">  <!-- 체크박스 -->
            <input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
        </td>
        <td rowspan="<?php echo $rowspan; ?>"> 
            <?php echo $sodr['disp_pt_id']; ?> <!-- 가맹점 -->
        </td>

        <td rowspan="<?php echo $rowspan; ?>">  <!-- 주문번호 -->
            <a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a>
            <?php echo $sodr['disp_mobile']; ?>
        </td>
        <?php } ?>

        <td class="td_img">  <!-- 상품이미지 -->
            <a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a>
        </td>

        <td class="td_itname">  <!-- 상품명-->
            <a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a>
        </td>

        <td>  <!-- 수량 -->
            <?php echo number_format($row2['sum_qty']); ?>
        </td>

        <td class="tac">  <!-- 판매총액 -->
            <?php echo number_format($row2['goods_price']); ?>
        </td>

        <td>  <!-- 포인트 (2021-01-05) 포인트 현대리바트 추가 -->
            <?php echo number_format( ( $row2['use_point']+$row2['use_point2'] )  ); ?>
        </td>

        <td>  <!-- 실결제금액 -->
            <?php echo number_format( $row2['use_price'] ); ?>
        </td>

        <td class="tac td_price"> <!-- 총주문금액 -->
            <?php echo number_format( $row2['use_price']+$row2['use_point']+$row2['use_point2'] );?>
            <? if ($row2['cancel_price'])  {?> <br><span style="color:red;">-
                <? echo number_format($row2['cancel_price']);?> <span>
<?}
else if($row2['refund_price'])  {?><br><span style="color:red;">-
                        <? echo number_format($row2['refund_price']);?> <span>
<?}
if (((($row2['dan'] == 9 || $row2['dan'] == 7) && $row2['paymethod']=='가상계좌') || (($row2['dan'] == 9 || $row2['dan'] == 7) && $row2['paymethod']=='계좌이체')) && !$row['cancel_price']){?>
                            <br><span style="color:red;">-
                            <? echo number_format($row2['use_price']);?> <span>
                    <?}?>
        </td>



        <?php if($k == 0) { ?>
        <td rowspan="<?php echo $rowspan; ?>">  <!-- 결제수단 -->
            <?php echo $sodr['disp_paytype']; ?>
        </td>

        <td rowspan="<?php echo $rowspan; ?>">  <!-- 주문자 -->
            <?php echo $sodr['disp_od_name']; ?>
            <?php echo $sodr['disp_mb_id']; ?>
        </td>

        <td rowspan="<?php echo $rowspan; ?>"> <!-- 주문일 -->
            <?php echo substr($row['od_time'],2,14); ?>
        </td>
        <?php } ?>

        <td>
            <?php echo $gw_status[$row2['dan']]; ?> <!-- 주문상태 -->
        </td>

        <td class="tac"> 
            <?php echo substr($row2['rcent_time'],2,14)?>  <!--최종 처리일 -->
        </td>

        <td>
            <?php echo $row2['calculate'] ?> <!-- 정산처리유무 -->
        </td>
    </tr>
<?php
    }
}
sql_free_result($result);
if($i==0)
    echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
?>
    </tbody>
    </table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function checked_multi(checkEl){
    if(checkEl == ''){
        $('input:checkbox[name="od_status_2"]').prop('checked',false);
        $('input:checkbox[name="od_status_3"]').prop('checked',false);
        $('input:checkbox[name="od_status_4"]').prop('checked',false);
        $('input:checkbox[name="od_status_5"]').prop('checked',false);
        $('input:checkbox[name="od_status_6"]').prop('checked',false);
        $('input:checkbox[name="od_status_7"]').prop('checked',false);
        $('input:checkbox[name="od_status_8"]').prop('checked',false);
        $('input:checkbox[name="od_status_9"]').prop('checked',false);
        $('input:checkbox[name="od_status_10"]').prop('checked',false);
        $('input:checkbox[name="od_status_11"]').prop('checked',false);
        $('input:checkbox[name="od_status_12"]').prop('checked',false);
        $('input:checkbox[name="od_status_13"]').prop('checked',false);
    }
    else{
        $('input:checkbox[name="od_status_0"]').prop('checked',false);
    }
}



function dan_change(){
    $('#dan').val($('#dan_val').text());
}
function fcalculatelist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택정산") {

        if(!confirm("선택한 자료를 정산하시겠습니까?")) {
            return false;
        }else{

        }
    }								
    return true;
}


$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

    $("#frmPtCalcExcel").on("click", function() {
        var type = $(this).attr("id");
        var od_chk = new Array();
        var od_id = "";
        var $el_chk = $("input[name='chk[]']");

        $el_chk.each(function(index) {
            if($(this).is(":checked")) {
                od_chk.push($("input[name='od_id["+index+"]']").val());
            }
        });

        if(od_chk.length > 0) {
            od_id = od_chk.join();
        }
        if(od_id == "") {
            alert("처리할 자료를 하나 이상 선택해 주십시오.");
            return false;
        } else {
            this.href = "./partner/pt_calculate_excel2.php?od_id="+od_id;
            return true;
        }
    });
});

</script>
