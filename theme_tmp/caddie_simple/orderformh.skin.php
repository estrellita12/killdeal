<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');

//20191219 도담골프,골프유닷넷 포인트 사용
if($pt_id=='dodamgolf')
{
	$config['usepoint_yes'] = 1;
}

?>


<!-- 주문서작성 시작 { -->
<!-- <p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p> -->

<h2 class="pg_tit subpg_tit">
	<span>주문결제</span>
</h2>



<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">

<div class="tbl_wrap_box">
	<p class="pg_cnt marb15">
		<strong class="line">01. 주문상품</strong>주문하실 상품 내역에 수량 및 주문금액이 틀리지 않는지 반드시 확인하시기 바랍니다.
		<?php if($is_member && $pt_id == 'dodamgolf') echo "</br>※ 포인트를 사용 할 경우 사용한 부분 만큼 적립에서 제외 됩니다.";?>
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w120">
			<col>
			<col class="w120">
			<col class="w120">
			<col class="w120">
			<col class="w120">
			<col class="w120">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">이미지</th>
			<th scope="col">상품/옵션정보</th>
			<th scope="col">수량</th>
			<th scope="col">상품금액</th>
			<th scope="col">소계</th>
			<th scope="col">포인트</th>
			<th scope="col">배송비</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$tot_point = 0;
		$tot_sell_price = 0;
		$tot_opt_price = 0;
		$tot_sell_qty = 0;
		$tot_sell_amt = 0;
		$seller_id = array();

		$sql = " select *
				   from shop_cart
				  where index_no IN ({$ss_cart_id})
					and ct_select = '0'
				  group by gs_id
				  order by index_no ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs = get_goods($row['gs_id']);

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
					   from shop_cart
					  where gs_id = '$row[gs_id]'
						and ct_direct = '$set_cart_id'
						and ct_select = '0'";
			$sum = sql_fetch($sql);

			$it_name = stripslashes($gs['gname']);
			$it_options = print_item_options($row['gs_id'], $set_cart_id);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}

			if($is_member) {
				if($pt_id == 'dodamgolf')
				{
					switch(get_session('ss_mb_gd'))
					{
						//브론즈
						case '1':
						$point = 0;
						break;
						//실버
						case '3':
						$point = floor($sum['price'] * 0.01);
						break;
						//골드
						case '4':
						$point = floor($sum['price'] * 0.03);
						break;
						//VIP
						case '5':
						$point = floor($sum['price'] * 0.05);
						break;
						//임직원
						case '2':
						$point = floor($sum['price'] * 0.05);
						break;
						//센터장
						case '6':
						$point = floor($sum['price'] * 0.05);
						break;
					}
				}
				else
				{
					$point = $sum['point'];
				}
			}

			$supply_price = $sum['supply_price'];
			$sell_price = $sum['price'];
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 배송비
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$seller_id[$i] = $gs['mb_id'];

			$href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
		?>
		<tr>
			<td class="tac">
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
				<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
				<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
				<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
				<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
				<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
				<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
				<?php echo get_it_image($row['gs_id'], $gs['simg1'], 100, 100); ?>
			</td>
			<td class="td_name"><?php echo $it_name; ?></td>
			<td class="tac"><?php echo number_format($sell_qty); ?></td>
			<td class="tac"><?php echo number_format($sell_amt); ?>원</td>
			<td class="tac"><?php echo number_format($sell_price); ?>원</td>
			<td class="tac"><?php echo number_format($point); ?></td>
			<td class="tac"><?php echo number_format($info['price']); ?>원</td>
		</tr>
		<?php
			$tot_point += (int)$point;
			$tot_sell_price += (int)$sell_price;
			$tot_opt_price += (int)$sell_opt_price;
			$tot_sell_qty += (int)$sell_qty;
			$tot_sell_amt += (int)$sell_amt;
		}

		// 배송비 검사
		$send_cost = 0;
		$com_send_cost = 0;
		$sep_send_cost = 0;
		$max_send_cost = 0;

		$k = 0;
		$condition = array();
		foreach($item_sendcost as $key) {
			list($userid, $bundle, $price) = explode('|', $key);
			$condition[$userid][$bundle][$k] = $price;
			$k++;
		}

		$com_array = array();
		$val_array = array();
		foreach($condition as $key=>$value) {
			if($condition[$key]['묶음']) {
				$com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
				$max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
                // (2020-12-28) 배송비 측정 오류 수정
                //$com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
                $com_array[] = array_search( max($condition[$key]['묶음']) , $condition[$key]['묶음'] ); // max key
				$val_array[] = max(array_values($condition[$key]['묶음']));// max value
			}
			if($condition[$key]['개별']) {
				$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
				$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
				$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
			}
		}

		$baesong_price = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
		$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
		$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액


		$tot_price1 = $tot_price;//org_price 셋팅
			  
		if($pt_id =='golf')
		{
			 if($httpCode != 200) {// 정상적 실행X =>예외처리_20201021
                      $use_point2 = 0;  
			 }
			 if($use_point2 >= $tot_price)
			 {
				  $de_useprice = $tot_price; //기본금 사용액
				  $tot_price -= $de_useprice;
				  $point_etc = $use_point2 - $de_useprice; //기본금 잔액
					 
			 }
			 else //기본금이 0이거나 결제금액보다 작을때
			 {
				  $de_useprice = $use_point2;
				  $tot_price -= $de_useprice;
		    	  $point_etc = 0;
			 }
		}
			
			
		?>
		</tbody>
		</table>
		
	</div>
	<!-- 총 주문금액 표시 start -->
   
	<!-- 총 주문금액 표시 end -->
