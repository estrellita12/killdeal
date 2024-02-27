<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$chk_count = count($_POST['chk']);
if(!$chk_count)
    alert('처리할 자료를 하나 이상 선택해 주십시오.');

$od_sms_ipgum_check		= 0;
$od_sms_baesong_check	= 0;
$od_sms_delivered_check = 0;
$od_sms_cancel_check	= 0;
$od_cancel_change		= 0;


//현대리바트_20190715
$pt_id = $_POST['pt_id'];
$od_id = $_POST['od_id'];//주문번호
$mb_id = $_POST['mb_id'];//회원번호
$chk_data = $_POST['chk_data'];//사용자명 
$chk_od_hp = $_POST['od_hp'];//사용자전화번호 2021-08-09
$od_time = $_POST['od_time'];//주문일

$shop_no = $_POST['shop_no'];
$shopevent_no = $_POST['shopevent_no'];
$tmp_od_no = "";
$tmp_od_no_4 = "";
//==============================================================================
// 주문상태변경
//------------------------------------------------------------------------------
for($i=0; $i<$chk_count; $i++)
{
	// 실제 번호를 넘김
	$k				= $_POST['chk'][$i];
	$od_no			= $_POST['od_no'][$k];
	$change_status  = $_POST['change_status'][$k];
	$current_status = $_POST['current_status'][$k];
	$delivery		= trim($_POST['delivery'][$k]);
	$delivery_no	= trim($_POST['delivery_no'][$k]);

	if($_POST['act_button'] == '입금대기') $change_status = 1;
	if($_POST['act_button'] == '결제완료') $change_status = 2;
	if($_POST['act_button'] == '주문취소') $change_status = 6;
	if($_POST['act_button'] == '전체반품') $change_status = 7;
	if($_POST['act_button'] == '전체환불') $change_status = 9;


	//****현대리바트_20190715 (orderform.php에서 넘긴값을 여기서 받아야 한다.)
	$item_cd = $_POST['item_cd'][$k];
    $goods_price = $_POST['goods_price'][$k];
	$use_point2 = $_POST['use_point2'][$k];
	$use_price = $_POST['use_price'][$k];
	$sum_qty = $_POST['sum_qty'][$k];
	$item_nm = $_POST['item_nm'][$k];
	$mda_gb = $_POST['mda_gb'][$k]; //배열로 받을 필요가 없다.(웹/모바일)
	$deli_amt = $_POST['deli_amt'][$k];
	$taxsave_yes = $_POST['taxsave_yes'][$k];
    $tax_hp = $_POST['tax_hp'][$k];//핸드폰번호
    $tax_saupja_no = $_POST['tax_saupja_no'][$k];//사업자번호
	$magam_uresult = $_POST['magam_uresult'][$k];//포인트사용마감 성공여부
	$cash_uresult = $_POST['cash_uresult'][$k];//현금영수증신청 성공여부

	//골프유닷넷_포인트_20191220
    $use_point = $_POST['use_point'][$k];
	$sum_point = $_POST['sum_point'][$k];
  
	switch($change_status) {
		case '1': // 입금대기
			if($current_status != 2) continue;
			change_order_status_1($od_no);
			break;
		case '2': // 입금완료
			if($current_status != 1) continue;
			change_order_status_2($od_no);
			$od_sms_ipgum_check++;
			break;
		case '3': // 배송준비
			if($current_status != 2) continue;
			change_order_status_3($od_no, $delivery, $delivery_no);
			break;
		case '4': // 배송중
			if($current_status != 3) continue;
			change_order_status_4($od_no, $delivery, $delivery_no);
            
            // 2021-08-02
            if($tmp_od_no_4 != "") $tmp_od_no_4 .= ", ";
            $tmp_od_no_4 .=  $od_no;



			//현대리바트_20190715
			if($pt_id == 'golf')
		    {
				  $proc_dm = TB_TIME_YHS;//14자리 년월일시분초
				  $order_no = base64_encode($od_id);
                  $order_no2 = jsonfy2($order_no);
				  $hdata = array(
                          'ORDER_NO' => $order_no2,
	    	              'MEDIA_CD' => 'MW',
					      'PROC_STS' => '104',
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

			      //주문상태 업데이트 로그 쌓기
                  $value['c_type'] = "301";//배송중
                  $value['url'] = $url; 
                  $value['return_code'] = $ca_result; //응답코드
                  $value['call_date'] = TB_TIME_YMDHIS;
                  insert("hwelfare_log", $value);//DB에 insert하기
				  curl_close($ch);

		    }
			
			$od_sms_baesong_check++;
			break;
		case '5': // 배송완료
			if(!in_array($current_status, array(3,4))) continue;
			change_order_status_5($od_no);
			//현대리바트_20190721
			//주문상태에 대한 변경 호출도 use_point2사용내역이 있어야 하는게 아닌가??
			if($pt_id == 'golf')
		    {
				  $proc_dm = TB_TIME_YHS;//14자리 년월일시분초
				  $order_no = base64_encode($od_id);
                  $order_no2 = jsonfy2($order_no);
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
				 

		    }else if($pt_id =='golfu'){  

				//포인트 적립
				if($sum_point > 0 ){
				     $agent = "GOLFUNET";
                     $pass = "GOLFUNET!@#$";
                     $memId = substr($mb_id,6);//golfu_ID
				     $data = "exec=point&memId=".$memId."&pass=".$pass;
	                 $data .= "&ptype=1&point=".$sum_point."&pcode=14&orderId=".$od_id."&memo=구매_적립";
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

			$od_sms_delivered_check++;
			break;
		case '6': // 취소_회원이 직접취소
			if($current_status != 1) continue;
			change_order_status_6($od_no);
           
			//$od_sms_cancel_check++;
			$od_cancel_change++;
			break;
		case '7': // 반품완료_운영자가 처리
			//***일단 주석처리 반품중(11)에서 왜 안되는건지?
			//if($current_status != 5) continue;
			if($current_status == 7) continue;

			//***중요: 리바트 오픈이후 프로세스에 맞게 수정보완필요(ex. 반품은 취소처리 못하게 한다든지)_20190831
           
		    change_order_status_7($od_no);

            // 2021-08-02			
            if($tmp_od_no != "") $tmp_od_no .= ", ";
            $tmp_od_no .=  $od_no;

			//
            if($pt_id == 'golf')
		    {
				  $proc_dm = TB_TIME_YMD2;//8자리 년월일
				  $order_no = base64_encode($od_id);
                  $order_no2 = jsonfy2($order_no);
				  $hdata = array(
                          'ORDER_NO' => $order_no2,
	    	              'MEDIA_CD' => 'MW',
					      'PROC_STS' => '106',
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
                  $value['c_type'] = "303";//주문상태변경_회수완료
                  $value['url'] = $url; 
                  $value['return_code'] = $ca_result; //응답코드
                  $value['call_date'] = TB_TIME_YMDHIS;
                  insert("hwelfare_log", $value);//DB에 insert하기

              
                  if($i == 0)// 1번만 실행되어야 한다. 
				   {
				        
                                               
						 if(is_null($shop_no) && is_null($shopevent_no)){
                                  $shop_no3 = "27F0E34B60C08D0F34799E99601BB6FE";
						          $shopevent_no3 = "EEB1D1011079696148DBFC6731FA02A8";

						 }else{
                                  $shop_no2 = base64_encode($shop_no);
                                  $shop_no3 = jsonfy2($shop_no2);
    	  			              $shopevent_no2 = base64_encode($shopevent_no);
                                  $shopevent_no3 = jsonfy2($shopevent_no2);
						 }

						

                         $stotal = get_order_spay($od_id);//*****총계_관리자페이지 실행여부 확인필요
                         if($stotal['usepoint2'] > 0) //기본금 사용 존재
                         {
         
                              $mem_no = substr($mb_id,5);//hwelf100001865241
                              $mem_no2 = base64_encode($mem_no);
                              $mem_no3 = jsonfy2($mem_no2);
                              $proc_code = base64_encode("300");//취소
                              $proc_code2 = jsonfy2($proc_code);
				 
                              $order_no = base64_encode($od_id);
                              $order_no2 = jsonfy2($order_no);

                              $mem_nm = iconv("UTF-8","EUC-kr",$chk_data);//charset변환==>이처리가 정상적임. 
                              $mem_nm2 = base64_encode($mem_nm);
                              $mem_nm3 = jsonfy2($mem_nm2);	
				  
                              $use_point21 = base64_encode($stotal['usepoint2']);//토탈 기본금 사용액
	   	                      $use_point22 = jsonfy2($use_point21);	


                              $hdata = array(
                                'mem_id' => $mem_no3,
	                            'shopevent_no' => $shopevent_no3,
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

                              $load_string = simplexml_load_string($res);
		                      $ma_result = $load_string->return_code;
                              $value['c_type'] = "102";//포인트취소
                              $value['url'] = $url; 
                              $value['return_code'] = $ma_result; //응답코드
                              $value['call_date'] = TB_TIME_YMDHIS;
                              insert("hwelfare_log", $value);//DB에 insert하기
                              curl_close($ch);
     
	                     } // $stotal['usepoint2'] >0 close

						 if($cash_uresult == 'Y')//현금영수증 신청 성공 
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
					             $proc_sts = "102";//(101: 신청 , 102: 취소)
				                 $hdata_cash = array(
                                    'ORDER_NO' => $order_no2, //암호화
	    	                        'PROC_STS' => $proc_sts, 
						            'MEM_IDNT_VAL' =>  $mem_idnt_val, // 암호
		                            'SHOPEVENT_NO' => $shopevent_no3, 
	    	                        'MEM_NO' => $mem_no3,
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
							     $load_string = simplexml_load_string($res);
		                         $ca_result = $load_string->return_code;
		                         if($ca_result == '000')//완료(******od_no ->od_id)
	                             {
											
                                       $sql = "update shop_order set cash_cresult = 'Y' where od_id = '$od_id' ";
			                                sql_query($sql);
		                         }

								 $value['c_type'] = "402";//현금영수증 취소
                                 $value['url'] = $cashurl; 
                                 $value['return_code'] = $ca_result; //응답코드
                                 $value['call_date'] = TB_TIME_YMDHIS;
                                 insert("hwelfare_log", $value);//DB에 insert하기
 						         curl_close($ch);
				                        
				         }	//cash_uresult =Y close   
					  
				   } // for문 첫실행 close (******아래쪽으로 이동할 필요가 있다.현금영수증 취소도 1번 실행되어야 한다.)
				  
				   //1.현대리바트 포인트 취소 호출
                   //기본금사용의 존재유무 파악 필요
				 				       
				   //(taxsave_yes   Y:개인소득공제 , S: 사업자 지출 , N: 미발행)
				   if($magam_uresult == 'Y') //포인트사용 마감 성공
				   {
                         
                         $item_cd2 = base64_encode($item_cd); 
				         $item_cd3 = jsonfy2($item_cd2);

			             $mproc_code = base64_encode("20");//포인트 취소
                         $mproc_code2 = jsonfy2($mproc_code);

			             $order_dm = TB_TIME_YMD2;
   		                 $order_dm2 = base64_encode($order_dm);
                         $order_dm3 = jsonfy2($order_dm2);

				         $tax_gb = base64_encode("1");//과세여부 
                         $tax_gb2 = jsonfy2($tax_gb);
                         //goods_price,use_point2,use_price,sum_qty,item_nm,mda_gb(0:web ,1:mobile),deli_amt

                         $item_nm22 = iconv("UTF-8","EUC-kr", $item_nm);//charset변환 
                         $item_nm2 = base64_encode($item_nm22);
                         $item_nm3 = jsonfy2($item_nm2);

				         $saleprice = base64_encode($goods_price);
                         $saleprice2 = jsonfy2($saleprice);

				         $ux_point = base64_encode($use_point2);
                         $ux_point2 = jsonfy2($ux_point);

                         $etc_amt =  base64_encode($use_price);
				         $etc_amt2 =  jsonfy2($etc_amt);

				         $deli_amt2 =  base64_encode($deli_amt);
				         $deli_amt3 =  jsonfy2($deli_amt2);

				         $order_qty =  base64_encode($sum_qty);
				         $order_qty2 =  jsonfy2($order_qty);

				         $dc_price = base64_encode("0");//할인금액(**추후 다시 체크해볼것)
                         $dc_price2 = jsonfy2($dc_price);

				         $item_price = $goods_price/$sum_qty;//제품단가
                         $item_price2 =  base64_encode($item_price);
				         $item_price3 =  jsonfy2($item_price2);

				         $magam_gb = "101";//(마감기준 : 101 ->주문기준 , 102 ->배송기준)
				         if($mda_gb =='0'){
			                    $mda_gb2 = "101";
	                     }else{
			                    $mda_gb2 = "102";
		                 }
              
				         $hdata2 = array(
                             'ORDER_NO' => $order_no2,
	    	                 'ITEM_CD' => $item_cd3,
			                 'ORDER_GB' => $mproc_code2,
			                 'ORDER_DM' =>  $order_dm3,
			                 'SHOP_NO' => $shop_no3,
		                     'SHOPEVENT_NO' => $shopevent_no3,
	    	                 'MEM_NO' => $mem_no3,
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
	                     //echo("url:".$url2."<br>");
				            
				             
             	         $ch = curl_init();
		                 curl_setopt($ch, CURLOPT_URL, $url2);
		                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		                 curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                     curl_setopt($ch, CURLOPT_HEADER, 0);

		                 $res = curl_exec($ch);
                         $load_string2 = simplexml_load_string($res);
		                 $ma_result = $load_string2->return_code;
		                 if($ma_result == '000')//완료
	                     {
						       //*****index_no에 대한 처리 필요(orderform에서 넘기던가 해야함.)
                               $sql = "update shop_order set magam_cresult = 'Y' where od_no = '$od_no' ";
			                   sql_query($sql);
		                 }

						 $value['c_type'] = "202";//포인트취소_마감
                         $value['url'] = $url2; 
                         $value['return_code'] = $ma_result; //응답코드
                         $value['call_date'] = TB_TIME_YMDHIS;
                         insert("hwelfare_log", $value);//DB에 insert하기
		  		         curl_close($ch);
				           
		           }//magam_uresult = Y  close

			}else if($pt_id == 'golfu'){

				//1.포인트 사용 환원
				if($use_point > 0 ){
				     $agent = "GOLFUNET";
                     $pass = "GOLFUNET!@#$";
                     $memId = substr($mb_id,6);//golfu_ID
				     $data = "exec=point&memId=".$memId."&pass=".$pass;
	                 $data .= "&ptype=1&point=".$use_point."&pcode=14&orderId=".$od_id."&memo=반품_환원";
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
			    //2.포인트 적립 취소
				if($sum_point > 0 ){
				     $agent = "GOLFUNET";
                     $pass = "GOLFUNET!@#$";
                     $memId = substr($mb_id,6);//golfu_ID
				     $data2 = "exec=point&memId=".$memId."&pass=".$pass;
	                 $data2 .= "&ptype=3&point=".$sum_point."&pcode=14&orderId=".$od_id."&memo=반품_적립취소";
                     $postdata2 = golfu_Encrypt_EnCode($data2, $agent);
                     $senddata2 = "agent=".$agent."&postdata=".urlencode($postdata2);
                     $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
                     $result2 = golfu_HTTP_CURL($host, $senddata2);
                     $res_dec2 = json_decode($result2);
                     if($res_dec2->success){//true or false
                         $ma_result2 = "success";
	                 }else {
                         $ma_result2 = "fail";
	                 }
	                 $value['c_type'] = "102";//포인트 차감
                     $value['url'] = $data2; 
                     $value['return_code'] = $ma_result2; //응답코드
                     $value['call_date'] = TB_TIME_YMDHIS;
                     insert("agency_log", $value);//DB에 insert하기
				}
               
			} //pt_id =golfu close

            // 2021-08-09
            else if($pt_id == 'golfrock'){
                $stotal = get_order_spay($od_id);//총계
                $i_usepoint = (int)$stotal['usepoint'];
                if($i_usepoint > 0) //적립금 사용 존재
                {
                    $member_id = explode("_",$mb_id);
                    $res_dec = golfrock_point('point_cancel', $member_id[1], $chk_data , $od_id,'골프용품', $i_usepoint );
                }
            }

            $od_sms_cancel_check++;
			$od_cancel_change++;
			break;
		case '8': // 교환완료
			//if($current_status != 5) continue;
			if($current_status == 8) continue;
			change_order_status_8($od_no);
			break;
		case '9': // 환불완료_운영자가 처리
			if(!in_array($current_status, array(2,3))) continue;
			change_order_status_9($od_no);
				
            // 2021-08-02
            if($tmp_od_no != "") $tmp_od_no .= ", ";
            $tmp_od_no .=  $od_no;

			if($pt_id == 'golf')
		    {
				   if($i == 0)// 1번만 실행되어야 한다. 
				   {
				         if(is_null($shop_no) && is_null($shopevent_no)){
                                  $shop_no3 = "27F0E34B60C08D0F34799E99601BB6FE";
						          $shopevent_no3 = "EEB1D1011079696148DBFC6731FA02A8";

						 }else{
                                  $shop_no2 = base64_encode($shop_no);
                                  $shop_no3 = jsonfy2($shop_no2);
    	  			              $shopevent_no2 = base64_encode($shopevent_no);
                                  $shopevent_no3 = jsonfy2($shopevent_no2);
						 }

                         $stotal = get_order_spay($od_id);//*****총계_관리자페이지 실행여부 확인필요
                         if($stotal['usepoint2'] > 0) //기본금 사용 존재
                         {
         
                              $mem_no = substr($mb_id,5);//hwelf100001865241
                              $mem_no2 = base64_encode($mem_no);
                              $mem_no3 = jsonfy2($mem_no2);
                              $proc_code = base64_encode("300");//취소
                              $proc_code2 = jsonfy2($proc_code);
				 
                              $order_no = base64_encode($od_id);
                              $order_no2 = jsonfy2($order_no);

                              $mem_nm = iconv("UTF-8","EUC-kr",$chk_data);//charset변환==>이처리가 정상적임. 
                              $mem_nm2 = base64_encode($mem_nm);
                              $mem_nm3 = jsonfy2($mem_nm2);	
				  
                              $use_point21 = base64_encode($stotal['usepoint2']);//토탈 기본금 사용액
	   	                      $use_point22 = jsonfy2($use_point21);	


                              $hdata = array(
                                'mem_id' => $mem_no3,
	                            'shopevent_no' => $shopevent_no3,
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

                              $load_string = simplexml_load_string($res);
		                      $ma_result = $load_string->return_code;
                              $value['c_type'] = "102";//포인트취소
                              $value['url'] = $url; 
                              $value['return_code'] = $ma_result; //응답코드
                              $value['call_date'] = TB_TIME_YMDHIS;
                              insert("hwelfare_log", $value);//DB에 insert하기
                              curl_close($ch);
     
	                     } // $stotal['usepoint2'] >0 close

						 if($cash_uresult == 'Y')//현금영수증 신청 성공 
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
					             $proc_sts = "102";//(101: 신청 , 102: 취소)
				                 $hdata_cash = array(
                                    'ORDER_NO' => $order_no2, //암호화
	    	                        'PROC_STS' => $proc_sts, 
						            'MEM_IDNT_VAL' =>  $mem_idnt_val, // 암호
		                            'SHOPEVENT_NO' => $shopevent_no3, 
	    	                        'MEM_NO' => $mem_no3,
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
							     $load_string = simplexml_load_string($res);
		                         $ca_result = $load_string->return_code;
		                         if($ca_result == '000')//완료(******od_no ->od_id)
	                             {
											
                                       $sql = "update shop_order set cash_cresult = 'Y' where od_id = '$od_id' ";
			                                sql_query($sql);
		                         }

								 $value['c_type'] = "402";//현금영수증 취소
                                 $value['url'] = $cashurl; 
                                 $value['return_code'] = $ca_result; //응답코드
                                 $value['call_date'] = TB_TIME_YMDHIS;
                                 insert("hwelfare_log", $value);//DB에 insert하기
 						         curl_close($ch);
				                        
				         }	//cash_uresult =Y close   
					  
				   } // for문 첫실행 close (******아래쪽으로 이동할 필요가 있다.현금영수증 취소도 1번 실행되어야 한다.)
				  
				   //1.현대리바트 포인트 취소 호출
                   //기본금사용의 존재유무 파악 필요
				 				       
				   //(taxsave_yes   Y:개인소득공제 , S: 사업자 지출 , N: 미발행)
				   if($magam_uresult == 'Y') //포인트사용 마감 성공
				   {
                         
                         $item_cd2 = base64_encode($item_cd); 
				         $item_cd3 = jsonfy2($item_cd2);

			             $mproc_code = base64_encode("20");//포인트 취소
                         $mproc_code2 = jsonfy2($mproc_code);

			             $order_dm = TB_TIME_YMD2;
   		                 $order_dm2 = base64_encode($order_dm);
                         $order_dm3 = jsonfy2($order_dm2);

				         $tax_gb = base64_encode("1");//과세여부 
                         $tax_gb2 = jsonfy2($tax_gb);
                         //goods_price,use_point2,use_price,sum_qty,item_nm,mda_gb(0:web ,1:mobile),deli_amt

                         $item_nm22 = iconv("UTF-8","EUC-kr", $item_nm);//charset변환 
                         $item_nm2 = base64_encode($item_nm22);
                         $item_nm3 = jsonfy2($item_nm2);

				         $saleprice = base64_encode($goods_price);
                         $saleprice2 = jsonfy2($saleprice);

				         $ux_point = base64_encode($use_point2);
                         $ux_point2 = jsonfy2($ux_point);

                         $etc_amt =  base64_encode($use_price);
				         $etc_amt2 =  jsonfy2($etc_amt);

				         $deli_amt2 =  base64_encode($deli_amt);
				         $deli_amt3 =  jsonfy2($deli_amt2);

				         $order_qty =  base64_encode($sum_qty);
				         $order_qty2 =  jsonfy2($order_qty);

				         $dc_price = base64_encode("0");//할인금액(**추후 다시 체크해볼것)
                         $dc_price2 = jsonfy2($dc_price);

				         $item_price = $goods_price/$sum_qty;//제품단가
                         $item_price2 =  base64_encode($item_price);
				         $item_price3 =  jsonfy2($item_price2);

				         $magam_gb = "101";//(마감기준 : 101 ->주문기준 , 102 ->배송기준)
				         if($mda_gb =='0'){
			                    $mda_gb2 = "101";
	                     }else{
			                    $mda_gb2 = "102";
		                 }
              
				         $hdata2 = array(
                             'ORDER_NO' => $order_no2,
	    	                 'ITEM_CD' => $item_cd3,
			                 'ORDER_GB' => $mproc_code2,
			                 'ORDER_DM' =>  $order_dm3,
			                 'SHOP_NO' => $shop_no3,
		                     'SHOPEVENT_NO' => $shopevent_no3,
	    	                 'MEM_NO' => $mem_no3,
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
	                     //echo("url:".$url2."<br>");
				            
				             
             	         $ch = curl_init();
		                 curl_setopt($ch, CURLOPT_URL, $url2);
		                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		                 curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
	                     curl_setopt($ch, CURLOPT_HEADER, 0);

		                 $res = curl_exec($ch);
                         $load_string2 = simplexml_load_string($res);
		                 $ma_result = $load_string2->return_code;
		                 if($ma_result == '000')//완료
	                     {
						       //*****index_no에 대한 처리 필요(orderform에서 넘기던가 해야함.)
                               $sql = "update shop_order set magam_cresult = 'Y' where od_no = '$od_no' ";
			                   sql_query($sql);
		                 }

						 $value['c_type'] = "202";//포인트취소_마감
                         $value['url'] = $url2; 
                         $value['return_code'] = $ma_result; //응답코드
                         $value['call_date'] = TB_TIME_YMDHIS;
                         insert("hwelfare_log", $value);//DB에 insert하기
		  		         curl_close($ch);
				           
		           }//magam_uresult = Y  close
    	    } //pt_id=golf close
			else if($pt_id =='golfu'){ //골프유 포인트 환원_20191220

				 $agent = "GOLFUNET";
                 $pass = "GOLFUNET!@#$";
                 $memId = substr($mb_id,6);//golfu_ID
				 $data = "exec=point&memId=".$memId."&pass=".$pass;
	             $data .= "&ptype=1&point=".$use_point."&pcode=14&orderId=".$od_id."&memo=관리자주문취소_환원";
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

            // 2021-08-09			
            else if($pt_id == 'golfrock'){
                $stotal = get_order_spay($od_id);//총계
                $i_usepoint = (int)$stotal['usepoint'];
                if($i_usepoint > 0) //적립금 사용 존재
                {
                    $member_id = explode("_",$mb_id);
                    $res_dec = golfrock_point('point_cancel', $member_id[1], $chk_data , $od_id,'골프용품', $i_usepoint );
                }
            }

			$od_sms_cancel_check++;
			$od_cancel_change++;
			break;
		case '10': // 반품신청
			if($current_status != 5) continue;
			change_order_status_10($od_no, $od_id);
			break;
		case '11': // 반품중
			if(!in_array($current_status, array(5,10))) continue;
			change_order_status_11($od_no, $delivery, $delivery_no);
			
			break;
		case '12': // 교환신청
			if($current_status != 5) continue;
			change_order_status_12($od_no, $od_id);
			break;
		case '13': // 교환중
			if(!in_array($current_status, array(5,12))) continue;
			change_order_status_13($od_no, $delivery, $delivery_no);
			//***현대리바트 회수완료 api 호출하기
			//$proc_dm = TB_TIME_YMD2 , 'PROC_STS' => '106'
			break;
	}
    
    // (2021-03-04)
    $dan_row = get_order($od_no,"dan");
    order_change_update($od_id, $od_no, $member['id'], $gw_status[$current_status], $gw_status[$dan_row['dan']] , ''  );
}
//현대리바트 호출url출력
//exit;
//------------------------------------------------------------------------------

//==============================================================================
// 문자전송
//------------------------------------------------------------------------------
if($od_sms_ipgum_check)
{
	icode_order_sms_send($pt_id, $od_hp, $od_id, 3); // 입금완료 문자
	//dreamline_order_sms_send($od_id, 1);
	dabonem_order_sms_send($od_id, 2);
}
if($od_sms_baesong_check)
{
	icode_order_sms_send($pt_id, $od_hp, $od_id, 4); // 배송중 문자
	//dreamline_order_sms_send($od_id, 2);
	dabonem_order_sms_send($od_id, 4, "and od_no in ($tmp_od_no_4)");
}
if($od_sms_delivered_check)
{
	icode_order_sms_send($pt_id, $od_hp, $od_id, 6); // 배송완료 문자
}
if($od_sms_cancel_check)
{
	icode_order_sms_send($pt_id, $od_hp, $od_id, 5); // 주문취소 문자
    dabonem_order_sms_send($od_id, '7', "and od_no in ($tmp_od_no)");
}
//------------------------------------------------------------------------------

// 상품 모두 취소일 경우 주문상태 변경
$mod_history = '';
$pg_res_cd = '';
$pg_res_msg = '';
$pg_cancel_log = '';

if($od_cancel_change) {
	$sql = " select COUNT(*) as od_count1,
                    SUM(IF(dan = '6' OR dan = '7' OR dan = '9', 1, 0)) as od_count2,
					SUM(refund_price) as od_refund_price,
					SUM(use_price) as od_receipt_price
			   from shop_order
			  where od_id = '$od_id' ";
    $row = sql_fetch($sql);

	if($row['od_count1'] == $row['od_count2']) {
		// PG 신용카드 결제 취소일 때
		$od_receipt_price = $row['od_receipt_price'];
		if($od_receipt_price > 0 && $row['od_refund_price'] == 0) {
			$sql = " select * from shop_order where od_id = '$od_id' ";
			$od = sql_fetch($sql);

			if($od['od_tno'] && ($od['paymethod'] == '신용카드' || $od['paymethod'] == '간편결제' || $od['paymethod'] == '복지카드' || $od['paymethod'] == 'KAKAOPAY') || ($od['od_pg'] == 'inicis' && $od['paymethod'] == '삼성페이')) {
				// 가맹점 PG결제 정보
				$default = set_partner_value($od['od_settle_pid']);

				switch($od['od_pg']) {
					case 'lg':
						include_once(TB_SHOP_PATH.'/settle_lg.inc.php');

						$LGD_TID = $od['od_tno'];

						$xpay = new XPay($configPath, $CST_PLATFORM);

						// Mert Key 설정
						$xpay->set_config_value('t'.$LGD_MID, $default['de_lg_mert_key']);
						$xpay->set_config_value($LGD_MID, $default['de_lg_mert_key']);

						$xpay->Init_TX($LGD_MID);

						$xpay->Set('LGD_TXNAME', 'Cancel');
						$xpay->Set('LGD_TID', $LGD_TID);

						if($xpay->TX()) {
							$res_cd = $xpay->Response_Code();
							if($res_cd != '0000' && $res_cd != 'AV11') {
								$pg_res_cd = $res_cd;
								$pg_res_msg = $xpay->Response_Msg();
							}
						} else {
							$pg_res_cd = $xpay->Response_Code();
							$pg_res_msg = $xpay->Response_Msg();
						}
						break;
					case 'inicis':
						include_once(TB_SHOP_PATH.'/settle_inicis.inc.php');
						$cancel_msg = iconv_euckr('쇼핑몰 운영자 승인 취소');

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
						$inipay->SetField("admin",     $default['de_inicis_admin_key']); // 비대칭 사용키 키패스워드
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
							$pg_res_cd = $res_cd;
							$pg_res_msg = iconv_utf8($res_msg);
						}
						break;
					case 'KAKAOPAY':
						include_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
						$_REQUEST['TID']               = $od['od_tno'];
						$_REQUEST['Amt']               = $od_receipt_price;
						$_REQUEST['CancelMsg']         = '쇼핑몰 운영자 승인 취소';
						$_REQUEST['PartialCancelCode'] = 0;
						include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
						break;
					case 'kcp':
						//include_once(TB_SHOP_PATH.'/settle_kcp.inc.php');

					    if($od['paymethod'] == '복지카드'){ //복지카드 설정_20200518
                            include_once(TB_SHOP_PATH.'/settle_kcp2.inc.php');
			                $default['de_kcp_mid'] = "A8HRJ";
                            $default['de_kcp_site_key'] = '3Bbeo5luAlZqUwvsowTZ-y6__';
			            }else{
			                include_once(TB_SHOP_PATH.'/settle_kcp.inc.php');

		             	}
						require_once(TB_SHOP_PATH.'/kcp/pp_ax_hub_lib.php');

						// locale ko_KR.euc-kr 로 설정
						setlocale(LC_CTYPE, 'ko_KR.euc-kr');

						$c_PayPlus = new C_PP_CLI_T;

						$c_PayPlus->mf_clear();

						$tno = $od['od_tno'];
						$tran_cd = '00200000';
						$cancel_msg = iconv_euckr('쇼핑몰 운영자 승인 취소');
						$cust_ip = $_SERVER['REMOTE_ADDR'];
						$bSucc_mod_type = "STSC";

						$c_PayPlus->mf_set_modx_data( "tno",      $tno );  // KCP 원거래 거래번호
						$c_PayPlus->mf_set_modx_data( "mod_type", $bSucc_mod_type );  // 원거래 변경 요청 종류
						$c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip );  // 변경 요청자 IP
						$c_PayPlus->mf_set_modx_data( "mod_desc", $cancel_msg );  // 변경 사유

						$c_PayPlus->mf_do_tx( $tno,  $g_conf_home_dir, $g_conf_site_cd,
											  $g_conf_site_key,  $tran_cd,    "",
											  $g_conf_gw_url,  $g_conf_gw_port,  "payplus_cli_slib",
											  $ordr_idxx, $cust_ip, "3" ,
											  0, 0, $g_conf_key_dir, $g_conf_log_dir);

						$res_cd  = $c_PayPlus->m_res_cd;
						$res_msg = $c_PayPlus->m_res_msg;

						if($res_cd != '0000') {
							$pg_res_cd = $res_cd;
							$pg_res_msg = iconv_utf8($res_msg);
						}

						// locale 설정 초기화
						setlocale(LC_CTYPE, '');
						break;
				}

				// PG 취소요청 성공했으면
				if($pg_res_cd == '') {
					$pg_cancel_log = ' PG 신용카드 승인취소 처리';

					// 전체취소
					$sql = " select index_no from shop_order where od_id = '$od_id' order by index_no asc ";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {
						$sql = " update shop_order
									set refund_price = use_price
								  where index_no = '{$row['index_no']}' ";
						sql_query($sql);
					}
				}
			}
		}

		// 관리자 주문취소 로그
		$mod_history = TB_TIME_YMDHIS.' '.$member['id'].' 주문취소 처리'.$pg_cancel_log."\n";
    }
}

if($mod_history) { // 주문변경 히스토리 기록
	$sql = " update shop_order
				set od_mod_history = CONCAT(od_mod_history,'$mod_history')
			  where od_id = '$od_id' ";
	sql_query($sql);
}

$url = TB_ADMIN_URL."/pop_orderform.php?od_id=$od_id";

// 신용카드 취소 때 오류가 있으면 알림
if($pg_res_cd && $pg_res_msg) {
    alert('오류코드 : '.$pg_res_cd.' 오류내용 : '.$pg_res_msg, $url);
}

goto_url($url);
?>
