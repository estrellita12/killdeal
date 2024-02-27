<?php
//set_include_path('/var/www/html/public_html');
define('_TUBEWEB_', true);

if(PHP_VERSION >= '5.1.0') {
    //if(function_exists("date_default_timezone_set")) date_default_timezone_set("Asia/Seoul");
    date_default_timezone_set("Asia/Seoul");
}


define('TB_MYSQLI_USE', true);
define('TB_DISPLAY_SQL_ERROR', TRUE);

define('TB_SERVER_TIME',    time());
define('TB_TIME_YEAR',      date("Y", TB_SERVER_TIME));
define('TB_TIME_MONTH',     date("m", TB_SERVER_TIME));
define('TB_TIME_DAY',       date("d", TB_SERVER_TIME));
define('TB_TIME_YM',        date("Y-m", TB_SERVER_TIME));
define('TB_TIME_YMDHIS',    date("Y-m-d H:i:s", TB_SERVER_TIME));
define('TB_TIME_YHS',       date("YmdHis", TB_SERVER_TIME));
define('TB_TIME_YMD',       substr(TB_TIME_YMDHIS, 0, 10));
define('TB_TIME_HIS',       substr(TB_TIME_YMDHIS, 11, 8));

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('TB_ESCAPE_PATTERN') && defined('TB_ESCAPE_REPLACE')) {
        $pattern = TB_ESCAPE_PATTERN;
        $replace = TB_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

include_once("data/dbconfig.php");
include_once("lib/global.lib.php"); // PC+모바일 공통 라이브러리
include_once("lib/mirae.lib.php");

$connect_db = sql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
$select_db  = sql_select_db(TB_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

// mysql connect resource $tb 배열에 저장 - 명랑폐인님 제안
$tb['connect_db'] = $connect_db;
sql_set_charset('utf8', $connect_db);
//sql_set_charset('utf8', $connect_db_tgs);

//------------------------------------------------------------------------------------------------
$config = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
//------------------------------------------------------------------------------------------------------------

/*
// 설정일이 지난 장바구니 상품 삭제
if($default['de_cart_keep_term'] > 0) {
    //$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_cart_keep_term'] * 86400));
    //$sql = " delete from shop_cart where left(ct_time,10) < '$tmp_before_date' and ct_select='0' and od_id='' ";
    $sql = " delete from shop_cart where date_add(ct_time, INTERVAL 7 DAY)  <= now() and ct_select='0' and od_id='' ";
    sql_query($sql, FALSE);
}
*/

/*
// 설정일이 지난 찜상품 삭제
if($default['de_wish_keep_term'] > 0) {
    //$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_wish_keep_term'] * 86400));
    //$sql = " delete from shop_wish where left(wi_time,10) < '$tmp_before_date' ";
    $sql = " delete from shop_wish where date_add(wi_time, INTERVAL 7 DAY)  <= now() ";
    sql_query($sql, FALSE);
}
*/

/*
// 배송시작한지 5일지난 상품 배송 완료로 설정
//$tmp_before_date = date("Y-m-d H:i:s", TB_SERVER_TIME - ($day_seven * 86400));
$sql = " select * from shop_order where date_add(delivery_date, INTERVAL 5 DAY)  < now() and delivery IS NOT NULL and dan = 4 " ;
$res = sql_query($sql,FALSE);
for($i=0; $row=sql_fetch_array($res); $i++)
{
    change_order_status_5($row['od_no']);

} //for close
*/

/*
// 이 작업을 언제 할지 고민 해봐야 함...
$sql = " select * from shop_order where date_add(delivery_date, INTERVAL 5 DAY)  >= now() and delivery IS NOT NULL and dan = 4 " ;
$res = sql_query($sql,FALSE);
for($i=0; $row=sql_fetch_array($res); $i++)
{
    $tmp = explode('|',$row['delivery']);
    if("CJ대한통운"==$tmp[0]){
        if( isset($row['od_no']) && isset($row['delivery_no'])  ){
            $snoopy=new snoopy;
            $s_res="";
            $snoopy->fetch("https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=$row[delivery_no]");
            $txt=$snoopy->results;
            //$txt = preg_replace('/\s+/', ' ', $tmp);
            //$rex="/\<td class=\"last_b\"\>(.*)\<\/td\>/";
            $rex = "/\<td class=\"last_b\"\>.*완료.*\<\/td\>/";
            preg_match($rex,$txt,$s_res);
            if( isset( $s_res[0] ) ){
                change_order_status_5($row['od_no']);
                //echo "배송완료";
            }else{
                //echo "배송중";
            }
            echo "-------------------\n";
        
        }
    }
}
*/

/*
// 설정일이 지난 배송완료상품 구매확정
if($default['de_final_keep_term'] > 0) {
    $sql = " update shop_order
             set user_ok = '1'
               , user_date = '".TB_TIME_YMDHIS."'
           where date_add(invoice_date, INTERVAL 3 DAY)  <= now()
             and user_ok = '0'
             and dan = '5' ";

    sql_query($sql, FALSE);

    //20201029 구매확정 후 판매수수료부여
    $sql = "SELECT * FROM shop_order WHERE dan = '5' and user_ok = '1' and user_date like ".TB_TIME_YMD."%";

    $res = sql_query($sql, FALSE);

    for($i=0; $row=sql_fetch_array($res); $i++)
    {
        $od = get_order($row['od_no']);
        $gs = unserialize($od['od_goods']);
        insert_sale_pay($od['pt_id'], $od, $gs);
    }


}
*/

/*
// 설정일이 지난 미입금된 주문내역 자동취소
if($default['de_misu_keep_term'] > 0) {
    $sql = " select *
            from shop_order
            where date_add(od_time, INTERVAL 3 DAY)  <= now()
            and dan = '1'
            order by index_no ";
    $res = sql_query($sql);
    while($row=sql_fetch_array($res)) {
        change_order_status_6($row['od_no']);

        // 메모남김
        $sql = " update shop_order
                    set shop_memo = CONCAT(shop_memo,\"\\n미입금 자동 주문취소 - ".TB_TIME_YMDHIS." (취소이유 : {$default['de_misu_keep_term']}일경과)\")
                  where od_no = '{$row['od_no']}' ";
        sql_query($sql);
    }
}
*/

/*
// 유튜브 크롤링 작업
$video_list = array(
    'killdeal' => 'UC1vCF_oF13DLh7l5TAQGHMQ',
    'baksajang' => 'UC3eNWzE-pftCXatCuUAaHGw',
    'honggolf' => 'UCUXDKiEsZH9Kcad-hksAK7g',
    'maniamall' => 'UCwkmakJghnpwrDuAUh9BxrA'
);

foreach($video_list as $pt_id=>$code){
    $v_row = youtube_video($code);

    //print_r($v_row);
    //$sql = " delete from shop_board_video where pt_id='$pt_id'";
    //sql_query($sql);

    foreach($v_row as $i){
        if(isset($i) && isset($pt_id)){
            $sql = " insert into shop_board_video(pt_id,v_code,get_time) value('$pt_id','$i',now())";
            sql_query($sql);
        }
    }

}
*/

// 유튜브 크롤링 작업
$video_list = array(
    'admin' => 'UC1vCF_oF13DLh7l5TAQGHMQ',
    'baksajang' => 'UC3eNWzE-pftCXatCuUAaHGw',
    'teeluv' => 'UCUXDKiEsZH9Kcad-hksAK7g',
    'maniamall' => 'UCwkmakJghnpwrDuAUh9BxrA',
    'dukhomall' => 'UC87L0f4S4ibqt5aux5iHwMg'
);

foreach($video_list as $pt_id=>$code){
    get_youtube_video(42, $pt_id,$code);
}

// 마니아타임즈에서 뉴스 데이터 가져오기
//get_mania_news(45);



?>
