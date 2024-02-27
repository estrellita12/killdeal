<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");
include_once("./_head.php");



$name = $_POST['user_name'];
$email = $_POST['email'];
$title = $_POST['title'];
$textbox = $_POST['textbox'];
$user_file = $_FILES['user_file'];

//$recive_email = "csk@mwd.kr";
$recive_email = " sweet@mwd.kr";

ob_start();
include_once(TB_SHOP_PATH.'/standing_point_send.php');
$content = ob_get_contents();
ob_end_clean();


$file = array();
		$file[] = attach_file($_FILES['user_file']['name'], $_FILES['user_file']['tmp_name']);
		




$result = mailer($name, $email, $recive_email ,$title, $content,0,$file);



if ($result){
	alert("입점 신청서가 성공적으로 발송되었습니다.");
	include_once(TB_THEME_PATH.'/standing_point.skin.php');
}else{
	alert("메일 발송에 실패하셨습니다.");
	include_once(TB_THEME_PATH.'/standing_point.skin.php');
}



include_once("./_tail.php");
?>