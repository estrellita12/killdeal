<?php
//header("Content-Type: text/html; charset=UTF-8");
$hostname = "localhost";
$username = "craw";
$password = "test1234";
$tb_nm = "smart_store_review";
($conn = mysqli_connect($hostname, $username, $password, "review")) or
    die(
        "html>script language='JavaScript'>alert('Unable to connect to database! Please try again later.');/script>/html>"
    );

$chk_date = date("Y-m-d",strtotime("-3 days"));
$query = "select * from {$tb_nm} where reg_dt >= '{$chk_date}' limit 10000";
$res = mysqli_query($conn, $query);
$list = array();
for($i=0;$row = mysqli_fetch_assoc($res); $i++){
    array_push($list,$row);
}
$list = json_encode($list);

$fields = array();
$fields['review2'] = $list;
$post_query = http_build_query($fields);
//$url = "http://172.20.100.100:8060/API/review_api.php";
$url = "https://killdeal.co.kr/API/review_api.php";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_HEADER, true);
/*
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post_query)
));
*/
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post_query);
//curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
$response = curl_exec($ch);
curl_close($ch);
print_r($response);
?>
