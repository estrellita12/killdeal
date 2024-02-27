<?php
include_once("./_common.php");

check_demo();

$ca_no = trim($_POST['ca_no']);
if(!$ca_no) {
	alert('올바른 방법으로 이용해 주십시오.');
}

$srcfile = TB_DATA_PATH.'/category';

$ca = sql_fetch("select * from shop_category where index_no='$ca_no'");
$len = strlen($ca['catecode']);
if($len > 0) {
	$sql = "select * from shop_category where left(upcate,$len)='$ca[catecode]'";
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)) {
		if($row['index_no']) { // 대상 하위 삭제
			@unlink($srcfile."/".$row['cateimg1']);
			@unlink($srcfile."/".$row['cateimg2']);
			@unlink($srcfile."/".$row['headimg']);

			sql_query("delete from shop_category where index_no='$row[index_no]'", FALSE);
		}
	}

	@unlink($srcfile."/".$ca['cateimg1']);
	@unlink($srcfile."/".$ca['cateimg2']);
	@unlink($srcfile."/".$ca['headimg']);

	sql_query("delete from shop_category where index_no='$ca_no'", FALSE);  // 삭제대상
}

goto_url(TB_ADMIN_URL."/category.php?$q1");
?>