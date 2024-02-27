<?php
include_once('./common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// 2021-07-30
$sql = "select de_maintype_best from shop_default";
$res = sql_query($sql);
$row=sql_fetch_array($res);
$list_best = unserialize(base64_decode($row['de_maintype_best']));
$sql_gcode = $list_best[0][code];

$sql = "select * from shop_goods where shop_state = 0 and index_no in ({$sql_gcode})  $sql_search";
$res = sql_query($sql);
$goods_info = array();
for($i=0; $row=sql_fetch_array($res); $i++) {
    $discount_rate = floor(($row['normal_price']-$row['goods_price'])/$row['normal_price']*100);
    $good = array("img" => 'https://mall.teeluv.co.kr/data/goods/'.$row['simg1'], "name" => $row['gname'], "normal_price" => $row['normal_price'],
        "good_price" => $row['goods_price'], "discount_rate" => $discount_rate.'%', "gs_id" => $row['index_no']);
    array_push($goods_info, $good);
}
shuffle($goods_info);
$json = to_han(json_encode($goods_info));
print_r($json);



/*
// (2021-07-29)
$sql = "select de_maintype_best from shop_default";
$res = sql_query($sql);
$row=sql_fetch_array($res);
$list_best = unserialize(base64_decode($row['de_maintype_best']));
$gcode_list = explode(",",$list_best[0][code]);

$goods_info = array();
for( $i=0;$i<4;$i++ ){
    $x = rand(0,29);
    $sql = "select * from shop_goods where shop_state = 0 and index_no = $gcode_list[$x] ";
    $res = sql_query($sql);
    $row=sql_fetch_array($res);
    $discount_rate = floor(($row['normal_price']-$row['goods_price'])/$row['normal_price']*100);
    $good = array("img" => 'https://mall.teeluv.co.kr/data/goods/'.$row['simg1'], "name" => $row['gname'], "normal_price" => $row['normal_price'],
        "good_price" => $row['goods_price'], "discount_rate" => $discount_rate.'%', "gs_id" => $row['index_no']);
    array_push($goods_info, $good);
    if(isset($row)){
        break;
    }
}
$json = to_han(json_encode($goods_info));
print_r($json);
*/




?>