</div>





<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
<!-- 회원이 보유한 현대리바트 기본금 -->
<input type="hidden" name="mb_point2" value="<?php echo $use_point2;?>">
<!-- 기본금 사용액 -->
<!-- 
기본금 사용이 use_point22 ->use_point2로 변경되면서 불필요
<input type="hidden" id="use_point2" name="use_point2" value="<?php echo $de_useprice;?>">
-->
<? if($pt_id == "golf" ) { //현대리바트 ?>
    <input type="hidden" name="pt_id" value="<?php echo $pt_id ?>">
	<input type="hidden" name="use_point" value="0">

<? }else{ ?>
    <input type="hidden" name="pt_id" value="<?php echo $mb_recommend; ?>">
<? } ?>

<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="coupon_total" value="0">
<input type="hidden" name="coupon_price" value="">
<input type="hidden" name="coupon_lo_id" value="">
<input type="hidden" name="coupon_cp_id" value="">
<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
<input type="hidden" name="baesong_price2" value="0">
<input type="hidden" name="org_price" value="<?php echo $tot_price1; ?>">
<input type="hidden" name="tot_price_" value="<?php echo $tot_price; ?>"> <!-- email 결제금액 setting_20190925 -->


		
		

<section id="sod_fin_orderer" >
   	
	<h2 class="pg_cnt marb15">
		<strong>02. 고객정보</strong>
	</h2>
	<div class="tbl_frm01 tbl_wrap tbl_wrap1">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>

     

		<?php if(!$is_member) { // 비회원이면 ?>
		<tr>
			<th scope="row">비밀번호</th>
			<td>
				<input type="password" name="od_pwd" required itemname="비밀번호" class="frm_input required" size="20">
				<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">이름</th>
			
            <td><input type="text" name="name" value="<?php echo get_session('mem_nm'); ?>" readonly required itemname="이름" class="frm_input required" size="20"></td>
			
		</tr>
<!-- 		<tr> -->
<!-- 			<th scope="row">전화번호</th> -->
<!-- 			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td> -->
<!-- 		</tr> -->
		<tr>
			<th scope="row">휴대폰(<font color=red>*</font>)</th>
			
            <td><input type="text" name="cellphone" onKeyup="PhoneNumberSplit(this);" value="<?php echo $member['cellphone']; ?>" required  class="frm_input required" size="20" maxlength="13"></td>
			

		</tr>
		<tr>
			<th scope="row">주소(<font color=red>*</font>)</th>
			<td>
				<div>
					<input type="text" readonly name="zip" value="<?php if($member['zip'] != null) { echo $member['zip']; } else { echo $address['b_zip']; }?>" required itemname="우편번호" class="frm_input required line_none" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt8">
					<input type="text" readonly name="addr1" value="<?php if($member['addr1'] != null) { echo $member['addr1']; } else { echo $address['b_addr1']; } ?>" required itemname="주소" class="frm_input required line_none" size="60" readonly> 기본주소
				</div>
				<div class="padt8">
					<input type="text" name="addr2" value="<?php if($member['addr2'] != null) { echo $member['addr2']; } else { echo $address['b_addr2']; } ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt8">
					<input type="text" name="addr3" value="<?php if($member['addr3'] != null) { echo $member['addr3']; } else { echo $address['b_addr3']; } ?>" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php if($member['addr_jibeon'] != null) { echo $member['addr_jibeon']; } else { echo $address['b_addr_jibeon']; } ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">이메일(<font color=red>*</font>)</th>
			<td>
				<label><input type="text" name="email" value="<?php echo $member['email']; ?>" required class="frm_input required" size="30"></label>
				<label>@<input type="hidden" name="email2" id="email2" class="frm_input required" size="30"></label>
				<select id="email2_select" name="email2_select" onChange="email_change()">
					<option value="">직접입력</option>
					<option value="naver.com">naver.com</option>
					<option value="hanmail.net">hanmail.net</option>
					<option value="daum.net">daum.net</option>
					<option value="gmail.com">gmail.com</option>
					<option value="nate.com">nate.com</option>
					<option value="hotmail.com">hotmail.com</option>
					<option value="yahoo.co.kr">yahoo.co.kr</option>
					<option value="paran.com">paran.com</option>
					<option value="empas.com">empas.com</option>
					<option value="dreamwiz.com">dreamwiz.com</option>
					<option value="freechal.com">freechal.com</option>
					<option value="lycos.co.kr">lycos.co.kr</option>
					<option value="korea.com">korea.com</option>
					<option value="hanmir.com">hanmir.com</option>
				</select>
			</td>
		</tr>
		</table>
	</div>
</section>
<section id="sod_fin_receiver">
	<h2 class="pg_cnt marb15">
		<strong>03. 배송정보</strong>
	</h2>
	<div class="tbl_frm01 tbl_wrap tbl_wrap1">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">배송지선택</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 주문자와 동일</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 신규배송지</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 배송지목록</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">받는사람</th>
			<td><input type="text" name="b_name" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
<!-- 		<tr> -->
<!-- 			<th scope="row">전화번호</th> -->
<!-- 			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td> -->
<!-- 		</tr> -->
		<tr>
			<th scope="row">휴대폰</th>
			<td><input type="text" name="b_cellphone" required itemname="핸드폰" onKeyup="PhoneNumberSplit(this);" maxlength="13" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" readonly name="b_zip" required itemname="우편번호" class="frm_input required line_none" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt8">
					<input type="text" readonly name="b_addr1" required itemname="주소" class="frm_input required line_none" size="60" readonly> 기본주소
				</div>
				<div class="padt8">
					<input type="text" name="b_addr2" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt8">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">배송메세지</th>
			<td>
				<select name="sel_memo">
					<option value="">메세지를 선택해주세요.</option>
					<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
					<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
					<option value="부재시 휴대폰으로 연락바랍니다.">부재시 휴대폰으로 연락바랍니다.</option>
					<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
				</select>
				<input type="text" name="memo" class="frm_textbox mart5">
				<!-- <textarea name="memo" class="frm_textbox mart5" rows="3"></textarea> -->
				<!-- <span class="frm_info"><strong class="fc_red">"택배사원"</strong>에 전하실 말씀을 써주세요~!<br>C/S관련문의는 고객센터에 작성해주세요. 이곳에 남기시면 확인이 불가능합니다.</span> -->
			</td>
		</tr>
		</table>
	</div>
</section>

<div class="pay_sod_wrap">
<div class="cart_l">
<section id="sod_fin_pay">
	<h2 class="pg_cnt marb15">
		<strong>04. 결제수단</strong>
	</h2>
	<div class="tbl_frm01 tbl_wrap tbl_wrap1">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>

		
		<tr>
			<th scope="row">결제방법</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "에스크로 ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">카카오페이</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="무통장" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">무통장입금</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="신용카드" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">신용카드</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="휴대폰" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">휴대폰</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="계좌이체" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">계좌이체</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="가상계좌" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">가상계좌</label>'.PHP_EOL;
				}
				if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point']) && $pt_id != "golf" && $pt_id != "dodamgolf") {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="포인트" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">포인트결제</label>'.PHP_EOL;
				}
                //현대복지몰
				if($is_member && $pt_id == "golf" ) {
					echo '<input type="radio" name="paymethod" id="paymethod_point2" value="복지카드" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point2">현대(복지)카드</label>'.PHP_EOL;
				}


				// PG 간편결제
				if($default['de_easy_pay_use']) {
					switch($default['de_pg_service']) {
						case 'lg':
							$pg_easy_pay_name = 'PAYNOW';
							break;
						case 'inicis':
							$pg_easy_pay_name = 'KPAY';
							break;
						case 'kcp':
							$pg_easy_pay_name = 'PAYCO';
							break;
					}

					echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="간편결제" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<tr style="display:none;" class="bank_acc_tr">
			<th scope="row">입금계좌선택</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr style="display:none;" class="bank_acc_tr">
			<th scope="row">입금자명</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
<!-- 현금영수증 신청영역이 리바트에서도 필요하다면 id를 구분해서 별도 태그 생성(ex. name=tax_hp , tax_hp2) -->
<?php if($pt_id !='golf') { ?>

		<?php if(!$config['company_type']) { ?>
		<tr style="display:none;" class="tax_tr">
			<th scope="row">현금영수증발행</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">개인 소득공제용</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">사업자 지출증빙용</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">미발행</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">휴대폰번호</th>
			<td>
				<input type="text" name="tax_hp" onKeyup="PhoneNumberSplit(this);" maxlength="13" class="frm_input" size="20">
				<span class="frm_info">
					현금영수증은 1원이상 현금 구매시 발급이 가능합니다.<br>
					현금영수증은 구매대금 입금확인일 다음날 발급됩니다.<br>
					현금영수증 홈페이지 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">사업자등록번호</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<!-- origin: style="display:none;" class="tax_tr" 항목X -->
		<tr style="display:none;" class="tax_tr"> 
			<th scope="row">세금계산서발행</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">발행요청</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">미발행</label>
			</td>
		</tr>
		
		<tr class="taxbill_fld">
			<th scope="row">사업자등록번호</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">상호(법인명)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> 예 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">대표자</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> 예 : 홍길동</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">사업장주소</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">업태</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> 예 : 도소매</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">종목</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> 예 : 전자부품</td>
		</tr>
		<?php } //company_type close ?>

	<?php } //pt_id=golf close ?>
		<tr>
			<th scope="row">합계</th>
			<td class="bold"><?php echo display_price($tot_price1); ?></td>
		</tr>
		<!-- start -->
         
      	<tr>
			<th scope="row">기본금 사용금액</th>
			<td>  <!-- use_point22 ->use_point2 -->
				(-)<input type="text" id="use_point2" name="use_point2" value="<?php echo $de_useprice;?>"  class="frm_input" size="12"  readonly style="font-weight:bold;">원  (사용후 기본금 잔여금액 : 
				<span id="hwelpoint"><?php echo number_format($point_etc); ?> </span> 원)<!-- 사용후 기본금 잔여금액 -->
               <!-- 
			    onkeyup="calculate_temp_point2(this.value);this.value=number_format(this.value);"
			   -->
			   <br><br>*기본금 보유금액은 상품판매가에 먼저 적용됩니다.	
			</td>
		</tr>
			
		<?php
		  if(get_session('cashreceipt_yn') == 'Y') {
		?>
	    <tr>
			<th scope="row">기본금 현금영수증 신청</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">개인 소득공제용</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">사업자 지출증빙용</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">미발행</label>
			</td>
		</tr>
	   <? } ?>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">휴대폰번호</th>
			<td>
				<input type="text" name="tax_hp"  maxlength="13" class="frm_input" size="20" placeholder="01012341234">
				<span class="frm_info">
					현금영수증은 1원이상 현금 구매시 발급이 가능합니다.<br>
					현금영수증은 구매대금 입금확인일 다음날 발급됩니다.<br>
					현금영수증 홈페이지 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">사업자등록번호</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
        		
		<!-- end -->
		<tr>
			<th scope="row">추가배송비</th>
			<td>
				<strong><span id="send_cost2">0</span>원</strong>
				<span class="fc_999">(지역에 따라 추가되는 도선료 등의 배송비입니다.)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // 보유쿠폰
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">할인쿠폰</th>
			<td>(-) <strong><span id="dc_amt">0</span>원 <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>사용 가능 쿠폰 <?php echo $cp_count[3]; ?>장</a> </span></span></td>
		</tr>
		<?php } ?>
		
	
		
    
		<tr>
			<th scope="row">총 결제금액</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> 원
			</td>
		</tr>
		<!--20191210 에스크로 관련 약관 추가-->
		<!--
		<tr>
			<td>
			<p style="text-align:center">※ 전자상거래 구매 안전 서비스 안내</p>
			
			</td>
			<td>
				
				전자금융거래법에 따라 금융감독(원) 위원회에 결제대금 예치업을 등록하였으며, 안전거래를 위해 구매 금액, 결제수단에 상관없이 모든 거래에 대하여 저희 쇼핑몰에서 가입한 구매안전서비스(에스크로)를 자동으로 적용하고 있습니다.
			</td>
		</tr>
		-->
	
		</table>

	</div>
