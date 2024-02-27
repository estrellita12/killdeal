<?php
include_once('./_common.php');
/******************************
 * 파일명: golfunet_point_send.php
* 설  명: 골프유닷넷 포인트 연동  프로세스
* 수정일: 2019.11.27
* 수정자: siokyu
* 버  전: 1.0
* 비  고:
*******************************/
@extract($_GET);
$agent = "GOLFUNET";
$pass = "GOLFUNET!@#$";

//$exec = isset($exec) ? strval($exec) : 'memberPoint'; //point ,memberPoint
$exec = "memberPoint";
$ptype = isset($ptype) ? strval($ptype) : '1';
$memId = "hyeukje"; //회원 아이디 yusiok
$isReal = true ;

switch( trim($exec) )
{
	case "point":
		$data = "exec=point&memId=".$memId."&pass=".$pass;

		// ptype : 1 적립, 3 차감, point = 포인트 값, pcode=14 : 포인트 사용 코드 값 14고정, orderId= 주문번호, memo = 상품명 + 구매 적립(반품 차감, 포인트 사용)
		if ($ptype == '1') { //적립
			$data .= "&ptype=1&point=4884&pcode=14&orderId=20101708453918&memo=구매 적립";
		} else { // 차감
			$data .= "&ptype=3&point=10000&pcode=14&orderId=20061117155431&memo=구매 차감";
		}
		$postdata = golfu_Encrypt_EnCode($data, $agent);

		//start
         $senddata = "agent=".$agent."&postdata=".urlencode($postdata);
         $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
         $result = golfu_HTTP_CURL($host, $senddata);
         $res_dec = json_decode($result);
         if($res_dec->success){//true or false
             $ma_result = "success";
	     }else {
             $ma_result = "fail";
	     }
	     $value['c_type'] = "102";//포인트 차감
         $value['url'] = $data; 
         $value['return_code'] = $ma_result; //응답코드
         $value['call_date'] = TB_TIME_YMDHIS;
         insert("agency_log", $value);//DB에 insert하기

		//end

		break;

	case "memberPoint":
		$data = "exec=memberPoint&memId=".$memId."&pass=".$pass;
	 	$postdata = Encrypt_EnCode($data, $agent);

		break;
   
   
	default:
		echo json_encode(array('success'=>false,'msg'=>'잘못된 접근입니다.'));
		exit;
		break;
	
}




 $senddata = "agent=".$agent."&postdata=".urlencode($postdata);


if ($isReal) {
	$host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
	$result = HTTP_CURL($host, $senddata);

    
} else {
	$host = "http://localhost:8010/GolfuScore/agent/golfunet_point_proc.php";
	$result = HTTP_Post($host, $senddata);
	$result = substr($result, strpos($result, "\r\n\r\n")+4);
}

// 성공시 {"success":true,"msg":"success"},  실패시 {"success":false,"msg":"정보 저장중 오류가 발생 하였습니다.6-0"} //맨뒤 6-0 : 회원정보 없음, 6-1 : 포인트 인서트 실패, 6-2 : 회원 적립(차감) 실패
echo $result;
$yummy = json_decode($result);

echo $yummy->point; 






exit;


function Encrypt_EnCode($txt, $serverkey) {

	$tmp = "";
	$ctr = 0;
	$cnt = strlen($txt);
	$len = strlen($serverkey);

	for ($i=0; $i<$cnt; $i++) {

		if ($ctr==$len){
			$ctr=0;
		}
		$tmp .= substr($txt,$i,1) ^ substr($serverkey,$ctr,1);
		$ctr++;
	}

	$tmp = base64_encode($tmp);

	return $tmp;

}
function HTTP_Post($URL,$data) {

	$URL_Info=parse_url($URL);
	$str = $data;
//	$str = "";
//	if(!empty($data)) foreach($data AS $k => $v) $str .= urlencode($k).'='.urlencode($v).'&';


	$path = $URL_Info["path"];
	$host = $URL_Info["host"];
	$port = $URL_Info["port"];
	if (empty($port)) $port=80;
	$result = "";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	$http  = "POST $path HTTP/1.0\r\n";
	$http .= "Host: $host\r\n";
	$http .= "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n";
	$http .= "Content-length: " . strlen($str) . "\r\n";
	$http .= "Connection: close\r\n\r\n";
	$http .= $str . "\r\n\r\n";
	fwrite($fp, $http);
	while (!feof($fp)) { $result .= fgets($fp, 4096); }
	fclose($fp);
	return $result;
}
function HTTP_CURL($url,$data) {

	$ch = curl_init();
	$agent = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
	curl_setopt ($ch, CURLOPT_SSLVERSION,0); // SSL 버젼 (https 접속시에 필요)
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    // 응답 값을 브라우저에 표시하지 말고 값을 리턴
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	// 헤더는 제외하고 content 만 받음
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_REFERER, $url);
	//curl_setopt($ch, CURLOPT_USERAGENT, $agent);

	$res = curl_exec($ch);
	curl_close($ch);


	return $res;
}

?>
