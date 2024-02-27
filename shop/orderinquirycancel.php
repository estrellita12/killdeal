<?php
include_once('./_common.php');

// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러

//**일단 주석처리_기능확인후 주석해제 필요
/*
if($token && get_session("ss_token") == $token) {
    // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
    set_session("ss_token", "");
} else {
    set_session("ss_token", "");
    alert("토큰 에러", TB_URL);
}
*/

$od = sql_fetch(" select * from shop_order where od_id = '$od_id' and mb_id = '{$member['id']}' ");

if(!$od['od_id']) {
    alert("존재하는 주문이 아닙니다.");
}

// 주문취소 가능여부 체크
$od_count1 = $od_count2 = $od_cancel_price = 0;

$sql = " select dan, cancel_price from shop_order where od_id = '$od_id' order by index_no asc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$od_count1++;
	if(in_array($row['dan'], array('1','2')))
		$od_count2++;

	$od_cancel_price += (int)$row['cancel_price'];
}

$uid = md5($od['od_id'].$od['od_time'].$od['od_ip']);

if($od_cancel_price > 0 || $od_count1 != $od_count2) {
	alert("취소할 수 있는 주문이 아닙니다.", TB_SHOP_URL."/orderinquiryview.php?od_id=$od_id&uid=$uid");
}

// PG 결제 취소
if($od['od_tno']) {
    switch($od['od_pg']) {
        case 'lg':
            require_once(TB_SHOP_PATH.'/settle_lg.inc.php');
            $LGD_TID = $od['od_tno']; //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)

            $xpay = new XPay($configPath, $CST_PLATFORM);

            // Mert Key 설정
            $xpay->set_config_value('t'.$LGD_MID, $default['de_lg_mert_key']);
            $xpay->set_config_value($LGD_MID, $default['de_lg_mert_key']);
            $xpay->Init_TX($LGD_MID);

            $xpay->Set("LGD_TXNAME", "Cancel");
            $xpay->Set("LGD_TID", $LGD_TID);

            if($xpay->TX()) {
                //1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
                /*
                echo "결제 취소요청이 완료되었습니다.  <br>";
                echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
                echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
                */
            } else {
                //2)API 요청 실패 화면처리
                $msg = "결제 취소요청이 실패하였습니다.\\n";
                $msg .= "TX Response_code = " . $xpay->Response_Code() . "\\n";
                $msg .= "TX Response_msg = " . $xpay->Response_Msg();

                alert($msg);
            }
            break;
        case 'inicis':
            include_once(TB_SHOP_PATH.'/settle_inicis.inc.php');
            $cancel_msg = iconv_euckr('주문자 본인 취소-'.$cancel_memo);

            /*********************
             * 3. 취소 정보 설정 *
             *********************/
            $inipay->SetField("type",      "cancel");                        // 고정 (절대 수정 불가)
            $inipay->SetField("mid",       $default['de_inicis_mid']);       // 상점아이디
            /**************************************************************************************************
             * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
             * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
             * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
             * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
             **************************************************************************************************/
            $inipay->SetField("admin",     $default['de_inicis_admin_key']); //비대칭 사용키 키패스워드
            $inipay->SetField("tid",       $od['od_tno']);                   // 취소할 거래의 거래아이디
            $inipay->SetField("cancelmsg", $cancel_msg);                     // 취소사유

            /****************
             * 4. 취소 요청 *
             ****************/
            $inipay->startAction();

            /****************************************************************
             * 5. 취소 결과                                           	*
             *                                                        	*
             * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
             * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
             * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
             * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
             * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
             * (현금영수증 발급 취소시에만 리턴됨)                          *
             ****************************************************************/

            $res_cd  = $inipay->getResult('ResultCode');
            $res_msg = $inipay->getResult('ResultMsg');

            if($res_cd != '00') {
                alert(iconv_utf8($res_msg).' 코드 : '.$res_cd);
            }
            break;
        case 'kcp':

            if($od['paymethod'] == '복지카드'){ //복지카드 설정_20200508
               require_once(TB_SHOP_PATH.'/settle_kcp2.inc.php');
			   $default['de_kcp_mid'] = "A8HRJ";
               $default['de_kcp_site_key'] = '3Bbeo5luAlZqUwvsowTZ-y6__';
			}else{
			   require_once(TB_SHOP_PATH.'/settle_kcp.inc.php');

			}



            $_POST['tno'] = $od['od_tno'];
            $_POST['req_tx'] = 'mod';
            $_POST['mod_type'] = 'STSC';
            if($od['od_escrow']) {
                $_POST['req_tx'] = 'mod_escrow';
                $_POST['mod_type'] = 'STE2';
                if($od['paymethod'] == '가상계좌')
                    $_POST['mod_type'] = 'STE5';
            }
            $_POST['mod_desc'] = iconv("utf-8", "euc-kr", '주문자 본인 취소-'.$cancel_memo);
            $_POST['site_cd'] = $default['de_kcp_mid'];



            // 취소내역 한글깨짐방지
            setlocale(LC_CTYPE, 'ko_KR.euc-kr');

            include TB_SHOP_PATH.'/kcp/pp_ax_hub.php';

            // locale 설정 초기화
            setlocale(LC_CTYPE, '');
			break;
    }
}

