<?php
include_once('./common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

//$m_sql_common = m_get_killdeal(); //킬딜특가 상품정보를 가져옴


if (isset($_POST["search_name"])!=='' )  {
    $search_nm = $_POST["search_name"];



$sql_search = " and ( gname like '%$search_nm%' or find_in_set('$search_nm', a.keywords) >= 1 ) ";
$sql_common = sql_goods_search($sql_search);


// 테이블의 전체 레코드수만 얻음
$sql = " select count(a.index_no) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

//$sql = " select * $sql_common ";
//$result = sql_query($sql);

$goods_info = array();

//for($i=0; $row=sql_fetch_array($result); $i++) { //각 상품의 정보를 변수에 담아줌
//  $it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
//  $it_name = $row['gname'];
//  $it_imageurl =  $row['simg1'];
//  $it_price = $row['normal_price'];
//  $it_amount = $row['goods_price'];
//
//  $discount_rate = floor(($row['normal_price']-$row['goods_price'])/$row['normal_price']*100); //할인률 계산
  // var_dump($row);

  //$goods = array ("search_src" => 'https://refreshclub.killdeal.co.kr/data/goods/'.$row['simg1'],"name" => $row['gname'], "normal_price" => $row['normal_price'],
  // "good_price" => $row['goods_price'], "discount_rate" => $discount_rate.'%', "gs_id" => $row['index_no']);
  $goods = array ("search_src" => 'https://killdeal.co.kr/shop/search.php?ss_tx='.$search_nm,"total_count" =>$total_count);
  array_push($goods_info, $goods); //상품정보를 JSON형태로 포장해서 배열에 담아줌
}
  
  
$json = to_han(json_encode($goods_info));
$json = preg_replace("/\\\/", "", $json);
// var_dump($json);
print_r($json);
/*
API 전달 사항
- 상품이미지주소(full uri) 

- 상품명

- 정상가격

- 할인된가격

- 할인율(회의시 논의된 사항)
*/

?>