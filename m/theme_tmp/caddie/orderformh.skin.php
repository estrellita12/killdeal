<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');

//20191218 도담골프 포인트 사용
if($pt_id=='dodamgolf')
{
	$config['usepoint_yes'] = 1;
}
 ?>


<!-- 주문서작성 시작 { -->
<div id="sod_approval_frm">
<?php
ob_start();
?>
	<div class="sod_frm_wrap">
		<p class="pg_cnt marb15">
			<strong>1. 주문상품</strong>
		</p>
		<ul class="sod_list">
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

				$it_name = '<strong>'.stripslashes($gs['gname']).'</strong>';
				$it_options = mobile_print_item_options($row['gs_id'], $set_cart_id);
				if($it_options){
					$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
				}
				
				//20191226 도담골프 적립금 정책
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
				$supply_price = $sum['supply_price'];
				$sell_price = $sum['price'];		
				$sell_opt_price = $sum['opt_price'];
				$sell_qty = $sum['qty'];
				$sell_amt = $sum['price'] - $sum['opt_price'];

				// 회원이 아니면 포인트초기화
				if(!$is_member) $point = 0;

				// 배송비
				if($gs['use_aff'])
					$sr = get_partner($gs['mb_id']);
				else
					$sr = get_seller_cd($gs['mb_id']);

				$info = get_item_sendcost($sell_price);
				$item_sendcost[] = $info['pattern'];

				$seller_id[$i] = $gs['mb_id'];

				$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
			?>

			<li class="sod_li">
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">		
				<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
				<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
				<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
				<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
				<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
				<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
				
				<div class="li_name">
					<?php echo $it_name; ?>
					<div class="li_mod" style="padding-left:100px;"></div>
					<span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span>
				</div>

				<div class="li_total_box">
					<span class="total_price total_span"><span>판매가</span>
					<strong><?php echo number_format($sell_price); ?>원</strong></span>
					<span class="total_point total_span"><span>배송비</span>
					<strong><?php echo number_format($info['price']); ?>원</strong></span>
					<span class="total_point total_span total_span_tot"><span>주문금액</span>
					<strong><?php echo number_format($sell_price); ?>원</strong></span>
				</div>
			
			</li>

			<?php
				$tot_point += (int)$point;
				$tot_sell_price += (int)$sell_price;
				$tot_opt_price += (int)$sell_opt_price;
				$tot_sell_qty += (int)$sell_qty;
				$tot_sell_amt += (int)$sell_amt;
			} // for 끝

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
                    // (2020-12-28) 배송비 선택 오류
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
			
			if($i == 0) {
				alert('장바구니가 비어 있습니다.', TB_MSHOP_URL.'/cart.php');
			}
			?>
		</ul>
	   
		<dl id="sod_bsk_tot">
			<dt class="sod_bsk_sell"><span>총 상품금액</span></dt>
			<dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> 원</strong></dd>
			<dt class="sod_bsk_dvr"><span>총 배송비</span></dt>
			<dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> 원</strong></dd>
			<!-- 현대리바트 기본금_20190807-->
			<!-- 디폴트 기본금을 찍어주고 회원이 기본금을 수정할수 있는건지 확인 필요함. -->
			<!-- *********결제수단이 무통장인경우 현금영수증 정상 작동 , 기본금일때 작동X (결제수단으로 확인해볼것) -->
          <? if($pt_id == "golf" ) {  ?>
			<dt class="sod_bsk_dvr"><span>기본금 사용(-)</span></dt>
			<dd class="sod_bsk_dvr"><strong>- <input type="text" id="use_point2" name="use_point2" value="<?php echo $de_useprice;?>" class="frm_input w100" onkeyup="calculate_temp_point2(this.value);this.value=number_format(this.value);" readonly>원</strong></dd>
			<dt class="sod_bsk_dvr"><span>사용후 기본금 잔여금액</span></dt>
			<dd class="sod_bsk_dvr"><strong><span id="hwelpoint"><?php echo number_format($point_etc); ?> </span>원</strong></dd> 
		  <?php } ?>
			<dt class="sod_bsk_cnt"><span>총 결제금액</span></dt>
			<dd class="sod_bsk_cnt">
			  <input type="text" name="tot_price2" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="color:red;font-weight:bold;text-align:right;"> 원
			</dd>
			<!-- <dt class="sod_bsk_point"><span>포인트</span></dt>
			<dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> P</strong></dd> -->
		</dl>
	</div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	?>



