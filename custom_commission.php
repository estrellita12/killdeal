<?php
include_once('./common.php');


$sql = " SELECT *
FROM shop_order
WHERE LEFT( od_time, 10 ) <= '2020-05-06'
AND delivery IS NOT NULL
AND dan = '5'
AND user_ok = '1'
ORDER BY od_time desc
"
;
$res = sql_query($sql, FALSE);

for($i=0; $row=sql_fetch_array($res); $i++)
{
	
	echo $row['od_id'].' num : '.($i+1).'  '.$row['od_time'];
	/*
	echo $row['b_name'];
	echo $row['user_date'];
	echo "<br>";
	*/
	custom_change_order_status_5($row['od_no'], $row['user_date']);
}


function custom_change_order_status_5($od_no, $user_date, $delivery='', $delivery_no='')
{
	global $config;

	$od = get_order($od_no);
	//print_r($od);
	/*
	$sql = " update shop_order
				set dan = '5'
				  , invoice_date = '".TB_TIME_YMDHIS."' ";

	if(is_null_time($od['delivery_date'])) // 배송일이 비었나?
		$sql .= " , delivery_date = '".TB_TIME_YMDHIS."' ";

	if($delivery)
		$sql .= " , delivery = '$delivery' ";

	if($delivery_no)
		$sql .= " , delivery_no = '$delivery_no' ";

	$sql .= " where od_no = '$od_no' ";
	sql_query($sql);
	*/
	// 상품 판매수량 반영
	//add_sum_qty($od['gs_id']);

	// 상품정보
	$gs = unserialize($od['od_goods']);
	
	// 주문완료 후 배송완료시에 쿠폰발행
	if($config['coupon_yes'] && !$gs['use_aff'] && $od['mb_id']) {
		$member = get_member($od['mb_id']);
		$cp_used = is_used_coupon('2', $od['gs_id']);
		if($cp_used) {
			$cp_id = explode(",", $cp_used);
			for($g=0; $g<count($cp_id); $g++) {
				if($cp_id[$g]) {
					$cp = sql_fetch("select * from shop_coupon where cp_id='{$cp_id[$g]}'");
					insert_used_coupon($od['mb_id'], $od['name'], $cp);
				}
			}
		}
	}

	// 포인트 적립
	/*
	if($od['mb_id'] && $od['sum_point'] > 0) {
		insert_point($od['mb_id'], $od['sum_point'], "주문번호 {$od['od_id']} ({$od_no}) 배송완료", "@delivery", $od['mb_id'], "{$od['od_id']},{$od_no}");
	}
	*/
	// 가맹점 판매수수료 적립
	echo $od['pt_id'];
	custom_insert_sale_pay($od['pt_id'], $od, $gs);
}

// 판매수수료 지급
function custom_insert_sale_pay($pt_id, $od, $gs)
{
	global $config;

	// 판매수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_sale_use']) return;

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return;

	// 가맹점이 아니면 리턴
	if(!is_partner($pt_id)) return;

	// 가맹점 정보
	$mb = get_member($pt_id, 'grade');

	$amount = 0;
	echo ' grade : '.$mb['grade'];
	echo ' con : '.($config['pf_sale_benefit_'.$mb['grade']]);
	// 원가 계산
	if($config['pf_sale_flag']) {
		if($od['supply_price'] > 0) // 공급가
			$amount = $od['goods_price'] - $od['supply_price'];

		if($config['pf_sale_flag'] == 1)
			$amount = $amount - ($od['coupon_price'] + $od['use_point']); // 할인쿠폰 + 포인트결제
	} else {
		$amount = $od['use_price'] - $od['baesong_price']; // 순수결제액 - 배송비
	}

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return;

	if($gs['ppay_type']) { // 개별설정
		echo "개별";
		$sale_benefit_dan  = $gs['ppay_dan'];
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		echo "공통";
		$sale_benefit_dan  = $config['pf_sale_benefit_dan'];
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit	   = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);
	}

	// 판매수수료를 적용할 단계가 없다면 리턴
	if($sale_benefit_dan < 1) return;

	for($i=0; $i<$sale_benefit_dan; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == 'admin')
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (int)trim($sale_benefit[$i]);
		
		if($benefit <= 0) continue;

		$pt_pay = 0;
		
		if($sale_benefit_type)
			$pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
		else
			$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

		// 추천인 정보
		$mb = get_member($pt_id, 'pt_id, payment, payflag');

		// 개별 추가 판매수수료
		if($mb['payment']) {
			if($mb['payflag'])
				$pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
			else
				$pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
		}

		// 적용할 수수료가 없다면 건너뜀
		if($pt_pay <= 0) continue;

		custom_insert_pay($pt_id, $pt_pay, $od['invoice_date'], "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료", 'sale', $od['od_no'], $od['od_id']);

		// 상위 추천인을 담고 다시 배열로 돌린다
		$pt_id = $mb['pt_id'];
		echo "  done";
		echo "<br>";
	} // for
}

function custom_insert_pay($mb_id, $pay, $invoice_date, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='')
{
	// 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
	if($pay == 0 || !is_partner($mb_id)) { return 0; }

	// 이미 등록된 내역이라면 건너뜀
	if($rel_table || $rel_id || $rel_action)
	{
		$sql = " select count(*) as cnt
				   from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);
		if($row['cnt'])
			return -1;
	}

	$pt_pay = get_pay_sum($mb_id); // 회원수수료
	$pp_balance = $pt_pay + $pay; // 잔액

	$sql = " insert into shop_partner_pay
				set mb_id = '$mb_id'
				  , pp_datetime = '$invoice_date'
				  , pp_content = '".addslashes($content)."'
				  , pp_pay = '$pay'
				  , pp_use_pay = '0'
				  , pp_balance = '$pp_balance'
				  , pp_rel_table = '$rel_table'
				  , pp_rel_id = '$rel_id'
				  , pp_rel_action = '$rel_action'
				  , pp_referer = '$referer'
				  , pp_agent = '$agent' ";
	sql_query($sql);

	// 수수료를 사용한 경우 수수료 내역에 사용금액 기록
	if($pay < 0) {
		insert_use_pay($mb_id, $pay);
	}

	// 수수료 UPDATE
	$sql = " update shop_member set pay = '$pp_balance' where id = '$mb_id' ";
	sql_query($sql);

	// 누적수수료에 따른 자동 레벨업
	check_promotion($mb_id);

	return 1;
}
?>