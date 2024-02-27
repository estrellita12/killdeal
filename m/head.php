<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

//20200819 아이멤버스 폐쇄몰
if($pt_id == 'imembers'){
	if(get_session('ss_mb_id')==''){
		goto_url('https://www.imembers.co.kr/bbs/login.php');
	}	
}


if($pt_id == "golf"){
  if(!get_session('hmem_chk'))//현대리바트 회원 h골프 동의 체크여부
  {
            if(!empty($_GET['MEM_NO']) && !empty($_GET['SHOPEVENT_NO'])){//값이 존재하면
		    
			     $mem_no = base64_decode(jsonfy($_GET['MEM_NO']));
			     set_session('ss_mb_id', $mem_no);
			     set_session('mem_no', $mem_no);//회원번호
                 set_session('cashreceipt_yn' , $_GET['CASHRECEIPT_YN']);

	             $mem_nm = base64_decode(jsonfy($_GET['MEM_NM']));
			     $mem_nm2 = iconv("EUC-KR","UTF-8",$mem_nm); 
			     set_session('mem_nm', $mem_nm2);//회원명
	             $shopevent_no = base64_decode(jsonfy($_GET['SHOPEVENT_NO']));
			     set_session('shopevent_no', $shopevent_no);//상점번호
		         $shop_no = base64_decode(jsonfy($_GET['SHOP_NO']));
			     set_session('shop_no', $shop_no);//행사번호
			     //mem_no를 조회후 처음접근시 동의체크 페이지, 추후에는 메인페이지가 보여진다.
			     $mb = get_hwelfare_member($mem_no);//현대리바트 등록여부 확인
              	 if(!$mb['mem_id']){
				      goto_url(TB_MBBS_URL.'/register2.php?url='.$urlencode);
			     }else {
                      set_session('hmem_chk', 'yy');//체크  
					   if(!empty($_GET['RETURN_URL'])){
                          //현대리바트_배너클릭시 이동URL
						   $move =  $_GET['RETURN_URL']; 
						  
						   ?>
                            <script>
                            var tt = decodeURIComponent("<? echo $move;?>");
					        location.replace(tt);

						    </script>
					      <?
					  }

				 }
			}
			else{
                  
                 $hmem_no = base64_encode(get_session("mem_no"));
                 $hmem_no2 = jsonfy2($hmem_no);
				 $hmb = get_hwelfare_member($hmem_no2);//현대리바트 등록여부 확인
              	 if(!$hmb['mem_id']){
				    //동의 체크 페이지로 이동한다.
				    goto_url(TB_MBBS_URL.'/register2.php?url='.$urlencode);
			     }else{
                    set_session('hmem_chk', 'yy');//체크             
				 }
       		}
			
		    
    }
	else {
		          if(!empty($_GET['RETURN_URL'])){
                       $move = $_GET['RETURN_URL'];
                        //echo($move2);
					   ?>
					    <script>
                            var tt = decodeURIComponent("<? echo $move;?>");
					        location.replace(tt);

						</script>
    
					   <?

 				  }
       
    }
	
}// pt_id = golf close
if($member['id'] != "admin")
{
    if($pt_id == "golf") 
    {
		 //header("Content-Type:text/html;charset=euc-kr");
         if(!get_session('mem_no')) //shopevent_no , shop_no
	     {
			
		     exit('restrict access');//한글 출력시 문자캐릭터셋 변경 필요
			
         }
    }
}



include_once(TB_MPATH."/head.sub.php");

// (2020-12-10) 이츠골프와 리프레쉬클럽 스킨 디자인 파일 합침
if($pt_id == 'itsgolf' || $pt_id == 'refreshclub' || $pt_id == 'teeshot'){
	include_once(TB_MTHEME_PATH.'/head_v2.skin.php');
}else if($pt_id=="kimcaddie"){
	include_once(TB_MTHEME_PATH.'/head_v3.skin.php');
}else{
	include_once(TB_MTHEME_PATH.'/head.skin.php');
}


if(defined('_MINDEX_')) { // index에서만 실행
    //include_once(TB_MPATH."/popup.inc.php"); // 팝업
    include_once(TB_MPATH.'/popup.inc.php'); // 팝업레이어
}
//(2020-12-10) 마니아몰 링크타고 들어오면 모든 페이지에서 팝업
else if($pt_id=='maniamall' && $_GET['popup']=='yes'){
    include_once(TB_MPATH.'/maniamall_popup.inc.php'); // 팝업레이어
}



?>


