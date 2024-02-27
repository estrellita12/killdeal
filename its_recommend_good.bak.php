<?php
include_once('./common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

$sql = "select de_maintype_best from shop_default";
$res = sql_query($sql);

for($i=0; $row=sql_fetch_array($res); $i++) {
	$list_best = unserialize(base64_decode($row['de_maintype_best']));
}

for($i=0; $i<1; $i++) {
	$str = '';
	
	$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
	$list_code = array_unique($list_code); //중복된 아이디 제거
	$list_code = array_filter($list_code); // 빈 배열 요소를 제거
	$list_code = array_values($list_code); // index 값 주기

	$succ_count = 0;

	for($g=0; $g<4; $g++) {
		//echo $list_code[$g]."</br>";
		$gcode = trim($list_code[$g]);

		if($g != 3)
		{
			$sql_gcode .= $gcode.", ";
		}
		else
		{
			$sql_gcode .= $gcode;
		}
	}

}				
//print_r($list_best);
/* 20191106 모바일 베스트 100 상품정렬 주석처리
$res = bestsell_itemtype2($pt_id, $type, $sql_search, $sql_order);
$total_count = sql_num_rows($res);
*/
$ncat = substr($cat, 2, 1);
if($cat == '000') {
	$ncat = '0';
} else if ($cat == '001'){
	$ncat = '1';
} else if ($cat == '002'){
	$ncat = '3';
} else if ($cat == '003') {
	$ncat = '2';
} else if (!$cat) {
	$ncat = '0';
}

//echo $list_best[$ncat][code];
$list_best = $list_best[$ncat][code];
//echo $list_best;
$sql_search = "where index_no in ({$sql_gcode})";

//echo $sql_search;

$result = bestsell_itemtype2($pt_id, $type, $sql_search, $sql_gcode);
$total_count = sql_num_rows($result);
//var_dump ($result);
$goods_info = array();

for($i=0; $row=sql_fetch_array($result); $i++) {
	$discount_rate = floor(($row['normal_price']-$row['goods_price'])/$row['normal_price']*100);
	//echo $discount_rate;
	/*
	echo ($row['gname']);
	echo "<br>";
	echo ($row['normal_price']);
	echo "<br>";
	echo ($row['goods_price']);
	echo "<br>";
	$discount_rate = ($row['normal_price']-$row['goods_price'])/$row['normal_price']*100;
	echo ($discount_rate);
	echo "<br>";
	echo ('https://itsgolf.killdeal.co.kr/data/goods/'.$row['simg1']);
	echo "<br>";
	echo "-------------------------------";
	echo "<br>";
	*/
	$good = array("img" => 'https://itsgolf.killdeal.co.kr/data/goods/'.$row['simg1'], "name" => $row['gname'], "normal_price" => $row['normal_price'],
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