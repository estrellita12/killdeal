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

$sql = "select * from shop_order $sql_search order by od_time";
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
                <col class="w100">
                <col class="w100">
                <col class="w50">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th scope="col">주문일시</th>
                <th scope="col">가맹점</th>
                <th scope="col">주문번호</th>
                <th scope="col">옵션</th>
            </tr>
            </thead>
            <tbody class="list">
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $bg = 'list'.($i%2);
                
                $it_options = print_complete_options($row['gs_id'], $row['od_id']);
                if($it_options){
                    $it_options = '<div class="sod_opt">'.$it_options.'</div>';
                }

            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="tar"><?php echo $row['od_time']; ?></td>
                <td class="tar fs11"><?php echo trans_pt_name($row['pt_id']) ?></td>
                <td class="tar"><a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row    ['od_id']; ?></a></td>
                <td class="tar"><?php echo $it_options; ?></td>
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
