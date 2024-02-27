<?php
include_once('./common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// (2021-07-29)
$sql = "select de_maintype_best from shop_default";
$res = sql_query($sql);
$row=sql_fetch_array($res);
$list_best = unserialize(base64_decode($row['de_maintype_best']));

$sql_gcode = $list_best[0][code];

$sql = "select * from shop_goods where shop_state = 0 and index_no in ({$sql_gcode}) order by goods_price limit 4";
$res = sql_query($sql);

$goods_info = array();
for($i=0; $row=sql_fetch_array($res); $i++) {
    $discount_rate = floor(($row['normal_price']-$row['goods_price'])/$row['normal_price']*100);
    $good = array("img" => 'https://mall.teeluv.co.kr/data/goods/'.$row['simg1'], "name" => $row['gname'], "normal_price" => $row['normal_price'],
    "good_price" => $row['goods_price'], "discount_rate" => $discount_rate.'%', "gs_id" => $row['index_no']);

    array_push($goods_info, $good);
}

$json = to_han(json_encode($goods_info));
print_r($json);


/*
API 전달 사항
- 상품이미지주소(full uri) 

- 상품명

- 정상가격

- 할인된가격

- 할인율(회의시 논의된 사항)
*/
/*
for($i=0; $row=sql_fetch_array($result); $i++) {
	$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
	$it_image = get_it_image($row['index_no'], $row['simg1'], 290, 290);
	$it_name = cut_str($row['gname'], 100);
	$it_price = get_price($row['index_no']);
	$it_amount = get_sale_price($row['index_no']);
	$it_point = display_point($row['gpoint']);

	$is_uncase = is_uncase($row['index_no']);
	$is_free_baesong = is_free_baesong($row);
	$is_free_baesong2 = is_free_baesong2($row);

	// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
	$it_sprice = $sale = '';
	if($row['normal_price'] > $it_amount && !$is_uncase) {
		$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
		$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
		$it_sprice = display_price2($row['normal_price']);
}
*/
/*
$list_code = explode(',',$arr[0][code]);

for($g=0; $g<count($list_code); $g++) {
	$gcode = trim($list_code[$g]);

	$sql_gcode = " and index_no = {$gcode}";
}

$sql_search = "where index_no in ({$list_best})";
$sql = "select * from shop_goods where ";
*/
?>
