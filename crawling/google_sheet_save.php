<?php
if( !empty($_POST['goods_list']) ){
    $fp = fopen("./data/goods_list.json", "w");
    fwrite($fp, $_POST["goods_list"]);
    fclose($fp);
}
exit();
/*
$goods_list = json_decode($_POST["goods_list"], true);
$i = 1;
foreach ($goods_list as $gs) {
    $i = $i + 1;
    $navercode = $gs[2];
    echo $navercode;
    $arr = [];
    exec("python3 ./python/naver_review.py {$navercode}", $arr);
    print_r($arr);
    echo "<br>-----------<br>";
    if ($i > 3) {
        break;
    }
}
*/
?>
