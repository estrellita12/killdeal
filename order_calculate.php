<?php

	include_once('./common.php');


	$sql = "select od_no, od_id from shop_order where dan = 5";
	
	$res = sql_query($sql);
	
	$cnt = 1;
	for($i=0; $row=sql_fetch_array($res); $i++) {
		print_r($row);
		echo $cnt;
		echo "<br>";
		$cnt++;
	}
?>