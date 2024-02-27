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

/*
function tb_path()
{
    $result['path'] = str_replace('\\', '/', dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$tb_path = tb_path();
*/

//include_once($tb_path['path'].'/config.php');   // 설정 파일
 include_once('/home1/killdeal/public_html/config.php');

 //unset($tb_path);

// multi-dimensional array에 사용자지정 함수적용
/*
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}
*/

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


  //마감호출api 공통정보
  //$shopevent_no = "EEB1D1011079696148DBFC6731FA02A8";
  //$shop_no = "27F0E34B60C08D0F34799E99601BB6FE";
 
  $dateString = date("Y-m-d", time());
  $dateString21 = date("Y-m-d H:i:s",time());
  $dateString22 = date("Y-m-d H:i:s",strtotime ("-1 hour"));//1시간전 주문건 마감호출
  
  
  //***1.시간단위호출 
  //***마감재호출(curl_remagam.php에 마감취소호출여부 체크변수 필요: ex) magam_call_chk2 -> 마감취소호출여부)
  $sql = " select * from shop_order where od_time >= '".$dateString22."' and od_time < '".$dateString21."' and ( dan =2 OR dan =3 OR dan =6 OR dan=9 ) and use_point2 > 0 and pt_id = 'golf' order by index_no desc ";
  $result = sql_query($sql);
  //echo("sql:".$sql);
  
  for($i=0; $row=sql_fetch_array($result); $i++)
  {
	    $od_no = $row['od_id'];
	    $order_no = base64_encode($row['od_id']);
		$order_no2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_no);
		$mem_no = substr($row['mb_id'],5);//hwelf100001865241
		$mem_no2 = base64_encode($mem_no);
		$mem_no3 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$mem_no2);
		//$point = $row['use_point2'];
        $ux_point = base64_encode($row['use_point2']);//상품별 포인트 사용액
        $ux_point2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$ux_point);
		//$saprice = $row['goods_price'];
        $saleprice = base64_encode($row['goods_price']);
        $saleprice2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$saleprice);
		//$etc = $row['use_price'];
		$etc_amt = base64_encode($row['use_price']);
        $etc_amt2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$etc_amt);
		$deli_amt = base64_encode($row['baesong_price'] + $row['baesong_price2']);
        $deli_amt2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$deli_amt);

		//$it_cd = $row['gs_id'];
		$item_cd = base64_encode($row['gs_id']);
        $item_cd2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$item_cd);
		
        $mproc_code = base64_encode("10");//포인트 사용(**20: 포인트 취소)
	    $mproc_code2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$mproc_code);
		$order_dm = substr($row['od_time'],0, 10); //2019-06-10
		$order_dm2 = str_replace("-","",$order_dm); 
        $order_dm3 = base64_encode($order_dm2);
		$order_dm4 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_dm3);

		$shop_no = base64_encode($row['shop_no']);
		$shop_no2 = jsonfy2($shop_no);
		$shopevent_no = base64_encode($row['shopevent_no']);
		$shopevent_no2 = jsonfy2($shopevent_no);

     	$gs = unserialize($row['od_goods']); //상품명
		$item_nm = get_text($gs['gname']); 
		$item_nm22 = iconv("UTF-8","EUC-kr", $item_nm);//charset변환 

		$item_nm2 = base64_encode($item_nm22);
        $item_nm3 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$item_nm2);
		$item_price = get_text($gs['goods_price'])/$row['sum_qty'];//**단품가 
		$item_price2 = base64_encode($item_price);
        $item_price3 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$item_price2);
        $order_qty = base64_encode($row['sum_qty']);
        $order_qty2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_qty);
        $dc_price = base64_encode("0");//할인금액(**추후 다시 체크해볼것)
        $dc_price2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$dc_price);
		$tax_gb = base64_encode("1");//과세여부 gs_notax
        $tax_gb2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$tax_gb);
		//od_mobile(0: 웹 , 1: 모바일)
		if($row['od_moible'] =='0'){
			$mda_gb = "101";
		}else{
			$mda_gb = "102";
		}
		$magam_gb = "101";//(마감기준 : 101 ->주문기준 , 102 ->배송기준)
               
		//curl start 
        $hdata = array(
             'ORDER_NO' => $order_no2,
	    	 'ITEM_CD' => $item_cd2,
			 'ORDER_GB' => $mproc_code2,
			 'ORDER_DM' =>  $order_dm4,
			 'SHOP_NO' => $shop_no2,
		     'SHOPEVENT_NO' => $shopevent_no2,
	    	 'MEM_NO' => $mem_no3,
			 'TAX_GB' => $tax_gb2,
			 'SALEPRICE' => $saleprice2,
			 'POINT_AMT' => $ux_point2,
			 'ETC_AMT' => $etc_amt2,
			 'MEDIA_CD' => 'MW',
			 'DELI_AMT' => $deli_amt2,
			 'ITEM_NM' => $item_nm3,
		     'ITEM_PRICE' => $item_price3,
	    	 'ORDER_QTY' => $order_qty2,
			 'DC_PRICE' => $dc_price2,
			 'MAGAM_GB' => $magam_gb,
			 'MDA_GB' => $mda_gb
        );

	    $url = "https://gift.e-hyundai.com/hb2efront_new/pointOpenAPIMagam.do?".http_build_query($hdata);
		//echo("url:".$url."\n");
		//echo("item_nm22:".$item_nm22."<br>");
   	
    	$ch = curl_init(); // PHP에서는 질의를 요청하기에 앞서, Curl을 사용함을 기억하자.
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	    curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
     
 
	    $load_string = simplexml_load_string($res);
		$ma_result = $load_string->return_code;
        
        $sql = "insert into hwelfare_log(c_type,url,return_code,call_date) VALUES ('201','{$url}' ,'{$ma_result}','".TB_TIME_YMDHIS."')"; 
	    sql_query($sql);

		curl_close($ch);
		
		        
		if($ma_result == '000')//완료
	    {
             $sql = "update shop_order set magam_uresult = 'Y' where index_no = '$row[index_no]' ";
			 sql_query($sql);
			 //****포인트 취소마감 start_20200501
			 if($row['dan'] == 6 or $row['dan'] == 9){ //기본금 취소
                   $mproc_code = base64_encode("20");//포인트 사용(**20: 포인트 취소)
	               $mproc_code2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$mproc_code);
                   
                   $hdata = array(
                       'ORDER_NO' => $order_no2,
	    	           'ITEM_CD' => $item_cd2,
			           'ORDER_GB' => $mproc_code2,
			           'ORDER_DM' =>  $order_dm4,
			           'SHOP_NO' => $shop_no2,
		               'SHOPEVENT_NO' => $shopevent_no2,
	    	           'MEM_NO' => $mem_no3,
			           'TAX_GB' => $tax_gb2,
			           'SALEPRICE' => $saleprice2,
			           'POINT_AMT' => $ux_point2,
			           'ETC_AMT' => $etc_amt2,
			           'MEDIA_CD' => 'MW',
			           'DELI_AMT' => $deli_amt2,
			           'ITEM_NM' => $item_nm3,
		               'ITEM_PRICE' => $item_price3,
	    	           'ORDER_QTY' => $order_qty2,
			           'DC_PRICE' => $dc_price2,
			           'MAGAM_GB' => $magam_gb,
			           'MDA_GB' => $mda_gb
                    );

	                $url = "https://gift.e-hyundai.com/hb2efront_new/pointOpenAPIMagam.do?".http_build_query($hdata);
		
    	            $ch = curl_init(); // PHP에서는 질의를 요청하기에 앞서, Curl을 사용함을 기억하자.
		            curl_setopt($ch, CURLOPT_URL, $url);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		            curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                curl_setopt($ch, CURLOPT_HEADER, 0);
		            $res = curl_exec($ch);
      
	                $load_string = simplexml_load_string($res);
		            $ma_result2 = $load_string->return_code;
        
                    $sql = "insert into hwelfare_log(c_type,url,return_code,call_date) VALUES ('202','{$url}' ,'{$ma_result}','".TB_TIME_YMDHIS."')"; 
	                sql_query($sql);

		            curl_close($ch);

				if($ma_result2 == '000')//완료
	            {
                         $sql = "update shop_order set magam_cresult = 'Y' where index_no = '$row[index_no]' ";
			             sql_query($sql);
				}
				if($ma_result2 == '000' || $ma_result2 == '001' || $ma_result2 == '002' || $ma_result2 == '003' || $ma_result2 == '004' ){
                         //굳이 필요없을것으로 판단(다만 마감취소호출여부 체크 필요할수도 있다. : 추후 보완필요)
						 /*
			             $sql = "update shop_order set magam_call_chk2 = 'Y' where index_no = '$row[index_no]' ";
			             sql_query($sql);
						 */
       	        }
		


			 } //****포인트 취소마감 end
		}// 마감성공 close

		//마감호출 실행여부 start_20200317
		if($ma_result == '000' || $ma_result == '001' || $ma_result == '002' || $ma_result == '003' || $ma_result == '004' ){
             //000:완료 , 001:기존DATA존재 , 002:기타에러, 003:마감완료, 004:파라미터 에러
			 $sql = "update shop_order set magam_call_chk = 'Y' where index_no = '$row[index_no]' ";
			 sql_query($sql);
       	}
		//마감호출 실행여부 end
		
		
	
	
   
		
		

  }
  




 ?>
