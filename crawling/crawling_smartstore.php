<?php
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Asia/Seoul');

function removeEmojis($text)
{
    $clean_text = "";
    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);
    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);
    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);
    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);
    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    // Match Flags
    $regexDingbats = '/[\x{1F1E6}-\x{1F1FF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    // Others
    $regexDingbats = '/[\x{1F910}-\x{1F95E}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F980}-\x{1F991}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F9C0}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F9F9}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    return $clean_text;
}

$arr = [];
exec("python3 /home/mwdevelop/killdeal/crawling/get_sheet.py", $arr);

$hostname = "localhost";
$username = "craw";
$password = "test1234";
$tb_nm = "smart_store_review";
($conn = mysqli_connect($hostname, $username, $password, "review")) or
    die(
        "html>script language='JavaScript'>alert('Unable to connect to database! Please try again later.');/script>/html>"
    );

$fp = fopen("/home/mwdevelop/killdeal/crawling/data/goods_list.json", "r");
$goods_list = fgets($fp);
fclose($fp);

$goods_list = json_decode($goods_list, false);
$start = 0;
$end = count($goods_list);
for ($i = $start; $i < $end; $i++) {
    $arr = [];
    $gs = $goods_list[$i];
    $gs_id = $gs[0];
    $killdeal_gs_id = $gs[1];
    $navercode = $gs[2];

    if($navercode == "2303368399"){
        continue;
    }
    if (strlen($navercode) <= 4) {
        continue;
    }
    $query = "select count(index_no) as cnt,max(reg_time) as reg_time from {$tb_nm} where sbnet_gs_id='{$gs_id}' and killdeal_gs_id='{$killdeal_gs_id}' ";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);
    $cnt = $row['cnt'];
    $chk_date = $row['reg_time']?substr($row['reg_time'],0,10):"";

    echo "---------------------------------\n";
    echo date("Y-m-d H:i:s")."\n";
    echo "python3 /home/mwdevelop/killdeal/crawling/naver_review.py {$navercode} {$cnt} {$chk_date} \n";
    exec("python3 /home/mwdevelop/killdeal/crawling/naver_review.py {$navercode} {$cnt} {$chk_date}", $arr);
    print_r($arr);

    if ($arr[0] == 1) {
        $review_list = json_decode($arr[1], true);
        foreach ($review_list as $rv) {
            $rv_mb_name = trim($rv["writer"]);
            $rv_opt_name = trim($rv["opt_name"]);
            $rv_memo = trim($rv["contents"]);
            $rv_memo = removeEmojis($rv_memo);
            $query = "select count(index_no) as cnt from {$tb_nm} where killdeal_gs_id='{$killdeal_gs_id}' and reg_time='{$rv["reg_date"]}' and mb_name='{$rv_mb_name}' and memo='{$rv_memo}' and opt_name='{$rv_opt_name}'";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($res);
            if ($row["cnt"] <= 0) {
                $query =
                    "insert into {$tb_nm}(sbnet_gs_id, killdeal_gs_id, smart_store_gs_id, mb_name,score,opt_name,memo,reg_time,re_file,reg_dt) values('{$gs_id}','{$killdeal_gs_id}','{$navercode}','{$rv_mb_name}','{$rv["star"]}','{$rv_opt_name}','{$rv_memo}','{$rv["reg_date"]}','{$rv["image_url"]}','" .
                    date("Y-m-d H:i:s", time()) .
                    "' )";
                $res = mysqli_query($conn, $query);
            }
        }
    }
}

?>
