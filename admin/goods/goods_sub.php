<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/goods/goods_get.inc.php');

$sql_common = " from shop_goods ";
$sql_search = " where use_aff = 0 and shop_state = 0 ";

//include_once(TB_ADMIN_PATH.'/goods/goods_sub.php');
include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
    $page_rows = $_SESSION['ss_page_rows'];
else
    $page_rows = 10;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select *,(sum_qty*goods_price) as sum_price $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="#" id="frmGoodsExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./goods/goods_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
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
                <?php echo option_selected('index_no', $sfl, '상품코드'); ?>
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
                <?php echo option_selected('update_time', $q_date_field, "최근수정일"); ?>
                <?php echo option_selected('reg_time', $q_date_field, "최초등록일"); ?>
            </select>
            <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">브랜드</th>
        <td>
            <select name="q_brand">
                <?php
                echo option_selected('', $q_brand, '전체');
                $sql = "select * from shop_brand where br_user_yes='0' order by br_name asc ";
                $res = sql_query($sql);
                while($row = sql_fetch_array($res)){
                    echo option_selected($row['br_id'], $q_brand, $row['br_name']);
                }
                ?>
            </select>
        </td>
        <th scope="row">배송가능 지역</th>
        <td>
            <select name="q_zone">
                <?php echo option_selected('',  $q_zone, '전체'); ?>
                <?php echo option_selected('전국', $q_zone, '전국'); ?>
                <?php echo option_selected('강원도', $q_zone, '강원도'); ?>
                <?php echo option_selected('경기도', $q_zone, '경기도'); ?>
                <?php echo option_selected('경상도', $q_zone, '경상도'); ?>
                <?php echo option_selected('서울/경기도', $q_zone, '서울/경기도'); ?>
                <?php echo option_selected('서울특별시', $q_zone, '서울특별시'); ?>
                <?php echo option_selected('전라도', $q_zone, '전라도'); ?>
                <?php echo option_selected('제주도', $q_zone, '제주도'); ?>
                <?php echo option_selected('충청도', $q_zone, '충청도'); ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">상품재고</th>
        <td>
            <select name="q_stock_field" id="q_stock_field">
                <?php echo option_selected('stock_qty', $q_stock_field, "재고수량"); ?>
                <?php echo option_selected('noti_qty', $q_stock_field, "통보수량"); ?>
            </select>
            <label for="fr_stock" class="sound_only">재고수량 시작</label>
            <input type="text" name="fr_stock" value="<?php echo $fr_stock; ?>" id="fr_stock" class="frm_input" size="6"> 개 이상 ~
            <label for="to_stock" class="sound_only">재고수량 끝</label>
            <input type="text" name="to_stock" value="<?php echo $to_stock; ?>" id="to_stock" class="frm_input" size="6"> 개 이하
        </td>
        <th scope="row">상품가격</th>
        <td>
            <select name="q_price_field" id="q_price_field">
                <?php echo option_selected('goods_price', $q_price_field, "판매가격"); ?>
                <?php echo option_selected('supply_price', $q_price_field, "공급가격"); ?>
                <?php echo option_selected('normal_price', $q_price_field, "시중가격"); ?>
                <?php echo option_selected('gpoint', $q_price_field, "포인트"); ?>
            </select>
            <label for="fr_price" class="sound_only">상품가격 시작</label>
            <input type="text" name="fr_price" value="<?php echo $fr_price; ?>" id="fr_price" class="frm_input" size="6"> 원 이상 ~
            <label for="to_price" class="sound_only">상품가격 끝</label>
            <input type="text" name="to_price" value="<?php echo $to_price; ?>" id="to_price" class="frm_input" size="6"> 원 이하
        </td>
    </tr>
    <tr>
        <th scope="row">판매여부</th>
        <td>
            <?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '1', '진열'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '2', '품절'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '3', '단종'); ?>
            <?php echo radio_checked('q_isopen', $q_isopen, '4', '중지'); ?>
        </td>
        <th scope="row">필수옵션</th>
        <td>
            <?php echo radio_checked('q_option', $q_option,  '', '전체'); ?>
            <?php echo radio_checked('q_option', $q_option, '1', '사용'); ?>
            <?php echo radio_checked('q_option', $q_option, '0', '미사용'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">과세유형</th>
        <td>
            <?php echo radio_checked('q_notax', $q_notax,  '', '전체'); ?>
            <?php echo radio_checked('q_notax', $q_notax, '1', '과세'); ?>
            <?php echo radio_checked('q_notax', $q_notax, '0', '비과세'); ?>
        </td>
        <th scope="row">추가옵션</th>
        <td>
            <?php echo radio_checked('q_supply', $q_supply,  '', '전체'); ?>
            <?php echo radio_checked('q_supply', $q_supply, '1', '사용'); ?>
            <?php echo radio_checked('q_supply', $q_supply, '0', '미사용'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">타임세일</th>
        <td>
            <?php echo radio_checked('q_timesale', $q_timesale,  '', '전체'); ?>
            <?php echo radio_checked('q_timesale', $q_timesale, '1', '사용'); ?>
            <?php echo radio_checked('q_timesale', $q_timesale, '0', '미사용'); ?>
        </td>
        <th scope="row">사이드배너</th>
        <td>
            <?php echo radio_checked('q_sidebanner', $q_sidebanner,  '', '전체'); ?>
            <?php echo radio_checked('q_sidebanner', $q_sidebanner, '1', '사용'); ?>
            <?php echo radio_checked('q_sidebanner', $q_sidebanner, '0', '미사용'); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">장바구니 추천</th>
        <td>
            <?php echo radio_checked('q_recomm_use', $q_recomm_use,  '', '전체'); ?>
            <?php echo radio_checked('q_recomm_use', $q_recomm_use, '1', '사용'); ?>
            <?php echo radio_checked('q_recomm_use', $q_recomm_use, '0', '미사용'); ?>
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


