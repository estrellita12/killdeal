<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<script>




		$(document).ready(function(){



			$.ajax({

		              url : 'https://giftdev.e-hyundai.com/hb2efront_new/pointOpenAPI.do',
					  //url : 'hprox.php', //crossDomain문제로 Prox서버를 담당하는 페이지 호출
		              type : 'GET',
                      data : {
			                     mem_id : '<?php echo $mem_no2;?>',
                                 shopevent_no : '<?php echo $shopevent_no2;?>',
								 proc_code : '<?php echo $proc_code2;?>',
								 chk_data : '<?php echo $mem_nm3;?>',
							     point : '<?php echo $u_point2;?>',  //포인트사용건 합산 금액 필요
								 order_no : '<?php echo $order_no2;?>' ,
                                 media_cd : 'MW'
		                     },
				      dataType : 'XML',
		              success : function(result){
							 alert("success");

					  },
					  error: function(xhr, status, error) {
			                 alert(error);
		               }
	        });




		});








</script>

<!--

  20190630_마감호출 시점

1. 주문과 동시에 마감호출

     ->public_html/theme/orderinquiryview2.skin.php

	   php : curl호출(포인트사용 or 취소)
	   스크립트 ready() : 마감호출 api

2. 일단위 마감호출

     ->별도페이지 생성후 crontab에 등록

의문사항: 같은 주문번호내 A상품,B상품 주문후 B상품 취소하는 경우

-->


