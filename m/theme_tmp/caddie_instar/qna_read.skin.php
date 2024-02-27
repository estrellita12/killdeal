<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>
<div class="m_bo_bg">
    <div class="m_bo_wrap">
        <table class="tbl03">
        <colgroup>
            <col style="width:70px">
            <col style="width:auto">
        </colgroup>
        <tbody>
        <tr>
            <th>문의유형</th>
            <td><?php echo $qa['catename']; ?> </td>
        </tr>
        <tr>
            <th>이름</th>
		    <td><?php echo $qa['mb_id']; ?></td>
        </tr>
        <tr>
            <th>날짜</th>
		    <td><span class="wr_day"><?php echo substr($qa['wdate'],0,10); ?></span></td>
        <tr>
            <th>주문번호</th>
            <td><?php echo $qa['od_id']; ?> </td>
        </tr>
        <tr>
            <th>이미지</th>
            <td><?php
            $qimg = TB_DATA_PATH.'/qna/'.$qa['qna_file'];
            if(is_file($qimg) && $qa['qna_file']) {
                $size = @getimagesize($bimg);
                $width = 300;
                $qimg = rpc($qimg, TB_PATH, TB_URL);
                echo '<img src="'.$qimg.'" width="'.$width.'">';
            }else{
                echo '<img src="/img/qa_noimage.png" width="30px">';
            }
            ?></td>
        </tr>
        <tr>
            <th>내용</th>
            <td><?php echo nl2br($qa['memo']); ?> </td>
        </tr>
        </tbody>
        </table>
	<?php if($qa['result_yes']) { ?>
	        <div class="qna_reply">
		        <p class="date"><span class="ic_tit">답변</span> <?php echo substr($qa['result_date'],0,10); ?></p>
		        <p>

<?php
$rimg = TB_DATA_PATH.'/reply/'.$qa['reply_file'];
if(is_file($rimg) && $qa['reply_file']) {
    $size = @getimagesize($rimg);
    $width = 300;
    $rimg = rpc($rimg, TB_PATH, TB_URL);
    echo '<img src="'.$rimg.'" width="'.$width.'"><br><br>';
}
echo nl2br($qa['reply']); ?></p>
	        </div>
	<?php } ?>
    </div>
	<div class="btn_confirm">
		<a href="<?php echo TB_MBBS_URL; ?>/qna_write.php" class="btn_medium">상담문의</a>		
		<a href="<?php echo TB_MBBS_URL; ?>/qna_modify.php?index_no=<?php echo $index_no; ?>" class="btn_medium bx-white">수정</a>
		<a href="<?php echo TB_MBBS_URL; ?>/qna_list.php" class="btn_medium bx-white">목록</a>
		<a href="javascript:del('<?php echo TB_MBBS_URL; ?>/qna_read.php?index_no=<?php echo $index_no; ?>&mode=d');" class="btn_medium bx-white">삭제</a>
	</div>
</div>

<script>
function del(url) {
	answer = confirm('삭제 하시겠습니까?');
	if(answer==true) { 
		location.href = url; 
	}
}
</script>