// 주문 취소
$cancel_memo = addslashes(strip_tags($cancel_memo));

$sql = " select od_no from shop_order where od_id = '$od_id' order by index_no asc ";
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
	change_order_status_6($row['od_no']);
}

// 메모남김
$sql = " update shop_order
			set shop_memo = CONCAT(shop_memo,\"\\n주문자 본인 직접 취소 - ".TB_TIME_YMDHIS." (취소이유 : {$cancel_memo})\")
		 where od_id = '$od_id' ";
sql_query($sql);

// 주문취소 문자전송
//icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 5);
//dreamline_order_sms_send($od_id, 4);

if($od['paymethod'] == '가상계좌'){
    dabonem_order_sms_send($od_id, 16);
}else{
    dabonem_order_sms_send($od_id, 26);
}

if($pt_id == "golf") {


   //1. **카드취소시 상점코드 구분해서 취소가 필요할것으로 보인다.(ex. 일반,복지)

   $stotal = get_order_spay($od_id);//총계
   if($stotal['usepoint2'] > 0) //기본금 사용 존재
   {
         //**세션으로 할지 DB값으로 할지 고민필요
		 $shop_no = base64_encode(get_session("shop_no"));
		 $shop_no2 = jsonfy2($shop_no);

         $mem_no = base64_encode(get_session("mem_no"));
         $mem_no2 = jsonfy2($mem_no);

		 $shopevent_no = base64_encode(get_session("shopevent_no"));
         $shopevent_no2 = jsonfy2($shopevent_no);

		 $proc_code = base64_encode("300");//취소
		 $proc_code2 = jsonfy2($proc_code);

		 $mem_nm = iconv("UTF-8","EUC-kr",get_session("mem_nm"));//charset변환
         $mem_nm2 = base64_encode($mem_nm);
         $mem_nm3 = jsonfy2($mem_nm2);

		 $use_point21 = base64_encode($stotal['usepoint2']);//토탈 기본금 사용액
		 $use_point22 = jsonfy2($use_point21);

		 $order_no = base64_encode($od_id);
         $order_no2 = jsonfy2($order_no);

		 $hdata = array(
              'mem_id' => $mem_no2,
	    	  'shopevent_no' => $shopevent_no2,
			  'proc_code' => $proc_code2,
			  'chk_data' => $mem_nm3,
			  'point' => $use_point22,
			  'order_no' => $order_no2,
			  'media_cd' => 'MW'
		 );
		 $url = "https://gift.e-hyundai.com/hb2efront_new/pointOpenAPI.do?".http_build_query($hdata);

         $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		 curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	     curl_setopt($ch, CURLOPT_HEADER, 0);
         $res = curl_exec($ch);

		 //포인트 취소 로그 쌓기
		 $load_string = simplexml_load_string($res);
		 $ma_result = $load_string->return_code;
         $value['c_type'] = "102";//포인트취소
         $value['url'] = $url;
         $value['return_code'] = $ma_result; //응답코드
         $value['call_date'] = TB_TIME_YMDHIS;
         insert("hwelfare_log", $value);//DB에 insert하기

		 curl_close($ch);


    }//기본금 사용액 >0 close
		 //2. 포인트 취소_마감데이타전송
		 $sql = " select * from shop_order where od_id = '$od_id' order by index_no asc ";
         $result = sql_query($sql);

		 for($i=0; $row=sql_fetch_array($result); $i++)
         {


               //echo("i:".$i."<br>");
	           if($row['magam_uresult'] == 'Y')//포인트사용 마감호출 O ->포인트 취소마감호출->현금영수증 신청여부 체크
			   {
                     //echo("aaaaaaa");
				      if($i == 0)//한번만 실행
				      {

				         if($row['cash_uresult'] == 'Y')//현금영수증 신청 성공여부
				         {
						     //현금영수증 취소호출
						     $taxsave_yes = $row['taxsave_yes'];//현금영수증요청, Y:개인소득공제 , S: 사업자 지출 , N: 미발행
						     if($taxsave_yes =='Y' || $taxsave_yes == 'S')//현금영수증 존재
			                 {
					               if($taxsave_yes == 'Y')//소득공제
					               {
					                   $tax_hp2 = base64_encode($row['tax_hp']);
                                       $tax_hp3 = jsonfy2($tax_hp2);
				 		               $mem_idnt_val = $tax_hp3; //식별번호(핸드폰)
						               $installment = "00";//현금영수증
					               }
						           else if($taxsave_yes == 'S') //사업자지출
					               {
							           $tax_saupja_no2 = base64_encode($row['tax_saupja_no']);
                                       $tax_saupja_no3 = jsonfy2($tax_saupja_no2);
							           $mem_idnt_val =  $tax_saupja_no3; //식별번호(사업자번호)
							           $installment = "01";//지출증빙
					               }
					               $proc_sts = "102";//(101: 신청 , 102: 취소)
				                   $hdata_cash = array(
                                       'ORDER_NO' => $order_no2, //암호화
	    	                           'PROC_STS' => $proc_sts,
						               'MEM_IDNT_VAL' =>  $mem_idnt_val, // 암호
		                               'SHOPEVENT_NO' => $shopevent_no2,
	    	                           'MEM_NO' => $mem_no2,
			                           'INSTALLMENT' => $installment,
    		                           'MEDIA_CD' => 'MW'
			                       );
						           $cashurl = "https://gift.e-hyundai.com/hb2efront_new/openAPICashReceipt.do?".http_build_query($hdata_cash);
	                               //echo("cashurl:".$cashurl."<br>");
						           //url값 출력 확인 후 실행해볼것

             	                   $ch = curl_init();
		                           curl_setopt($ch, CURLOPT_URL, $cashurl);
		                           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		                           curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                               curl_setopt($ch, CURLOPT_HEADER, 0);

		                           $res = curl_exec($ch);

							       //현금영수증 취소 로그 쌓기
							       $load_string = simplexml_load_string($res);
		                           $ca_result = $load_string->return_code;
								   if($ca_result == '000')//완료
	                               {
							            $sql = "update shop_order set cash_cresult = 'Y' where index_no = '$row[index_no]' ";
			                            sql_query($sql);
		                           }
                                   $value['c_type'] = "402";//현금영수증
                                   $value['url'] = $cashurl;
                                   $value['return_code'] = $ca_result; //응답코드
                                   $value['call_date'] = TB_TIME_YMDHIS;
                                   insert("hwelfare_log", $value);//DB에 insert하기

		  		                   curl_close($ch);

				             }	// taxsave_yes = Y or S close

                         }//현금영수증 신청여부 : 성공 close

				    } //처음한번만 실행 close


					$item_cd2 = base64_encode($row['gs_id']);
				    $item_cd3 = jsonfy2($item_cd2);

		            $mproc_code = base64_encode("20");//포인트 취소
                    $mproc_code2 = jsonfy2($mproc_code);

                    $order_dm = TB_TIME_YMD2;
				    $order_dm2 = base64_encode($order_dm);
                    $order_dm3 = jsonfy2($order_dm2);

				    $tax_gb = base64_encode("1");//과세여부
					$tax_gb2 = jsonfy2($tax_gb);

				    //상품명 처리완료
                    $gs = unserialize($row['od_goods']); //상품명
		            $item_nm = get_text($gs['gname']);
		            $item_nm22 = iconv("UTF-8","EUC-kr", $item_nm);//charset변환
          		    $item_nm2 = base64_encode($item_nm22);
                    $item_nm3 = jsonfy2($item_nm2);

				    $saleprice = base64_encode($row['goods_price']);
                    $saleprice2 = jsonfy2($saleprice);

                    $ux_point = base64_encode($row['use_point2']);
                    $ux_point2 = jsonfy2($ux_point);

                    $etc_amt =  base64_encode($row['use_price']);
				    $etc_amt2 =  jsonfy2($etc_amt);

                    $deli_amt = $row['baesong_price'] + $row['baesong_price2'];
				    $deli_amt2 =  base64_encode($deli_amt);
				    $deli_amt3 =  jsonfy2($deli_amt2);

				    $order_qty =  base64_encode($row['sum_qty']);
				    $order_qty2 =  jsonfy2($order_qty);

				    $dc_price = base64_encode("0");//할인금액(**추후 다시 체크해볼것)
                    $dc_price2 = jsonfy2($dc_price);

				    $item_price = $row['goods_price']/$row['sum_qty'];//제품단가
                    $item_price2 =  base64_encode($item_price);
				    $item_price3 =  jsonfy2($item_price2);

				    $magam_gb = "101";//(마감기준 : 101 ->주문기준 , 102 ->배송기준)

				    if($row['od_moible'] == '0'){ //(0: PC , 1: 모바일)
			                $mda_gb2 = "101";
	                }else{
			                $mda_gb2 = "102";
		            }

				    $hdata2 = array(
                       'ORDER_NO' => $order_no2,
	    	           'ITEM_CD' => $item_cd3,
			           'ORDER_GB' => $mproc_code2,
			           'ORDER_DM' =>  $order_dm3,
			           'SHOP_NO' => $shop_no2,
		               'SHOPEVENT_NO' => $shopevent_no2,
	    	           'MEM_NO' => $mem_no2,
			           'TAX_GB' => $tax_gb2,
			           'SALEPRICE' => $saleprice2,
			           'POINT_AMT' => $ux_point2,
			           'ETC_AMT' => $etc_amt2,
			           'MEDIA_CD' => 'MW',
			           'DELI_AMT' => $deli_amt3,
			           'ITEM_NM' => $item_nm3,
		               'ITEM_PRICE' => $item_price3,
	    	           'ORDER_QTY' => $order_qty2,
			           'DC_PRICE' => $dc_price2,
			           'MAGAM_GB' => $magam_gb,
			           'MDA_GB' => $mda_gb2
                    );
				    $url2 = "https://gift.e-hyundai.com/hb2efront_new/pointOpenAPIMagam.do?".http_build_query($hdata2);

				    $ch = curl_init();
		            curl_setopt($ch, CURLOPT_URL, $url2);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		            curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                curl_setopt($ch, CURLOPT_HEADER, 0);

		            $res = curl_exec($ch);

                    $load_string = simplexml_load_string($res);
		            $ma_result = $load_string->return_code;
                    if($ma_result == '000')//완료
	                {
					     $sql = "update shop_order set magam_cresult = 'Y' where index_no = '$row[index_no]' ";
			             sql_query($sql);
		            }

                    $value['c_type'] = "202";//마감
                    $value['url'] = $url2;
                    $value['return_code'] = $ma_result; //응답코드
                    $value['call_date'] = TB_TIME_YMDHIS;
                    insert("hwelfare_log", $value);//DB에 insert하기

		  		    curl_close($ch);



			   } //마감성공여부 close



         } //for문 close


} //pt_id = golf close
//20191224 도담골프 주문 취소 포인트 반환
else if($pt_id=='dodamgolf')
{
	$sql = " select * from shop_order where od_id = '$od_id' order by index_no asc ";
	$result = sql_query($sql);

	$usr = str_replace("dd_","",get_session('ss_mb_id'));
	$kc = gen_keycode();
	$em = 0;
	$md = "plus";
	$mm = "[환불]도담골프 주문환불(".$od_id.")에 의한 적립금 환불";

	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$em = $em + $row['use_point'];
	}

	if(dodam_point($usr, $kc ,$em, $md, $mm) != null)
	{
		//포인트 증가 성공시 로그 구현, 보류
		dodam_point($memId, $kc, $em, $md, $mm);
	}
	else
	{
		echo "post_curl_error";
		exit;
	}

	//회원 정보 다시 가져오기 point 값 다시 호출
	$keycode = gen_keycode();
	$mem_info = get_member_info($keycode, $usr);
	set_session('ss_mb_gd', $mem_info->mem_gd);
	set_session('ss_mb_point', $mem_info->point);

} else if($pt_id == 'golfu'){

    //포인트 사용금액 존재 체크
	$stotal = get_order_spay($od_id);//총계
    if($stotal['usepoint'] > 0) //적립금 사용 존재
    {
		$use_point = $stotal['usepoint'];//적립금 사용액
        $agent = "GOLFUNET";
        $pass = "GOLFUNET!@#$";
        $memId = substr(get_session('ss_mb_id'),6);
	    $data = "exec=point&memId=".$memId."&pass=".$pass;
	    $data .= "&ptype=1&point=".$use_point."&pcode=14&orderId=".$od_id."&memo=주문취소_환원";
        $postdata = golfu_Encrypt_EnCode($data, $agent);
        $senddata = "agent=".$agent."&postdata=".urlencode($postdata);
        $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
        $result = golfu_HTTP_CURL($host, $senddata);
        $res_dec = json_decode($result);
        if($res_dec->success){//true or false
           $ma_result = "success";
	    }else {
           $ma_result = "fail";
	    }
	    $value['c_type'] = "101";//포인트 적립
        $value['url'] = $data;
        $value['return_code'] = $ma_result; //응답코드
        $value['call_date'] = TB_TIME_YMDHIS;
        insert("agency_log", $value);//DB에 insert하기
	}
}





 goto_url(TB_SHOP_URL."/orderinquiryview.php?od_id=$od_id&uid=$uid");



?>
