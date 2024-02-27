<?php
include_once('../common.php');
//var_dump($_POST);

$arr = array();
array_push($arr, $_POST['gs_1'], $_POST['gs_2'], $_POST['gs_3'], $_POST['gs_4']);

//var_dump($arr);

for($i=0; $i<4; $i++)
{
	$index = $i +1;
	$sql = "UPDATE udotnet_banner SET gs_id='$arr[$i]' WHERE num_index='$index'";
	sql_query($sql);
	//echo $sql;
	//echo "<br>";
}

$uploads_dir = "uimg/";
$allowed_ext = array('jpg','jpeg','png','gif');

// 변수 정리
$error = $_FILES['banner1']['error'];
$name = $_FILES['banner1']['name'];
$error2 = $_FILES['banner2']['error'];
$name2 = $_FILES['banner2']['name'];
$error3 = $_FILES['banner3']['error'];
$name3 = $_FILES['banner3']['name'];
$error4 = $_FILES['banner4']['error'];
$name4 = $_FILES['banner4']['name'];
$ext = array_pop(explode('.', $name));
 
// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
 
// 확장자 확인
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}
 
// 파일 이동
move_uploaded_file( $_FILES['banner1']['tmp_name'], "$uploads_dir/1.jpg");
move_uploaded_file( $_FILES['banner2']['tmp_name'], "$uploads_dir/2.jpg");
move_uploaded_file( $_FILES['banner3']['tmp_name'], "$uploads_dir/3.jpg");
move_uploaded_file( $_FILES['banner4']['tmp_name'], "$uploads_dir/4.jpg");

echo "<script>location.replace('udotnet.php')</script>";

?>

