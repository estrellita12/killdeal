<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/register.lib.php");

check_demo();
check_admin_token();

$mb_id = trim($_POST['mb_id']);
$mb = get_member($mb_id);

if(!$mb['id']) {
    alert('존재하지 않는 회원자료입니다.');
}

if($mb_id == 'admin') {
    alert('최고관리자는 수정하실 수 없습니다.');
}

if($member['id'] != 'admin' && $mb['grade'] <= $member['grade']) {
    alert('자신보다 레벨이 높거나 같은 회원은 수정할 수 없습니다.');
}

if($mb_id == $member['id'] && $mb_grade != $mb['grade']) {
    alert($mb_id.' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');
}


/*
unset($pfrm);
$pfrm['theme']			= $_POST['theme'];          //테마스킨
$pfrm['mobile_theme']	= $_POST['mobile_theme'];   //모바일테마스킨
$pfrm['payment']	    = $_POST['payment'];        //추가판매수수료
$pfrm['payflag']	    = $_POST['payflag'];        //수수료 단위
$pfrm['homepage']	    = $_POST['homepage'];       //개별 도메인
$pfrm['use_pg']		    = $_POST['use_pg'];         //개별PG결제허용
$pfrm['use_good']	    = $_POST['use_good'];       //개별상품판매허용
update("shop_member", $pfrm," where id='$mb_id'");
*/

/*
$pfrm['bank_name']		= $_POST['bank_name'];      //은행명
$pfrm['bank_account']	= $_POST['bank_account'];   //계좌번호
$pfrm['bank_holder']	= $_POST['bank_holder'];    //예금주명
$pfrm['usepoint_yes']	= $_POST['usepoint_yes'];   //
$pfrm['usepoint']	    = $_POST['usepoint'];       //
$pfrm['coupon_yes']		= $_POST['coupon_yes'];     //
$pfrm['gift_yes']	    = $_POST['gift_yes'];       //
$pfrm['non_mem_allow']	= $_POST['non_mem_allow'];  //
$pfrm['update_time']		= TB_TIME_YMDHIS;
*/

unset($pfrm);
$pfrm['saupja_yes']		    = $_POST['saupja_yes']; //쇼핑몰 사업자노출 여부
$pfrm['shop_name']			= $_POST['shop_name']; //쇼핑몰명
$pfrm['shop_name_us']		= $_POST['shop_name_us']; //쇼핑몰 영문명
$pfrm['company_type']		= $_POST['company_type']; //사업자유형
$pfrm['company_name']		= $_POST['company_name']; //회사명
$pfrm['company_saupja_no']  = $_POST['company_saupja_no']; //사업자등록번호
$pfrm['tongsin_no']		    = $_POST['tongsin_no']; //통신판매신고번호
$pfrm['company_tel']		= $_POST['company_tel']; //대표전화
$pfrm['company_fax']		= $_POST['company_fax']; //대표팩스
$pfrm['company_item']		= $_POST['company_item']; //업태
$pfrm['company_service']	= $_POST['company_service']; //종목
$pfrm['company_owner']		= $_POST['company_owner']; //대표자명
$pfrm['company_zip']		= $_POST['company_zip']; //사업장우편번호
$pfrm['company_addr']		= $_POST['company_addr']; //사업장주소
//$pfrm['company_hours']	= $_POST['company_hours']; //CS 상담가능시간
//$pfrm['company_lunch']	= $_POST['company_lunch']; //CS 점심시간
//$pfrm['company_close']	= $_POST['company_close']; //CS 휴무일
$pfrm['info_name']			= $_POST['info_name']; //정보책임자 이름
$pfrm['info_email']		    = $_POST['info_email']; //정보책임자 e-mail
$pfrm['shop_provision']		    = $_POST['shop_provision'];
$pfrm['shop_private']		    = $_POST['shop_private'];
$pfrm['shop_policy']		    = $_POST['shop_policy'];

update("shop_partner", $pfrm," where mb_id='$mb_id'");

//$pageName = basename($_SERVER['PHP_SELF']);
//partner_config_log($member['id'],$mb_id, $pageName,'가맹점 정보 수정',$pfrm);

goto_url(TB_ADMIN_URL.'/pop_partner_info.php?mb_id='.$mb_id);

?>
