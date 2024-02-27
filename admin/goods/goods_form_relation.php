<?php
include_once("./_common.php");

$gname = trim(strip_tags($gname));
$sca = trim($sca);
$mb_id = trim($mb_id);

if(!$sca && !$gname)
    die('<p>카테고리를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>');

if(!$mb_id)
    die('<p>판매자 아이디가 값이 넘어오지 않았습니다.</p>');

$sql_common = " from shop_goods ";
$sql_search = " where shop_state = 0 and index_no <> '$gs_id' and (use_aff = 0 or (use_aff = 1 and mb_id = '$mb_id')) ";

if($sca) {
	$sql_search .= " and (ca_id like '$sca%' or ca_id2 like '$sca%' or ca_id3 like '$sca%') ";
}

if($gname) {
    $sql_search .= " and (gname like '%$gname%') ";
}

$sql_order = " order by index_no desc ";

$list = '';

$sql = " select index_no, gname, simg1 $sql_common $sql_search $sql_order ";
$result = sql_query($sql);
for($i=0;$row=sql_fetch_array($result);$i++) {
    $sql2 = " select count(*) as cnt
				from shop_goods_relation
			   where gs_id = '$gs_id'
			     and gs_id2 = '{$row['index_no']}' ";
    $row2 = sql_fetch($sql2);
    if($row2['cnt'])
        continue;

    $gname = get_it_image($row['index_no'], $row['simg1'], 50, 50).' '.$row['gname'];

    $list .= '<li class="list_res">';
    $list .= '<input type="hidden" name="re_gs_id[]" value="'.$row['index_no'].'">';
    $list .= '<div class="list_item">'.$gname.'</div>';
    $list .= '<div class="list_item_btn"><button type="button" class="add_item btn_small">추가</button></div>';
    $list .= '</li>'.PHP_EOL;
}

if($list)
    $list = '<ul>'.$list.'</ul>';
else
    $list = '<p>등록된 상품이 없습니다.';

echo $list;
?>