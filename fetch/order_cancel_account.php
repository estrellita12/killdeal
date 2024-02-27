<?php 
    /*###############################################################
        2022-06-28 계좌이체/가상계좌 환불시 DB변경 환불처리
    */###############################################################

    //setting
    $data = json_decode(file_get_contents('php://input'));
    include_once("./fetch_common.php");

    //initialization
    $od_no = $data->od_no;
    $use_price = $data->use_price;
    
    //execution
    $sql = "UPDATE shop_order SET refund_price = {$use_price} WHERE od_no = {$od_no}";
    $result = sql_query($sql);

    echo json_encode($result);
?>