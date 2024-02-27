<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_popular ";
$sql_search = " where pt_id = 'admin' and TRIM(pp_word) <> '' ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 기간검색
if($fr_date && $to_date)
	$sql_search .= " and pp_date between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and pp_date = '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and pp_date = '$to_date' ";

$sql_group = " group by pp_word ";
$sql_order = " order by cnt desc ";

// 테이블의 전체 레코드수만 얻음
$sql_common .= $sql_search;
$sql = " select pp_word $sql_common $sql_group ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select pp_word, count(*) as cnt $sql_common $sql_group $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>기본검색</h2>
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
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('pp_word', $sfl, '검색어'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
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

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w60">
		<col>
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">순위</th>
		<th scope="col">검색어</th>
		<th scope="col">검색회수</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$word = get_text($row['pp_word']);
		$rank = ($i + 1 + ($rows * ($page - 1)));

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo number_format($rank); ?></td>
		<td class="tal"><?php echo $word; ?></td>
		<td><?php echo number_format($row['cnt']); ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
