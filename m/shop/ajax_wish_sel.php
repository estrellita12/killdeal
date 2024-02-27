<?php
include_once("./_common.php");

    $gs_id = $_POST['gs_id'][0];

    $sql = " select wi_id from shop_wish where mb_id = '{$member['id']}' and gs_id = '$gs_id' ";
    $row = sql_fetch($sql);
    if($row['wi_id']) { // 이미 있다면 ok        
        echo "ok";        
    }else{
        echo "no";
    }

?>