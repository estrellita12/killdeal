<?php
define('_PURENESS_', true);
include_once('./_common.php');
include_once(TB_LIB_PATH.'/register.lib.php');

$mb_email = trim($_POST['reg_mb_email']);
$mb_id    = trim($_POST['reg_mb_id']);

set_session('ss_check_mb_email', '');

if($msg = empty_mb_email($mb_email)) die($msg);
if($msg = valid_mb_email($mb_email)) die($msg);
if($msg = prohibit_mb_email($mb_email)) die($msg);
//if($msg = exist_mb_email($mb_email, $mb_id)) die($msg); // (2021-02-18) 이메일 중복 확인 제거

set_session('ss_check_mb_email', $mb_email);
?>
