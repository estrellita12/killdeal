<?php
define('_NEWWIN_', true);
include_once('./_common.php');

$sql = " select * from shop_order where od_id = '$od_id' ";
$od = sql_fetch($sql);
if(!$od['od_id']) {
    alert_close("주문서가 존재하지 않습니다.");
}

$od['mb_id'] = $od['mb_id'] ? $od['mb_id'] : "비회원";

$amount = get_order_spay($od_id); // 결제정보 합계

$tb['title'] = "주문내역 수정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_anchor = '<ul class="anchor">
<li><a href="#anc_sodr_list">주문상품 목록</a></li>
<li><a href="#anc_sodr_pay">주문결제 내역</a></li>
<li><a href="#anc_sodr_addr">주문자/배송지 정보</a></li>
</ul>';
?>

<div id="sodr_pop" class="new_win">
	<h1><?php echo $tb['title']; ?></h1>

	<section id="anc_sodr_list">
		<h4 class="anc_tit">주문상품 목록</h4>
		<?php echo $pg_anchor; ?>
		<div class="local_desc02 local_desc">
			<p>
				주문일시 <strong><?php echo substr($od['od_time'],0,16); ?> (<?php echo get_yoil($od['od_time']); ?>)</strong> <span class="fc_214">|</span>
				주문총액 <strong><?php echo number_format($amount['buyprice']); ?></strong>원
			</p>
		</div>

		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="od_hp" value="<?php echo $od['cellphone']; ?>">
		<input type="hidden" name="od_email" value="<?php echo $od['email']; ?>">
		<input type="hidden" name="mb_id" value="<?php echo $od['mb_id']; ?>">
		<input type="hidden" name="chk_data" value="<?php echo $od['name']; ?>">
		<input type="hidden" name="pt_id" value="<?php echo $od['pt_id']; ?>">
		<input type="hidden" name="shop_no" value="<?php echo $od['shop_no']; ?>">
		<input type="hidden" name="shopevent_no" value="<?php echo $od['shopevent_no']; ?>">
		<input type="hidden" name="pg_cancel" value="0">
		<input type="hidden" name="od_time" value="<?php echo substr($od['od_time'],0,10); ?>">

		<div class="tbl_head01">
			<table id="sodr_list">
			<colgroup>
				<col class="w60">
				<col>
				<col class="w90">
				<col class="w90">
				<col class="w60">
				<col class="w70">
				<col class="w70">
				<col class="w70">
				<col class="w70">
				<!-- 현대리바트 복지포인트 추가 -->
				<col class="w70">
				<col class="w70">
			</colgroup>
			<thead>
			<tr>
				<th scope="col">이미지</th>
				<th scope="col">주문상품</th>
				<th scope="col">주문상태</th>
				<th scope="col">수량</th>
				<th scope="col">상품금액</th>
				<th scope="col">배송비</th>
				<th scope="col">쿠폰할인</th>
				<th scope="col">포인트결제</th>
				<th scope="col">실결제금액</th>
				<th scope="col">총주문금액</th>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			$chk_cnt	= 0; // 전체 배열
			$chk_count1 = 0; // 입금대기 수
			$chk_count2 = 0; // 입금완료 수
			$chk_count5 = 0; // 배송완료 수
			$chk_cancel = 0; // 클래임 수
			$sum_point  = 0; // 포인트적립

			$sql = " select * from shop_order where od_id = '$od_id' order by od_time desc, index_no asc ";
			$result = sql_query($sql);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$gs = unserialize($row['od_goods']);
                
				$it_options = print_complete_options($row['gs_id'], $row['od_id']);
				if($it_options){
					$it_options = '<div class="sod_opt">'.$it_options.'</div>';
				}
                
			?>
			<tr class="<?php echo $bg; ?>">
				<td>
					<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 40, 40); ?></a>
				</td>
				<td class="tal">
					<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"  target="_blank"><?php echo get_text($gs['gname']); ?></a>
					<?php echo $it_options; ?>
					<?php if(in_array($row['dan'], array(3,4,5,10,11,12,13))) { ?>
					<div class="frm_info">
                        <?php echo get_delivery_inquiry($row['delivery'], $row['delivery_no'], 'btn_ssmall'); ?>
					</div>
					<?php } ?>
				</td>
				<td>
                    <?php echo $gw_status[$row['dan']];  ?>
				</td>
				<td><?php echo number_format($row['sum_qty']); ?></td>
				<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
				<td class="tar"><?php echo number_format($row['baesong_price']); ?></td>
				<td class="tar"><?php echo number_format($row['coupon_price']); ?></td>
				<td class="tar"><?php echo number_format($row['use_point']+$row['use_point2']); ?></td>
				<td class="tar"><?php echo number_format($row['use_price']); ?></td>
				<td class="td_price"><?php echo number_format($row['use_price']+$row['use_point']+$row['use_point2']); ?></td>
			</tr>
            <?php
			}
			?>
			</tbody>
			</table>
		</div>

		<?php if($od['od_test']) { ?>
		<div class="od_test_caution">주의) 이 주문은 테스트용으로 실제 결제가 이루어지지 않았으므로 절대 배송하시면 안됩니다.</div>
		<?php } ?>

		<?php if($od['od_mod_history']) { ?>
		<section id="sodr_qty_log">
			<h3>주문 전체취소 처리 내역</h3>
			<div>
				<?php echo conv_content($od['od_mod_history'], 0); ?>
			</div>
		</section>
		<?php } ?>
	</section>

	<?php
	// 결제방법
	$s_receipt_way = $od['paymethod'];

	if($od['paymethod'] == '간편결제') {
		if($od['od_pg'] == 'lg')
			$s_receipt_way = 'PAYNOW';
		else if($od['od_pg'] == 'inicis')
			$s_receipt_way = 'KPAY';
		else if($od['od_pg'] == 'kcp')
			$s_receipt_way = 'PAYCO';
		else
			$s_receipt_way = $od['paymethod'];
	}

	if($amount['usepoint'] > 0)
		$s_receipt_way .= "+포인트";
	?>

	<section id="anc_sodr_pay" class="new_win_desc mart30">
		<h3 class="anc_tit">주문결제 내역</h3>
		<?php echo $pg_anchor; ?>
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="mod_type" value="receipt">

		<div class="compare_wrap">
			<section id="anc_sodr_chk" class="compare_left">
				<h3>결제상세정보 확인</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w150">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row" class="bg0">총 상품금액</th>
						<td class="tar bg0"><?php echo display_price($amount['price']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="bg0">총 배송비</th>
						<td class="tar fc_197 bg0">(+) <?php echo display_price($amount['baesong']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="bg0">총 쿠폰할인</th>
						<td class="tar fc_197 bg0">(-) <?php echo display_price($amount['coupon']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="bg0">포인트결제</th>
						<td class="tar fc_197 bg0">(-) <?php echo display_price($amount['usepoint']+$amount['usepoint2']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="bg0">실 결제금액</th>
						<td class="tar bg0"><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">총 주문금액</th>
						<td class="td_price"><?php echo display_price($amount['buyprice']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="fc_red bg1">환불금액</th>
						<td class="td_price bg1 fc_red">(-) <?php echo display_price($amount['refund']); ?></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

			<section id="anc_sodr_paymo" class="compare_right">
				<h3>결제상세정보 수정</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w150">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row">주문번호</th>
						<td><?php echo $od['od_id']; ?></td>
					</tr>
					<tr>
						<th scope="row">주문일시</th>
						<td><?php echo $od['od_time']; ?> (<?php echo get_yoil($od['od_time']); ?>)</td>
					</tr>
					<tr>
						<th scope="row">주문채널</th>
						<td><strong><?php echo $od['shop_id']; ?></strong> <?php echo $od['od_mobile']?'모바일':'PC'; ?> 쇼핑몰에서 주문</td>
					</tr>
					<tr>
						<th scope="row">결제방법</th>
						<td><?php echo $s_receipt_way; ?></td>
					</tr>
					<?php if(in_array($od['paymethod'], array('무통장', '가상계좌', '계좌이체'))) { ?>
					<?php
					if($od['paymethod'] == '무통장')
						$bank_account = get_bank_account("bank", $od['bank']);
					else if($od['paymethod'] == '가상계좌')
						$bank_account = $od['bank'].'<input type="hidden" name="bank" value="'.$od['bank'].'">';
					else if($od['paymethod'] == '계좌이체')
						$bank_account = $od['paymethod'];
					?>
					<?php if(in_array($od['paymethod'], array('무통장', '가상계좌'))) { ?>
					<tr>
						<th scope="row"><label for="bank">계좌번호</label></th>
						<td><?php echo $bank_account; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th scope="row"><label for="deposit_name">입금자명</label></th>
						<td><input type="text" name="deposit_name" value="<?php echo get_text($od['deposit_name']); ?>" id="deposit_name" class="frm_input" placeholder="실 입금자명"></td>
					</tr>
					<tr>
						<th scope="row"><?php echo $od['paymethod']; ?> 입금액</th>
						<td><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">입금확인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '입금 확인일시 정보가 없습니다.';
							else
								echo $od['receipt_time'].' ('.get_yoil($od['receipt_time']).')';
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '휴대폰') { ?>
					<tr>
						<th scope="row">휴대폰번호</th>
						<td><?php echo get_text($od['bank']); ?></td>
					</tr>
					<tr>
						<th scope="row">휴대폰 결제액</th>
						<td><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">결제 확인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '결제 확인일시 정보가 없습니다.';
							else
								echo $od['receipt_time'].' ('.get_yoil($od['receipt_time']).')';
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '신용카드') { ?>
					<tr>
						<th scope="row">신용카드 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">카드 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '신용카드 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == 'KAKAOPAY') { ?>
					<tr>
						<th scope="row" class="sodr_sppay">KAKOPAY 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row" class="sodr_sppay">KAKAOPAY 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '신용카드 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '간편결제' || ($od['od_pg'] == 'inicis' && $od['paymethod'] == '삼성페이') ) { ?>
					<tr>
						<th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo $s_receipt_way. ' 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>
					</tbody>
					</table>
				</div>
			</section>
		</div>

	</section>


	<section id="anc_sodr_addr">
		<h3 class="anc_tit">주문자/배송지 정보</h3>
		<?php echo $pg_anchor; ?>

		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="mod_type" value="info">

		<div class="compare_wrap">
			<section id="anc_sodr_orderer" class="compare_left">
				<h3>주문하신 분</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w100">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row"><label for="od_name">이름</label></th>
						<td><input type="text" name="name" value="<?php echo get_text($od['name']); ?>" id="od_name" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row"><label for="cellphone">핸드폰</label></th>
						<td><input type="text" name="cellphone" value="<?php echo get_text($od['cellphone']); ?>" id="cellphone" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row">주소</th>
						<td>
							<label for="zip" class="sound_only">우편번호</label>
							<input type="text" name="zip" value="<?php echo $od['zip']; ?>" id="zip" required class="frm_input required" size="5" maxlength="5">
							<button type="button" class="btn_small grey" onclick="win_zip('frmorderform2', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">주소검색</button><br>
							<span id="od_win_zip" style="display:block"></span>
							<input type="text" name="addr1" value="<?php echo get_text($od['addr1']); ?>" id="addr1" required class="frm_input required" size="35">
							<label for="addr1">기본주소</label><br>
							<input type="text" name="addr2" value="<?php echo get_text($od['addr2']); ?>" id="addr2" class="frm_input" size="35">
							<label for="addr2">상세주소</label><br>
							<input type="text" name="addr3" value="<?php echo get_text($od['addr3']); ?>" id="addr3" class="frm_input" size="35">
							<label for="addr3">참고항목</label><br>
							<input type="hidden" name="addr_jibeon" value="<?php echo get_text($od['addr_jibeon']); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="od_email">E-mail</label></th>
						<td><input type="text" name="email" value="<?php echo $od['email']; ?>" id="od_email" required class="frm_input required" size="30"></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

			<section id="anc_sodr_taker" class="compare_right">
				<h3>받으시는 분</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w100">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row"><label for="b_name">이름</label></th>
						<td><input type="text" name="b_name" value="<?php echo get_text($od['b_name']); ?>" id="b_name" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row"><label for="b_cellphone">핸드폰</label></th>
						<td><input type="text" name="b_cellphone" value="<?php echo get_text($od['b_cellphone']); ?>" id="b_cellphone" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row">주소</th>
						<td>
							<label for="b_zip" class="sound_only">우편번호</label>
							<input type="text" name="b_zip" value="<?php echo $od['b_zip']; ?>" id="b_zip" required class="frm_input required" size="5" maxlength="5">
							<button type="button" class="btn_small grey" onclick="win_zip('frmorderform2', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');">주소검색</button><br>
							<input type="text" name="b_addr1" value="<?php echo get_text($od['b_addr1']); ?>" id="b_addr1" required class="frm_input required" size="35">
							<label for="b_addr1">기본주소</label><br>
							<input type="text" name="b_addr2" value="<?php echo get_text($od['b_addr2']); ?>" id="b_addr2" class="frm_input" size="35">
							<label for="b_addr2">상세주소</label><br>
							<input type="text" name="b_addr3" value="<?php echo get_text($od['b_addr3']); ?>" id="b_addr3" class="frm_input" size="35">
							<label for="b_addr3">참고항목</label><br>
							<input type="hidden" name="b_addr_jibeon" value="<?php echo get_text($od['b_addr_jibeon']); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">전달 메세지</th>
						<td><?php if($od['memo']) echo get_text($od['memo'], 1);else echo "없음"; ?></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

		</div>

		<div class="btn_confirm">
			<a href="javascript:window.close();" class="btn_medium bx-white">닫기</a>
		</div>
	</section>

</div>


<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>
