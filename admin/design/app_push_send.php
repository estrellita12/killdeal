<?php
include_once("./_common.php");


function send_notification2 ($title="",$contents="",$imgurl="",$link="", $fcm_key="")
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $myMessage = array(
        "title" => $title,
        "contents" => $contents,
        "imgurl" => $imgurl,
        "link" => $link
    );
    $message = array("message" => $myMessage);
    $fields = array(
        "to"=>"/topics/plan",
        'data' => $message
    );
    $headers = array(
        'Authorization:key='.$fcm_key,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

$pu_no = $_GET['pu_no'];

$sql = " select * from shop_app_push where pu_no=$pu_no ";
$result = sql_query($sql);
$row=sql_fetch_array($result);

$title = $row['pu_title'];
$body = $row['pu_body'];
$img = $row['pu_img'];
$link = $row['pu_link'];
$fcm_key = "";

if($row['mb_id']=='admin'){
    $fcm_key = "AAAAFSJphBA:APA91bHirxw_umthOqaLQotgxrOq6TDix9jIWDHH_XyFYJ_s50hpFViQlXRPm7LNdHMJopxndxBSUX2lU0wFXLApNCM5mECAfoo6t58ZIBqLQiHTVV3xSAjz1Md32SG5KfwHQtVLB5wP";
}else if($row['mb_id']=='maniamall'){
    $fcm_key = "AAAAsIhxRII:APA91bHbLLN4Numl3FjsBhgmLLZV4HvcV2ZthUz-QDbfDDAG5ljFrwSgspBznajdut_76oQEE_2bai3_BFsPtGC2xi0KTqk4Z-_cnt5ZXKaTiYnY96iuTqEz3tA61JaSfHmnOBNlD9d5";
}else if($row['mb_id']=='baksajang'){
    $fcm_key = "AAAAsIhxRII:APA91bHbLLN4Numl3FjsBhgmLLZV4HvcV2ZthUz-QDbfDDAG5ljFrwSgspBznajdut_76oQEE_2bai3_BFsPtGC2xi0KTqk4Z-_cnt5ZXKaTiYnY96iuTqEz3tA61JaSfHmnOBNlD9d5";
}else{
    alert("가맹점 key값이 올바르지않습니다.");
}

$pimg = TB_DATA_PATH.'/appPush/'.$row['pu_img'];
if(is_file($pimg) && $row['pu_img']) {
    $img = rpc($pimg, TB_PATH, TB_URL);
} else {
    alert("이미지가 존재하지않습니다");
}

$message_status = send_notification2( $title,$body,$img,$link,$fcm_key );

unset($value);
$value['sdate']   = TB_TIME_YMDHIS;
update("shop_app_push", $value, "where pu_no='$pu_no'");

goto_url(TB_ADMIN_URL."/design.php?code=app_push_list");

?>
