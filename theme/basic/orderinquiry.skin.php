<?php
if(!defined('_TUBEWEB_')) exit;

if($pt_id !='golfu'){ //골프유닷넷_좌측메뉴X_20200106
include_once(TB_THEME_PATH.'/aside_my.skin.php');
}
?>

<div id="con_lf" class="inquiry_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>
		<?php if($is_member) {
		$sql = " select count(if(dan = 2,dan,null)) as dan0, 
										count(if(dan = 3,dan,null)) as dan1,
										count(if(dan = 4,dan,null)) as dan2,
										count(if(dan = 5,dan,null)) as dan3,
										count(if(dan = 6,dan,null)) as dan4,
										count(if(dan = 7,dan,null)) as dan5,
										count(if(dan = 9,dan,null)) as dan6,
										count(dan) as tot_dan
										from shop_order where mb_id = '{$member['id']}' ";
		$res = sql_query($sql);
		$row = sql_fetch_array($res);
		?>
	<div class="baesong_wrap">
		<ul class="baesong_list">
			<li class="<?php if($row['dan0'] != 0) echo "active" ?>">결제완료<span>(<?php echo $row['dan0'] ?>)</span></li>
			<li class="<?php if($row['dan1'] != 0) echo "active" ?>">배송준비중<span >(<?php echo $row['dan1'] ?>)</span></li>
			<li class="<?php if($row['dan2'] != 0) echo "active" ?>">배송중<span>(<?php echo $row['dan2'] ?>)</span></li>
			<li>배송완료<span>(<?php echo $row['dan3'] ?>)</span></li>
		</ul>
		<span class="cancel_list">취소/환불<span class="cancel_cnt" style="padding-left:3px;color:#ed3636;">(<?php echo $row['dan4'] + $row['dan5'] + $row['dan6']; ?>)</span></span>
	</div>
	<?php } ?>
	<h2 class="anc_tit">상세보기 버튼을 클릭하시면 주문상세내역을 조회하실 수 있습니다.</h2>
	<div class="tbl_head02 tbl_wrap">
		<table>
			<caption>주문상세내역 및 상품정보</caption>
		<colgroup>
			<col class="w110">
			<col>
			<col class="w120">
			<col class="w140">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">주문일자</th>
			<th scope="col">상품정보</th>
			<th scope="col">결제금액</th>
			<th scope="col">상태</th>
		</tr>
		</thead>
		<tbody>
		<?php
		
		for($i=0; $row=sql_fetch_array($result2); $i++) {
			$sql = " select * from shop_order where od_id = '$row[od_id]' ";
			$sql.= " order by index_no ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res)+1;
			
			for($k=0; $ct=sql_fetch_array($res); $k++) {
				//$od = get_order($ct['od_no']); //사용 안함 전체 주문서에서 불러와서 오류 발생 20200616
				$gs = unserialize($ct['od_goods']);
				$it_options = print_complete_options($ct['gs_id'], $ct['od_id']);

				if($it_options){
					$it_options = '<div class="sod_opt">'.$it_options.'</div>';
				}

				$hash = md5($ct['gs_id'].$ct['od_no'].$ct['od_id']);
				$dlcomp = explode('|', trim($ct['delivery']));
				$href = TB_SHOP_URL.'/view.php?index_no='.$ct['gs_id'];

				if($k == 0) {
		?>
		<tr>
			<td class="tac" rowspan="<?php echo $rowspan; ?>">
				<p class="bold"><?php echo substr($row['od_time'],0,10);?></p>
				<p class="padt5"><a href="<?php echo TB_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $ct['od_id']; ?>" class="btn_small grey">상세보기</a></p>
			</td>
		</tr>
		<?php } ?>
		<tr class="rows">
			<td>
				<div class="ini_wrap">
					<table class="wfull">
					<colgroup>
						<col class="w70">
						<col>
					</colgroup>
					<tr>
						<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($ct['od_id'], $gs['simg1'], 60, 60); ?></a></td>
						<td class="vat tal">
							<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
							<?php echo $it_options ?>
							<p class="padt3 fc_999">주문번호 : <?php echo $ct['od_id']; ?> / 수량 : <?php echo display_qty($ct['sum_qty']); ?> / 배송비 : <?php echo display_price($od['baesong_price']); ?></p>
							<?php if($ct['dan'] == 5) { ?>
							<p class="padt3">
								<?php if(is_null_time($ct['user_date'])) { ?>
								<a href="javascript:final_confirm('<?php echo $hash; ?>');" class="btn_ssmall red">구매확정</a>
								<a href="<?php echo TB_SHOP_URL; ?>/orderchange.php?gs_id=<?php echo $ct['gs_id']; ?>&od_id=<?php echo $ct['od_id']; ?>&q=반품" onclick="win_open(this, 'winorderreturn', '650', '530','yes');return false;" class="btn_ssmall bx-white">반품신청</a>
								<a href="<?php echo TB_SHOP_URL; ?>/orderchange.php?gs_id=<?php echo $ct['gs_id']; ?>&od_id=<?php echo $ct['od_id']; ?>&q=교환" onclick="win_open(this, 'winorderchange', '650', '530','yes');return false;" class="btn_ssmall bx-white">교환신청</a>
								<?php } ?>
								<!-- <a href="<?php echo TB_SHOP_URL; ?>/orderreview.php?gs_id=<?php echo $ct['gs_id']; ?>&od_id=<?php echo $od['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">구매후기 작성</a> -->
                                <a href="<?php echo TB_SHOP_URL; ?>/view_user_form.php?gs_id=<?php echo $ct['gs_id']; ?>" onclick="win_open(this, 'winorderreview', '700', '600','yes');return false;" class="btn_ssmall bx-white">리뷰작성</a>
							</p>
							<?php } ?>
						</td>
					</tr>
					</table>
				</div>
			</td>
			<td class="tac mypage_price"><?php echo display_price($ct['use_price']); ?></td>
			<td class="tac">
				<p><?php echo $gw_status[$ct['dan']]; ?></p>
				<?php if($dlcomp[0] && $ct['delivery_no']) { ?>
				<p class="padt3 fc_90 fs13"><?php echo $dlcomp[0]; ?><br><?php echo $ct['delivery_no']; ?></p>
				<?php } ?>
				<?php if($dlcomp[1] && $ct['delivery_no']) { ?>
				<p class="padt3"><?php echo get_delivery_inquiry($ct['delivery'], $ct['delivery_no'], 'btn_ssmall'); ?></p>
				<?php } ?>
			</td>
		</tr>
		<?php }
		}
		if($i==0)
			echo '<tr><td colspan="4" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
