<?php
include_once("./_common.php");

$change_type = $_GET['type'];
$pl_no = $_GET['pl_no'];
$pl_order = $_GET['pl_order'];

if($change_type==2 && $pl_order > 1){
    $sql = "update shop_goods_plan set pl_order = -1 where pl_order = $pl_order - 1";
    sql_query($sql);
    $sql = "update shop_goods_plan set pl_order =  $pl_order-1 where pl_order = $pl_order";
    sql_query($sql);
    $sql = "update shop_goods_plan set pl_order =  $pl_order where pl_order = -1";
    sql_query($sql);

}else if($change_type==1 ){
    $sql = "update shop_goods_plan set pl_order = -1 where pl_order = $pl_order + 1";
    sql_query($sql);
    $sql = "update shop_goods_plan set pl_order =  $pl_order+1 where pl_order = $pl_order";
    sql_query($sql);
    $sql = "update shop_goods_plan set pl_order =  $pl_order where pl_order = -1";
    sql_query($sql);
}
goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");

?>