</section>

<!-- 전자상거래 동의 start -->
<br>
<section id="ec_privacy">
	<h3 class="pg_cnt marb15">
		주문할 상품의 상품명,상품가격,배송정보를 확인하였으며, 구매에 동의하시겠습니까
	</h3>
	<!--
    <fieldset id="guest_agree2">
		<input type="checkbox" id="agree2" value="1">
		<label for="agree2">동의합니다.(전자상거래법 제8조 2항) </label>
	</fieldset>
	-->
	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">동의합니다.(전자상거래법 제8조 2항)</label>
	</fieldset>
</section>

<!-- 전자상거래 동의 end -->


</div>

<div class="cart_r">
	<div id="sod_bsk_tot">
		<p>최종 결제 금액 확인</p>
		<div class="sod_bsk_tot_box">
			<dl>
				<dt>총 상품금액</dt>
				<dd class="total_pri" data-price="<?php echo $tot_sell_price; ?>"><span class="spr"><?php echo display_price2($tot_sell_price); ?></span></dd>
			</dl>
			<!-- 현대리바트만 필요한 부분 -->
			<dl>
				<dt>기본금(-)</dt>
				<dd class="total_delivery"><span id="h_point" class="spr"><?php  echo display_price2($de_useprice *-1); ?></span></dd>
			</dl><br>
			<dl>
				<dt>배송비</dt>
				<dd class="total_delivery"><span class="spr"><?php echo display_price2($tot_send_cost); ?></span></dd>
			</dl>
		</div>
		<div class="sod_bsk_total_box">
			<dl>
				<dt>결제예정금액</dt>
				<dd class="fc_red"><span id="tot_price3" class="spr"><?php echo display_price2($tot_price); ?></span></dd>
			</dl>		
		</div>
	</div>

	<div class="btn_confirm">
		<input type="submit" value="주문하기" class="btn_large wset">
		<!-- <a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="btn_large bx-white">취소</a> -->
	</div>
