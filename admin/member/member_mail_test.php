<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

check_demo();

$tb['title'] = "회원메일 테스트";

$name  = get_text($member['name']);
$grade = get_grade($member['grade']);
$mb_id = $member['id'];
$email = $member['email'];

$sql = "select ma_subject, ma_content from shop_mail where ma_id = '{$ma_id}' ";
$ma = sql_fetch($sql);

$subject = $ma['ma_subject'];
$content = $ma['ma_content'];
$content = preg_replace("/{이름}/", $name, $content);
$content = preg_replace("/{레벨명}/", $grade, $content);
$content = preg_replace("/{아이디}/", $mb_id, $content);
$content = preg_replace("/{이메일}/", $email, $content);

$mb_md5 = md5($member['id'].$member['email'].$member['reg_time']);

$content = $content . '<p>더 이상 정보 수신을 원치 않으시면 [<a href="'.TB_BBS_URL.'/email_stop.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5.'" target="_blank">수신거부</a>] 해 주십시오.</p>';

mailer($config['company_name'], $member['email'], $member['email'], $subject, $content, 1);

alert($member['name'].'('.$member['email'].')님께 테스트 메일을 발송하였습니다. 확인하여 주십시오.');
?>