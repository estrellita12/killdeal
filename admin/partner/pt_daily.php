<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order";

// 날짜선택(datepicker)
$sql_seah = "";
if($fr_date && $to_date)
    $sql_seah = "and left(od_time,10) between '$fr_date' and '$to_date' ";
else if(!$fr_date && $to_date)
    $sql_seah = "and left(od_time,10) <= '$to_date' ";
else if($fr_date && !$to_date)
    $sql_seah = "and left(od_time,10) >= '$fr_date' ";

$date_msg = "";
if(!$fr_date && !$to_date){
    $date_msg = "전체";
}else{
    $date_msg = $fr_date.' ~ '.$to_date;
}

if(!$orderby) {
    $filed = "buy_price";
    $sod = "desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";


$sql = "select '$date_msg' as date_msg,pt_id,count(distinct od_id) as od_cnt,sum(goods_price+baesong_price) as buy_price,sum(sum_qty) as qty_cnt from shop_order where dan in ('2','3','4','5','8','12','13') $sql_seah group by pt_id $sql_order";
$res = sql_query($sql);

$pt_list = get_partner_list();

$tot_od_cnt = 0;
$tot_qty_cnt = 0;
$tot_buy_price = 0;
/*
$btn_frmline = <<<EOF
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./partner/pt_daily_excel.php?code=pt_daily&fr_date=$fr_date&to_date=$to_date" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
<a href="./partner/pt_total_excel.php" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 전체데이터 엑셀저장</a>
EOF;
*/
$btn_frmline = <<<EOF
<a href="./partner/pt_daily_excel.php?code=pt_daily&fr_date=$fr_date&to_date=$to_date" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
<a href="./partner/pt_daily2_excel.php?code=pt_daily&fr_date=$fr_date&to_date=$to_date" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 상세결과 엑셀저장</a>
EOF;


include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<!-- 가맹점별 총 매출과 총 주문수량,총 주문건을 조회 할 수 있음  -->
<h2>가맹점별 매출 검색</h2>
<p style="font-size:13px;color:#999;padding-bottom:15px;">* 가맹점별 선택기간 매출의 합 조회</p>
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
                <th scope="row">기간검색</th>
                <td>
                    <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="btn_confirm" style="margin-bottom:20px;">
        <input type="submit" value="검색" class="btn_medium">
        <input type="button" value="초기화" id="frmRest" class="btn_medium red">
    </div>
</form>
<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01"> 
   <table id="sodr_list">
        <colgroup>
            <col class="w20">
            <col class="w100">
            <col class="w100">  
            <col class="w50">  
            <col class="w150"> 
            <col class="w150"> 
        </colgroup>
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">가맹점명</th>
                <th scope="col">날짜</th>
                <th scope="col"><?php echo subject_sort_link('od_cnt',$q2); ?>주문건</th>
                <th scope="col"><?php echo subject_sort_link('qty_cnt',$q2); ?>주문수량</th>
                <th scope="col"><?php echo subject_sort_link('buy_price',$q2); ?>매출</th>
            </tr>
        </thead>
        <tbody>
<?php 
for($i=0; $row=sql_fetch_array($res); $i++) { 
?>
        <tr>
            <td><!--<input type="radio" name="pt_chk" value="<?php echo $row['pt_id']; ?>"> --></td>
            <td><span>
            <?php 
                echo trans_pt_name($row['pt_id']);  
                if ( ($key = array_search($row['pt_id'], $pt_list) ) !== false) {
                    unset($pt_list[$key]);
                }
            ?>
            </span></td>
            <td><?php echo $row['date_msg'];?> </td>
            <td><?php echo number_format($row['od_cnt']); $tot_od_cnt+=$row['od_cnt'];  ?>건</td>
            <td><strong style="color:gray"><?php echo number_format($row['qty_cnt']); $tot_qty_cnt+=$row['qty_cnt']; ?>개</strong></td>
            <td><strong><?php echo number_format($row['buy_price']); $tot_buy_price+=$row['buy_price']; ?>원</strong></td>
        </tr>
        <?php } ?>

        <?php foreach($pt_list as $x){ ?>
        <tr>
            <td><!--<input type="radio" name="pt_chk" value="<?php echo $x; ?>">--> </td>
            <td><span><?php echo trans_pt_name($x); ?> </span></td>
            <td><?php echo $date_msg ?></td>
            <td>0건</td>
            <td><strong style="color:gray">0개</strong></td>
            <td><strong>0원</strong></td>
        </tr>
        <?php } ?>
        <tr>
            <th scope=col colspan="2" rowspan="2">전체 가맹점</th>
            <th scope=col>날짜</th>
            <th scope=col>전체 주문건</th>
            <th scope=col>전체 주문수량</th>
            <th scope=col>전체 매출</th>
        </tr>
        <tr>
            <td scope=col><?php echo $date_msg ?></td>
            <td scope=col><?php echo number_format($tot_od_cnt); ?>건</td>
            <td scope=col><strong><?php echo number_format($tot_qty_cnt); ?>개</strong></td>
            <td scope=col><strong><?php echo number_format($tot_buy_price); ?>원</strong></td>
        </tr>
 
        </tbody>
    </table>
</div>

    <script>


    $(function(){
        $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

        $("#frmOrderExcel").on("click", function() {
            var pt_chk = "";
            var $el_chk = $("input[name='pt_chk']");
            $el_chk.each(function(index) {
                if($(this).is(":checked")) {
                    pt_chk = $(this).val();
                }
            });

            if(pt_chk == "") {
                alert("처리할 자료를 하나 이상 선택해 주십시오.");
                return false;
            } else {
                this.href = "./partner/pt_daily_excel.php?code=pt_daily&fr_date=<?php echo $fr_date ?>&to_date=<?php echo $to_date ?>&pt_chk="+pt_chk;
                return true;
            }
        });
    });

    </script>