<div id="sod_frm">
	
	<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
    
	<!-- 기본금 사용액 -->
	<!--
	기본금 사용이 use_point22 ->use_point2로 변경되면서 불필요
	<input type="hidden" id="use_point2" name="use_point2" value="<?php echo $de_useprice;?>">
	-->
	<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
	<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
	
	<!-- 현대리바트 보유기본금 -->
    <input type="hidden" name="mb_point2" value="<?php echo $use_point2 ?>">

    <? if($pt_id == "golf" ) {  ?>
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
	<input type="hidden" name="tot_price_" value="<?php echo $tot_price; ?>"> <!-- email 결제금액 setting_201901008 -->
	<?php if(!$is_member || !$config['usepoint_yes']) { ?>
	<input type="hidden" name="use_point" value="0">
	<?php } ?>

	<?php echo $content; ?>
	
  
	<section id="sod_frm_orderer">
	 
		<p class="pg_cnt marb15">
			<strong>2. 고객정보</strong>
			<span><i>*</i> 표시된 항목은 필수입력 정보입니다.</span>
		</p>

	<!-- 기본금 현금영수증 신청 start -->
    <? if($pt_id == "golf" && get_session('cashreceipt_yn') == 'Y') { 
	
		    
	?>
  
		<h2 class="anc_tit">기본금 현금영수증 신청</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr class="taxsave_section">
				<td>
					<p>현금영수증</p>
					<select name="taxsave_yes" onchange="tax_save2(this.value);" class="wfull">
						<option value="N">발행안함</option>
						<option value="Y">개인 소득공제용</option>
						<option value="S">사업자 지출증빙용</option>
					</select>
				    <div id="taxsave_fld_11" style="display:none;"> 
						<input type="text" name="tax_hp" class="frm_input" placeholder="핸드폰번호">
						
					 </div> 
					<div id="taxsave_fld_22" style="display:none;">
						<input type="text" name="tax_saupja_no" class="frm_input" placeholder="사업자등록번호">
					</div>
				</td>
			</tr>
		
			</tbody>
			</table>
		</div>
	
	<?    
	    }
	?>
	<!-- 기본금 현금영수증 신청 end -->

		<div class="odf_tbl">
			<table>
			<tbody>
			<?php if(!$is_member) { // 비회원이면 ?>
			<tr>
				<td>
					<p class="required">비밀번호</p>
					<input type="password" name="od_pwd" required class="frm_input required" maxlength="20">
					<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
				</td>
			</tr>
			<?php } ?>
            <tr>
                <td>
					<p class="required">이름</p>
				  <?php if($pt_id == 'golf'){ ?>
                    <input type="text" name="name" value="<?php echo get_session('mem_nm'); ?>" readonly required class="frm_input required" maxlength="20">
				  <?php }else if($pt_id == 'golfpang') { ?>
                    <input type="text" name="name" value="<?php echo $_COOKIE['gp_name'];?>" required class="frm_input required" maxlength="20">
				  <?php //20191115 더골프쇼 주문폼 정보 자동생성
					    }else if($pt_id == 'thegolfshow') { ?>
                    <input type="text" name="name" value="<?php echo get_session('ss_mb_name');?>" required class="frm_input required" maxlength="20">
				  <?php }else{ ?> 
				    <input type="text" name="name" value="<?php echo $member['name']; ?>" required class="frm_input required" maxlength="20">
				  <?php } ?>
				</td>
            </tr>
			<tr>
				<td><p class="required">핸드폰</p>
				<?php if($pt_id == 'golfpang'){ ?>
				     <input type="text" name="cellphone" value="<?php echo $_COOKIE['gp_phone']; ?>" onKeyup="PhoneNumberSplit(this);" required class="frm_input required" maxlength="20"> 
				<?php //20191115 더골프쇼 주문폼 정보 자동생성
					  }else if($pt_id == 'thegolfshow'){ ?>
				     <input type="text" name="cellphone" value="<?php echo get_session('ss_mb_phone'); ?>" onKeyup="PhoneNumberSplit(this);" required class="frm_input required" maxlength="20"> 
				<?php }else{ ?> 
				     <input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" onKeyup="PhoneNumberSplit(this);" required class="frm_input required" maxlength="20">
				<?php } ?>
				</td>
			</tr>
		
			<tr>
				<td>
					<p class="required">주소</p>
                    <input type="text" readonly name="zip" value="<?php if($member['zip'] != null) { echo $member['zip']; } else { echo $address['b_zip']; } ?>" required class="frm_input required addr_zip" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey addr_btn">주소검색</button><br>
                    <input type="text" name="addr1" value="<?php if($member['addr1']) { echo $member['addr1']; } else { echo $address['b_addr1']; } ?>" required class="frm_input frm_address required line_none"><br>   
                    <input type="text" name="addr2" value="<?php if($member['addr2']) { echo $member['addr2']; } else { echo $address['b_addr2']; } ?>" class="frm_input frm_address"><br>
                    <input type="text" name="addr3" value="<?php if($member['addr3']) { echo $member['addr3']; } else { echo $address['b_addr3']; } ?>" class="frm_input frm_address" readonly><br>
                    <input type="hidden" name="addr_jibeon" value="<?php if($member['addr_jibeon']) { echo $member['addr_jibeon']; } else { echo $address['b_addr_jibeon']; }?>">
				</td>
			</tr>
			<tr>
				<td><p class="required">이메일</p>
				<?php //20191115 더골프쇼 주문폼 정보 자동생성
					  if($pt_id == 'thegolfshow'){ ?>
				   <input type="text" name="email" value="<?php echo get_session('ss_mb_email'); ?>" required class="frm_input required wfull"> 
				<?php }else{ ?> 

				<label><input type="text" name="email" value="<?php echo $member['email']; ?>" required class="frm_input required odr_email_form" size="30"></label>
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
				 
				 </td>
				<?php } ?>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_frm_taker">
		<p class="pg_cnt marb15">
			<strong>3. 배송정보</strong>
			<div class="ad_sel_addr_chk">
				<span><i>*</i> 표시된 항목은 필수입력 정보입니다.</span>
				<div>
					<input type="radio" name="ad_sel_addr" value="1" id="sel_addr1" class="css-checkbox lrg">
					<label for="sel_addr1" class="css-label padr5">고객정보와 동일</label>
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label padr5">신규배송지</label><br>
				</div>
			</div>
			
		</p>
		<div class="odf_tbl">
			<table>
			<tbody>
			<!-- <tr>
				<th scope="row">배송지선택</th>
				<td>
					
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label">신규배송지</label>
					<?php if($is_member) { ?>
					<br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
					<label for="sel_addr3" class="css-label">배송지목록</label>
					<?php } ?>
				</td>
			</tr> -->
			<tr>
				<td><p class="required">이름</p><input type="text" name="b_name" required class="frm_input required"></td>
			</tr>
			<tr>
				<td><p class="required">핸드폰</p><input type="text" name="b_cellphone" onKeyup="PhoneNumberSplit(this);" required class="frm_input required"></td>
			</tr>
			<!-- 20200221 이츠골프 요청으로 전화번호 주석처리
			<tr>
				<td><p class="required">전화번호</p><input type="text" name="b_telephone" class="frm_input"></td>
			</tr>
			-->
			<tr>
				<td>
					<p class="required">주소</p>
                    <input type="text" readonly name="b_zip" required class="frm_input required addr_zip" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey addr_btn">주소검색</button><br>
                    <input type="text" name="b_addr1" required class="frm_input frm_address required line_none"><br>   
                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly><br>
					<input type="hidden" name="b_addr_jibeon" value="">				
				</td>
			</tr>
			<tr>
				<td>
					<select name="sel_memo" class="wfull">
						<option value="">메세지를 선택해주세요</option>
						<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
						<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
						<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
						<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
					</select>
					<div class="padt5">
						<input type="text" name="memo" id="memo" class="frm_textbox">
						<!-- <textarea name="memo" id="memo" class="frm_textbox"></textarea> -->
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	$escrow_title = "";
	if($default['de_escrow_use']) {
		$escrow_title = "에스크로 ";
	}

	$multi_settle = '';
	if($is_kakaopay_use)
		//$multi_settle .= "<option value='KAKAOPAY'>카카오페이</option>\n";
		$multi_settle .= "<div id='sel_pay11'><input type='radio' name='paymethod' value='KAKAOPAY' id='sel_pay1' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay1' class='css-label '>카카오페이</label></div>";
	if($default['de_bank_use'])
		//$multi_settle .= "<option value='무통장'>무통장입금</option>\n";
		$multi_settle .= "<div id='sel_pay22'><input type='radio' name='paymethod' value='무통장' id='sel_pay2' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay2' class='css-label '>무통장입금</label></div>";
	if($default['de_card_use'])
		//$multi_settle .= "<option value='신용카드'>신용카드</option>\n";
		$multi_settle .= "<div id='sel_pay33'><input type='radio' name='paymethod' value='신용카드' id='sel_pay3' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay3' class='css-label '>신용카드</label></div>";
	if($default['de_hp_use'])
		//$multi_settle .= "<option value='휴대폰'>휴대폰</option>\n";
		$multi_settle .= "<div id='sel_pay44'><input type='radio' name='paymethod' value='휴대폰' id='sel_pay4' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay4' class='css-label '>휴대폰</label></div>";
	if($default['de_iche_use'])
		//$multi_settle .= "<option value='계좌이체'>".$escrow_title."계좌이체</option>\n";	
		$multi_settle .= "<div id='sel_pay55'><input type='radio' name='paymethod' value='계좌이체' id='sel_pay5' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay5' class='css-label '>계좌이체</label></div>";
		//$multi_settle .= "<div id='sel_pay55'><input type='radio' name='paymethod' value='계좌이체' id='sel_pay5' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay5' class='css-label '>".$escrow_title."<br>계좌이체</label></div>" 에스크로 삭제;
	if($default['de_vbank_use'])
		//$multi_settle .= "<option value='가상계좌'>".$escrow_title."가상계좌</option>\n";
		$multi_settle .= "<div id='sel_pay66'><input type='radio' name='paymethod' value='가상계좌' id='sel_pay6' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay6' class='css-label '>무통장입금</label></div>";
	if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point']) && ($pt_id !='golf'))
		//$multi_settle .= "<option value='포인트'>포인트결제</option>\n";
		$multi_settle .= "<div id='sel_pay77'><input type='radio' name='paymethod' value='포인트' id='sel_pay7' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay7' class='css-label '>포인트결제</label></div>";
    //현대리바트_20190807
	if($is_member && $pt_id == "golf")
	    //$multi_settle .= "<option value='복지카드'>현대(복지)카드</option>\n";
		$multi_settle .= "<div id='sel_pay88'><input type='radio' name='paymethod' value='복지카드' id='sel_pay8' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay8' class='css-label '>현대(복지)카드</label></div>";
 
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
		//$multi_settle .= "<option value='간편결제'>{$pg_easy_pay_name}</option>\n";
		$multi_settle .= "<div><input type='radio' name='paymethod' value='간편결제' id='sel_pay9' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay9' class='css-label '>{$pg_easy_pay_name}</label></div>\n";
	}

	// 이니시스를 사용중일때만 삼성페이 결제가능
	if($default['de_samsung_pay_use'] && ($default['de_pg_service'] == 'inicis')) {
		//$multi_settle .= "<option value='삼성페이'>삼성페이</option>\n";
		//$multi_settle .= "<div><input type='radio' name='paymethod' value='삼성페이' id='sel_pay10' class='css-checkbox lrg' onchange='calculate_paymethod(this.value);'><label for='sel_pay10' class='css-label '>삼성페이</label></div>\n";
	}
	?>

	<section id="sod_frm_pay">
		<h2 class="anc_tit">결제정보 입력</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<td>	
					<p>결제방법</p>
					<div class="paymethod">
						<?php echo $multi_settle; ?>
					<!-- <select name="paymethod" onchange="calculate_paymethod(this.value);" class="wfull">
						<option value="">선택하기</option> -->
						<!-- <?php echo $multi_settle; ?> -->
					<!-- </select> -->
					</div>
				</td>
			</tr>
			
			<tr>
				<td><p>합계</p><strong><?php echo display_price($tot_price1); ?></strong></td>
			</tr>
			<tr>
				<td>
					<p>추가배송비</p>
					<strong><span id="send_cost2">0</span>원</strong>
					<span class="fc_999">(지역에 따라 추가되는 배송비)</span>
				</td>
			</tr>
			<?php
			if($is_member && $config['coupon_yes']) { // 보유쿠폰
				$sp_count = get_cp_precompose($member['id']);
			?>
			<tr>
				<td>
					<p>할인쿠폰</p>
					<span id="dc_coupon"><a href="javascript:window.open('<?php echo TB_MSHOP_URL; ?>/ordercoupon.php');" class="btn_small bx-red">사용 가능 쿠폰 <?php echo $sp_count[3]; ?>장</a>&nbsp;</span>(-)&nbsp;&nbsp;<strong><span id="dc_amt">0</strong>원&nbsp;<span id="dc_cancel" style="display:none;"><a href="javascript:coupon_cancel();" class="btn_small grey">삭제</a></span></span>
				</td>
			</tr>
			<?php } ?>
			<?php 
			if($is_member && $config['usepoint_yes'] && $pt_id != 'golf') { ?>
			<tr>
				<td>
					<p>포인트결제</p>
					<input type="text" name="use_point" value="0" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="frm_input w100"> 원
					<div>잔액 : <b><?php echo display_point($member['point']); ?></b> (<?php echo display_point($config['usepoint']); ?> 부터 사용가능)</div>
				</td>
			</tr>
			<?php } ?>

			<!-- **기본금 내역(-)을 출력할수도 있다. -->
			
			<tr>
				<td>
					<p>총 결제금액</p>
					<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="color:red;font-weight:bold;"> 원
					
				</td>
			</tr>
			<!--
			<tr>
				<td>
					※ 전자상거래 구매 안전 서비스 안내 <br>
					전자금융거래법에 따라 금융감독(원) 위원회에 결제대금 예치업을 등록하였으며, 안전거래를 위해 구매 금액, 결제수단에 상관없이 모든 거래에 대하여 저희 쇼핑몰에서 가입한 구매안전서비스(에스크로)를 자동으로 적용하고 있습니다.
				</td>
			</tr>
			-->
			</tbody>
			</table>
		</div>
	</section>

	 <section id="bank_section" style="display:none;">
		<h2 class="anc_tit">입금하실 계좌</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">무통장계좌</th>
				<td>
					<?php echo mobile_bank_account("bank"); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">입금자명</th>
				<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input w100"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section> 

	<!-- 주문동의 start -->
    <section id="guest_privacy">
		<div>
		  주문할 상품의 상품명,상품가격,배송정보를 확인하였으며, 구매에 동의하시겠습니까
		</div>
		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1" class="css-checkbox lrg">
			<label for="agree" class="css-label">동의합니다.(전자상거래법 제8조 2항)</label>
		</div>
	</section>
	<!-- 주문동의 end -->

