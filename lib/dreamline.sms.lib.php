<?php
if(!defined('_TUBEWEB_')) exit;

//문자열 자르기
function spacing($text,$size) 
{
	for($i=0; $i<$size; $i++) $text.=" ";
	$text = substr($text,0,$size);
	return $text;
}

function CheckCommonType($dest) 
{
   	$dest=preg_replace("/[^0-9]/i","",$dest);
	if(strlen($dest)<10 || strlen($dest)>11) return "휴대폰 번호가 틀렸습니다";
	$CID=substr($dest,0,3);
	if( preg_match("/[^0-9]/i",$CID) || ($CID!='010' && $CID!='011' && $CID!='016' && $CID!='017' && $CID!='018' && $CID!='019') ) return "휴대폰 앞자리 번호가 잘못되었습니다";
}

//드림라인문자 서비스 연결 클래스
class DL_SMS 
{
	var $TR_SENDSTAT;	// 0: 발송대기, 1: 전송완료, 2:결과 수신완료
	var $TR_MSGTYPE;	// 문자전송형태 0: 일반메세지, 1:콜백 URL메세지
	var $TR_PHONE;		// 수신번호
	var $TR_CALLBACK;	// 송신자 전화번호
	var $TR_MSG;		// 전송할메세지
	var $TR_ORG_CALLBACK; // 송신자 원 발신번호

	function __construct() 
	{
		$this->TR_SENDSTAT = "0";
		$this->TR_MSGTYPE = "0";
		$this->TR_PHONE = "";
		$this->TR_CALLBACK = "";
		$this->TR_MSG = "";
		$this->TR_ORG_CALLBACK = "";
	}

	function Check_Data($dest, $callBack, $Caller, $msg) 
	{
        // 내용 검사 1 
		$Error = CheckCommonType($dest);
		if($Error) return $Error;
		// 내용 검사 2
		if( preg_match("/[^0-9]/i",$callBack) ) return "회신 전화번호가 잘못되었습니다";

        //$msg=cut_char($msg,80); // 80자 제한
		$this->TR_PHONE = spacing($dest,11);
		$this->TR_ORG_CALLBACK = spacing($callBack,11);
		$this->TR_CALLBACK = spacing($Caller,11);
		//$this->TR_MSG = spacing($msg,80);
		$this->TR_MSG = $msg;
	}

	function Send () 
	{
		$sql = "INSERT INTO SMS_MSG (TR_SENDDATE, TR_SENDSTAT, TR_MSGTYPE, TR_PHONE, TR_CALLBACK, TR_MSG, TR_ORG_CALLBACK) 
		VALUES(NOW(), '$this->TR_SENDSTAT', '$this->TR_MSGTYPE', '$this->TR_PHONE', '$this->TR_CALLBACK', '$this->TR_MSG', '$this->TR_ORG_CALLBACK')";

		sql_query($sql);
	}
}
?>