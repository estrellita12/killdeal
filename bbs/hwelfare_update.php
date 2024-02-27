<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/register.lib.php');
include_once(TB_LIB_PATH.'/mailer.lib.php');


$value['mem_id'] = get_session('mem_no');//회원번호(아이디)
$value['mem_nm'] = get_session('mem_nm');//회원명

insert("hwelfare_member", $value);//DB에 insert하기
   
set_session('hmem_chk', 'yy');//체크
//메인페이지 이동
goto_url(TB_URL);

?>