<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">주문번호 <em><?php echo $od_id; ?></em> 의 상세내역입니다.</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w90">
			<col>
			<col class="w100">
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
        $st_count1 = $st_count2 = $st_cancel_price = 0;
        $custom_cancel = false;

		$sql = " select * from shop_order where od_id = '$od_id' group by od_id order by index_no desc ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$sql = " select * from shop_cart where od_id = '$od_id' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res) + 1;

			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$rw = get_order($ct['od_no']);
				$gs = unserialize($rw['od_goods']);

				$hash = md5($rw['gs_id'].$rw['od_no'].$rw['od_id']);
				$dlcomp = explode('|', trim($rw['delivery']));

				$href = TB_SHOP_URL.'/view.php?index_no='.$rw['gs_id'];

				unset($it_name);
				$it_options = print_complete_options($ct['gs_id'], $ct['od_id']);
				if($it_options){
					$it_name = '<div class="sod_opt">'.$it_options.'</div>';
				}

				if($k == 0) {
		?>
		<tr>
			<td class="tac" rowspan="<?php echo $rowspan; ?>">
				<p class="bold"><?php echo substr($rw['od_time'],0,10);?></p>
				<p class="padt5"><a href="<?php echo TB_SHOP_URL; ?>/orderprint.php?od_id=<?php echo $rw['od_id']; ?>" onclick="win_open(this,'winorderprint','670','600','yes');return false;" class="btn_small bx-white"><i class="fa fa-print fs14 vam marb2 marr3"></i> 인쇄</a></p>
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
						<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($rw['od_id'], $gs['simg1'], 60, 60); ?></a></td>
						<td class="vat tal">
							<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
							<?php echo $it_name; ?>
							<p class="padt3 fc_999">주문번호 : <?php echo $rw['od_id']; ?> / 수량 : <?php echo display_qty($rw['sum_qty']); ?> / 배송비 : <?php echo display_price($rw['baesong_price']); ?></p>
							<?php if($rw['dan'] == 5) { ?>
							<p class="padt3">
								<?php if(is_null_time($rw['user_date'])) { ?>
								<a href="javascript:final_confirm('<?php echo $hash; ?>');" class="btn_ssmall red">구매확정</a>
								<a href="<?php echo TB_SHOP_URL; ?>/orderchange.php?gs_id=<?php echo $rw['gs_id']; ?>&od_id=<?php echo $rw['od_id']; ?>&q=반품" onclick="win_open(this, 'winorderreturn', '650', '530','yes');return false;" class="btn_ssmall bx-white">반품신청</a>
								<a href="<?php echo TB_SHOP_URL; ?>/orderchange.php?gs_id=<?php echo $rw['gs_id']; ?>&od_id=<?php echo $rw['od_id']; ?>&q=교환" onclick="win_open(this, 'winorderchange', '650', '530','yes');return false;" class="btn_ssmall bx-white">교환신청</a>
								<?php } ?>
								<a href="<?php echo TB_SHOP_URL; ?>/orderreview.php?gs_id=<?php echo $rw['gs_id']; ?>&od_id=<?php echo $rw['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">구매후기 작성</a>
							</p>
							<?php } ?>
						</td>
					</tr>
					</table>
				</div>
			</td>
			<td class="tar"><?php echo display_price($rw['use_price']); ?></td>
			<td class="tac">
				<p><?php echo $gw_status[$rw['dan']]; ?></p>
				<?php if($dlcomp[0] && $rw['delivery_no']) { ?>
				<p class="padt3 fc_90"><?php echo $dlcomp[0]; ?><br><?php echo $rw['delivery_no']; ?></p>
				<?php } ?>
				<?php if($dlcomp[1] && $rw['delivery_no']) { ?>
				<p class="padt3"><?php echo get_delivery_inquiry($rw['delivery'], $rw['delivery_no'], 'btn_ssmall'); ?></p>
				<?php } ?>
			</td>
		</tr>
		<?php
				$st_count1++;
				if(in_array($rw['dan'], array('1','2')))
					$st_count2++;

				$st_cancel_price += $rw['cancel_price'];
			}
		}

		// 주문상태가 배송중 이전 단계이면 고객 취소 가능
		if($st_count1 > 0 && $st_count1 == $st_count2)
			$custom_cancel = true;
		?>
		</tbody>
		</table>
	</div>

	<dl id="sod_ws_tot">
		<dt class="bt_nolne">주문총액</dt>
		<dd class="bt_nolne"><strong><?php echo display_price($stotal['price']); ?></strong></dd>
		<?php if($stotal['coupon']) { ?>
		<dt>쿠폰할인</dt>
		<dd><strong><?php echo display_price($stotal['coupon']); ?></strong></dd>
		<?php } ?>
		<?php if($stotal['usepoint']) { ?>
		<dt>포인트결제</dt>
		<dd><strong><?php echo display_point($stotal['usepoint']); ?></strong></dd>
		<?php } ?>
		<?php if($stotal['usepoint2']) { ?>
		<dt>현대복지포인트결제</dt>
		<dd><strong><?php echo display_point($stotal['usepoint2']); ?></strong></dd>
		<?php } ?>
		<?php if($stotal['baesong']) { ?>
		<dt>배송비</dt>
		<dd><strong><?php echo display_price($stotal['baesong']); ?></strong></dd>
		<?php } ?>
		<dt class="ws_price">총계</dt>
		<dd class="ws_price"><strong><?php echo display_price($stotal['useprice']); ?></strong></dd>
		<dt class="bt_nolne">포인트적립</dt>
		<dd class="bt_nolne"><strong><?php echo display_point($stotal['point']); ?></strong></dd>
	</dl>

	<section id="sod_fin_pay">
		<h2 class="anc_tit">결제정보</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">주문번호</th>
				<td><?php echo $od_id; ?></td>
			</tr>
			<tr>
				<th scope="row">주문일시</th>
				<td><?php echo $od['od_time']; ?></td>
			</tr>
			<tr>
				<th scope="row">결제방식</th>
				<td><?php echo ($easy_pay_name ? $easy_pay_name.'('.$od['paymethod'].')' : $od['paymethod']); ?></td>
			</tr>
			<tr>
				<th scope="row">결제금액</th>
				<td><?php echo display_price($stotal['useprice']); ?></td>
			</tr>
			<?php
			if(!is_null_time($od['receipt_time'])) {
			?>
			<tr>
				<th scope="row">결제일시</th>
				<td><?php echo $od['receipt_time']; ?></td>
			</tr>
			<?php
			}

			// 승인번호, 휴대폰번호, 거래번호
			if($app_no_subj) {
			?>
			<tr>
				<th scope="row"><?php echo $app_no_subj; ?></th>
				<td><?php echo $app_no; ?></td>
			</tr>
			<?php
			}

			// 계좌정보
			if($disp_bank) {
			?>
			<tr>
				<th scope="row">입금자명</th>
				<td><?php echo get_text($od['deposit_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">입금계좌</th>
				<td><?php echo get_text($od['bank']); ?></td>
			</tr>
			<?php
			}

			if($disp_receipt) {
			?>
			<tr>
				<th scope="row">영수증</th>
				<td>
					<?php
					if($od['paymethod'] == '휴대폰')
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
						} else if($od['od_pg'] == 'inicis') {
							$hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
						} else if($od['od_pg'] == 'kcp') {
							$hp_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'mcash_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
						}
					?>
					<a href="javascript:;" onclick="<?php echo $hp_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}

					if($od['paymethod'] == '신용카드')
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
						} else if($od['od_pg'] == 'inicis') {
							$card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
						} else if($od['od_pg'] == 'kcp') {
							$card_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'card_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
						}
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}

					if($od['paymethod'] == 'KAKAOPAY')
					{
						$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}

			// 현금영수증 발급을 사용하는 경우에만
			if($default['de_taxsave_use']) {
				// 미수금이 없고 현금일 경우에만 현금영수증을 발급 할 수 있습니다.
				if(!is_null_time($od['receipt_time']) && ($od['paymethod'] == '무통장' || $od['paymethod'] == '계좌이체' || $od['paymethod'] == '가상계좌')) {
			?>
			<tr>
				<th scope="row">현금영수증</th>
				<td>
				<?php
				if($od['od_cash'])
				{
					if($od['od_pg'] == 'lg') {
						require_once TB_SHOP_PATH.'/settle_lg.inc.php';

						switch($od['paymethod']) {
							case '계좌이체':
								$trade_type = 'BANK';
								break;
							case '가상계좌':
								$trade_type = 'CAS';
								break;
							default:
								$trade_type = 'CR';
								break;
						}
						$cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
					} else if($od['od_pg'] == 'inicis') {
						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/Cash_mCmReceipt.jsp?noTid='.$cash['TID'].'&clpaymethod=22\',\'showreceipt\',\'width=380,height=540,scrollbars=no,resizable=no\');';
					} else if($od['od_pg'] == 'kcp') {
						require_once TB_SHOP_PATH.'/settle_kcp.inc.php';

						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\''.TB_CASH_RECEIPT_URL.$default['de_kcp_mid'].'&orderid='.$od_id.'&bill_yn=Y&authno='.$cash['receipt_no'].'\', \'taxsave_receipt\', \'width=360,height=647,scrollbars=0,menus=0\');';
					}
				?>
					<a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>" class="btn_small">현금영수증 확인하기</a>
				<?php
				}
				else {
				?>
					<a href="javascript:;" onclick="window.open('<?php echo TB_SHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn_small">현금영수증 발급하기</a>
				<?php } ?>
				</td>
			</tr>
			<?php
				}
			}
			?>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_fin_orderer">
		<h2 class="anc_tit">주문하신 분</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tr>
				<th scope="row">이 름</th>
				<td><?php echo get_text($od['name']); ?></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><?php echo get_text($od['telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><?php echo get_text($od['cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">주 소</th>
				<td><?php echo get_text(sprintf("(%s)", $od['zip']).' '.print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon'])); ?></td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><?php echo get_text($od['email']); ?></td>
			</tr>
			</table>
		</div>
	</section>

	<section id="sod_fin_receiver">
		<h2 class="anc_tit">받으시는 분</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tr>
				<th scope="row">이 름</th>
				<td><?php echo get_text($od['b_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><?php echo get_text($od['b_telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><?php echo get_text($od['b_cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">주 소</th>
				<td><?php echo get_text(sprintf("(%s)", $od['b_zip']).' '.print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon'])); ?></td>
			</tr>
			<?php if($od['memo']) { ?>
			<tr>
				<th scope="row">전하실 말씀</th>
				<td><?php echo conv_content($od['memo'], 0); ?></td>
			</tr>
			<?php } ?>
			</table>
		</div>
	</section>

	<?php
	// 취소한 내역이 없다면
	if($st_cancel_price == 0 && $custom_cancel) {
	?>
	<section id="sod_fin_cancel">
		<h2>주문취소</h2>
		<button type="button" onclick="document.getElementById('sod_fin_cancelfrm').style.display='block';" class="btn_medium wset">주문 취소하기</button>

		<div id="sod_fin_cancelfrm">
			<form method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel.php" onsubmit="return fcancel_check(this);">
			<input type="hidden" name="od_id"  value="<?php echo $od_id; ?>">
			<input type="hidden" name="token"  value="<?php echo $token; ?>">
			<label for="cancel_memo">취소사유</label>
			<input type="text" name="cancel_memo" id="cancel_memo" required class="frm_input required" size="40" maxlength="100">
			<input type="submit" value="확인" class="btn_small">
			</form>
		</div>
	</section>
	<?php }


	?>
</div>

<script>



</script>

<?php
  $sql = " select * from shop_order where od_id = '$od_id' order by index_no desc ";
  $result = sql_query($sql);
  for($i=0; $row=sql_fetch_array($result); $i++)
  {

        $ux_point = base64_encode($row['use_point2']);//상품별 포인트 사용액
        $ux_point2 = jsonfy2($ux_point);
		$saleprice = base64_encode($row['goods_price']);
        $saleprice2 = jsonfy2($saleprice);
		$etc_amt = base64_encode($row['use_price']);
        $etc_amt2 = jsonfy2($etc_amt);
		$deli_amt = base64_encode($row['baesong_price'] + $row['baesong_price2']);
        $deli_amt2 = jsonfy2($deli_amt);
		$item_cd = base64_encode($row['gs_id']);
        $item_cd2 = jsonfy2($item_cd);
		if($_GET['way'] == "cancel"){
			$mproc_code = base64_encode("20");//포인트 취소
        }else{ //사용
            $mproc_code = base64_encode("10");//포인트 사용
        }
	    $mproc_code2 = jsonfy2($mproc_code);
		$order_dm = substr($row['od_time'],0, 10); //2019-06-10
		$order_dm2 = str_replace("-","",$order_dm);
		$order_dm3 = jsonfy2($order_dm2);

     	$gs = unserialize($row['od_goods']); //상품명
		$item_nm = get_text($gs['gname']);
		$item_nm2 = base64_encode($item_nm);
        $item_nm3 = jsonfy2($item_nm2);
		$item_price = get_text($gs['goods_price']);//단품가
		$item_price2 = base64_encode($item_price);
        $item_price3 = jsonfy2($item_price2);
        $order_qty = base64_encode($row['sum_qty']);
        $order_qty2 = jsonfy2($order_qty);
        $dc_price = base64_encode("0");//할인금액(**추후 다시 체크해볼것)
        $dc_price2 = jsonfy2($dc_price);

		//od_mobile(0: 웹 , 1: 모바일)
		if($row['od_moible'] =='0'){
			$mda_gb = "101";
		}else{
			$mda_gb = "102";
		}
		$magam_gb = "101";//(마감기준 : 101 ->주문기준 , 102 ->배송기준)
  ?>

<script>

 alert('1');
 /*
 ajax_magam('<?php echo $order_no2;?>','<?php echo $item_cd2;?>','<?php echo $mproc_code2;?>','<?php echo $order_dm3;?>','<?php echo $shop_no;?>','<?php echo $shopevent_no2;?>','<?php echo $mem_no2;?>','<?php echo $tax_gb2;?>','<?php echo $saleprice2;?>','<?php echo $ux_point2;?>','<?php echo $etc_amt2;?>','<?php echo $deli_amt2;?>','<?php echo $item_nm2;?>','<?php echo $item_price2;?>','<?php echo $order_qty2;?>','<?php echo $dc_price2;?>','<?php echo $magam_gb;?>','<?php echo $mda_gb;?>');
*/

	$.ajax({

		              url : 'https://giftdev.e-hyundai.com/hb2efront_new/pointOpenAPIMagam.do',
					  type : 'GET',
                      async: false,
				      data : {
			                     ORDER_NO : '<?php echo $order_no2;?>', //주문번호
                                 ITEM_CD : '<?php echo $item_cd2;?>',//상품번호
                                 ORDER_GB : '<?php echo $mproc_code2;?>',//주문구분
                                 ORDER_DM : '<?php echo $order_dm3;?>',//주문일자
                                 SHOP_NO : '<?php echo $shop_no;?>',//상점번호
                                 SHOPEVENT_NO : '<?php echo $shopevent_no2;?>' ,//행사번호
                                 MEM_NO : '<?php echo $mem_no2;?>',//회원번호
                                 TAX_GB : '<?php echo $tax_gb2;?>',//과세여부
                                 SALEPRICE : '<?php echo $saleprice2;?>',//판매금액
                                 POINT_AMT : '<?php echo $ux_point2;?>',//포인트사용금액
                                 ETC_AMT : '<?php echo $etc_amt2;?>' ,//해당주문에 사용한 기타결제금액
                                 MEDIA_CD : 'MW',//매체구분값
                                 DELI_AMT: '<?php echo $deli_amt2;?>',//배송비
                                 ITEM_NM : '<?php echo $item_nm2;?>',//상품명
                                 ITEM_PRICE : '<?php echo $item_price2;?>',//단품가격
                                 ORDER_QTY : '<?php echo $order_qty2;?>' ,//주문수량
                                 DC_PRICE : '<?php echo $dc_price2;?>',//할인금액
                                 MAGAM_GB : '<?php echo $magam_gb;?>' ,//회수완료일(101: 주문기준)
                                 MDA_GB : '<?php echo $mda_gb;?>'//매체구분(PC:101 , 모바일:102)
		                     },
				      dataType : 'XML',
		              success : function(result){
							  $(result).find('param').each(function(){  // xml 문서 item 기준으로 분리후 반복
                              var return_code = $(this).find("return_code").text();
							  alert(return_code);

					  },
					  error: function(xhr, status, error) {
			                 alert(error);
		               }
	        });
</script>



 <?

  }


 ?>

<script>
function fcancel_check(f)
{
    if(!confirm("주문을 정말 취소하시겠습니까?"))
        return false;

    var memo = f.cancel_memo.value;
    if(memo == "") {
        alert("취소사유를 입력해 주십시오.");
        return false;
    }

    return true;
}

</script>

