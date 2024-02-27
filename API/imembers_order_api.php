<?php
include_once('../common.php');

header('Content-type: application/json');
header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// if($_POST['imsid'] != ''){ // 아이디값 받아서 변수에 저장
//   $ims_id = $_POST['imsid'];
//   var_dump($_POST['imsid']);
// }
// var_dump($ims_id);

if (isset($_POST["imembers_uuid"]) =='' ||  isset($_POST["imembers_uuid"])== null )  {
  return false;
}

// 아이디값 받아서 첫번째 $sql에 넣어줌
// 해당아이디값으로 주문내역검색
$imembers_uuid = "imembers_".$_POST["imembers_uuid"];

$sql = " select * 
         from shop_order
         where mb_id = '".$imembers_uuid."'
         group by od_id 
         order by index_no desc ";
$result2 = sql_query($sql);
//var_dump($sql);


$goods_info = array();

for($i=0; $row=sql_fetch_array($result2); $i++) {
  $sql = " select * from shop_order where od_id = '$row[od_id]' "; //받은 아이디값의 주문번호로 주문내역 조회
  $sql.= " order by index_no ";
  $res = sql_query($sql);
  $rowspan = sql_num_rows($res)+1;


  for($k=0; $ct=sql_fetch_array($res); $k++) { 
    $gs = unserialize($ct['od_goods']);
    $it_options = options_data($ct['gs_id'], $ct['od_id']); // 상위 for문의 주문번호로 해당 주문건의 상품옵션 가져옴
    $dlcomp = explode('|', trim($ct['delivery'])); // 배송출발 - 완료된상품이면 배송추적url 및 택배사정보
    $href = TB_SHOP_URL.'/view.php?index_no='.$ct['gs_id']; // 상품상세url

    $order_info = array( // 데이터를 배열에 담아줆
      "od_id" => $row['od_id'],
      "name" => $row['name'],
      "dan" => $row['dan'],
      "cellphone" => $row['cellphone'],
	  "zip" => $row['zip'],
      "addr" => $row['addr1'],
      "addr2" => $row['addr2'],
      "addr3" => $row['addr3'],
	  "b_name" => $row['b_name'],
	  "b_cellphone" => $row['b_cellphone'],
	  "b_zip" => $row['b_zip'],
      "b_addr1" => $row['b_addr1'],
      "b_addr2" => $row['b_addr2'],
      "b_addr3" => $row['b_addr3'],
      "gs_id" => $row['gs_id'],
      "goods_price" => $row['goods_price'],
      "use_price" => $row['use_price'],
	  "baesong_price" => $row['baesong_price'],
	  "baesong_price2" => $row['baesong_price2'],
      "goods_name" =>$gs['gname'],
      "opt" => $it_options
    );

    array_push($goods_info, $order_info); //상품정보를 JSON형태로 포장해서 배열에 담아줌
    // $json_de = base64_decode($json);
    // print_r($json_de);
    // $json_ar = explode(',',$json_de);
    // print_r($json_ar);
    // $json_fetch = json_decode($json_de);
    // print_r($json_fetch);
    // var_dump($order_info);
    // $order_info_en = base64_encode(implode(',',$order_info)); // 배열 문자열변환 후 인코딩
    // var_dump($order_info_en);
    // $order_info_dn = base64_decode($order_info_en);
    // var_dump($order_info_dn);
    // $order_info_ar = explode(',',$order_info_dn);
    // var_dump($order_info_ar);
  }
}

$json = to_han(json_encode($goods_info)); // 배열을 제이슨형태로 변환
$json = preg_replace("/\\\/", "", $json);
print_r($json);

// 상품옵션 추출 함수
function options_data($gs_id, $od_id)
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {

		if(!$row['io_id']) continue;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

      $str .= PHP_EOL.$comma.$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")";

			$str = trim($str);
			// $comma = '|';
	}
	return $str;
}

?>