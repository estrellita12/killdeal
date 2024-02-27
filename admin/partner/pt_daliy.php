<?php
if(!defined('_TUBEWEB_')) exit;
// $dan = "";

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>

<!-- 가맹점별 총 매출과 총 주문수량,총 주문건을 조회 할 수 있음  -->

<h2>가맹점별 매출 검색</h2>
<p style="font-size:13px;color:#999;padding-bottom:15px;">* 가맹점별 선택기간 매출의 합 조회</p>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">기간검색</th>
				<td>
					<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="btn_confirm" style="margin-bottom:20px;">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium red">
</div>
</form>

<form name="forderlist" id="forderlist" action="./partner/pt_calculate_update.php" method="post" onsubmit="return fcalculatelist_submit(this.value);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<colgroup>
		<col class="w100">
		<col class="w100">  
		<col class="w50">  
		<col class="w150"> 
	</colgroup>
	<thead>
	<tr>
		<th scope="col">날짜</th>
		<th scope="col">가맹점 명</th>
		<th scope="col">주문수</th>
		<th scope="col">매출/주문 수량</th>>
	</tr>
	</thead>
	<tbody>
	<?php
	// 날짜선택(datepicker)
	if($fr_date && $to_date)
	$seah[] = "and left(od_time,10) between '$fr_date' and '$to_date' ";
	else if($fr_date && !$to_date)
	$seah[] = "and left(od_time,10) between '$fr_date' and '$fr_date' ";
	else if(!$fr_date && $to_date)
	$seah[] = "and left(od_time,10) between '$to_date' and '$to_date' ";
	
	// 검색조건(날짜)를 where문에 추가
	if($seah) {
		$sql_seah = implode(' and ', $seah);
	}
	
	// 어드민 매출구하는 쿼리 분리
	// 가맹점은 pt_id값을 shop_partner 테이블에서 mb_id로 불러오는데 admin은 해당쿼리를 사용할수없음
	// dan(주문상태) 입금완료,배송준비,배송중,배송완료,교환신청,교환중,교환완료 만 검색함
	// 실제로 결제가 된 금액만 조회를함
	// 반품,환불,취소같이 결제금액이 빠지는 필드는 검색하지않음
	$adsql = "select SUM(goods_price + baesong_price) as buy_price, od_id ,od_time
						from shop_order 
						where dan IN ('2','3','4','5','8','12','13')
						and pt_id = 'admin'
						$sql_seah
						group by od_id ";
	$ad_res = sql_query($adsql);
	$ad_rows = sql_num_rows($ad_res);
	
	// 주문수량과 매출합계 구하는 쿼리
	for($d=0;$d<$ad_row=sql_fetch_array($ad_res);$d++){
		$ad_amount = get_order_no_refuse($ad_row['od_id']);
		$ad_tot_price += $ad_amount['buyprice'];
		$ad_tot_qty += $ad_amount['qty'];
	}
	

	// 가맹점별 매출구하는 쿼리
	$sql = " select mb_id from shop_partner order by shop_name";
	$res = sql_query($sql);
	
	for($i=0;$i<$row=sql_fetch_array($res);$i++){
			// dan(주문상태) 입금완료,배송준비,배송중,배송완료,교환신청,교환중,교환완료 만 검색함
			// 실제로 결제가 된 금액만 조회를함
			// 반품,환불,취소같이 결제금액이 빠지는 필드는 검색하지않음
			$sql2 = " select SUM(goods_price + baesong_price) as buy_price, od_id ,od_time
				from shop_order 
				where dan IN ('2','3','4','5','8','12','13')
				and pt_id = '{$row['mb_id']}'
				$sql_seah
				group by od_id ";

		$res2 = sql_query($sql2);
		$total_count = sql_num_rows($res2);
		$tot_orderprice = 0;
		$tot_amount = 0;
	
		// 주문수량과 매출합계 구하는 쿼리
		for($v=0;$v<$row2=sql_fetch_array($res2);$v++) {
			$amount = get_order_no_refuse($row2['od_id']);
			$tot_orderprice += $amount['buyprice'];
			$tot_amount += $amount['qty'];
		}
		$tot_all_amount += $tot_amount;
	?>
	<tr style="height:60px;" class="<?php echo $bg; ?>">
		<td style="font-weight:500;font-size:16px;">
		<?php 
			if(!$fr_date && !$to_date){
				echo "전체";
			}else{
				echo $fr_date.' - '.$to_date;
				} 
		?>
		</td>
		<td style="font-weight:500;font-size:16px;">  
			<span><?php echo trans_pt_name($row['mb_id']); ?></span>
		</td>
		<td style="font-weight:700;font-size:15px;">  
			주문 건 : <?php echo number_format($total_count); ?> 건<br>
		</td>
		<td>  
			<?php echo $tt; ?>
		<p style="font-weight:700;font-size:16px;">총 매출 : <?php echo number_format($tot_orderprice) ?>원</p><br>
		<p style="font-weight:700;font-size:15px;color:#888;">(총 주문 수량 : <?php echo number_format($tot_amount); ?>개)</p>
		</td>
	</tr>
	<?php $tot_price += $tot_orderprice;

} ?>
	<tr style="height:60px;">
		<td style="font-weight:500;font-size:16px;">
			<?php 
				if(!$fr_date && !$to_date){
					echo "전체";
				}else{
					echo $fr_date.' - '.$to_date;
					} 
			?>
		</td>
		<td style="font-weight:500;font-size:16px;">
			본사
		</td>
		<td style="font-weight:700;font-size:15px;">
			주문 건 : <?php echo $ad_rows; ?>건 <br>
			총 주문 수량 : <?php echo $ad_tot_qty; ?>
		</td>
		<td style="font-weight:700;font-size:16px;">
			총 매출 : <?php echo number_format($ad_tot_price); ?>원
		</td>
	</tr>
	</tbody>
	</table>
		<table>
		<colgroup>
			<col style="width:400px;">  
			<col style="width:530px;">  
			<col>
		</colgroup>
		<thead>
			<tr>
			<th scope=col>날짜</th>
			<th scope=col>전사 총 수량</th>
			<th scope=col>전사 총 매출</th>
			</tr>
		</thead>
		<tbody>
			<tr style="height:50px;">
				<td style="font-weight:700;font-size:15px;">
					<?php 
						if(!$fr_date && !$to_date){
							echo "전체";
						}else{
							echo $fr_date.' - '.$to_date;
							} 
					?>
				</td>
				<td style="font-weight:700;font-size:15px;">
					<?php
						echo $tot_all_amount + $ad_tot_qty;
					?>
				 </td>
				<td style="font-weight:700;font-size:15px;">
					<?php
						echo number_format($tot_price + $ad_tot_price);
					?> 원
				</td>
			</tr>
		</tbody>
	</table>
