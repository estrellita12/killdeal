<?php

header("Content-Type:text/html;charset=euc-kr");
/*******************************************************************************
 ** 공통 변수, 상수, 코드
 *******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!defined('TB_SET_TIME_LIMIT')) define('TB_SET_TIME_LIMIT', 0);
@set_time_limit(TB_SET_TIME_LIMIT);


//===========================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//-----------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
    'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
    'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if(isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if(isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//===========================================================================================================


//include_once($tb_path['path'].'/config.php');   // 설정 파일
include_once('/home1/killdeal/public_html/config.php');

//unset($tb_path);

// multi-dimensional array에 사용자지정 함수적용


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



//==============================================================================
// 공통
//------------------------------------------------------------------------------
//$dbconfig_file = TB_DATA_PATH.'/'.TB_DBCONFIG_FILE;
$dbconfig_file = "/home1/killdeal/public_html/data/dbconfig.php";
if(file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once("/home1/killdeal/public_html/lib/partner.lib.php"); // 가맹점 라이브러리
    include_once("/home1/killdeal/public_html/lib/global.lib.php"); // PC+모바일 공통 라이브러리
    include_once("/home1/killdeal/public_html/lib/common.lib.php"); // PC전용 라이브러리
    include_once("/home1/killdeal/public_html/lib/mobile.lib.php"); // 모바일전용 라이브러리
    include_once("/home1/killdeal/public_html/lib/mirae.lib.php"); // 추가 라이브러리
    //include_once(/home/killdeal/public_html/lib/thumbnail.lib.php"); // 썸네일 라이브러리
    //include_once(TB_LIB_PATH."/editor.lib.php"); // 에디터 라이브러리
    //include_once(TB_LIB_PATH."/login-oauth.php"); // SNS 로그인

    $connect_db = sql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(TB_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $tb 배열에 저장 - 명랑폐인님 제안
    $tb['connect_db'] = $connect_db;

    sql_set_charset('utf8', $connect_db);
    if(defined('TB_MYSQL_SET_MODE') && TB_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if(defined(TB_TIMEZONE)) sql_query(" set time_zone = '".TB_TIMEZONE."'");
} else {
    header('Content-Type: text/html; charset=utf-8');

    die($dbconfig_file.' 파일을 찾을 수 없습니다.');
}


//**1. 배송중상태 7일경과시 배송완료 상태 변경

//$tmp_before_date = date("Y-m-d H:i:s", TB_SERVER_TIME - ($day_seven * 86400));
$sql = " select * from shop_order where date_add(delivery_date, INTERVAL 5 DAY)  < now() and delivery IS NOT NULL and dan = 4 " ;
$res = sql_query($sql, FALSE);


for($i=0; $row=sql_fetch_array($res); $i++)
{
    // (2021-04-05)
    order_change_update($row['od_id'], $row['od_no'], 'system', '배송중', '배송완료' , '배송중 상태에서 5일 경과됨'  );

    change_order_status_5($row['od_no']);

    if($row['pt_id']== 'golf'){ //현대리바트


              /*

               if($row['use_point2'] > 0){
                    $proc_dm = TB_TIME_YHS;//14자리 년월일시분초
                    $order_no = base64_encode($row['od_id']);
                    //$order_no2 = jsonfy2($order_no);
                    $order_no2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_no);
                    $item_cd = $row['gs_id'];
                    $hdata = array(
                         'ORDER_NO' => $order_no2,
                         'MEDIA_CD' => 'MW',
                         'PROC_STS' => '105',
                         'PROC_DM' => $proc_dm,
                         'ITEM_CD' => $item_cd
                    );
                    $url = "https://gift.e-hyundai.com/hb2efront_new/pointMagamProc.do?".http_build_query($hdata);
                    //echo("url:".$url."<br>");

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
                    curl_setopt($ch, CURLOPT_HEADER, 0);

                    $res = curl_exec($ch);
                    $load_string = simplexml_load_string($res);
                    $ca_result = $load_string->return_code;

                    //주문상태변경 로그 쌓기
                    $value['c_type'] = "302";//주문상태변경_배송완료
                    $value['url'] = $url;
                    $value['return_code'] = $ca_result; //응답코드
                    $value['call_date'] = TB_TIME_YMDHIS;
                    insert("hwelfare_log", $value);//DB에 insert하기

                    curl_close($ch);

               } //포인트사용액 >0 close
 */

    }else if($row['pt_id']== 'golfu'){ //골프유닷넷_적립금부여하기

        $memId = substr($row['mb_id'],6);
        $point = $row['sum_point'];
        $ptype = "1";//적립
        $txt = "구매 적립";
        $od_id = $row['od_id'];
        golfu_point($memId,$point,$ptype,$txt,$od_id);
    }


} //for close

//**2. 배송완료 ->구매확정 돌리기(3일경과시)


?>
