<?php
if(!defined('_TUBEWEB_')) exit;

$ma_last_option = "";

// 공급사, 차단된 회원은 제외
$sql_common = " from shop_member ";
$sql_where  = " where supply = '' and intercept_date = '' ";

// 회원ID ..에서 ..까지
if($mb_id1 != 1)
    $sql_where .= " and id between '{$mb_id1_from}' and '{$mb_id1_to}' ";

// E-mail에 특정 단어 포함
if($mb_email != "")
    $sql_where .= " and email like '%{$mb_email}%' ";

// 메일링
if($mb_mailling != "")
    $sql_where .= " and mailser = '{$mb_mailling}' ";

// 권한
$sql_where .= " and grade between '{$mb_level_to}' and '{$mb_level_from}' ";

$sql = " select COUNT(*) as cnt {$sql_common} {$sql_where} ";
$row = sql_fetch($sql);
$cnt = $row['cnt'];
if($cnt == 0)
    alert('선택하신 내용으로는 해당되는 회원자료가 없습니다.');

// 마지막 옵션을 저장합니다.
$ma_last_option .= "mb_id1={$mb_id1}";
$ma_last_option .= "||mb_id1_from={$mb_id1_from}";
$ma_last_option .= "||mb_id1_to={$mb_id1_to}";
$ma_last_option .= "||mb_email={$mb_email}";
$ma_last_option .= "||mb_mailling={$mb_mailling}";
$ma_last_option .= "||mb_level_from={$mb_level_from}";
$ma_last_option .= "||mb_level_to={$mb_level_to}";

sql_query(" update shop_mail set ma_last_option = '{$ma_last_option}' where ma_id = '{$ma_id}' ");
?>

<form name="fmailselectlist" id="fmailselectlist" method="post" action="<?php echo TB_ADMIN_URL; ?>/member.php?code=mail_select_update">
<input type="hidden" name="ma_id" value="<?php echo $ma_id; ?>">

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
		<col width="150px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">아이디</th>
		<th scope="col">회원명</th>
		<th scope="col">레벨</th>
		<th scope="col">E-mail</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$ma_list = $cr = "";
	$sql = " select * $sql_common $sql_where order by id ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$ma_list .= $cr . $row['email'] . "||" . $row['id'] . "||" . get_text($row['name']) . "||" . get_grade($row['grade']) . "||" . $row['reg_time'];
		$cr = "\n";
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<?php echo ($i+1); ?>
			<input type="hidden" name="ma_list" value="<?php echo $ma_list; ?>">
		</td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td class="tal"><?php echo get_text($row['name']); ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td class="tal"><?php echo $row['email']; ?></td>
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
