<?php
	include_once('../common.php');
	
	$sql = "select * from udotnet_banner";
	
	$res = sql_query($sql);
	echo "현재 유닷넷 등록된 상품 코드";
	echo "<br>";
	for($i=0; $row=sql_fetch_array($res); $i++) {
		echo $row['num_index']."번 상품 코드 : ";
		echo $row['gs_id'];
		echo "<br>";
	}
?>

<form action="udotnet_banner_update.php" method="post" enctype="multipart/form-data">
	1번 배너 연결 상품코드 수정 : <input type="text" name="gs_1"/>
	이미지 업로드 : 
	<input type="file" name="banner1"><br>
	2번 배너 연결 상품코드 수정 : <input type="text" name="gs_2"/>
	이미지 업로드 : 
	<input type="file" name="banner2"><br>
	3번 배너 연결 상품코드 수정 : <input type="text" name="gs_3"/>
	이미지 업로드 : 
	<input type="file" name="banner3"><br>
	4번 배너 연결 상품코드 수정 : <input type="text" name="gs_4"/>
	이미지 업로드 : 
	<input type="file" name="banner4"><br>
	<br>
	<input type="submit" value="교체"/>
</form>