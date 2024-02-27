<?php
if(!defined('_TUBEWEB_')) exit;

// 전체회원수
$sql = " select COUNT(*) as cnt from shop_member ";
$row = sql_fetch($sql);
$tot_cnt = $row['cnt'];

// 차단된회원수
$sql = " select COUNT(*) as cnt from shop_member where intercept_date <> '' ";
$row = sql_fetch($sql);
$finish_cnt = $row['cnt'];

if(!isset($mb_id1)) $mb_id1 = 1;
if(!isset($mb_level_from)) $mb_level_from = 9;
if(!isset($mb_level_to)) $mb_level_to = 1;
?>

<form name="fpointselectform" id="fpointselectform" action="<?php echo TB_ADMIN_URL; ?>/member.php?code=point_select_list" method="post" autocomplete="off">
<input type="hidden" name="token" value="">

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
		<td>전체회원 : <?php echo number_format($tot_cnt); ?>명 , (차단된회원수 : <?php echo number_format($finish_cnt); ?>명 , <strong>정상회원 : <?php echo number_format($tot_cnt - $finish_cnt); ?>명</strong> 중 포인트적용 대상 선택)</td>
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

<h2>포인트 지급/차감</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="po_content">포인트내용</label></th>
		<td>
			<input type="text" name="po_content" id="po_content" required class="required frm_input" size="60">
			<?php echo help('예) 보너스 포인트 지급합니다. 감사합니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="po_point">포인트</label></th>
		<td>
			<input type="text" name="po_point" id="po_point" required class="required frm_input" size="10">
			<em>포인트 차감시 예) -3000</em>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="다음" class="btn_large" accesskey="s">
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ포인트를 지급할 경우 양수만 입력하시기 바랍니다. 예) 3000</p>
			<p>ㆍ포인트를 차감할 경우 음수도 포함해 입력하시기 바랍니다. 예) -3000</p>
			<p class="fc_red">ㆍ포인트 차감금액이 현재 잔액보다 클경우 차감되지 않습니다.</p>
			<p class="fc_red">ㆍ차단된 회원과 공급사 회원을 제외한 정상 회원에게만 포인트가 적용됩니다.</p>
		</div>
	</div>
</div>

</form>

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
