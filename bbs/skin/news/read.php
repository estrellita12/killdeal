<?php
if(!defined('_TUBEWEB_')) exit;
$bo_memo        = $write['memo'];
?>
<style>
    .news_box{
        max-width : 600px;
        width : 100%;
        margin:0 auto;
    }
    .news_box img{
        width : 100%;
    }
    .news_box .subject{
        font-size : 16px !important;
    }
    .news_box .ref{
        text-align : right;
        color : gray;
    }
</style>


<div class="tbl_frm01 tbl_wrap">
	<table>
	<tbody>
	<tr>
		<td class="list1 fs14"><b><?php echo $bo_subject; ?></b></td>
	</tr>
	<tr>
        <td class="list1 tal"><b><?php echo $bo_writer_s; ?> 기자</b>, <b> 작성일</b> : <?php echo $bo_wdate; ?>, <b> 조회수</b> : <?php echo $bo_hit; ?></td>
	</tr>
	<tr>
		<td>
            <div class="news_box">
                <div class="subject"> <?php echo get_view_thumbnail(conv_content($bo_memo, 1), $thumbnail_width);?> </div>
                <br><br>
                <div class="ref">출처 : <?php echo $bo_file2; ?> </div>
            </div>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="page_wrap">
	<div class="lbt_box">
		<a href="./list.php?<?php echo $qstr1; ?>" class="btn_lsmall bx-white">목록</a>
	</div>
	<div class="rbt_box">
		<?php if(($member['index_no'] == $bo_writer) || is_admin()) { ?>
		<a href="./modify.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">수정</a>
		<?php } ?>
		<?php if($member['index_no'] && $member['grade']<=$board['reply_priv'] && $board['usereply']=='Y') { ?>
		<a href="./reply.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">답글</a>
		<?php } ?>
		<?php if(($member['index_no'] == $bo_writer) || is_admin()) { ?>
		<a href="./del.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">삭제</a>
		<?php } ?>
		<?php if($member['grade'] <= $board['write_priv']){ ?>
		<a href="./write.php?boardid=<?php echo $boardid; ?>" class="btn_lsmall">글쓰기</a>
		<?php } ?>
	</div>
</div>

<!--코멘트 출력부분-->
<?php if($board['usetail']=='Y') { ?>
<form name="fboardform" id="fboardform" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fboardform_submit(this);">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<?php
$sql = "select * from shop_board_{$boardid}_tail where board_index='$index_no' order by wdate asc";
$res = sql_query($sql);
if(sql_num_rows($res)) {
?>
<div class="tbl_frm02 tbl_wrap marb10">
	<table>
	<tbody>
	<?php
	while($row=sql_fetch_array($res)) {
		$bo_wdate = date("Y-m-d H:i:s",$row['wdate']);
	?>
	<tr class="list1">
		<td>작성자 : <b><?php echo $row['writer_s']; ?></b> (<?php echo $bo_wdate; ?>) <?php echo "<a href=\"./tail_del.php?tailindex={$row['index_no']}&{$qstr2}\" class=\"btn_ssmall bx-white\">삭제</a>"; ?></td>
	</tr>
	<tr>
		<td><?php echo conv_content($row['memo'], 0); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<?php } ?>

<?php if($is_member) { ?>
<table class="wfull bd">
<tr height="80" class="list1">
	<td width="9%" class="tac bold"><?php echo $member['name']; ?></td>
	<td width="81%"><textarea name="memo" class="frm_textbox h60"></textarea></td>
	<td class="tar padr10 padl10">
		<?php if($member['grade'] > $board['tail_priv']) { ?>
		<input type="button" onclick="alert('댓글을 작성할 권한이 없습니다.');" value="댓글입력" class="btn_medium grey h60">
		<?php } else {  ?>
		<input type="hidden" name="writer_s" value="<?php echo $member['name']; ?>">
		<input type="submit" value="댓글입력" class="btn_medium grey h60">
		<?php } ?>
	</td>
</tr>
</table>
<?php
} else {
	if($board['tail_priv'] == '99') {
?>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<tr>
		<td colspan="2">
			작성자 : <input type="text" name="writer_s" class="frm_input marr15" size="20">
			비밀번호 : <input type="password" name="passwd" class="frm_input" size="20">
		</td>
	</tr>
	<tr class="list1">
		<td width="90%"style="padding:10px 0 10px 10px"><textarea name="memo" class="frm_textbox h60"></textarea></td>
		<td class="tar padr10 padl10"><input type="submit" value="댓글입력" class="btn_medium grey h60"></td>
	</tr>
	</table>
</div>
<?php } else { ?>
<table class="wfull bd">
<tr height="80" class="list1">
	<td width="9%" class="tac bold"><?php echo $bo_writer_s; ?></td>
	<td width="81%"><textarea name="memo" class="frm_textbox h60"></textarea></td>
	<td class="tar padr10 padl10"><input type="button" onclick="alert('로그인후 댓글을 작성 가능합니다.');" value="댓글입력" class="btn_medium grey h60"></td>
</tr>
</table>
<?php }
}
?>
</form>
<?php } ?>

<script>
function fboardform_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.writer_s.value) {
		alert('작성자명을 입력하세요.');
		f.writer_s.focus();
		return false;
	}
	if(!f.passwd.value) {
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	if(!f.memo.value) {
		alert('댓글을 작성하지 않았습니다!');
		f.memo.focus();
		return false;
	}

	return true;
}

function imgview(img) {
	 window.open("imgviewer.php?img="+img,"img",'width=150,height=150,status=no,top=0,left=0,scrollbars=yes');
}
</script>

<?php
include_once(TB_BBS_PATH."/skin/{$board['list_skin']}/read_list.php");
?>
