<?

?><?php
if(!defined('_TUBEWEB_')) exit;
// $dan = "";

// 주문서 query 공통
include_once(TB_ADMIN_PATH.'/partner/pt_product_query.php');
?>

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong>
	<strong class="ov_a">총주문 수량 : <?php echo number_format($tot_orderproduct); ?>개</strong>
</div>

<form name="forderlist" id="forderlist" action="./partner/pt_calculate_update.php" method="post" onsubmit="return fcalculatelist_submit(this.value);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<colgroup>
		<col class="w20"> <!-- 체크박스 -->
		<col class="w100">  <!-- 가맹점(pt_id) -->
		<col class="w80">  <!-- 상품명 -->
		<col > <!-- 상품이미지 -->
		<col class="w100">  <!-- 판매가 -->
		<col class="w100">  <!-- 판매수량 -->
		<col class="w100">  <!-- 판매총액 -->
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">가맹점</th>		
		<th scope="col" colspan="2">상품명</th>
		<th scope="col">조회수</th>
		<th scope="col">판매가</th>
		<th scope="col">판매수량</th>
		<th scope="col">판매총액</th>
	</tr>
	</thead>
	<tbody>
	<?php

			//var_dump($sql);
			$rowspan = sql_num_rows($result);
			for($k=0; $row2=sql_fetch_array($result); $k++) {
                $gs = unserialize($row2['info_value']);
                $gs_id = $row2['index_no'];
	?>
	<tr class="<?php echo $bg; ?>">
		
	
		<td>
			<input type="hidden" name="index_no[<?php echo $k; ?>]" value="<?php echo $row2['index_no']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['index_no']; ?></label>
			<input type="checkbox" name="chk[]" value="<?php echo $k; ?>" id="chk_<?php echo $k; ?>">
		</td>
		
		<td>
		<!-- 가맹점 -->
			<? if ($sfl =='') { 
				
			}else{ 
				echo $row2['pt_id']; 
			}?>
			
		</td>
		

		<td class="td_img">  <!-- 상품이미지 -->
        <a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row2['simg1'], 40, 40); ?></a>
		</td>

		<td class="td_itname">  <!-- 상품명-->
			<a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $gs_id; ?>" target="_blank"><?php echo get_text($row2['gname']); ?></a>
		</td>
		<td>  <!-- 조회수-->
			<?php echo number_format($row2['readcount']); ?>
		</td>
        <td>  <!-- 판매가-->
        <?php echo number_format($row2['goods_price']); ?>
        </td>
        
		<td>  <!-- 판매수량 -->
			<?php echo number_format($row2['sum_qty']); ?>
		</td>

		<td class="tac">  <!-- 판매총액 -->
			<?php echo number_format($row2['goods_price_sum']); ?>
		</td>
	</tr>
	<?php
		}	
	sql_free_result($result);
	if($k==0)
		echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php

echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

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
		var index_no = "";
		var $el_chk = $("input[name='chk[]']");
		
		$el_chk.each(function(index) {
			if($(this).is(":checked")) {
				od_chk.push($("input[name='index_no["+index+"]']").val());
			}
		});
		
		if(od_chk.length > 0) {
			index_no = od_chk.join();
		}
		if(index_no == "") {
			alert("처리할 자료를 하나 이상 선택해 주십시오.");
			return false;
		} else {
			if(type == 'frmOrderPrint') {
				var url = "./order/order_print.php?index_no="+index_no;
				window.open(url, "frmOrderPrint", "left=100, top=100, width=670, height=600, scrollbars=yes");
				return false;
			} else {
				this.href = "./partner/pt_product_excel2.php?index_no="+index_no;
				return true;
			}
		}
	});
});

</script>
