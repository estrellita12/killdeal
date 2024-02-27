<?php
if(!defined('_TUBEWEB_')) exit;

$sql = " select * from shop_mail where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);
if(!$ma['ma_id'])
    alert('보내실 내용을 선택하여 주십시오.');

// 전체회원수
$sql = " select COUNT(*) as cnt from shop_member ";
$row = sql_fetch($sql);
$tot_cnt = $row['cnt'];

// 차단된회원수
$sql = " select COUNT(*) as cnt from shop_member where intercept_date <> '' ";
$row = sql_fetch($sql);
$finish_cnt = $row['cnt'];

$last_option = explode('||', $ma['ma_last_option']);
for($i=0; $i<count($last_option); $i++) {
    $option = explode('=', $last_option[$i]);
    // 동적변수
    $var = $option[0];
    $$var = $option[1];
}

if(!isset($mb_id1)) $mb_id1 = 1;
if(!isset($mb_level_from)) $mb_level_from = 9;
if(!isset($mb_level_to)) $mb_level_to = 1;
if(!isset($mb_mailling)) $mb_mailling = 1;
?>

<form name="fsendmailselectform" action="<?php echo TB_ADMIN_URL; ?>/member.php?code=mail_select_list" method="post" autocomplete="off">
<input type="hidden" name="ma_id" value="<?php echo $ma_id; ?>">

<h2>회원정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원 통계</th>
		<td>전체회원 : <?php echo number_format($tot_cnt); ?>명 , (차단된회원수 : <?php echo number_format($finish_cnt); ?>명 , <strong>정상회원 : <?php echo number_format($tot_cnt - $finish_cnt); ?>명</strong> 중 메일 발송 대상 선택)</td>
	</tr>
	<tr>
		<th scope="row">회원 아이디</th>
		<td class="td_article">
			<article>
			<input type="radio" name="mb_id1" value="1" id="mb_id1_all" <?php echo $mb_id1?"checked":""; ?>> <label for="mb_id1_all">전체</label>
			<input type="radio" name="mb_id1" value="0" id="mb_id1_section" <?php echo !$mb_id1?"checked":""; ?>> <label for="mb_id1_section">구간</label>
			</article>
			<article>
			<input type="text" name="mb_id1_from" value="<?php echo $mb_id1_from; ?>" id="mb_id1_from" title="시작구간" class="frm_input" size="10"> 에서
			<input type="text" name="mb_id1_to" value="<?php echo $mb_id1_to; ?>" id="mb_id1_to" title="종료구간" class="frm_input" size="10"> 까지
			</article>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="mb_email">E-mail</label></th>
		<td>
			<input type="text" name="mb_email" value="<?php echo $mb_email; ?>" id="mb_email" class="frm_input" size="40">
			<?php echo help("메일 주소에 단어 포함 (예 : @".preg_replace('#^(www[^\.]*\.){1}#', '', $_SERVER['HTTP_HOST']).")") ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="mb_mailling">메일링</label></th>
		<td>
			<select name="mb_mailling" id="mb_mailling">
				<option value="Y">수신동의한 회원만
				<option value="">전체
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">레벨</th>
		<td>
			<label for="mb_level_from" class="sound_only">최소레벨</label>
			<?php echo get_member_select("mb_level_from", $mb_level_from); ?> 에서
			<label for="mb_level_to" class="sound_only">최대레벨</label>
			<?php echo get_member_select("mb_level_to", $mb_level_to); ?> 까지
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="다음" class="btn_large" accesskey="s">
	<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=mail_list<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ차단된 회원과 공급사 회원을 제외한 정상 회원에게만 메일이 발송됩니다.</p>
		</div>
	</div>
</div>

<script>
$(function() {
    $("input[name=mb_id1]").live("click",function() {
        switch($(this).val()) {
            case "1":
				$("#mb_id1_from").attr("disabled",true);
				$("#mb_id1_from").css("background-color","#eee");
				$("#mb_id1_to").attr("disabled",true);
				$("#mb_id1_to").css("background-color","#eee");
                break;
			case '0':
				$("#mb_id1_from").attr("disabled",false);
				$("#mb_id1_from").css("background-color","#fff");
				$("#mb_id1_to").attr("disabled",false);
				$("#mb_id1_to").css("background-color","#fff");
				break;
        }
    });

	$("#mb_id1_all").trigger( "click" );
});
</script>