</div>
</div>
</form>
<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			
			win_open(tb_shop_url+'/orderaddress.php','win_address', 500, 500, 'no');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("input[name=memo]").val($(this).val());
    });
});

// 휴대폰번호  자릿수판단 - 자동삽입
function PhoneNumberSplit(x){
	var number = x.value.replace(/[^0-9]/g, "");
	var phone = "";

	if(number.length < 4){
		phone += number;
	}
	else if(number.length < 7){
		phone += number.substr(0,3);
		phone += "-";
		phone += number.substr(3);
	}
	else if(number.length < 11){
		phone += number.substr(0,3);
		phone += "-";
		phone += number.substr(3,3);
		phone += "-"
		phone += number.substr(6);
	}
	else{
		phone += number.substr(0,3);
		phone += "-";
		phone += number.substr(3,4);
		phone += "-"
		phone += number.substr(7);
	}
	x.value = phone;
}

// 도서/산간 배송비 검사
function calculate_sendcost(code) {
    $.post(
        tb_shop_url+"/ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=baesong_price2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function calculate_order_price() {

	var mb_point2 = 0;
	var point_etc = 0;
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인

	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
	var mb_point2 = 0;
	<?php if($pt_id == "golf") { 
		
	?>
	mb_point2   = parseInt($("input[name=use_point2]").val().replace(/[^0-9]/g, "")); //기본금결제
	//alert("기본금:" + mb_point2);
	//var de_point = parseInt($("input[name=mb_point2]").val()); // 기본금 total 잔액
	//point_etc = de_point-mb_point2;
    //$("#hwelpoint").html(point_etc);
	 

	<?php } ?>
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point + mb_point2);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=tot_price_]").val(String(tot_price));
  
	
	//alert(tot_price);
}

