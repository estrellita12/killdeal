<?php
if(!defined('_TUBEWEB_')) exit;

check_demo();

check_admin_token();

include_once(TB_LIB_PATH."/mailer.lib.php");

$countgap  = 10; // 몇건씩 보낼지 설정
$maxscreen = 500; // 몇건씩 화면에 보여줄건지?
$sleepsec  = 200; // 천분의 몇초간 쉴지 설정
?>

<div class="local_desc02 local_desc">
	<p>
		메일 발송중 ...<br>
		처리완료까지 다소 시간이 걸릴 수 있습니다.<br>
		<strong class="fc_red">[끝]</strong> 이라는 단어가 나오기 전에는 중간에 중지하지 마세요.
	</p>
</div>

<div class="local_desc01 local_desc">
	<span id="cont"></span>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_ADMIN_URL; ?>/seller.php?code=mail_select_form" class="btn_large">확인</a>
</div>

<?php
flush();
ob_flush();

$ma_subject = trim($_POST['ma_subject']);
$ma_content = trim($_POST['ma_content']);
$select_seller_list = trim($_POST['ma_list']);

//print_r2($_POST); EXIT;
$seller_list = explode("\n", conv_unescape_nl($select_seller_list));

$cnt = 0;
for($i=0; $i<count($seller_list); $i++)
{
    list($to_email, $mb_id, $name, $owner, $saupja_no, $tel, $fax, $dam) = explode("||", trim($seller_list[$i]));

    $sw = preg_match("/[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*@[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*/", $to_email);
    // 올바른 메일 주소만
    if($sw == true)
    {
        $cnt++;

        $content = $ma_content;
		$content = preg_replace("/{아이디}/", $mb_id, $content);
        $content = preg_replace("/{회사명}/", $name, $content);
        $content = preg_replace("/{대표자명}/", $owner, $content);
        $content = preg_replace("/{사업자등록번호}/", $saupja_no, $content);
        $content = preg_replace("/{전화번호}/", $tel, $content);
		$content = preg_replace("/{팩스번호}/", $fax, $content);
		$content = preg_replace("/{담당자명}/", $dam, $content);

        mailer($config['company_name'], $member['email'], $to_email, $ma_subject, $content, 1);

        echo "<script> document.all.cont.innerHTML += '$cnt. $to_email ($mb_id : $name)<br>'; </script>\n";
        //echo "+";
        flush();
        ob_flush();
        ob_end_flush();
        usleep($sleepsec);
        if($cnt % $countgap == 0)
        {
            echo "<script> document.all.cont.innerHTML += '<br>'; document.body.scrollTop += 1000; </script>\n";
        }

        // 화면을 지운다... 부하를 줄임
        if($cnt % $maxscreen == 0)
            echo "<script> document.all.cont.innerHTML = ''; document.body.scrollTop += 1000; </script>\n";
    }
}
?>
<script> document.all.cont.innerHTML += "<br>총 <?php echo number_format($cnt); ?>건 발송<br><br><font color=crimson><b>[끝]</b></font>"; document.body.scrollTop += 1000; </script>