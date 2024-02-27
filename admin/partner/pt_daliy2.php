<?php
if(!defined('_TUBEWEB_')) exit;
// $dan = "";

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

include_once(TB_ADMIN_PATH.'/partner/pt_daliy_query.php');

?>
<!--  어드민 => 가맹점별 매출(ver2)-->
<!--  날짜별 매출을 조회  -->
<!--  가맹점선택을 반드시 해야함  -->
<!--  해당페이지에서 검색조건 선택 후 pt_daliy_query.php 에서 쿼리 실행 후 결과 값 도출  -->

<h2>가맹점별 매출 검색</h2>
<p style="font-size:13px;color:#999;padding-bottom:15px;">날짜별 가맹점 매출을 조회(가맹점선택을 반드시해야함)</p>
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
      <tr>
				<th scope="row">가맹점선택</th>
				<td>
					<select name="sfl">
						<option value='' selected="selected">선택</option>
						<option value='admin'>본사</option>
						<?php
							for($i = 0;$rowId = sql_fetch_array($result2);$i++){
								echo option_selected("{$rowId['mb_id']}", $sfl ,trans_pt_name($rowId['mb_id']));
							}
							?>
					</select>
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
		<th scope="col">판매수량</th>
		<th scope="col">매출</th>
	</tr>
	</thead>
	<tbody>
	<?php
		for($i=0;$i<$row = sql_fetch_array($result);$i++) {
	?>
	<tr style="height:60px;" class="<?php echo $bg; ?>">
		<td style="font-weight:500;font-size:18px;">
			<?php echo $row['od_time']; ?>
		</td>
		<td style="font-weight:500;font-size:18px;">
        <?php echo trans_pt_name($row['pt_id']); ?>
		</td>
		<td style="font-weight:700;font-size:18px;">  
			<?php echo $row['sum_qty']; ?>개
		</td>
		<td style="font-weight:700;font-size:18px;">  
			<?php echo number_format($row['goods_price_sum']); ?>원
		</td>
	</tr>
		<?php } ?>
	<?php
	sql_free_result($result);
	if($i==0)
		echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<!-- <?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?> -->

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
				this.href = "./partner/pt_daliy_excel.php?od_id="+od_id;
				return true;
			}
		}
	});
});

</script>
