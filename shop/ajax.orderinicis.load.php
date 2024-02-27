<?
include_once('./_common.php');

if(empty($_POST))
    die('정보가 넘어오지 않았습니다.');

    $od_id = trim($_POST['od_id']);
    
    // (2020-12-03) 취소완료 후 다시 결제 방지를 위해 조건 추가
    //$od_check = sql_fetch("select * from shop_order where od_id = '$od_id' and dan=2 ");
    $od_check = sql_fetch("select * from shop_order where od_id = '$od_id' and (dan=2 or dan=6) ");
    if(isset($od_check['od_id']) ){
        echo $od_check['od_id'];
    }else{
        echo null;
    }
    
?>
