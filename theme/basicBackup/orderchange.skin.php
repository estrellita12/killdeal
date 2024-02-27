<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="sit_use_write" class="new_win">
	<h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<form name="forderchange" method="post" action="<?php echo $form_action_url; ?>">
	<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
	<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
	<input type="hidden" name="q" value="<?php echo $q; ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w90">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">상품명</th>
			<td><?php echo get_text($gs['gname']); ?></td>
		</tr>
		<tr>
			<th scope="row">주문번호</th>
			<td><?php echo $od['od_id']; ?></td>
		</tr>
		<tr>
			<th scope="row">주문자명</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row"><?php echo $q; ?>사유</th>
			<td><textarea name="change_memo" required class="required frm_textbox wufll"></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

    <div class="win_btn">
        <input type="submit" value="신청" class="btn_medium">
		<a href="javascript:window.close();" class="btn_medium bx-white">취소</a>
    </div>

	</form>
</div>
