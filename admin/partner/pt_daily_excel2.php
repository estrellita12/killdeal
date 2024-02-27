<?php
include_once("./_common.php");

check_demo();

//if(!$code)
//	alert("코드번호가 넘어오지 않았습니다.");

$sql_seah = "where 1 = 1 and left(od_time,10) between '2020-01-01' and DATE_FORMAT(CURDATE(),'%Y-%m-%d') ";
if($fr_date && $to_date)
    $sql_seah = "where 1=1 and left(od_time,10) between '$fr_date' and '$to_date' ";
else if(!$fr_date && $to_date)
    $sql_seah = "where 1=1 and left(od_time,10) between '2020-01-01' and '$to_date' ";
else if($fr_date && !$to_date)
    $sql_seah = "where 1=1 and left(od_time,10) between '$fr_date' and DATE_FORMAT(CURDATE(),'%Y-%m-%d') ";


$sql = " select A1.od_time as date_msg, ifnull(sum(A2.goods_price + A2.baesong_price),0) as buy_price , '$pt_chk' as pt_id ,ifnull(sum(A2.sum_qty),0) as qty_cnt, ifnull(count(distinct A2.od_id),0) as od_cnt
    from (SELECT a.od_time FROM (
        SELECT CURDATE( ) - INTERVAL( a.a + ( 10 * b.a ) + ( 100 * c.a ) ) DAY AS od_time
        FROM (
            SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS a
            CROSS JOIN
            ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS b
            CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            UNION ALL SELECT  6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS c ) AS a
            $sql_seah
            ORDER BY a.od_time desc ) as A1
            left outer join (
                select * from shop_order where pt_id='".$pt_chk."' and dan IN ('2','3','4','5','8','12','13')
                                            ) as A2
                                            on A1.od_time = left(A2.od_time,10)
                                            group by A1.od_time ";

$result = sql_query($sql);

$cnt = @sql_num_rows($result);

if(!$cnt)
    alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/partner/pt_daily_excel.sub.php');
?>
