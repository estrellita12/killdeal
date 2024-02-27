<?php
	include_once('../common.php');
	$sql = "select * from udotnet_banner";
	
	$arr = array();
	
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		array_push($arr, $row['gs_id']);
	}
	
	$cache = date("YmdHis");
?>
<html>
	<div>
		<a href="https://shopping.golfu.net/shop/view.php?index_no=<?php echo $arr[0]; ?>">
		<img src="https://shopping.golfu.net/udotnet/uimg/1.jpg?<?php echo$date; ?>" border="0"/>
		</a>
	</div>
	<div>
		<a href="https://shopping.golfu.net/shop/view.php?index_no=<?php echo $arr[1]; ?>">
		<img src="https://shopping.golfu.net/udotnet/uimg/2.jpg?<?php echo$date; ?>" border="0"/>
		</a>
	</div>
	<div>
		<a href="https://shopping.golfu.net/shop/view.php?index_no=<?php echo $arr[2]; ?>">
		<img src="https://shopping.golfu.net/udotnet/uimg/3.jpg?<?php echo$date; ?>" border="0"/>
		</a>
	</div>
	<div>
		<a href="https://shopping.golfu.net/shop/view.php?index_no=<?php echo $arr[3]; ?>">
		<img src="https://shopping.golfu.net/udotnet/uimg/4.jpg?<?php echo$date; ?>" border="0"/>
		</a>
	</div>
</html>