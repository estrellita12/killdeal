<?php
define('NO_CONTAINER', true);
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");
include_once(TB_ADMIN_PATH."/admin_topmenu.php");

$sodr1 = admin_order_status_sum("where dan = 1 "); // 총 입금대기
$sodr2 = admin_order_status_sum("where dan = 2 "); // 총 입금완료
$sodr3 = admin_order_status_sum("where dan = 3 "); // 총 배송준비
$sodr4 = admin_order_status_sum("where dan = 4 "); // 총 배송중

$sodr10 = admin_order_status_sum("where dan = 10 "); // 총 반품 시청
$sodr11 = admin_order_status_sum("where dan = 11 "); // 총 반품 중
$sodr12 = admin_order_status_sum("where dan = 12 "); // 총 교환 신청
$sodr13 = admin_order_status_sum("where dan = 13 "); // 총 교환 중
?>

<div id="main_wrap">
	<section>
		<h2>전체 주문통계<a href="<?php echo TB_ADMIN_URL; ?>/order.php?code=list" class="btn_small">주문내역 바로가기</a></h2>
		<div class="order_vbx">
			<dl class="od_bx3">
				<dt>주문상태 현황</dt>
				<dd>
					<p class="ddtit">입금대기</p>
					<p><?php echo number_format($sodr1['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">결제완료</p>
					<p><?php echo number_format($sodr2['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송준비</p>
					<p><?php echo number_format($sodr3['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송중</p>
					<p><?php echo number_format($sodr4['cnt']); ?></p>
				</dd>
			</dl>
			<dl class="od_bx3">
				<dt>클래임 현황</dt>
				<dd>
					<p class="ddtit">반품 신청</p>
					<p><?php echo number_format($sodr10['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">반품 중</p>
					<p><?php echo number_format($sodr11['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">교환 신청</p>
					<p><?php echo number_format($sodr12['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">교환 중</p>
					<p><?php echo number_format($sodr13['cnt']); ?></p>
				</dd>
			</dl>
		</div>
	</section>

	<section class="sidx_head01">
		<h2>최근 주문내역<a href="<?php echo TB_ADMIN_URL; ?>/order.php?code=list" class="btn_small">주문내역 바로가기</a></h2>
		<table>
		<thead>
		<tr>
			<th scope="col">주문번호</th>
			<th scope="col">주문자명</th>
			<th scope="col">수령자명</th>
			<th scope="col">전화번호</th>
			<th scope="col">결제방법</th>
			<th scope="col">총주문액</th>
			<th scope="col">주문일시</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = " select od_id,name,b_name,cellphone,paymethod, SUM(goods_price + baesong_price) as buyprice, od_time from shop_order where dan > 0 group by od_id order by index_no desc limit 5 ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++){
			//$amount = get_order_spay($row['od_id']);
		?>
		<tr class="tr_alignc">
			<td><?php echo $row['od_id']; ?></td>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['b_name']; ?></td>
			<td><?php echo $row['cellphone']; ?></td>
			<td><?php echo $row['paymethod']; ?></td>
			<td><?php echo number_format($row['buyprice']); ?></td>
			<td><?php echo substr($row['od_time'],0,16); ?> (<?php echo get_yoil($row['od_time']); ?>)</td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</section>

	<section class="sidx_head01">
		<h2>최근 회원가입<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=list" class="btn_small">회원관리 바로가기</a></h2>
		<table>
		<thead>
		<tr>
			<th scope="col">이름</th>
			<th scope="col">아이디</th>
			<th scope="col">레벨</th>
			<th scope="col">이메일</th>
			<th scope="col">접속횟수</th>
			<th scope="col">추천인</th>
			<th scope="col">가입일시</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = "select * from shop_member where id <> 'admin' order by index_no desc limit 5";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++){
		?>
		<tr class="tr_alignc">
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo get_grade($row['grade']); ?></td>
			<td><?php echo $row['email']; ?></td>
			<td><?php echo $row['login_sum']; ?></td>
			<td><?php echo $row['pt_id']; ?></td>
			<td><?php echo substr($row['reg_time'],0,16); ?> (<?php echo get_yoil($row['reg_time']); ?>)</td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</section>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>
