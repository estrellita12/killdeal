<?php
include_once("./_common.php");

if(!$config['coupon_yes']) {
    alert_close("쿠폰사용이 중지 되었습니다.");
}

if(!$is_member) {
	alert_close("회원 전용 서비스입니다.");
}

if(!$lo_id) {
	alert_close("올바른 방법으로 이용해 주십시오.");
}

$tb['title'] = '쿠폰적용 상품';
include_once(TB_PATH."/head.sub.php");

$sql_search = sql_coupon_log($lo_id);
$sql_common = sql_goods_list($sql_search);
$sql_order = " order by index_no desc ";

$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 35;
$mod = 5; // 가로 출력 수
$td_width = (int)(100 / $mod);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_THEME_PATH.'/coupon_goods.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>