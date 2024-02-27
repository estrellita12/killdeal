<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);

$value['mb_id']      = 'admin';
$value['ts_name']    = $_POST['ts_name'];
$value['ts_it_code'] = $_POST['ts_it_code'];
$value['ts_use']     = $_POST['ts_use'];
$value['ts_sale_rate']   = $_POST['ts_sale_rate'];
$value['ts_sale_unit']   = $_POST['ts_sale_unit'];
$value['ts_sb_date']     = $_POST['ts_sb_date']." ".$_POST['ts_sb_time'];
$value['ts_ed_date']     = $_POST['ts_ed_date']." ".$_POST['ts_ed_time'];

if($w == '') {
    insert("shop_goods_timesale", $value);
    $ts_no = sql_insert_id();

    goto_url(TB_ADMIN_URL."/goods.php?code=timesale_form&w=u&ts_no=$ts_no");

} else if($w == 'u') {
    update("shop_goods_timesale", $value, "where ts_no='$ts_no'");

    goto_url(TB_ADMIN_URL."/goods.php?code=timesale_form&w=u&ts_no=$ts_no$qstr&page=$page");
}
?>