function fbuyform_submit(f) {

	var f = document.buyform;
	var pattern = /([0-9a-zA-Z_-]+)@([0-9a-z_-]+)\.([0-9a-z_-]+)/; //정규표현식 변수
	var hanglepattern = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/; // 정규표현식(한글)변수

	//이메일 선택하지않고 직접입력
	if(f.email2_select.value == "") {
		//abc@abc.abc 형식인지 검사
		if(!pattern.test(f.email.value) || hanglepattern.test(f.email.value)) {

			alert("이메일 형식이 잘못돼었습니다.");
			f.email.focus();
			return false;	
		}
	}
	// 이메일선택
	if(f.email2_select.value != ""){
		// abc@abc.abc 형식이면 false(아이디부분만 입력해야함)
		if(pattern.test(f.email.value) || hanglepattern.test(f.email.value)){
			alert("이메일 형식을 확인해주세요");
			f.email.focus();
			return false;
		}
		// 입력한 이메일 + 선택한 이메일주소 합쳐줆
		f.email2.value = f.email2_select.value;
		f.email.value = f.email.value + "@" + f.email2_select.value;
	}
	
	if(f.email.value == ""){ // email에 내용이 입력안돼면 return false

		f.email.focus();
		alert("이메일을 입력해주세요.")
		return false;
	}

    errmsg = "";
    errfld = "";

	
	var temp_point2	= parseInt(no_comma(f.use_point2.value));//리바트
	var mb_point2	= parseInt(f.mb_point2.value);
 	var sell_price	= parseInt(f.org_price.value);
	var send_cost2	= parseInt(f.baesong_price2.value);
	var mb_coupon	= parseInt(f.coupon_total.value);
	//var mb_point	= parseInt(f.mb_point.value);
	var tot_price	= sell_price + send_cost2 - mb_coupon;

   
  	<?php if(!$config['company_type']) { ?>
	if(temp_point2 >0 && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "휴대폰번호를 입력하세요");
	}
	<?php } ?>



	var paymethod_check = false;
	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
			paymethod_check = true;
		}
	}

	if(f.tot_price.value != 0)
	{
		if(!paymethod_check) {
        alert("결제방법을 선택하세요.");
        return false;
        }
	}
	
    
    

	
    /*
    if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("청약의사 재확인을 동의해 주셔야 주문을 진행하실 수 있습니다.");
			return false;
		}
	}
	*/
	if(!f.agree.checked) {
		alert("청약의사 재확인을 동의해 주셔야 주문을 진행하실 수 있습니다.");
		f.agree.focus();
		return false;
	}

    
    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    
   

	
	if(!confirm("주문내역이 정확하며, 주문 하시겠습니까?\n[복지카드(복지포인트)/기본금 결제시 부분취소가 불가합니다.]")) {
		f.email.focus();
		return false;
	}


	//f.use_point.value = no_comma(f.use_point.value);
	//현대리바트
    f.use_point2.value = no_comma(f.use_point2.value);
	f.tot_price.value = no_comma(f.tot_price.value);
   
	return true;
}

