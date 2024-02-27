<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/partner/pt_get.inc.php');

$sql_common = " from shop_partner p, shop_member m ";
$sql_search = " where p.mb_id = m.id ";

include_once(TB_ADMIN_PATH.'/partner/pt_query.inc.php');

if(!$orderby) {
    $filed = "p.state";
    $sod = "asc, p.index_no desc";
} else {
    $sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select p.*, m.name,m.pt_id,m.anew_date $sql_common  $sql_search  $sql_order  limit $from_record, $rows ";
$result = sql_query($sql);

// 결제금액합계
$row2 = sql_fetch(" select SUM(p.receipt_price) as sum_price, SUM(m.pay) as sum_pay  {$sql_common} {$sql_search} ");
$stotal_price = (int)$row2['sum_price'];
$stotal_pay = (int)$row2['sum_pay'];

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');


$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택승인" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;

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
				<?php echo option_selected('p.mb_id', $sfl, '가맹점ID'); ?>
				<?php echo option_selected('m.name', $sfl, '가맹점명'); ?>
				<?php echo option_selected('m.pt_id', $sfl, '추천인'); ?>
				<?php echo option_selected('p.deposit_name', $sfl, '입금자명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="spt">						
				<?php echo option_selected('p.reg_time', $spt, "신청일"); ?>
				<?php echo option_selected('m.anew_date', $spt, "등업일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">신청등급</th>
		<td>				
			<?php echo get_search_level('sst', $sst, 2, 6); ?>
		</td>				
	</tr>
	<tr>
		<th scope="row">승인상태</th>
		<td>
			<?php echo radio_checked('ste', $ste,  '', '전체'); ?>
			<?php echo radio_checked('ste', $ste, '0', '대기'); ?>
			<?php echo radio_checked('ste', $ste, '1', '완료'); ?>
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

<form name="fanewlist" id="fanewlist" method="post" action="./partner/pt_anewlistupdate.php" onsubmit="return fanewlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 명 조회
	<strong class="ov_a">총 결제금액 <?php echo number_format($stotal_price); ?>원 </strong>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w130">
		<col class="w130">
		<col class="w100">
		<col class="w150">
		<col class="w100">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('state',$q2); ?>승인</a></th>
		<th scope="col"><?php echo subject_sort_link('name',$q2); ?>가맹점명</a></th>
		<th scope="col"><?php echo subject_sort_link('mb_id',$q2); ?>가맹점ID</a></th>			
		<th scope="col"><?php echo subject_sort_link('pt_id',$q2); ?>추천인</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('anew_grade',$q2); ?>신청등급</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('receipt_price',$q2); ?>결제금액</a></th>
		<th scope="col"><?php echo subject_sort_link('reg_time',$q2); ?>신청일시</a></th>
		<th scope="col"><?php echo subject_sort_link('anew_date',$q2); ?>등업일</a></th>
		<th scope="col">본사 입금계좌 및 전달사항</th>	
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$disabled = '';
		if($row['state']) $disabled = ' disabled';

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>"<?php echo $disabled; ?>>
		</td>
		<td><?php echo $row['state']?'완료':'<span class="fc_255">대기</span>'; ?></td>
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>		
		<td class="tal"><?php echo $row['pt_id']; ?></td>
		<td><?php echo get_grade($row['anew_grade']); ?></td>
		<td class="tar"><?php echo number_format($row['receipt_price']); ?></td>
		<td><?php echo $row['reg_time']; ?></td>
		<td><?php echo !is_null_time($row['anew_date'])?$row['anew_date']:''; ?></td>
		<td class="tal">
			<?php echo ($row['pay_settle_case']==1 && $row['bank_acc']) ? '<p class="fc_197">'.$row['bank_acc'].' (입금자명 : '.get_text($row['deposit_name']).')</p>':''; ?>
			<?php echo get_text($row['memo']); ?>
		</td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function fanewlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
