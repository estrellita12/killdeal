<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);
set_session('ss_order_inicis_id', $od_id);

$stotal = get_order_spay($od_id); // 총계 
//$stotal = get_order_spay_nosum($od_id); // 총계 20200525 수정(결제취소시 결제금액 중첩 방지)
$tot_price = get_session('tot_price'); // 결제금액

$order_action_url = TB_HTTPS_MSHOP_URL.'/orderformresult.php';
include_once(TB_MTHEME_PATH.'/orderinicis.skin.php');

include_once("./_tail.php");
?>

<!-- 20200624 뒤로가기 로 중복 결제 방지 -->
<input type="hidden" id="od_id_num"  value="">
<script>


//20200624 결제 완료 후 뒤로가기 눌렀을 시, 중복 결제 방지 ---20200624
function ajax_uid_load(){
		var od_id = <?=$od_id?>;
		
		$.ajax({
			type:'POST',
			url:'<?=TB_URL?>/<?=TB_SHOP_DIR?>/ajax.orderinicis.load.php',
			data: {od_id :od_id},
			async: false,
			dataType:'html',
			error: function(XMLHttpRequest, status, errorThrown) {              
               alert(status);
           },
		   success: function (data) {				
				$("#od_id_num").val(data);
           }

		})
}


//20200624 결제 완료 후 뒤로가기 눌렀을 시, 중복 결제 방지 ---20200624

$(document).ready(function(){
    ajax_uid_load();
    if($("#od_id_num").val() !="" ) {
	    alert("이미 결제를 하셨거나, 잘못된 접근 방식입니다. 이미 결제 하셨다면, 마이페이지를 확인해주세요.");
	    location.href="<?=TB_URL?>";
    }
});


</script>