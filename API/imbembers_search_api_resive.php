<?
include_once('../common.php');
# An HTTP GET request example
  $url = 'https://golf.imembers.co.kr/API/imembers_search_api.php';

 $search_nm ='히스케이 볼드';
 $mkey= 'ZXFqNWVhdGdobkVRRVJBVFK1NfWdOv8PtgHtRSK+SLawvQXJoEXM7R5BTdOt9LpK75Uig1uEnN8YK5Tv/2MnX0bKD1SUUm9uKWnCZRrFH7KfEjt9+n/dOiuBf4zIdq4NpL9K6EwgHVGDakIZV7tNdQ==';
 $data_array = array('search_name'=>$search_nm ,'mkey'=>$mkey);
 
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

//  var_dump($response);
  $sub_json_object_array = getJsonText(json_decode($response), true);
  var_dump($sub_json_object_array);
// print_r($sub_json_object_array);

  for($i=0; $i< count($sub_json_object_array); $i++){
   echo $sub_json_object_array[$i]->search_nm;
   echo $sub_json_object_array[$i]->search_src;    
   echo $sub_json_object_array[$i]->total_count;
  }


  function getJsonText($jsontext) { 
 
    return str_replace("\\", "", $jsontext);
    
  }

?>
