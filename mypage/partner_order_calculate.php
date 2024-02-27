<?php
if(!defined('_TUBEWEB_')) exit;


// 주문서 query 공통
include_once(TB_ADMIN_PATH.'/partner/pt_query.php');

$btn_frmline = <<<EOF
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="../admin/partner/pt_excel.php?$q1&pt_id=$pt_id" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
EOF;
?>

<h2>상품판매 정산 리스트</h2>
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
				<th scope="row">최종처리일</th>
				<td>
					<select name="sel_field">
						<?php echo option_selected('rcent_time', $sel_field, "최종처리일"); ?>
					</select>
					<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
				</td>
			</tr>
			<tr class="choice_pt">
				<th scope="row">가맹점선택</th>
				<td>
					<select name="sfl">
						<?php
								echo option_selected("{$pt_id}", $sfl ,"{$pt_id}");
								?>
					</select>
				</td>
			</tr>
<!-- 			<tr> -->
<!-- 				<th scope="row">주문상태</th> -->
<!-- 					<td> -->
<!-- 							<?php echo check_checked_multi('od_status_0', $od_status_0, '','전체'); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_2', $od_status_2, '2', $gw_status[2]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_3', $od_status_3, '3', $gw_status[3]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_4', $od_status_4, '4', $gw_status[4]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_5', $od_status_5, '5', $gw_status[5]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_6', $od_status_6, '6', $gw_status[6]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_7', $od_status_7, '7', $gw_status[7]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_8', $od_status_8, '8', $gw_status[8]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_9', $od_status_9, '9', $gw_status[9]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_10', $od_status_10, '10', $gw_status[10]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_11', $od_status_11, '11', $gw_status[11]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_12', $od_status_12, '12', $gw_status[12]); ?> -->
<!-- 							<?php echo check_checked_multi('od_status_13', $od_status_13, '13', $gw_status[13]); ?> -->
<!-- 						</td> -->
<!-- 					</td> -->
<!-- 			</tr> -->
			<tr class="choice_pt">
				<th scope="row">처리상태</th>
				<td>
					<select name="calculate_yn">
						<?php echo option_selected('Y', $calculate_yn, "정산처리 후"); ?>
						<!-- <option value='Y' selected="selected">정산처리후</option> -->
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<!-- <div class="local_ov mart30"> -->
<!-- 	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회 -->
<!-- 	<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong> -->
<!-- 	<strong class="ov_a">총주문 수량 : <?php echo number_format($tot_orderproduct); ?>개</strong> -->
<!-- </div> -->

<form name="forderlist" id="forderlist" action="<?=TB_ADMIN_PATH?>/partner/pt_calculate_update.php" method="post" onsubmit="return fcalculatelist_submit(this.value);">
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
		<col class="w150">  <!-- 주문번호 -->
		<col class="w50">  <!-- 상품명 -->
		<col> <!-- 상품이미지 -->
		<col class="w30">  <!--수량-->
		<col class="w80">  <!-- 판매가 -->
		<col class="w80">  <!-- 결제금액 -->
		<col class="w80">  <!-- 결제수단 -->
		<col class="w80">  <!-- 주문자 -->
		<col class="w90">  <!-- 주문일 -->
		<col class="w70">  <!-- 주문상태 -->
		<col class="w100">	<!-- 최종처리일 -->
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">가맹점</th>
		<th scope="col">주문번호</th>
		<th scope="col" colspan="2">상품명</th>
		<th scope="col">수량</th>
		<th scope="col">판매총액</th>
		<th scope="col">결제금액</th>
		<th scope="col">결제수단</th>
		<th scope="col">주문자</th>
		<th scope="col">주문일</th>
		<th scope="col">주문상태</th>
		<th scope="col">최종처리일</th>
	</tr>
	</thead>
	<tbody>
	<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'list'.($i%2);
			$amount = get_order_spay($row['od_id']);
			$sodr = get_order_list($row, $amount);

			$sql = " select * {$sql_common} {$sql_search} and od_id = '{$row['od_id']}' order by index_no ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res);
			for($k=0; $row2=sql_fetch_array($res); $k++) {
				$gs = unserialize($row2['od_goods']);
	?>
	<tr class="<?php echo $bg; ?>">
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>">  <!-- 체크박스 -->
			<input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td rowspan="<?php echo $rowspan; ?>"> 
			<?php echo $sodr['disp_pt_id']; ?> <!-- 가맹점 -->
		</td>

		<td rowspan="<?php echo $rowspan; ?>">  <!-- 주문번호 -->
			<!-- <a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a> -->
            <?php echo $row['od_id']; ?>
			<?php echo $sodr['disp_mobile']; ?>
		</td>
		<?php } ?>

		<td class="td_img">  <!-- 상품이미지 -->
			<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a>
		</td>

		<td class="td_itname">  <!-- 상품명-->
			<a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a>
		</td>

		<td>  <!-- 수량 -->
			<?php echo number_format($row2['sum_qty']); ?>
		</td>

		<td class="tac">  <!-- 판매가 -->
			<?php echo number_format($row2['goods_price']); ?>
		</td>

		<td class="tac td_price">
			<?php echo number_format($row2['use_price']);?>
			<?php if ($row2['cancel_price'])  {?> 
			<br><span style="color:red;">-<? echo number_format($row2['cancel_price']);?> <span>
			<?php } else if($row2['refund_price']) { ?><br><span style="color:red;">-<?php echo number_format($row2['refund_price']); ?> <span>
			<?php } if (((($row2['dan'] == 9 || $row2['dan'] == 7) && $row2['paymethod']=='가상계좌') || (($row2['dan'] == 9 || $row2['dan'] == 7) && $row2['paymethod']=='계좌이체')) && !$row['cancel_price']){ ?>
			<br><span style="color:red;">-
			<? echo number_format($row2['use_price']);?> <span>
			<?}?>
		</td>

		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>">  <!-- 결제수단 -->
			<?php echo $sodr['disp_paytype']; ?>
		</td>

		<td rowspan="<?php echo $rowspan; ?>">  <!-- 주문자 -->
			<?php echo $sodr['disp_od_name']; ?>
			<?php echo $sodr['disp_mb_id']; ?>
		</td>

		<td rowspan="<?php echo $rowspan; ?>"> <!-- 주문일 -->
			<?php echo substr($row['od_time'],2,14); ?>
		</td>
		<?php } ?>

		<td> <!-- 주문상태 -->
			<?php echo $gw_status[$row2['dan']]; ?>
		</td>

		<td class="tac"> 
			<?php echo $row2['rcent_time']?>  <!--최종 처리일 -->
		</td>
	<?php
		}
	}
	sql_free_result($result);
	if($i==0)
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
				this.href = "../admin/partner/pt_excel2.php?od_id="+od_id;
				return true;
			}
		}
	});
});
</script>
