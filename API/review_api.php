<?php
if($_SERVER['REMOTE_ADDR']!="58.231.24.148"){
    exit;
}
$list = $_POST["review2"];
$list = json_decode($list,true);

include_once('../common.php');

for ($i = 0; $i < count($list); $i++) {
    $row = $list[$i];
    $value = array();
    $value['mb_id']=$row['mb_name'];
    $value['mb_name']=$row['mb_name'];
    $value['pt_id']="smartstore";
    $value['seller_id']="AP-100002";
    $value['gs_id']=$row['killdeal_gs_id'];
    $value['score']=$row['score'];
    $value['opt_name']=trim($row['opt_name']);
    $value['memo']=trim($row['memo']);
    $value['reg_time']=$row['reg_time'];
    $value['re_file']=$row['re_file'];
    $value['reg_dt']=$row['reg_dt'];
    $value['refer_site']="smartstore";
    if($value['score']<=2){
        $value['visible_yn'] = "n";
    }
    $sql = " select count(index_no) as cnt from shop_goods_review_2 where gs_id='{$value['gs_id']}' and mb_id='{$value['mb_id']}' and opt_name='{$value['opt_name']}' and memo='{$value['memo']}' and reg_time='{$value['reg_time']}'  ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    if($total_count <= 0){
        $res = insert("shop_goods_review_2",$value);
    }
    //echo "\n-------------\n";
}

?>