function calculate_temp_point(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('포인트 사용액은 숫자이어야 합니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
		f.tot_price_.value = tot_price - temp_point;//20191220 tot_price_ 에 포인트 게산 가격 반영
	}
}
//현대리바트 포인트 계산_20190816
function calculate_temp_point2(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point2.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('기본금 사용액은 숫자이어야 합니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.tot_price2.value = number_format(String(tot_price));
		f.use_point2.value = 0;
		f.use_point2.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
		f.tot_price2.value = number_format(String(tot_price - temp_point));
		//***use_point2설정 추가필요
		//기본금사용후 잔액표시처리_20190816
		var de_point = parseInt($("input[name=mb_point2]").val()); // 기본금 total 잔액
	    point_etc = de_point-temp_point;
        $("#hwelpoint").html(point_etc);
		//우측 최종결제금액확인 적용_20190816
		var t_price = number_format(String(tot_price - temp_point));
		$("#h_point").html(number_format(temp_point *(-1)) + "원");//기본금 사용액 출력하기
		$("#tot_price3").html(t_price + "원");


	 
	}
}
//유닷넷 포인트 계산_20191210
function calculate_temp_point3(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point2.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('기본금 사용액은 숫자이어야 합니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.tot_price2.value = number_format(String(tot_price));
		f.use_point2.value = 0;
		f.use_point2.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
		f.tot_price2.value = number_format(String(tot_price - temp_point));
	
	    /*
		var de_point = parseInt($("input[name=mb_point2]").val()); // 포인트 잔액
	    point_etc = de_point-temp_point;
        $("#hwelpoint").html(point_etc);
		*/
		//우측 최종결제금액확인 적용_20190816
		var t_price = number_format(String(tot_price - temp_point));
		$("#h_point").html(number_format(temp_point *(-1)) + "원");//기본금 사용액 출력하기
		$("#tot_price3").html(t_price + "원");


	 
	}
}



