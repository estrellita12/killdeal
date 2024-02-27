<?php
include_once('./common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

$m_sql_common = m_get_killdeal(); //킬딜특가 상품정보를 가져옴

$sql = " select * $m_sql_common";
$result = sql_query($sql);

$goods_info = array();

for($i=0; $row=sql_fetch_array($result); $i++) { //각 상품의 정보를 변수에 담아줌
  $it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
  $it_name = $row['gname'];
  $it_imageurl =  $row['simg1'];
  $it_price = $row['normal_price'];
  $it_amount = get_sale_price($row['index_no']);

  $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
  $discount_rate = number_format($sett,0);


  $goods = array ("img" => 'https://lgcare.killdeal.co.kr/data/goods/'.$row['simg1'],"name" => $row['gname'], "normal_price" => $row['normal_price'],
   "good_price" => $it_amount , "discount_rate" => $discount_rate.'%', "gs_id" => $row['index_no']);
  array_push($goods_info, $goods); //상품정보를 JSON형태로 포장해서 배열에 담아줌
}
  
  
$json = to_han(json_encode($goods_info));
$json = preg_replace("/\\\/", "", $json);
// var_dump($json);
print_r($json);

?>
