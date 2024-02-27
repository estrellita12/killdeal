<?
include_once('../common.php');
# An HTTP GET request example
  $url = 'https://golf.imembers.co.kr/API/imembers_order_api.php';

 $uuid= 'mhs1788';
 $imembers_uuid ='honggolf_'.$uuid;


 $data_array = array('imembers_uuid'=>$imembers_uuid);
 $data = http_build_query($data_array, '', '&');
 
  $handle = curl_init();
  curl_setopt($handle, CURLOPT_URL, $url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
  curl_setopt($handle, CURLOPT_POST,true);
  

  $response = curl_exec($handle);
  curl_close($handle);

  //var_dump($response);
  $sub_json_object_array = getJsonText(json_decode($response), true);

  var_dump($sub_json_object_array);
  //print_r($sub_json_object_array);

  for($i=0; $i< count($sub_json_object_array); $i++) {
    
    echo $sub_json_object_array[$i]->od_id;
    echo $sub_json_object_array[$i]->b_name;    
    echo $sub_json_object_array[$i]->zip;
    echo $sub_json_object_array[$i]->addr;
    echo $sub_json_object_array[$i]->addr2;
    echo $sub_json_object_array[$i]->addr3;
    echo $sub_json_object_array[$i]->goods_price;
    echo $sub_json_object_array[$i]->use_price;
    echo $sub_json_object_array[$i]->baesong_price;
    echo $sub_json_object_array[$i]->goods_name;
    echo $sub_json_object_array[$i]->opt;
    echo $sub_json_object_array[$i]->cellphone;
  }


  function getJsonText($jsontext) { 
 
    return str_replace("\\", "", $jsontext);
    
  }

?>
