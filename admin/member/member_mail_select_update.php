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
	<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=mail_list" class="btn_large">목록</a>
</div>

<?php
flush();
ob_flush();

$ma_id = trim($_POST['ma_id']);
$select_member_list = trim($_POST['ma_list']);

//print_r2($_POST); EXIT;
$member_list = explode("\n", conv_unescape_nl($select_member_list));

// 메일내용 가져오기
$sql = "select ma_subject, ma_content from shop_mail where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);

$subject = $ma['ma_subject'];

$cnt = 0;
for($i=0; $i<count($member_list); $i++)
{
    list($to_email, $mb_id, $name, $grade, $datetime) = explode("||", trim($member_list[$i]));

    $sw = preg_match("/[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*@[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*/", $to_email);
    // 올바른 메일 주소만
    if($sw == true)
    {
        $cnt++;

        $mb_md5 = md5($mb_id.$to_email.$datetime);

        $content = $ma['ma_content'];
        $content = preg_replace("/{이름}/", $name, $content);
        $content = preg_replace("/{레벨명}/", $grade, $content);
        $content = preg_replace("/{아이디}/", $mb_id, $content);
        $content = preg_replace("/{이메일}/", $to_email, $content);

        $content = $content . "<hr size=0><p><span style='font-size:9pt; font-familye:굴림'>▶ 더 이상 정보 수신을 원치 않으시면 [<a href='".TB_BBS_URL."/email_stop.php?mb_id={$mb_id}&amp;mb_md5={$mb_md5}' target='_blank'>수신거부</a>] 해 주십시오.</span></p>";

        mailer($config['company_name'], $member['email'], $to_email, $subject, $content, 1);

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