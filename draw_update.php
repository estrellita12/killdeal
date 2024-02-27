<?php
include_once('./_common.php');

if(empty($_POST))
    die('이름,휴대폰번호가 인증되지않았습니다.');

if(!$_SESSION['ss_mb_id']){
        alert('로그인 후 이용할 수 있습니다.', TB_URL);
}

$user_name = trim($_POST['name']);
$user_phone = trim($_POST['phone']);
$user_agree = trim($_POST['agree']);
//$user_inzng = trim($_POST['inzng']);
$user_ptId = trim($_SESSION['pt_id']);
$user_mId = trim($_SESSION['ss_mb_id']);

 $sql = " select phone from shop_event where phone = '$user_phone' ";
 $res = sql_query($sql);
 $row = sql_fetch_array($res);
 if($row['phone'] == $user_phone) {
         echo '이미 이벤트에 참여하셨습니다.';
         return false;
 }

$sql = "INSERT INTO shop_event(name,phone,agree,pt_id,mb_id,date_time) 
        values('$user_name','$user_phone','$user_agree','$user_ptId','$user_mId',NOW()) ";

$result = sql_query($sql, FALSE);

if ($result){
        $result= "이벤트 참여가 완료되었습니다.";
}
echo $result;

?>