<div class="buy-btn_cont">
    <div id="sod_bsk_act" class="btn_confirm">
		<div>
			<dl>
				<?php if($tot_price > 0) { ?>
				<dt class="sod_bsk_cnt_tot"><span>전체주문금액</span></dt>
				<dd class="sod_bsk_cnt_tot"><strong><?php echo number_format($tot_price); ?> 원</strong></dd>
				<?php } ?>
			</dl>
		</div>
		
		<input type="submit" value="결제하기" class="btn_medium wset <?php if($pt_id == 'golfya') echo "btn_medium_golfya";?>">
    </div>
</div>

<!-- 	<div class="btn_confirm">		
		<input type="submit" value="주문하기" class="btn_medium wset">
		<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_medium bx-white">주문취소</a>
	</div>
	 -->
</div>

</form>

<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();
        
		if(addr == "1") {
			//alert("1");
			gumae2baesong(true);
		} else if(addr == "2") {
			//alert("2");
			gumae2baesong(false);
		} else {
			win_open('./orderaddress.php','win_address');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("input[name=memo]").val($(this).val());
    });
});

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

//금액계산
function calculate_order_price() {
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
	//현대리바트_20190807
    var mb_point2 = 0;
	<?php if($pt_id == "golf") {  ?>

	mb_point2   = parseInt($("input[name=use_point2]").val().replace(/[^0-9]/g, "")); //기본금사용액

	<?php } ?>
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point + mb_point2);
    
	//20190815_paymethod지정_의미없는 로직으로 주석처리
    <?php if($pt_id == "golf") {   ?>
	/*
	if(tot_price == 0) //기본금전체 사용시 내부적으로 paymethod 지정
	{
	     //radio값 설정  
		 $("input[name=paymethod]").val("기본금");//****TEST필요
	}
	*/
	<?php } ?>

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=tot_price_]").val(String(tot_price));
}
//주문서 체크 스크립트
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
   
    
	
	var min_point	 = parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point   = parseInt(no_comma(f.use_point.value));

	<?php if($pt_id == "golf") { ?>

	var temp_point2	= parseInt(no_comma(f.use_point2.value));//리바트
	var mb_point2	= parseInt(f.mb_point2.value);
 
    <?php } ?>

	
	var sell_price   = parseInt(f.org_price.value);
	var send_cost2   = parseInt(f.baesong_price2.value);
	var mb_coupon    = parseInt(f.coupon_total.value);
	var mb_point     = parseInt(f.mb_point.value);
	
	//포인트가 반영X 주문금액
	var tot_price    = sell_price + send_cost2 - mb_coupon;
   
	
	//기본금 사용 스크립트 start
	
	
	//현금영수증 :select
	<?php if(!$config['company_type']) { ?>
	if(temp_point2 >0 && getSelectVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
		//alert('핸드폰번호를 입력하세요');
		//f.tax_hp.focus();
		//return false;
	}
	<?php } ?>


	
	//기본금 사용 스크립트 end

	//org_price == use_point2 로 하거나 아래의 로직
	//결제수단이 select -> radio 변경되면서 체크로직도 수정해야함_20190816
    if(f.tot_price.value != 0)
	{
		if(getRadioVal(f["paymethod"]) == ''){
	   	   alert("결제방법을 선택하세요.");
	       //f.paymethod.focus();
	       return false;
	    }
	}
	
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

    /*
	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("개인정보 수집 및 이용 내용을 읽고 이에 동의하셔야 합니다.");
			return false;
		}
	}
	*/

    
	if(!confirm("주문내역이 정확하며, 주문 하시겠습니까?\n[복지카드(복지포인트)/기본금 결제시 부분취소가 불가합니다.]"))
		return false;

	//f.use_point.value = no_comma(f.use_point.value);
	//현대리바트
	f.use_point2.value = no_comma(f.use_point2.value);
	f.tot_price.value = no_comma(f.tot_price.value);

    return true;
}

