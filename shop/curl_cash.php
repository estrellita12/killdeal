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
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
/*
if(get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(TB_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(TB_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(TB_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(TB_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config  = array();
$default = array();
$super	 = array();
$member  = array();
$partner = array();
$seller	 = array();
$tb		 = array();

//==============================================================================
// 항상 "www" 를 타고 들어오는 도메인은 "www" 를 제거

if(preg_match("/www\./i", $_SERVER['HTTP_HOST'])) {
	header("Location:http://".preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
}
*/

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

// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
$super = get_member('admin');
$super_hp = $super['cellphone'];

// 보안서버주소 설정
if(TB_HTTPS_DOMAIN) {
	define('TB_HTTPS_BBS_URL', TB_HTTPS_DOMAIN.'/'.TB_BBS_DIR);
    define('TB_HTTPS_MBBS_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_BBS_DIR);
    define('TB_HTTPS_SHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_SHOP_DIR);
    define('TB_HTTPS_MSHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_SHOP_DIR);
} else {
    define('TB_HTTPS_BBS_URL', TB_BBS_URL);
    define('TB_HTTPS_MBBS_URL', TB_MBBS_URL);
    define('TB_HTTPS_SHOP_URL', TB_SHOP_URL);
    define('TB_HTTPS_MSHOP_URL', TB_MSHOP_URL);
}

 
  //***크론탭 스케쥴링 등록 필요(암호화부분을 magam.php와 동일하게 교체가 필요할수도 있음.)
  //$dateString = date("Y-m-d", time());
  $dateString = date("Y-m-d",strtotime ("-1 days"));//전일데이타 현금영수증 신청하기

  //1.전일날짜에 대해 현금영수증 일괄신청 (****00 : 30분 1번호출실행 계획)
  //***체크사항 마감체크(magam_uresult가 1건은 Y , 1건은 N일경우: 현금영수증 신청금액이 상이하다.(ex.20000원 ->15000원)
  //$sql = "select * from shop_order where od_time like '".$dateString."%' and (dan=2 OR dan=3 OR dan=4) and (taxsave_yes = 'Y' or taxsave_yes = 'S') and magam_uresult = 'Y' and use_point2 > 0 and pt_id = 'golf' order by index_no desc ";

  $sql = " SELECT * FROM shop_order WHERE od_time LIKE  '".$dateString."%' AND ( dan =2 OR dan =3 OR dan =4 ) AND (taxsave_yes ='Y' OR taxsave_yes ='S') AND magam_uresult ='Y' AND use_point2 >0 AND pt_id = 'golf' GROUP BY od_id";

  //$sql = " SELECT * FROM shop_order WHERE (taxsave_yes ='Y' OR taxsave_yes ='S') AND magam_uresult ='Y' AND use_point2 >0 AND pt_id = 'golf'  and od_time >='2020-09-22 00:00:00' and od_time <='2020-10-21 00:00:00' and (dan=5 or dan=8 ) GROUP BY od_id "; //10월21일까지

  $result = sql_query($sql);
  for($i=0; $row=sql_fetch_array($result); $i++)
  {
	    //$od_no = $row['od_id'];
	    $order_no = base64_encode($row['od_id']);
		$order_no2 = jsonfy2($order_no);
		$mem_no = substr($row['mb_id'],5);//hwelf100001865241
		$mem_no2 = base64_encode($mem_no);
		$mem_no3 = jsonfy2($mem_no2);
		$taxsave_yes = $row['taxsave_yes'];
		$tax_hp =  $row['tax_hp']; 
		$tax_saupja_no = $row['tax_saupja_no']; 
    
		$shopevent_no = base64_encode($row['shopevent_no']);
		$shopevent_no2 = jsonfy2($shopevent_no);

		if($taxsave_yes =='Y' || $taxsave_yes == 'S')//현금영수증 존재
		{
		     if($taxsave_yes == 'Y')//소득공제
		     {
		          $tax_hp2 = base64_encode($tax_hp);
                  $tax_hp3 = jsonfy2($tax_hp2);
		  		  $mem_idnt_val = $tax_hp3; //식별번호(핸드폰)
				  $installment = "00";//현금영수증
		     }
		     else if($taxsave_yes == 'S') //사업자지출
			 {
			  	  $tax_saupja_no2 = base64_encode($tax_saupja_no);
                  $tax_saupja_no3 = jsonfy2($tax_saupja_no2);
				  $mem_idnt_val =  $tax_saupja_no3; //식별번호(사업자번호)
				  $installment = "01";//지출증빙
		     }
			 $proc_sts = "101";//(101: 신청 , 102: 취소)
             //curl start 
             $hdata = array(
             'ORDER_NO' => $order_no2, //암호화
	         'PROC_STS' => $proc_sts, 
		     'MEM_IDNT_VAL' =>  $mem_idnt_val, // 암호
		     'SHOPEVENT_NO' => $shopevent_no2, 
	         'MEM_NO' => $mem_no3,
		     'INSTALLMENT' => $installment,
    	     'MEDIA_CD' => 'MW'
		     ); 

	         $url = "https://gift.e-hyundai.com/hb2efront_new/openAPICashReceipt.do?".http_build_query($hdata);
		     //echo("url:".$url."<BR>");
   		         
    	     $ch = curl_init(); // PHP에서는 질의를 요청하기에 앞서, Curl을 사용함을 기억하자.
		     curl_setopt($ch, CURLOPT_URL, $url);
		     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		     curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	         curl_setopt($ch, CURLOPT_HEADER, 0);
		     $res = curl_exec($ch);
		             
			 $load_string = simplexml_load_string($res);
		     $ca_result = $load_string->return_code;
		     if($ca_result == '000')//완료
	         {
                $sql = "update shop_order set cash_uresult = 'Y' where od_id = '$row[od_id]' ";
			    sql_query($sql);
		     }
             //현금영수증 로그 쌓기
			 $load_string = simplexml_load_string($res);
		     $ma_result = $load_string->return_code;
             $value['c_type'] = "401";//현금영수증_신청
             $value['url'] = $url; 
             $value['return_code'] = $ma_result; //응답코드
             $value['call_date'] = TB_TIME_YMDHIS;
             insert("hwelfare_log", $value);//DB에 insert하기
		     curl_close($ch);
			
		    
		} //현금영수증 신청여부 close
		

  } //for문 close
 

 ?>
