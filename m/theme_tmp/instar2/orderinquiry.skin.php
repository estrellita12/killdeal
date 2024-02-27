<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="sod_v">
	<div id="sod_fin_no">
		<strong>총 <?php echo number_format($total_count); ?>건</strong>의 주문내역이 있습니다.
	</div>
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
	<div class="m_baesong_wrap">
		<ul class="m_baesong_list">
			<li class="<?php if($row['dan0'] != 0) echo "active" ?>">결제완료<span>(<?php echo $row['dan0'] ?>)</span></li>
			<li class="<?php if($row['dan1'] != 0) echo "active" ?>">배송준비중<span>(<?php echo $row['dan1'] ?>)</span></li>
			<li class="<?php if($row['dan2'] != 0) echo "active" ?>">배송중<span>(<?php echo $row['dan2'] ?>)</span></li>
			<li class="<?php if($row['dan3'] != 0) /*echo "active"*/ ?>">배송완료<span style="color:#000;">(<?php echo $row['dan3'] ?>)</span></li>
		</ul>
		<span class="cancel_list" style="color:#000;">취소/환불<span class="cancel_cnt" style="color:#ed3636;">(<?php echo $row['dan4'] + $row['dan5'] + $row['dan6']; ?>)</span></span>
	</div>
	<?php } ?>
	<div id="sod_inquiry">			
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++)
		{			
			echo '<li>'.PHP_EOL;
			//shop_cart -> shop_order 로 변경 20200616 
			$sql = " select * from shop_order where od_id = '$row[od_id]' ";
			$sql.= " order by index_no ";
			
			$res = sql_query($sql);

			for($k=0; $ct=sql_fetch_array($res); $k++) {

				//$rw = get_order($ct['od_no']); 20200616 오류로 인해 사용 안함
				$gs = unserialize($ct['od_goods']);

				$href = TB_MSHOP_URL.'/view.php?gs_id='.$ct['gs_id'];
				
				$dlcomp = explode('|', trim($ct['delivery']));
				
				$delivery_str = '';
				if($dlcomp[0] && $ct['delivery_no']) {
					$delivery_str = get_text($dlcomp[0]).' '.get_text($ct['delivery_no']);
				}
               if($dlcomp[1] && $ct['delivery_no']) {
                    $delivery_str .= "<br>";
                    $delivery_str .= get_delivery_inquiry($ct['delivery'], $ct['delivery_no'], 'btn_ssmall bx-white');
                }

				$uid = md5($ct['od_id'].$ct['od_time'].$ct['od_ip']);

				if($k == 0) {
		?>	        
            <div class="inquiry_idtime">
                <a href="<?php echo TB_MSHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $ct['od_id']; ?>&uid=<?php echo $uid; ?>" class="idtime_link"><?php echo $ct['od_id']; ?></a>
                <span class="idtime_time"><?php echo substr($ct['od_time'],2,8); ?></span>
            </div>
			<?php } ?>
			<div class="inquiry_info">
				<div class="inquiry_name">
					<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
				</div>
				<div class="inquiry_price">
					<?php echo display_price($ct['use_price']); ?>
				</div>
				<div class="inquiry_inv">
					<span class="inv_status"><?php echo $gw_status[$ct['dan']]; ?></span>
					<span class="inv_inv"><?php echo $delivery_str; ?></span>
				</div>
			</div>
		
		<?php
			}
			echo '</li>'.PHP_EOL;
		}

        if($i == 0)
            echo '<li class="empty_list">주문 내역이 없습니다.</li>';
        ?>
    </ul>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>

	</div>
</div>
