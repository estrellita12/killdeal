<?php
if(!defined('_TUBEWEB_')) exit;

// 회원 CRM 탭메뉴
function mb_pg_anchor($mb_id) {
?>
<ul class="anchor">
	<li><a href="./pop_memberform.php?mb_id=<?php echo $mb_id; ?>">회원정보수정</a></li>
	<?php if(is_seller($mb_id)) { ?>
	<li><a href="./pop_sellerform.php?mb_id=<?php echo $mb_id; ?>">공급사정보수정</a></li>
	<li><a href="./pop_sellerorder.php?mb_id=<?php echo $mb_id; ?>">공급사판매내역</a></li>
	<?php } ?>
	<li><a href="./pop_memberorder.php?mb_id=<?php echo $mb_id; ?>">주문내역</a></li>
	<li><a href="./pop_memberpoint.php?mb_id=<?php echo $mb_id; ?>">포인트</a></li>
</ul>
<?php
}
?>


<?php
// (2021-02-05) 새로운 탭 생성
function pt_pg_anchor($mb_id,$use_pg) {
?>
<ul class="anchor">
    <li><a href="./pop_partner_info.php?mb_id=<?php echo $mb_id; ?>">가맹점정보수정</a></li>
    <li><a href="./pop_partner_config.php?mb_id=<?php echo $mb_id; ?>">연동정보설정</a></li>
    <li><a href="./pop_partner_meta.php?mb_id=<?php echo $mb_id; ?>">검색엔진최적화설정</a></li>
    <li><a href="./pop_partner_sns.php?mb_id=<?php echo $mb_id; ?>">소셜네트워크설정</a></li>
    <li><a href="./pop_partner_logo.php?mb_id=<?php echo $mb_id; ?>">로고설정</a></li>
    <?php if( $use_pg ) { // 개별 PG결제 권한이있나? ?>
    <li><a href="./pop_partner_pg.php?mb_id=<?php echo $mb_id; ?>">전자결제설정</a></li>
    <?php } ?>
</ul>
<?php
}
?>


