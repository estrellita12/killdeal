<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "u") {
    $qa = sql_fetch("select * from shop_qa where index_no='$index_no'");
    if(!$qa['index_no'])
        alert("자료가 존재하지 않습니다.");
}

$qa['replyer'] = $qa['replyer'] ? $qa['replyer'] : $member['name'];
?>

<form name="fqaform" method="post" action="./help/help_qa_form_update.php"  enctype="MULTIPART/FORM-DATA" >
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

<div class="tbl_frm02">
    <table>
    <colgroup>
        <col class="w140">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">작성자 </th>
        <td><?php echo $qa['name'] ?><span style="color:#888;margin-left:3px;">(<?php echo $qa['mb_id']; ?>)</span></td>
    </tr>
    <tr>
        <th scope="row">날짜 </th>
        <td><?php echo $qa['wdate']; ?></td>
    </tr>
    <tr>
        <th scope="row">문의유형</th>
        <td style="font-weight:700"><?php echo $qa['catename'] ?></td>
    </tr>
    <!--
    <tr>
        <th scope="row">제목 </th>
        <td><?php echo $qa['subject']; ?></td>
    </tr>
    -->
    <tr>
        <th scope="row">주문번호 </th>
        <td><?php echo $qa['od_id']; ?></td>
    </tr>
    <!-- 20200525 요청에 의한 변경 -->
    <tr>
        <th scope="row">질문 </th>
        <td>
<?php
$qimg = TB_DATA_PATH.'/qna/'.$qa['qna_file'];
if(is_file($qimg) && $qa['qna_file']) {
    $size = @getimagesize($qimg);
    $width = 500;
    $qimg = rpc($qimg, TB_PATH, TB_URL);
    echo '<img src="'.$qimg.'" width="'.$width.'"><br>';
}
echo nl2br($qa['memo']);
?>

        </td>
    </tr>
    <tr>
        <th scope="row">답변자</th>
        <td>
            <input type="text" name="replyer" value="<?php echo $qa['replyer']; ?>" required itemname="답변자" class="frm_input required">
        </td>
    </tr>
    <tr>
        <th scope="row" rowspan="2">답변 </th>
        <td>
<?php
$rimg = TB_DATA_PATH.'/reply/'.$qa['reply_file'];
if(is_file($rimg) && $qa['reply_file']) {
    $size = @getimagesize($rimg);
    $width = 50;
    $rimg = rpc($rimg, TB_PATH, TB_URL);
    echo '<img src="'.$rimg.'" width="'.$width.'"><br><br>';
}
?>

            <input type="file" name="reply_file" id="qna_file">
            <input type="checkbox" name="reply_file_del" id="bn_file_del"> <label for="reply_file_del">삭제</label>
        </td>
    </tr>
    <tr>
        <td><textarea name="reply" class="frm_textbox"><?php echo $qa['reply']; ?></textarea></td>
    </tr>
    <?php if($qa['result_yes']) {?>
    <tr>
        <th scope="row">답변일</th>
        <td><?php echo $qa['result_date']; ?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<div class="btn_confirm">
    <input type="submit" value="저장" class="btn_large" accesskey="s">
    <a href="./help.php?code=qa<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>
