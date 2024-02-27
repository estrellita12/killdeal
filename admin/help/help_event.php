<?php
if(!defined('_TUBEWEB_')) exit;


$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_event ";
$sql_search = " where (1) ";
$sql_order  = " order by index_no desc";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

?>
<a href="./help/help_event_excel.php" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 이벤트 리스트 엑셀저장</a>

<style>

	.event_view_wrap th {
		font-weight: bold;
		font-size: 18px;
		height: 60px;
		vertical-align: middle;
	}

	.event_view_wrap td {
		height: 57px;
	}

</style>
<div class="event_view_wrap">
	<table>
		<colgroup>
			<col class="w150">
			<col class="w150">
			<col class="w150">
			<col class="w120">
			<col class="w120">
			<col class="w150">
			<col class="w150">
			<col class="w200">
		</colgroup>
		<thead>
			<tr>
				<th scope="col">응모번호</th>
				<th scope="col">이름</th>
				<th scope="col">휴대폰번호</th>
				<th scope="col">동의여부</th>
				<th scope="col">인증번호</th>
				<th scope="col">가맹점</th>
				<th scope="col">아이디</th>
				<th scope="col">참여날짜</th>
			</tr>
		</thead>
		<?php
	for($i=2; $row=sql_fetch_array($result); $i++) {
		$index_no = $row['index_no'];
    $row2 = sql_fetch("select * from shop_event");	
    
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $row['index_no'] ?></td>
			<td class="tac"><?php echo $row['name']; ?></td>
			<td class="tac"><?php echo $row['phone'] ?></td>
			<td class="tac"><?php echo $row['agree'] ?></td>
			<td class="tac"><?php echo $row['inzng'] ?></td>
			<td class="tac"><?php echo $row['pt_id'] ?></td>
			<td class="tac"><?php echo $row['mb_id'] ?></td>
			<td class="tac"><?php echo $row['date_time'] ?></td>
		</tr>
		<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
	?>
		</tbody>
	</table>
</div>
<?php echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=') ?>
