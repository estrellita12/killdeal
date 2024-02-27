<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="smb_my">

   <?if($pt_id !='golfu'){ ?>
	<section id="smb_my_ov">
        <h2>회원정보 개요</h2>
        <ul>
            <li>보유쿠폰<a href="<?php echo TB_MSHOP_URL; ?>/coupon.php"><?php echo display_qty($cp_count); ?></a></li>
            <li>보유포인트<a href="<?php echo TB_MSHOP_URL; ?>/point.php"><?php echo display_point($member['point']); ?></a></li>
        </ul>
        <dl>
            <dt>연락처</dt>
            <dd><?php echo ($member['telephone'] ? $member['telephone'] : '미등록'); ?></dd>
            <dt>E-Mail</dt>
            <dd><?php echo ($member['email'] ? $member['email'] : '미등록'); ?></dd>
            <dt>최종접속일시</dt>
            <dd><?php echo $member['today_login']; ?></dd>
            <dt>회원가입일시</dt>
            <dd><?php echo $member['reg_time']; ?></dd>
            <dt class="ov_addr">주소</dt>
            <dd class="ov_addr"><?php echo sprintf("(%s)", $member['zip']).' '.print_address($member['addr1'], $member['addr2'], $member['addr3'], $member['addr_jibeon']); ?></dd>
        </dl>
    </section>
    <?} ?>
    <section id="smb_my_od">
        <h2 class="anc_tit">최근 주문내역<span class="fr"><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" class="btn_txt">더보기<i class="fa fa-angle-right"></i></a></span></h2>
		<ul id="sod_inquiry">
			<?php
			$sql = " select *
					from shop_order
		   			where mb_id = '{$member['id']}'
			 		and dan <> '0'
		   			group by od_id
		   			order by index_no desc
		   			limit 0, 5 ";
			$result = sql_query($sql);

			for($i=0; $row=sql_fetch_array($result); $i++)
			{
				echo '<li>'.PHP_EOL;
				//shop_cart -> shop_order로 변경 //20200616
				$sql = " select * from shop_order where od_id = '$row[od_id]' ";
				$sql.= " order by index_no ";
				$res = sql_query($sql);

				for($k=0; $ct=sql_fetch_array($res); $k++) {
					//$rw = get_order($ct['od_no']); //전체를 불러와서 오류 발생 제거 20200616 

					$gs = unserialize($ct['od_goods']);

					$href = TB_MSHOP_URL.'/view.php?gs_id='.$ct['gs_id'];

					$dlcomp = explode('|', trim($ct['delivery']));

					$delivery_str = '';
					if($dlcomp[0] && $ct['delivery_no']) {
						$delivery_str = get_text($dlcomp[0]).' '.get_text($ct['delivery_no']);
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
    </section>

	<section id="smb_my_wish">
        <h2 class="anc_tit">최근 위시리스트<span class="fr"><a href="<?php echo TB_MSHOP_URL; ?>/wish.php" class="btn_txt">더보기<i class="fa fa-angle-right"></i></a></span></h2>
        <ul>
            <?php
            $sql = " select *
					   from shop_wish a, shop_goods b
                      where a.mb_id = '{$member['id']}'
                        and a.gs_id = b.index_no
                      order by a.wi_id desc
                      limit 0, 3 ";
            $result = sql_query($sql);
            for($i=0; $row=sql_fetch_array($result); $i++)
            {
                $image_w = 50;
                $image_h = 50;
                $image = get_it_image($row['gs_id'], $row['simg1'], $image_w, $image_h, true);
                $list_left_pad = $image_w + 10;
            ?>
            <li style="padding-left:<?php echo $list_left_pad + 10; ?>px">
                <div class="wish_img"><?php echo $image; ?></div>
                <div class="wish_info"><a href="<?php echo TB_MSHOP_URL; ?>/view.php?gs_id=<?php echo $row['gs_id']; ?>"><?php echo stripslashes($row['gname']); ?></a></div>
				<span class="info_date">보관일 <?php echo substr($row['wi_time'], 2, 8); ?></span>
            </li>
            <?php
            }
            if($i == 0) echo '<li class="empty_list">보관 내역이 없습니다.</li>';
            ?>
        </ul>
    </section>
</div>
