<?php
include_once("./_common.php");

$prev_url = $_SERVER["HTTP_REFERER"];

if(!$is_member) {
	alert("로그인 후 작성 가능합니다.",$prev_url);
}

$od_check = sel_count("shop_order", "where mb_id='{$member[id]}' and gs_id=$gs_id and dan in (2,3,4,5) ");
if(!$od_check && !$is_admin){
    //alert_close("주문 내역이 존재하지 않습니다.");
    //alert("리뷰 작성이 가능한 주문 내역이 존재하지 않습니다.",$prev_url);
    alert("리뷰 작성이 가능한 주문 내역이 존재하지 않습니다.","back");
}

$tb['title'] = '상품 리뷰 작성하기';
include_once(TB_MPATH."/head.sub.php");

$gs = get_goods($gs_id);

if($mode == "" || $mode == "w") {
	$mb_id      = $member['id'];
    $gs_id      = $gs_id;
    $seller_id  = $gs['mb_id'];
}
/*
else if($w == "u") {
	$re = sql_fetch("select * from shop_goods_review where index_no='$index_no'");
    $it_mid = $index_no;
	$score   = $re['score'];
	$memo    = $re['memo'];
	$fileurl1    = $re['fileurl1'];
}
*/
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);


$form_action_url = TB_HTTPS_MSHOP_URL.'/view_user_update.php';
include_once(TB_MTHEME_PATH.'/view_user_form.skin.php');
include_once(TB_MPATH."/tail.sub.php");
?>
