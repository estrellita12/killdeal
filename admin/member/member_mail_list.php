<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_mail ";
$sql_search = " where (1) ";
$sql_order  = " order by ma_id desc ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 테이블의 전체 레코드수만 얻음
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

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./member.php?code=mail_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 메일내용추가</a>
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
		<th scope="row"><label for="sfl">검색어</label></th>
		<td>
			<select name="sfl" id="sfl">
				<option value="ma_subject">제목</option>
			</select>
			<label for="stx" class="sound_only">검색어</label>
			<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input" size="30">
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

<form name="fmaillist" id="fmaillist" method="post" action="./member/member_mail_list_delete.php" onsubmit="return fmaillist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	총 메일수 : <b class="fc_red"><?php echo number_format($total_count); ?></b>건
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
		<col class="w50">
		<col class="w60">
		<col>
		<col class="w130">
		<col class="w80">
		<col class="w80">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">
			<label for="chkall" class="sound_only">전체</label>
			<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form);">
		</th>
		<th scope="col">번호</th>
		<th scope="col">제목</th>
		<th scope="col">작성일시</th>
		<th scope="col">테스트</th>
		<th scope="col">보내기</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$ma_id = $row['ma_id'];
		$ma_subject = get_text($row['ma_subject']);

		$s_frm = "<a href=\"".TB_ADMIN_URL."/member.php?code=mail_select_form&ma_id=$ma_id$qstr&page=$page\" class=\"tu\">보내기</a>";
		$s_upd = "<a href=\"".TB_ADMIN_URL."/member.php?code=mail_form&w=u&ma_id=$ma_id$qstr&page=$page\" class=\"btn_small bx-white\">수정</a>";

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="ma_id[<?php echo $i; ?>]" value="<?php echo $ma_id; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $ma_subject; ?> 메일</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td class="tal"><?php echo $ma_subject; ?></td>
		<td><?php echo $row['ma_time']; ?></td>
		<td><a href="<?php echo TB_ADMIN_URL; ?>/member/member_mail_test.php?ma_id=<?php echo $ma_id; ?>" class="tu">테스트</a></td>
		<td><?php echo $s_frm; ?></td>
		<td class="btn_group">
			<?php echo $s_upd; ?>
			<a href="<?php echo TB_ADMIN_URL; ?>/member/member_mail_preview.php?ma_id=<?php echo $ma_id; ?>" target="_blank" class="btn_small bx-white">보기</a>
		</td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
<form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ<b>테스트</b>는 등록된 최고관리자의 이메일로 테스트 메일을 발송합니다.</p>
			<p class="fc_red">ㆍ주의) 수신자가 동의하지 않은 대량 메일 발송에는 적합하지 않습니다. 수십건 단위로 발송해 주십시오.</p>
		</div>
	</div>
</div>

<script>
function fmaillist_submit(f)
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
</script>
