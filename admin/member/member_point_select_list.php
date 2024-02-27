<?php
if(!defined('_TUBEWEB_')) exit;

// 공급사, 차단된 회원은 제외
$sql_common = " from shop_member ";
$sql_where  = " where supply = '' and intercept_date = '' ";

// 회원ID ..에서 ..까지
if($mb_id1 != 1)
    $sql_where .= " and id between '{$mb_id1_from}' and '{$mb_id1_to}' ";

// 권한
$sql_where .= " and grade between '{$mb_level_to}' and '{$mb_level_from}' ";

$sql = " select COUNT(*) as cnt {$sql_common} {$sql_where} ";
$row = sql_fetch($sql);
$cnt = $row['cnt'];
if($cnt == 0)
    alert('선택하신 내용으로는 해당되는 회원자료가 없습니다.');
?>

<form name="fpointselectlist" id="fpointselectlist" method="post" action="<?php echo TB_ADMIN_URL; ?>/member/member_point_select_update.php">
<input type="hidden" name="po_point" value="<?php echo $po_point; ?>">
<input type="hidden" name="po_content" value="<?php echo $po_content; ?>">
<input type="hidden" name="token" value="">

<div class="local_desc02 local_desc">
	<p>
		선택된 회원수 : <strong><?php echo number_format($cnt); ?></strong>명
	</p>
</div>
<div class="tbl_head01" id="sit_select_tbl">
	<table>
	<colgroup>
		<col width="50px">
		<col width="150px">
		<col width="150px">
		<col>
		<col width="100px">
		<col width="100px">
		<col width="100px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">아이디</th>
		<th scope="col">회원명</th>
		<th scope="col">레벨</th>
		<th scope="col">포인트잔액</th>
		<th scope="col">적용포인트</th>
		<th scope="col">비고</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$point_mb_id = $cr = "";
	$sql = " select * $sql_common $sql_where order by id ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$isChk = '<span class="fc_00f">정상</span>';
		if(($po_point < 0) && ($po_point * (-1) > $row['point']))
			$isChk = '<span class="fc_red">잔액부족</span>';

		$point_mb_id .= $cr . $row['id'];
		$cr = ",";
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<?php echo ($i+1); ?>
			<input type="hidden" name="point_mb_id" value="<?php echo $point_mb_id; ?>">
		</td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td class="tal"><?php echo get_text($row['name']); ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td class="tar"><?php echo number_format($row['point']); ?></td>
		<td class="tar"><?php echo number_format($po_point); ?></td>
		<td><?php echo $isChk; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="mart10 fc_red">※ [주의] 포인트 차감금액이 현재 잔액보다 클경우 차감되지 않습니다.</div>

<div class="btn_confirm">
	<input type="submit" value="포인트적용" class="btn_large" accesskey="s">
	<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=point_select_form" class="btn_large bx-white">취소</a>
</div>
</form>
