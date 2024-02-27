<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
		  goto_url(TB_MSHOP_URL.'/orderform.php');
	}




$ss_cart_id = get_session('ss_cart_id');
if(!$ss_cart_id){
//	alert("주문하실 상품이 없습니다.");
	alert("주문하실 상품이 없습니다.", TB_URL);

}
set_session('tot_price', '');
set_session('use_point', '');

// 새로운 주문번호 생성
$od_id = get_uniqid();
set_session('ss_order_id', $od_id);

$tb['title'] = '주문서작성';
include_once("./_head.php");
//echo get_session('ss_mb_id');
if($is_member) { // 회원일때
	// 20200114 골팡 일때 ID세션에서 앞에 gp_ 추가
	if($pt_id == 'golfpang')
	{
		$ad = get_address('gp_'.get_session('ss_mb_id'));
	}else if($pt_id == 'golf'){ //현대리바트_20200312
        $ad = get_address('hwelf'.get_session('ss_mb_id'));
	}else
	{
		$ad = get_address(get_session('ss_mb_id'));
	}
	$address=sql_fetch_array($ad);
	//print_r($address);
	// 주문자가 가맹점이면 추천인을 자신으로 변경
	$mb_recommend = $member['pt_id'];
	if(is_partner($member['id'])) {
		$mb_recommend = $member['id'];
	}
} else {
	$mb_recommend = $pt_id;
	$member['point'] = 0;
}


$order_action_url = TB_HTTPS_SHOP_URL.'/orderformupdate.php';

//현대리바트 연동_포인트조회_20190603
if($pt_id == "golf")
{
   $mem_no = base64_encode(get_session("mem_no"));
   $mem_no2 = jsonfy2($mem_no);
  
   $shopevent_no = base64_encode(get_session("shopevent_no"));
   $shopevent_no2 = jsonfy2($shopevent_no);

   $proc_code = base64_encode("100");//포인트 조회
   $proc_code2 = jsonfy2($proc_code);

   $mem_nm = iconv("UTF-8","EUC-kr",get_session("mem_nm"));//charset변환 
   $mem_nm2 = base64_encode($mem_nm);
   $mem_nm3 = jsonfy2($mem_nm2);

   $hdata = array(
       'mem_id' => $mem_no2,
	   'shopevent_no' => $shopevent_no2,
	   'proc_code' => $proc_code2,
	   'chk_data' => $mem_nm3,
	   'media_cd' => 'MW'
   );
   $url = "https://gift.e-hyundai.com/hb2efront_new/pointOpenAPI.do?".http_build_query($hdata);

   //echo("url:".$url);
   $ch = curl_init(); 
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
   curl_setopt($ch, CURLOPT_HEADER, 0);
   $res = curl_exec($ch);
   
   $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

   
   if($httpCode == 200) {
          
	     $load_string = simplexml_load_string($res);
         $use_point2 = $load_string->return_point;//현대리바트 회원 기본금
         $ma_result = $load_string->return_code;

   }



   curl_close($ch);
   
  
   $value['c_type'] = "100";//포인트조회
   $value['url'] = $url; 
   $value['return_code'] = $ma_result; //응답코드
   $value['call_date'] = TB_TIME_YMDHIS;
   $value['error_log'] = $httpCode;
   insert("hwelfare_log", $value);//DB에 insert하기
 
   
   

} 
else if($pt_id == "golfu"){ //골프유닷넷_포인트조회_20191209

   $agent = "GOLFUNET";
   $pass = "GOLFUNET!@#$";
   //$exec = "memberPoint"; 
   $memId = substr(get_session('ss_mb_id'),6);
   $data = "exec=memberPoint&memId=".$memId."&pass=".$pass;
   $postdata = golfu_Encrypt_EnCode($data, $agent);

   $senddata = "agent=".$agent."&postdata=".urlencode($postdata);
   $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
   $result = golfu_HTTP_CURL($host, $senddata);
   $res_dec = json_decode($result);
   $config['usepoint'] = 10000;

	// (2020-12-08) 포인트 조회 실패시 포인트 값을 0으로 처리
	//			실패시 다시 조회해 올것인지는 고민 중..
	if(isset($res_dec->point)){
		$member['point'] = $res_dec->point;
	}else{
		$member['point'] = 0;
	}
    
   //$member['point'] = $res_dec->point;
   //**로그 추가 필요

}

if($pt_id == "golfu"){
    include_once(TB_THEME_PATH.'/orderform2.skin.php');
}else if($pt_id == "golf"){
    include_once(TB_THEME_PATH.'/orderformh.skin.php');
}else if($pt_id == "golftouro"){
    include_once(TB_THEME_PATH.'/orderform_test.skin.php');
}else {
    include_once(TB_THEME_PATH.'/orderform.skin.php');
}

   
include_once("./_tail.php");

?>
