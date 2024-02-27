<?php
define('_PURENESS_', true);
include_once("./_common.php");

$hash = trim($_POST['hash']);

if(empty($hash))
    die('hash 정보가 넘어오지 않았습니다.');

$sql = " update shop_order
			set user_ok = '1'
			  , user_date = '".TB_TIME_YMDHIS."'
		  where md5(concat(gs_id,od_no,od_id)) = '{$hash}'
			and user_ok = '0'
			and dan = '5' ";
$result = sql_query($sql, FALSE);

if(!$result)
	die('일치하는 주문정보가 없습니다.');
else
	die('');
?>