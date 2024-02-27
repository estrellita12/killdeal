<?php
include_once('./_common.php');

$sql = " select id, email, reg_time from shop_member where id = '{$mb_id}' ";
$row = sql_fetch($sql);
if (!$row['id'])
    alert('존재하는 회원이 아닙니다.', TB_URL);

if ($mb_md5) {
    $tmp_md5 = md5($row['id'].$row['email'].$row['reg_time']);
    if ($mb_md5 == $tmp_md5) {
        sql_query(" update shop_member set mailser = 'N' where id = '{$mb_id}' ");

        alert('정보메일을 보내지 않도록 수신거부 하였습니다.', TB_URL);
    }
}

alert('제대로 된 값이 넘어오지 않았습니다.', TB_URL);
?>