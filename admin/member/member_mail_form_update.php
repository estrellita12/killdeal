<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$sql_common = " ma_subject = '{$_POST['ma_subject']}',
				ma_content = '{$_POST['ma_content']}',
				ma_time		= '".TB_TIME_YMDHIS."',
				ma_ip = '{$_SERVER['REMOTE_ADDR']}' ";

if($w == "") {
	$sql = " insert into shop_mail
                set $sql_common ";
    sql_query($sql);

	$ma_id = sql_insert_id();
}
else if($w == "u")
{
    $sql = " update shop_mail
                set $sql_common
              where ma_id = '$ma_id' ";
    sql_query($sql);
}

if($w == "" || $w == "u")
	goto_url(TB_ADMIN_URL."/member.php?code=mail_form&w=u&ma_id=$ma_id$qstr&page=$page");
else
	goto_url(TB_ADMIN_URL."/member.php?code=mail_list");
?>