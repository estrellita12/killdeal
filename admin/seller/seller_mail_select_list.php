<?php
if(!defined('_TUBEWEB_')) exit;

// 승인된공급사, 담당자 이메일이있는 공급사만
$sql_common = " from shop_seller ";
$sql_where  = " where state = '1' and info_email <> '' ";

$sql = " select COUNT(*) as cnt {$sql_common} {$sql_where} ";
$row = sql_fetch($sql);
$cnt = $row['cnt'];
if($cnt == 0)
    alert('선택하신 내용으로는 해당되는 공급사자료가 없습니다.');
?>

<form name="fmailselectlist" id="fmailselectlist" method="post" action="<?php echo TB_ADMIN_URL; ?>/seller.php?code=mail_select_update">
<input type="hidden" name="ma_subject" value="<?php echo $ma_subject; ?>">
<input type="hidden" name="ma_content" value="<?php echo $ma_content; ?>">

<div class="local_desc02 local_desc">
	<p>
		선택된 공급사수 : <strong><?php echo number_format($cnt); ?></strong>명
	</p>
</div>
<div class="tbl_head01" id="sit_select_tbl">
	<table>
	<colgroup>
		<col width="50px">
		<col width="150px">
		<col width="150px">
		<col width="150px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">아이디</th>
		<th scope="col">공급사명</th>
		<th scope="col">담당자명</th>
		<th scope="col">E-mail</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$ma_list = $cr = "";
	$sql = " select * {$sql_common} {$sql_where} order by mb_id ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$info = array();
		$info[] = $row['info_email'];
		$info[] = $row['mb_id'];
		$info[] = get_text($row['company_name']);
		$info[] = get_text($row['company_owner']);
		$info[] = $row['company_saupja_no'];
		$info[] = $row['company_tel'];
		$info[] = $row['company_fax'];
		$info[] = get_text($row['info_name']);

		$ma_list .= $cr . implode("||", $info);
		$cr = "\n";
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<?php echo ($i+1); ?>
			<input type="hidden" name="ma_list" value="<?php echo $ma_list; ?>">
		</td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>
		<td class="tal"><?php echo get_text($row['company_name']); ?></td>
		<td class="tal"><?php echo get_text($row['info_name']); ?></td>
		<td class="tal"><?php echo $row['info_email']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="메일전송" class="btn_large" accesskey="s">
	<a href="javascript:history.go(-1);" class="btn_large bx-white">취소</a>
</div>
</form>
