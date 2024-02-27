<?php
include_once('../common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

//$sql = "select * from shop_goods_plan where mb_id='admin' and pl_use = '1' order by pl_no desc   LIMIT 2";
$sql = "select * from shop_goods_plan where mb_id='admin' and pl_use = '1' and  pl_sb_date <= NOW() and pl_ed_date >= NOW() order by pl_no desc   LIMIT 2";
$res = sql_query($sql);
$plan_info = array();
for($i=0; $row=sql_fetch_array($res); $i++) {
	$href = TB_SHOP_URL.'/planlist.php?pl_no='.$row['pl_no'];
	$bimg = TB_DATA_PATH.'/plan/'.$row['pl_limg'];
	if(is_file($bimg) && $row['pl_limg']) {
		$pl_limgurl = rpc($bimg, TB_PATH, TB_URL);
	} else {
		$pl_limgurl = TB_IMG_URL.'/plan_noimg.gif';
	}

	$plan = array("img" => $pl_limgurl, "name" => $row['pl_name'], "href" => $href);
	
	array_push($plan_info, $plan);
}

$json = to_han(json_encode($plan_info));

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
