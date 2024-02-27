<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "u") {
    $sql = " select * from shop_mail where ma_id = '{$ma_id}' ";
    $ma = sql_fetch($sql);
    if(!$ma['ma_id'])
        alert('등록된 자료가 없습니다.');
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./member.php?code=mail_list'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u')
	$frm_submit .= '<a href="./member.php?code=mail_form" class="btn_large bx-red">추가</a>'.PHP_EOL;
$frm_submit .= '</div>';
?>

<form name="fmailform" id="fmailform" action="./member/member_mail_form_update.php" onsubmit="return fmailform_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="ma_id" value="<?php echo $ma_id; ?>">

<h2>메일 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">치환 코드</th>
		<td>
			<ul class="sit_displace">
				<li class="mlst">{이름}</li>
				<li class="mlst">{레벨명}</li>
				<li class="mlst">{아이디}</li>
				<li class="mlst">{이메일}</li>
			</ul>
			<?php echo help('위 제공하는 치환코드를 메일내용에 입력하시면 자동으로 변환되어 메일에 적용 됩니다.','fc_084'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="ma_subject">메일 제목</label></th>
		<td>
			<input type="text" name="ma_subject" value="<?php echo $ma['ma_subject']; ?>" id="ma_subject" required class="required frm_input" size="60" maxlength="255">
			<?php echo help('예) 최대 10,000포인트 득템찬스!! 이벤트에 참여하세요!'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="ma_content">메일 내용</label></th>
		<td>
			<?php echo editor_html('ma_content', get_text($ma['ma_content'], 0)); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function fmailform_check(f)
{
    errmsg = "";
    errfld = "";

    check_field(f.ma_subject, "제목을 입력하세요.");

    if(errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    <?php echo get_editor_js("ma_content"); ?>
    <?php echo chk_editor_js("ma_content"); ?>

    return true;
}
</script>
