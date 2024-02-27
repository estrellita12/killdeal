<?php
if(!defined('_TUBEWEB_')) exit;

//20200819 ���̸���� ����
if($pt_id == 'imembers'){
	if(get_session('ss_mb_id')==''){
		goto_url('https://www.imembers.co.kr/bbs/login.php');
	}	
}

if($pt_id == "golf"){

  
  if(!get_session('hmem_chk'))//���븮��Ʈ ȸ�� h���� ���� üũ����
  {
            if(!empty($_GET['MEM_NO']) && !empty($_GET['SHOPEVENT_NO'])){//���� �����ϸ�
		    
			     $mem_no = base64_decode(jsonfy($_GET['MEM_NO']));
			     set_session('ss_mb_id', $mem_no);
			     set_session('mem_no', $mem_no);//ȸ����ȣ
				 set_session('cashreceipt_yn' , $_GET['CASHRECEIPT_YN']);//���ݿ����� ��뿩��(Y: ��� , N: �Ұ�)

				
	             $mem_nm = base64_decode(jsonfy($_GET['MEM_NM']));
			     $mem_nm2 = iconv("EUC-KR","UTF-8",$mem_nm); 
			     set_session('mem_nm', $mem_nm2);//ȸ����
	             $shopevent_no = base64_decode(jsonfy($_GET['SHOPEVENT_NO']));
			     set_session('shopevent_no', $shopevent_no);//������ȣ
		         $shop_no = base64_decode(jsonfy($_GET['SHOP_NO']));
			     set_session('shop_no', $shop_no);//����ȣ
				
			     $mb = get_hwelfare_member($mem_no);//���븮��Ʈ ��Ͽ��� Ȯ��
              	 if(!$mb['mem_id']){
				      goto_url(TB_BBS_URL.'/register2.php?url='.$urlencode);
			     }else {
                      set_session('hmem_chk', 'yy');//üũ  
					  if(!empty($_GET['RETURN_URL'])){
                           //return_url ���ڵ� �� ������ ��ȯ
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
		
	   } // h���� ȸ�� close
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



if($pt_id == "golf") 
{
		 //header("Content-Type:text/html;charset=euc-kr");
         if(!get_session('mem_no')) //shopevent_no , shop_no
	     {
			 //echo get_session('admin_ss_mb_id');
		     exit('restrict access !');//�ѱ� ��½� ����ĳ���ͼ� ���� �ʿ�
			
         }else{ // (2021-02-17) login  X
            $is_member = 1;
        }
}

if($pt_id == 'kimcaddie'){
    $httpOrigin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
    if(in_array($httpOrigin, array("https://kimcaddie.com"))) header("Access-Control-Allow-Origin: {$httpOrigin}");
    header('Access-Control-Allow-Credentials: true');
}

$main_banner_use = true;
if( $pt_id == "golfit" ){
    $main_banner_use = false;
}

include_once(TB_PATH.'/head.sub.php');

// (2020-12-10)� ���� ��� �� �� 
include_once(TB_THEME_PATH.'/head.skin.php');

?>