</div>
</form>

<script>
function checked_multi(checkEl){
	if(checkEl == ''){
				$('input:checkbox[name="od_status_2"]').prop('checked',false);
				$('input:checkbox[name="od_status_3"]').prop('checked',false);
				$('input:checkbox[name="od_status_4"]').prop('checked',false);
				$('input:checkbox[name="od_status_5"]').prop('checked',false);
				$('input:checkbox[name="od_status_6"]').prop('checked',false);
				$('input:checkbox[name="od_status_7"]').prop('checked',false);
				$('input:checkbox[name="od_status_8"]').prop('checked',false);
				$('input:checkbox[name="od_status_9"]').prop('checked',false);
				$('input:checkbox[name="od_status_10"]').prop('checked',false);
				$('input:checkbox[name="od_status_11"]').prop('checked',false);
				$('input:checkbox[name="od_status_12"]').prop('checked',false);
				$('input:checkbox[name="od_status_13"]').prop('checked',false);
	}
	else{
				$('input:checkbox[name="od_status_0"]').prop('checked',false);
	}
}



function dan_change(){
	$('#dan').val($('#dan_val').text());
}
function fcalculatelist_submit(f)
{
	if(!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}
	
	if(document.pressed == "선택정산") {
				
		if(!confirm("선택한 자료를 정산하시겠습니까?")) {
			return false;
        }else{
			
		}
	}								
		return true;
}
		
		
$(function(){
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

	$("#frmOrderExcel").on("click", function() {
		var type = $(this).attr("id");
		var od_chk = new Array();
		var od_id = "";
		var $el_chk = $("input[name='chk[]']");
		
		$el_chk.each(function(index) {
			if($(this).is(":checked")) {
				od_chk.push($("input[name='od_id["+index+"]']").val());
			}
		});
		
		if(od_chk.length > 0) {
			od_id = od_chk.join();
		}
		if(od_id == "") {
			alert("처리할 자료를 하나 이상 선택해 주십시오.");
			return false;
		} else {
			if(type == 'frmOrderPrint') {
				var url = "./order/order_print.php?od_id="+od_id;
				window.open(url, "frmOrderPrint", "left=100, top=100, width=670, height=600, scrollbars=yes");
				return false;
			} else {
				this.href = "./partner/pt_excel2.php?od_id="+od_id;
				return true;
			}
		}
	});
});

</script>