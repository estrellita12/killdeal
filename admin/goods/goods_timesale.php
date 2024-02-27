<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_timesale ";
$sql_search = " where mb_id = 'admin' ";
$sql_order = " order by ts_no desc ";
if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./goods.php?code=timesale_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 타임세일등록</a>
EOF;
?>

<h2>기본검색</h2>
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
        <th scope="row">검색어</th>
        <td>
            <select name="sfl">
                <?php echo option_selected('ts_name', $sfl, '타임세일명'); ?>
            </select>
            <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
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

<form name="fplanlist" id="fplanlist" method="post" action="./goods/goods_timesale_update.php" onsubmit="return ftimesalelist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
    <table>
    <colgroup>
        <col class="w50">
        <col class="w50">
        <col>
        <col class="w200">
        <col class="w200">
        <col class="w60">
    </colgroup>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
        <th scope="col">번호</th>
        <!-- <th scope="col">노출</th> -->
        <th scope="col">타임세일명</th>
        <th scope="col">시작시간</th>
        <th scope="col">종료시간</th>
        <th scope="col">수정</th>
    </tr>
    </thead>
    <?php
    for($i=0; $row=sql_fetch_array($result); $i++) {
        if($i==0)
            echo '<tbody class="list">'.PHP_EOL;
        $bg = 'list'.($i%2);
    
        $sql_search = " where 1!=1 ";
        $sql_order = "  ";
        if( isset($row) && $row!="" && $row[ts_it_code] !=""){
            $ts_list_code = explode(",", $row[ts_it_code]); // 배열을 만들고
            $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
            $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
            $ts_list_code = implode(",",$ts_list_code );
            $sql_search = " where index_no in ( $ts_list_code )";
            $sql_order = " order by field ( index_no, $ts_list_code ) ";
        }
        $sql = " select * from shop_goods $sql_search $sql_order";
        $gsres = sql_query($sql);

    ?>
    <tr class="<?php echo $bg; ?>">
        <td>
            <input type="hidden" name="ts_no[<?php echo $i; ?>]" value="<?php echo $row['ts_no']; ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
        </td>
        <td><?php echo $num--; ?></td>
        <td class="tac"><?php echo $row['ts_name'] ?><br>
            <div class="tbl_head02 fs11 dn">
                <table id="sodr_list">
                    <colgroup>
                        <col class="w60">
                        <col class="w60">
                        <col>
                        <col class="w50">
                        <col class="w50">
                        <col class="w60">
                        <col class="w60">
                        <col class="w60">
                        <col class="w60">
                        <col class="w60">
                        <col class="w70">
                        <col class="w70">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">상품코드</th>
                            <th scope="col">이미지</th>
                            <th scope="col">상품명</a></th>
                            <th scope="col">진열</a></th>
                            <th scope="col">재고</a></th>
                            <th scope="col">총주문수량</th>
                            <th scope="col">판매수량</th>
                            <th scope="col">취소수량</th>
                            <th scope="col">환불수량</th>
                            <th scope="col">반품수량</th>
                            <th scope="col" class="th_bg">판매가</a></th>
                            <th scope="col" class="th_bg">타임세일가</a></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        for($j=0; $gsrow=sql_fetch_array($gsres); $j++) {
                            $gs_id = $gsrow['index_no'];
                            $stockQty = number_format($gsrow['stock_qty']);

                            $time_sale_rate = $row['ts_sale_rate'];
                            $time_sale_unit = $row['ts_sale_unit'];
                            $price = ( $gsrow['goods_price'] - ( ($gsrow['goods_price'] / 100) * $time_sale_rate) );
                            if(strlen($price) > 1 && $time_sale_unit)
                                $price = floor((int)$price/(int)$time_sale_unit) * (int)$time_sale_unit;

                            $bg = 'list'.($j%2);

                            $odsql = " select sum(sum_qty) as total_sum_qty, sum(if(dan = 2 or dan = 3 or dan=4 or dan=5, sum_qty,0)) as buy_cnt, sum(if(dan = 6,sum_qty,0)) as cancel_cnt, sum(if(dan = 10 or dan = 11 or dan=7,sum_qty,0)) as return_cnt, sum(if(dan = 9,sum_qty,0)) as refund_cnt, sum(if(dan=12 or dan = 13 or dan = 8,sum_qty,0)) as change_cnt   from shop_order where gs_id='$gs_id' and dan>1  and od_time >= '$row[ts_sb_date]'  and od_time <= '$row[ts_ed_date]' ";
                            $odres = sql_query($odsql);
                            $odrow = sql_fetch_array($odres);
                    ?>
                        <tr class="<?php echo $bg; ?>">
                            <td><?php echo $gsrow['index_no']; ?></td>
                            <td><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $gsrow['simg1'], 40, 40); ?></a></td>
                            <td class="tal"><?php echo get_text($gsrow['gname']); ?></td>
                            <td><?php echo $gw_isopen[$gsrow['isopen']]; ?></td>
                            <td><?php echo $stockQty; ?></td>
                            <td><?php echo $odrow['total_sum_qty']==""? 0:$odrow['total_sum_qty']; ?></td>
                            <td><?php echo $odrow['buy_cnt']==""?0:$odrow['buy_cnt']; ?></td>
                            <td><?php echo $odrow['cancel_cnt']==""?0:$odrow['cancel_cnt']; ?></td>
                            <td><?php echo $odrow['return_cnt']==""?0:$odrow['return_cnt']; ?></td>
                            <td><?php echo $odrow['refund_cnt']==""?0:$odrow['refund_cnt']; ?></td>
                            <td class="tar"><?php echo number_format($gsrow['goods_price']); ?></td>
                            <td class="tar"><?php echo number_format($price); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </td>
        <td><?php echo $row['ts_sb_date'] ?></td>
        <td><?php echo $row['ts_ed_date'] ?></td>
        <?php $s_upd = "<a href=\"./goods.php?code=timesale_form&w=u&ts_no={$row['ts_no']}$qstr&page=$page\" class=\"btn_small\">수정</a>"; ?>
        <td><?php echo $s_upd ?></td>
    </tr>
    <?php
    }
    if($i==0)
        echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
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

    $('.tac').click( function() {
        $(this).find('.dn').toggle(200);
    });

});

function fplanlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>
















