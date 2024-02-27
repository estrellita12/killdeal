<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "전체 상품관리";
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_brand))			$qstr .= "&q_brand=$q_brand";
if(isset($q_zone))			$qstr .= "&q_zone=$q_zone";
if(isset($q_stock_field))	$qstr .= "&q_stock_field=$q_stock_field";
if(isset($fr_stock))		$qstr .= "&fr_stock=$fr_stock";
if(isset($to_stock))		$qstr .= "&to_stock=$to_stock";
if(isset($q_price_field))	$qstr .= "&q_price_field=$q_price_field";
if(isset($fr_price))		$qstr .= "&fr_price=$fr_price";
if(isset($to_price))		$qstr .= "&to_price=$to_price";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";
if(isset($q_option))		$qstr .= "&q_option=$q_option";
if(isset($q_supply))		$qstr .= "&q_supply=$q_supply";
if(isset($q_notax))			$qstr .= "&q_notax=$q_notax";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods ";
//$sql_search = " where use_aff = 1 and mb_id = '{$member['id']}' ";
$sql_search = " where use_aff = 0 ";

if($sca) {
    $sql_search .= " and (ca_id like '$sca%' or ca_id2 like '$sca%' or ca_id3 like '$sca%') ";
}

// 검색어
if($stx) {
    switch($sfl) {
    case "gname" :
    case "explan" :
    case "maker" :
    case "origin" :
    case "model" :
        $sql_search .= " and $sfl like '%$stx%' ";
        break;
    default :
        $sql_search .= " and $sfl like '$stx%' ";
        break;
    }
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and $q_date_field between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
    $sql_search .= " and $q_date_field between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
    $sql_search .= " and $q_date_field between '$to_date 00:00:00' and '$to_date 23:59:59' ";

// 브랜드
if(isset($q_brand) && $q_brand)
    $sql_search .= " and brand_uid = '$q_brand' ";

// 배송가능 지역
if(isset($q_zone) && $q_zone)
    $sql_search .= " and zone = '$q_zone' ";

// 상품재고
if($fr_stock && $to_stock)
    $sql_search .= " and $q_stock_field between '$fr_stock' and '$to_stock' ";

// 상품가격
if($fr_price && $to_price)
    $sql_search .= " and $q_price_field between '$fr_price' and '$to_price' ";

// 판매여부
if(isset($q_isopen) && is_numeric($q_isopen))
    $sql_search .= " and isopen='$q_isopen' ";

// 과세유형
if(isset($q_notax) && is_numeric($q_notax))
    $sql_search .= " and notax = '$q_notax' ";

// 상품 필수옵션
if(isset($q_option) && is_numeric($q_option)) {
    if($q_option)
        $sql_search .= " and opt_subject <> '' ";
    else
        $sql_search .= " and opt_subject = '' ";
}

// 상품 추가옵션
if(isset($q_supply) && is_numeric($q_supply)) {
    if($q_supply)
        $sql_search .= " and spl_subject <> '' ";
    else
        $sql_search .= " and spl_subject = '' ";
}

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
    $page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<script>
function getShortUrl(obj,preUrl){
    var next = $(obj).next();

    $.ajax({
    type: "POST",
        url: "../shop/ajax_short_url.php",
        data : {"preUrl":preUrl},
        dataType: "json",
        success: function (data) {
            $(next).css("display","inline-block");
            var obj = JSON.parse(data);
            var url = obj.result.url;

            $(next).val(url);
            $(next).select();

            <?php if( preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT']) == "0" &&  preg_match('/Trident/i',$_SERVER['HTTP_USER_AGENT']) == "0" ){ ?>
            navigator.clipboard.writeText(url)
                .then(() => {
                console.log('Text copied to clipboard');
            })
                .catch(err => {
                console.error('Error in copying text: ', err);
                });

            <?php } ?>
        },
        error:function(data){
            console.log("error : "+data)
        }
    });
}

</script>

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
                <?php echo option_selected('gname', $sfl, '상품명'); ?>
                <?php //echo option_selected('gcode', $sfl, '상품코드'); //(2021-02-26) ?>
                <?php echo option_selected('index_no', $sfl, '상품코드'); ?>
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
$sql = "select *
    from shop_brand
    where br_user_yes = '0' OR (br_user_yes = '1' and mb_id = '{$member['id']}')
    order by br_name asc ";
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
    </tbody>
    </table>
</div>
<div class="btn_confirm">
    <input type="submit" value="검색" class="btn_medium">
    <input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fgoodslist" id="fgoodslist" method="post" action="./partner_goods_list_update.php" onsubmit="return fgoodslist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
    <span class="ov_a">
        <select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;">
            <?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
            <?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
            <?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
            <?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
        </select>
    </span>
</div>

<div class="tbl_head02">
    <table id="sodr_list" class="tablef">
    <colgroup>
        <col class="w50">
        <col class="w50">
        <col class="w60">
        <col class="w120">
        <col>
        <col>
        <col class="w80">
        <col class="w80">
        <col class="w90">
        <col class="w90">
    </colgroup>
    <thead>
    <tr>
        <th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
        <th scope="col" rowspan="2">번호</th>
        <th scope="col" rowspan="2">이미지</th>
        <th scope="col"><?php echo subject_sort_link('index_no',$q2); ?>상품코드</a></th>
        <th scope="col" colspan="2"><?php echo subject_sort_link('gname',$q2); ?>상품명</a></th>
        <th scope="col"><?php echo subject_sort_link('reg_time',$q2); ?>최초등록일</a></th>
        <th scope="col"><?php echo subject_sort_link('isopen',$q2); ?>진열</a></th>
        <th scope="col" colspan="2" class="th_bg">가격정보</th>
    </tr>
    <tr class="rows">
        <th scope="col">가맹점ID</th>
        <th scope="col">업체명</th>
        <th scope="col">카테고리</th>
        <th scope="col"><?php echo subject_sort_link('update_time',$q2); ?>최근수정일</a></th>
        <th scope="col"><?php echo subject_sort_link('stock_qty',$q2); ?>재고</a></th>
        <th scope="col" class="th_bg"><?php echo subject_sort_link('normal_price',$q2); ?>시중가</a></th>
        <th scope="col" class="th_bg"><?php echo subject_sort_link('goods_price',$q2); ?>판매가</a></th>
    </tr>
    </thead>
    <tbody>
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
    $gs_id = $row['index_no'];
        /*
        if($row['stock_mod'])
            $stockQty = number_format($row['stock_qty']);
        else
            $stockQty = '<span class="txt_false">무제한</span>';
         */
    $stockQty = number_format($row['stock_qty']);
    $bg = 'list'.($i%2);
?>
    <tr class="<?php echo $bg; ?>">
        <td rowspan="2">
            <input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
        </td>
        <td rowspan="2"><?php echo $num--; ?></td>
        <td rowspan="2"><a href="https://<?php echo $member['homepage']; ?>/shop/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
        <td><?php echo $row['index_no']; ?></td>
        <td colspan="2" class="tal"><?php echo get_text($row['gname']); ?></td>
        <td><?php echo substr($row['reg_time'],2,8); ?></td>
        <td><?php echo $gw_isopen[$row['isopen']]; ?></td>
        <td rowspan="2" class="tar"><?php echo number_format($row['normal_price']); ?></td>
        <td rowspan="2" class="tar"><?php echo number_format($row['goods_price']); ?></td>
    </tr>
    <tr class="<?php echo $bg; ?>">
        <td class="fc_00f"><?php echo $row['mb_id']; ?></td>
        <td class="tal">https://<?php echo $member['homepage']; ?>/shop/view.php?index_no=<?php echo $gs_id; ?></td>
        <td class="tal"> 
            <span class="shortUrlRes curp" onclick="getShortUrl(this,'https://<?php echo $member['homepage']; ?>/shop/view.php?index_no=<?php echo $gs_id; ?>')">링크 복사 <i class="fa fa-share-alt" aria-hidden="true"></i></span>
            <input type="text" name="shortUrl" class="w200 frm_input dn marl5" style="vertical-align:revert">
        </td>
        <td class="fc_00f"><?php echo substr($row['update_time'],2,8); ?></td>
        <td><?php echo $stockQty; ?></td>
    </tr>
<?php
}
if($i==0)
    echo '<tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
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
    // 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
    $("#fr_date,#to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});




</script>

<?php
include_once("./admin_tail.sub.php");
?>
