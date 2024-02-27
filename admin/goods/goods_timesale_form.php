<?php
if(!defined('_TUBEWEB_')) exit;

if($w == '') {
    //$ts['ts_use'] = 1;
} else if($w == 'u') {
    $ts = sql_fetch("select * from shop_goods_timesale where ts_no = '{$ts_no}' ");
    $sb_date = explode(" ",$ts['ts_sb_date']);
    $ed_date = explode(" ",$ts['ts_ed_date']);
    if(!$ts['ts_no'])
        alert('자료가 존재하지 않습니다.');
}

$frm_submit = '<div class="btn_confirm">
    <input type="submit" value="저장" class="btn_large" accesskey="s">
    <a href="./goods.php?code=timesale'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
$frm_submit .= '</div>';



?>


<form name="fregform" method="post" action="./goods/goods_timesale_form_update.php" onsubmit="return fboardform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="ts_no" value="<?php echo $ts_no; ?>">
<input type="hidden" name="ts_use" value="1">

<div class="tbl_frm02">
    <table>
    <colgroup>
        <col class="w140">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">타임특가명</th>
        <td><input type="text" name="ts_name" value="<?php echo $ts['ts_name']; ?>" required itemname="타임특가명" class="frm_input required" size="50"></td>
    </tr>
    <tr>
        <th scope="row">시작시간</th>
        <td>
            <input type="date" name="ts_sb_date" value="<?php echo $sb_date[0]; ?>" required itemname="시작 날짜" class="frm_input required">
            <input type="time" name="ts_sb_time" value="<?php echo isset($sb_date) ? $sb_date[1] : "00:00" ?>" required itemname="시작 시간" class="frm_input required">
            <span class="fc_197 marl10">시작날짜의 시작시간부터 </span>
        </td>
    </tr>
    <tr>
        <th scope="row">종료시간</th>
        <td>
            <input type="date" name="ts_ed_date" value="<?php echo $ed_date[0]; ?>" required itemname="종료 날짜" class="frm_input required">
            <input type="time" name="ts_ed_time" value="<?php echo isset($ed_date) ? $ed_date[1] : "00:00"; ?>" required itemname="종료 시간" class="frm_input required">
            <span class="fc_197 marl10">종료날짜의 종료시간 전까지</span>
        </td>
    </tr>
    <tr>
        <th scope="row">할인율</th>
        <td class="tal">
            <input type="text" name="ts_sale_rate" value="<?php echo $ts['ts_sale_rate']; ?>" required itemname="타임특가명" class="frm_input required" size="10"> %
            <?php echo help('할인율은 정수만 설정 가능합니다.'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">절사</th>
        <td class="tal">
            <select name="ts_sale_unit">
                <?php echo option_selected('0', $ts['ts_sale_unit'], '사용안함'); ?>
                <?php echo option_selected('10', $ts['ts_sale_unit'], '십원 단위절사'); ?>
                <?php echo option_selected('100', $ts['ts_sale_unit'], '백원 단위절사'); ?>
                <?php echo option_selected('1000', $ts['ts_sale_unit'], '천원 단위절사'); ?>
                <?php echo option_selected('10000', $ts['ts_sale_unit'], '만원 단위절사'); ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">상품코드</th>
        <td>
            <textarea name="ts_it_code" class="frm_input wfull" style="height:50px;resize:none;" required class="required"><?php echo $ts['ts_it_code']; ?></textarea>
            <?php echo help('상품 코드 입력시  , 쉼표로 구분해서 입력해주세요.'); ?>
        </td>
    </tr>
    <tr>
<?php
$sql_search = " where 1!=1 ";
$sql_order = " ";
if(isset($ts) && $ts!="" && $ts[ts_it_code] !=""){
    $ts_list_code = explode(",", $ts[ts_it_code]); // 배열을 만들고
    $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
    $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
    $ts_list_code = implode(",",$ts_list_code );
    $sql_search = " where index_no in ( $ts_list_code )";
    $sql_order = " order by field ( index_no, $ts_list_code ) ";
}
$sql = " select count(*) as cnt from shop_goods $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * from shop_goods $sql_search $sql_order";
$result = sql_query($sql);
?>
        <th scope="row">관련상품<br><b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회</th>
        <td>
<div class="tbl_head02">
    <table id="sodr_list">
    <colgroup>
        <col class="w60">
        <col class="w60">
        <col>
        <col>
        <col class="w80">
        <col class="w80">
        <col class="w80">
        <col class="w80">
        <col class="w80">
        <col class="w80">
        <col class="w80">
    </colgroup>
    <thead>
    <tr>
        <th scope="col" >상품코드</th>
        <th scope="col" >이미지</th>
        <th scope="col" >상품명</a></th>
        <th scope="col" >카테고리</th>
        <th scope="col" >최초등록일</a></th>
        <th scope="col" >최근수정일</a></th>
        <th scope="col" >진열</a></th>
        <th scope="col" >재고</a></th>
        <th scope="col" class="th_bg">시중가</a></th>
        <th scope="col" class="th_bg">판매가</a></th>
        <th scope="col" class="th_bg">타임세일가</a></th>
    </tr>
    </thead>
    <tbody>
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
    $gs_id = $row['index_no'];
    $stockQty = number_format($row['stock_qty']);

    $time_sale_rate = $ts['ts_sale_rate'];
    $time_sale_unit = $ts['ts_sale_unit'];
    $price = ( $row['goods_price'] - ( ($row['goods_price'] / 100) * $time_sale_rate) );
    if(strlen($price) > 1 && $time_sale_unit)
        $price = floor((int)$price/(int)$time_sale_unit) * (int)$time_sale_unit;
    $bg = 'list'.($i%2);
?>
    <tr class="<?php echo $bg; ?>">
        <td><?php echo $row['index_no']; ?></td>
        <td><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
        <td class="tal"><?php echo get_text($row['gname']); ?></td>
        <td class="tal txt_succeed"><?php echo get_cgy_info($row); ?></td>
        <td><?php echo substr($row['reg_time'],2,8); ?></td>
        <td><?php echo substr($row['update_time'],2,8); ?></td>
        <td><?php echo $gw_isopen[$row['isopen']]; ?></td>
        <td><?php echo $stockQty; ?></td>
        <td class="tar"><?php echo number_format($row['normal_price']); ?></td>
        <td class="tar"><?php echo number_format($row['goods_price']); ?></td>
        <td class="fc_00f tar"><?php echo number_format($price); ?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>



        </td>
    </tr>

    </tbody>
    </table>
</div>
<?php echo $frm_submit; ?>
</form>



