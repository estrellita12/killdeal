<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/mailer.lib.php');

check_demo();

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

if(!$is_member)
    alert_close('회원만 이용하실 수 있습니다.');

$email_enc = new str_encrypt();
$to = $email_enc->decrypt($to);

if(substr_count($to, "@") > 1)
    alert_close('한번에 한사람에게만 메일을 발송할 수 있습니다.');

$file = array();
for ($i=1; $i<=$attach; $i++) {
    if($_FILES['file'.$i]['name'])
        $file[] = attach_file($_FILES['file'.$i]['name'], $_FILES['file'.$i]['tmp_name']);
}

$content = stripslashes($content);
if($type == 2) {
    $type = 1;
    $content = str_replace("\n", "<br>", $content);
}

// html 이면
if($type) {
    $current_url = TB_URL;
    $mail_content = '<!doctype html><html lang="ko"><head><meta charset="utf-8"><title>메일보내기</title><link rel="stylesheet" href="'.$current_url.'/style.css"></head><body>'.$content.'</body></html>';
}
else
    $mail_content = $content;

mailer($fnick, $fmail, $to, $subject, $mail_content, $type, $file);

// 임시 첨부파일 삭제
if(!empty($file)) {
    foreach($file as $f) {
        @unlink($f['path']);
    }
}

$html_title = '메일 발송중';
include_once(TB_PATH.'/head.sub.php');

alert_close('메일을 정상적으로 발송하였습니다.');

include_once(TB_PATH.'/tail.sub.php');
?>