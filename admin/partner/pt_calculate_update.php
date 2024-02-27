<?php
include_once("./_common.php");

check_demo();

check_admin_token();



$count = count($_POST['chk']);


if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}


//정산 업데이트 실행 
for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];	
	$od_id = $_POST['od_id'][$k];
	

	$od = get_order($od_id, 'od_id');;

	if($od['od_id']) {
		order_calculate_update($od_id);	
	}else{
		alert($od_id.' 존재하지 않은 주문번호 입니다.');
	}
		

	

	
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>