// 핸드폰번호  자릿수판단 - 자동삽입
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
		//20200109 포인트 사용치 반영
		//f.tot_price.value = number_format(String(tot_price - temp_point));
		f.tot_price.value = number_format(String(tot_price - temp_point));
		f.tot_price_.value = tot_price - temp_point;//tot_price_ 반영_20200109
	}
}

//중요함수_현대리바트 포인트 계산_20190816
function calculate_temp_point2(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point2.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('포인트 사용액은 숫자이어야 합니다.');
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
		var de_point = parseInt($("input[name=mb_point2]").val()); // 기본금 total
	    point_etc = de_point-temp_point;
        $("#hwelpoint").html(point_etc);
		//우측 최종결제금액확인 적용_20190816
		//var t_price = number_format(String(tot_price - temp_point));
		//$("#tot_price3").html(t_price + "원");


	 
	}
}

// 결제방법 선택시 호출
function calculate_paymethod(type) {

    
	
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var mb_point2   = parseInt($("input[name=mb_point2]").val()); // 기본금
	var tot_price  = sell_price + send_cost2 - mb_coupon;

  
	//배경색 지정_20190820
    switch(type){
		 
	
	     case '카카오페이':
		     
               $("#sel_pay11").css("background-color", "#FFFFF0");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '무통장':
		       
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFF0");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '신용카드':
		       
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFF0");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '휴대폰':
		      
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFF0");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '계좌이체':
		      
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFF0");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '가상계좌':
		       
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFF0");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
	     case '포인트':
		      
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFF0");
			   $("#sel_pay88").css("background-color", "#FFFFFF");
		       break;
		 case '복지카드':
		       
               $("#sel_pay11").css("background-color", "#FFFFFF");
			   $("#sel_pay22").css("background-color", "#FFFFFF");
			   $("#sel_pay33").css("background-color", "#FFFFFF");
			   $("#sel_pay44").css("background-color", "#FFFFFF");
			   $("#sel_pay55").css("background-color", "#FFFFFF");
			   $("#sel_pay66").css("background-color", "#FFFFFF");
			   $("#sel_pay77").css("background-color", "#FFFFFF");
			   $("#sel_pay88").css("background-color", "#FFFFF0");
		       break;
		
	}
   
	// 포인트잔액이 부족한가?
	if( type == '포인트' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("select[name=paymethod]").val('무통장');
		$("#bank_section").show();
		$(".bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false); 
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#taxsave_section").show();
		$(".taxsave_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case '무통장':
			$("#bank_section").show();
			$(".bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").show();
			$(".taxsave_section").show();
			<?php } ?>
			break;
		case '포인트':
			$("#bank_section").hide();
			$(".bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$(".taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$(".bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$(".taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
	}
}

// 현금영수증
function tax_save(val) {
	switch(val) {
		case 'Y': // 개인 소득공제용
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // 지출증빙용
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: // 발행안함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}
//현금영수증_리바트
function tax_save2(val) {
	switch(val) {
		case 'Y': // 개인 소득공제용
			$("#taxsave_fld_11").show();
			$("#taxsave_fld_22").hide();
			//$("#taxbill_section").hide();
			//$("select[name=taxbill_yes]").val('N');
			$("select[name=taxsave_yes]").val('Y');
			break;
		case 'S': // 지출증빙용
			$("#taxsave_fld_11").hide();
			$("#taxsave_fld_22").show();
			//$("#taxbill_section").hide();
			//$("select[name=taxbill_yes]").val('N');
			$("select[name=taxsave_yes]").val('S');
			break;
		default: // 발행안함
		    alert("현금영수증 발행안함");
			$("#taxsave_fld_11").hide();
			$("#taxsave_fld_22").hide();
			break;
	}
}

// 세금계산서
function tax_bill(val) {
	switch(val) {
		case 'Y':  // 발행함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_section").show();
			break;
		case 'N': //미발행
			$("#taxbill_section").hide();
			break;
	}
}

// 할인쿠폰 삭제
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
		//f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		//f.b_telephone.value		= '';
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
