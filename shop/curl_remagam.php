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
 include_once('/home/killdeal/public_html/config.php');

 //unset($tb_path);

// multi-dimensional array에 사용자지정 함수적용

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
$dbconfig_file = "/home/killdeal/public_html/data/dbconfig.php";
if(file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
	include_once("/home/killdeal/public_html/lib/partner.lib.php"); // 가맹점 라이브러리
	include_once("/home/killdeal/public_html/lib/global.lib.php"); // PC+모바일 공통 라이브러리
	include_once("/home/killdeal/public_html/lib/common.lib.php"); // PC전용 라이브러리
	include_once("/home/killdeal/public_html/lib/mobile.lib.php"); // 모바일전용 라이브러리
	

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


 
  $dateString = date("Y-m-d", time());
  $dateString21 = date("Y-m-d H:i:s",strtotime ("-30 minutes"));//30분 주문전 주문건 마감호출
  $dateString22 = date("Y-m-d H:i:s",strtotime ("-90 minutes"));//1시간 30분 주문전 주문건 마감호출
  
  
 


  
  $sql = " select * from shop_order where od_time >= '".$dateString22."' and od_time < '".$dateString21."' and ( dan =2 OR dan =3 OR dan =6 OR dan =9 ) and use_point2 > 0 and pt_id = 'golf' and magam_call_chk = 'N' order by index_no desc ";
  $result = sql_query($sql);
  //echo("sql:".$sql);
  
  for($i=0; $row=sql_fetch_array($result); $i++)
  {
	    $od_no = $row['od_id'];
	    $order_no = base64_encode($row['od_id']);
		$order_no2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_no);
		$mem_no = substr($row['mb_id'],5);
		$mem_no2 = base64_encode($mem_no);
		$mem_no3 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$mem_no2);
		//$point = $row['use_point2'];
        $ux_point = base64_encode($row['use_point2']);
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
		
		
		$mproc_code = base64_encode("10");//포인트사용 마감
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
		$item_price = get_text($gs['goods_price'])/$row['sum_qty']; 
		$item_price2 = base64_encode($item_price);
        $item_price3 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$item_price2);
        $order_qty = base64_encode($row['sum_qty']);
        $order_qty2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$order_qty);
        $dc_price = base64_encode("0");
        $dc_price2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$dc_price);
		$tax_gb = base64_encode("1");
        $tax_gb2 = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$tax_gb);
		
		if($row['od_moible'] =='0'){
			$mda_gb = "101";
		}else{
			$mda_gb = "102";
		}
		$magam_gb = "101";
               
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
		
   	
    	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	    curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
     
 
	    $load_string = simplexml_load_string($res);
		$ma_result = $load_string->return_code;
       
		
        $sql = "insert into hwelfare_log(c_type,url,return_code,call_date) VALUES ('203','{$url}' ,'{$ma_result}','".TB_TIME_YMDHIS."')"; 
		
       
	    sql_query($sql);
		
		        
		if($ma_result == '000')//완료
	    {
             $sql = "update shop_order set magam_uresult = 'Y' where index_no = '$row[index_no]' ";
			 sql_query($sql);
		}

		//마감호출 실행여부 start_20200317
		if($ma_result == '000' || $ma_result == '001' || $ma_result == '002' || $ma_result == '003' || $ma_result == '004' ){
             //000:완료 , 001:기존DATA존재 , 002:기타에러, 003:마감완료, 004:파라미터 에러
			 $sql = "update shop_order set magam_call_chk = 'Y' where index_no = '$row[index_no]' ";
			 sql_query($sql);
       	}
		//마감호출 실행여부 end
		
		
	
	
   
		curl_close($ch);
		

  }
  

  


 ?>
