<?php
define('_NEWWIN_', true);
include_once('./_common.php');

$sql_search = "where gs_id = $gs_id and dan>1";

if(isset($q_date_field) && $q_date_field=='od_time' && ( $fr_date || $to_date )){

    // 기간검색
    if($fr_date && $to_date)
        $sql_search .= " and $q_date_field between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
    else if($fr_date && !$to_date)
        $sql_search .= " and $q_date_field between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
    else if(!$fr_date && $to_date)
        $sql_search .= " and $q_date_field between '$to_date 00:00:00' and '$to_date 23:59:59' ";

    $msg = $fr_date." ~ ".$to_date;
}else{
    $msg = "전체";
}

$sql = "select pt_id, sum(sum_qty) as total_sum_qty, sum(if(dan = 2 or dan = 3 or dan=4 or dan=5, sum_qty,0)) as buy_cnt, sum(if(dan = 6,sum_qty,0)) as cancel_cnt, sum(if(dan = 10 or dan = 11 or dan=7,sum_qty,0)) as return_cnt, sum(if(dan = 9,sum_qty,0)) as refund_cnt, sum(if(dan=12 or dan = 13 or dan = 8,sum_qty,0)) as change_cnt , sum(if(dan = 0,sum_qty,0)) as dan6, count(od_id) as total_od_id, sum(goods_price+baesong_price) as total_use_price, pt_id as pt_id, od_time as od_time from shop_order $sql_search  group by pt_id ";

$result = sql_query($sql);

$tb['title'] = "가맹점 별 판매 수량";
include_once(TB_ADMIN_PATH."/admin_head.php");

?>
<div id="sodr_pop" class="new_win">
    <h1><?php echo $tb['title']; ?></h1>
    <section id="anc_sodr_list">
    <div class="local_desc02 local_desc">
        <p><?php echo $msg ?></p>
    </div>
        <div class="tbl_head01">
            <table id="sodr_list">
            <colgroup>
                <col>
                <col class="w40">
                <col class="w40">
                <col class="w40">
                <col class="w40">
                <col class="w40">
                <col class="w40">
                <col class="w40">
                <col class="w40">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">가맹점</th>
                <th scope="col">총주문건수</th>
                <th scope="col">총주문수량</th>
                <th scope="col">판매수량</th>
                <th scope="col">취소수량</th>
                <th scope="col">환불수량</th>
                <th scope="col">반품수량</th>
                <th scope="col">교환수량</th>
                <th scope="col">판매총액</th>
            </tr>
            </thead>
            <tbody class="list">
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $bg = 'list'.($i%2);
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="tar fs11"><?php echo trans_pt_name($row['pt_id']) ?></td>
                <td class="tar"><?php echo number_format($row['total_od_id']); ?></td>
                <td class="tar"><?php echo number_format($row['total_sum_qty']); ?></td>
                <td class="tar"><?php echo number_format($row['buy_cnt']); ?></td>
                <td class="tar"><?php echo number_format($row['cancel_cnt']); ?></td>
                <td class="tar"><?php echo number_format($row['return_cnt']); ?></td>
                <td class="tar"><?php echo number_format($row['refund_cnt']); ?></td>
                <td class="tar"><?php echo number_format($row['change_cnt']); ?></td>
                <td class="td_price"><?php echo number_format($row['total_use_price']); ?></td>
            </tr>
            <?php } ?>
            </tbody>
            </table>
        </div>
        <div class="btn_confirm">
            <a href="javascript:window.close();" class="btn_medium bx-white">닫기</a>
        </div>
    </section>
</div>
