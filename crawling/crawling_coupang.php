<?php
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Asia/Seoul');
//$hostname = "211.37.174.124";
$hostname = "localhost";
$username = "craw";
$password = "test1234";
$tb_nm = "smart_store_review";
($conn = mysqli_connect($hostname, $username, $password, "review")) or
    die(
        "html>script language='JavaScript'>alert('Unable to connect to database! Please try again later.');/script>/html>"
    );

$fp = fopen("./data/goods_list.json", "r");
$goods_list = fgets($fp);
fclose($fp);

$goods_list = json_decode($goods_list, false);
$start = 5001;
$end = count($goods_list);
//$start = count($goods_list);
//$end = 3000;
for ($i = $start; $i < $end; $i++) {
    echo "\n---------------------------------\n";
    $gs = $goods_list[$i];
    $navercode = $gs[2];
    echo $navercode;
    if (strlen($navercode) <= 4) {
        continue;
    }
    $arr = [];
    exec("python3 ./naver_review.py {$navercode}", $arr);
    print_r($arr);
    if ($arr[0] == 1) {
        $review_list = json_decode($arr[1], true);
        foreach ($review_list as $rv) {
            $query = "select count(index_no) as cnt from {$tb_nm} where sbnet_gs_id='{$gs[0]}' and reg_time='{$rv["reg_date"]}' and mb_name='{$rv["writer"]}'";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($res);
            if ($row["cnt"] <= 0) {
                $query =
                    "insert into {$tb_nm}(sbnet_gs_id, killdeal_gs_id, smart_store_gs_id, mb_name,score,memo,reg_time,re_file,reg_dt) values('{$gs[0]}','{$gs[1]}','{$gs[2]}','{$rv["writer"]}','{$rv["star"]}','{$rv["contents"]}','{$rv["reg_date"]}','{$rv["image_url"]}','" .
                    date("Y-m-d H:i:s", time()) .
                    "' )";
                echo $query;
                $res = mysqli_query($conn, $query);
                echo $res;
            }
        }
    }
}

?>