function email_change(){

	var f = document.buyform;

	if(f.email2_select.value == ""){ // ""은 직접입력
		f.email.value = '';
		f.email2.value = '';
	}
	else{
		if(f.email.value.indexOf("@") != -1) { // 메일값이있지만 다른 메일을 직접입력할경우
			f.email.value = '';
		}
	}
}

//결제수단 선택함수
function calculate_paymethod(type) {

	
	//복지카드 선택시 복지상점쪽으로 연결되어야 한다.
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var mb_point2   = parseInt($("input[name=mb_point2]").val()); // 보유기본금
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == '포인트' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$(".bank_acc_tr").show();
		$(".tax_tr").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
		$(".tax_tr").show();

		<?php } ?>

		return;
	}
   
	

	switch(type) {
		case '무통장':
			$("#bank_section").show();
			$(".bank_acc_tr").show();
			$(".tax_tr").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
		<?php if($pt_id == "golf") {  ?>
		    $("input[name=use_point2]").val(0);
			$("input[name=use_point2]").attr("readonly", false);
		<?php }  ?>
	  		calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			$(".tax_tr").show();
			<?php } ?>
			break;
		case '포인트':
			$("#bank_section").hide();
			$(".bank_acc_tr").show();
			$(".tax_tr").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
		<?php if($pt_id == "golf") {  ?>
		    $("input[name=use_point2]").val(0);
			$("input[name=use_point2]").attr("readonly", false);
		<?php }  ?>
	  
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".tax_tr").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		
		default: // 그외 결제수단
			$("#bank_section").hide();
			$(".bank_acc_tr").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
		<?php if($pt_id == "golf") {  ?>
			//20190816_기본금사용_결제금액반영
		    //$("input[name=use_point2]").val(0);
			//$("input[name=use_point2]").attr("readonly", false);
		<?php }  ?>
	  
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".tax_tr").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

//현대리바트
function calculate_paymethod2(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point2   = parseInt($("input[name=mb_point2]").val()); // 복지포인트
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == '포인트' && mb_point2 < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$(".bank_acc_tr").show();
		$(".tax_tr").show();
		$("input[name=use_point2]").val(0);
		$("input[name=use_point2]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
		$(".tax_tr").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case '무통장':
			$("#bank_section").show();
			$(".bank_acc_tr").show();
			$(".tax_tr").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			$(".tax_tr").show();
			<?php } ?>
			break;
		case '포인트':
			$("#bank_section").hide();
			$(".bank_acc_tr").hide();
			$(".tax_tr").hide();
			$("input[name=use_point2]").val(number_format(String(tot_price)));
			$("input[name=use_point2]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".tax_tr").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$(".bank_acc_tr").hide();
			$(".tax_tr").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".tax_tr").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}


function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$(".taxbill_fld").hide();
			break;
	}
}

function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 최종 결제금액
	var mb_coupon  = parseInt(f.coupon_total.value); // 쿠폰할인
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=coupon_total]").val(0);
	$("input[name=coupon_price]").val("");
	$("input[name=coupon_lo_id]").val("");
	$("input[name=coupon_cp_id]").val("");
}

// 구매자 정보와 동일합니다.
function gumae2baesong(checked) {
    var f = document.buyform;
   
    
    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
//		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
//		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } 주문서작성 끝 -->
