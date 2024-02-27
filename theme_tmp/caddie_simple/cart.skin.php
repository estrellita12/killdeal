<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 장바구니 시작 { -->
<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

<h2 class="pg_tit subpg_tit">
	<?php echo $tb['title']; ?>
</h2>
<!-- <p><img src="<?php echo TB_IMG_URL; ?>/tit_cart.gif"></p> -->


<form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">
<div class="cart_wrap">
	<div class="pg_cnt cart_pg_cnt">
		<p class="item_cnt">총 <em><?php echo number_format($cart_count); ?></em>개</p>

		<?php if($i > 0) { ?>
		<div id="sod_bsk_btn">
			<!--<div><button type="button" class="btn_lsmall bx-white all_select_btn">전체선택</button></div>-->
			<div><button type="button" onclick="return form_check('seldelete');" class="btn_medium bx-white">선택상품삭제</button></div>
			<div><button type="button" onclick="return form_check('alldelete');" class="btn_medium bx-white">장바구니비우기</button></div>
		</div>
		<?php } ?>
	</div>
	<div class="cart_l" style="<?php if($cart_count <= 0) { echo "width:100%;";}?>">
		<div class="tbl_head02 tbl_wrap">
			<table>
				<caption>장바구니상품목록</caption>
			<colgroup>
				<col class="w50">
				<col class="w120">
				<col>
				<col class="w100">
				<col class="w110">
				<col class="w70">
				<!-- <col class="w90">
				<col class="w90"> -->
			</colgroup>
			<thead>
			<tr>
				<th scope="col">
					<label for="ct_all" class="sound_only">상품전체</label>
					<input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
				</th>
				<th scope="col">이미지</th>
				<th scope="col" class="txt_l">상품/옵션정보</th>
				<th scope="col"></th>
				<th scope="col"></th>
				<th scope="col"></th>
				<!-- <th scope="col">수량</th>
				<th scope="col">상품금액</th>
				<th scope="col">소계</th>
				<th scope="col">포인트</th>
				<th scope="col">배송비</th> -->
			</tr>
			</thead>
			<tbody>
			<?php
			$tot_point		= 0;
			$tot_sell_price = 0;
			$tot_opt_price	= 0;
			$tot_sell_qty	= 0;
			$tot_sell_amt	= 0;

			for($i=0; $row=sql_fetch_array($result); $i++) {
				$gs = get_goods($row['gs_id']);

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
								SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
								SUM(io_price * ct_qty) as opt_price
							from shop_cart
						   where gs_id = '$row[gs_id]'
							 and ct_direct = '$set_cart_id'
							 and ct_select = '0'";
				$sum = sql_fetch($sql);

				if($i==0) { // 계속쇼핑
					$continue_ca_id = $row['ca_id'];
				}

				unset($it_name);
				unset($mod_options);
				$it_options = print_item_options($row['gs_id'], $set_cart_id);
				if($it_options) {
					$mod_options = '<div class="sod_option_btn"><button type="button" class="btn_medium bx-white mod_options">옵션변경/추가</button></div>';
					$it_name = '<div class="sod_opt">'.$it_options.'</div>';
				}

				$point = $sum['point'];
				$sell_price = $sum['price'];
				$sell_opt_price = $sum['opt_price'];
				$sell_qty = $sum['qty'];
				//$sell_amt = $sum['price'] - $sum['opt_price'];
				$sell_amt = $sum['price'];
				
				// 배송비
				if($gs['use_aff'])
					$sr = get_partner($gs['mb_id']);
				else
					$sr = get_seller_cd($gs['mb_id']);

				$info = get_item_sendcost($sell_price);
				$item_sendcost[] = $info['pattern'];

				$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
			?>
			<tr>
				<td class="tac">
					<label for="ct_chk_<?php echo $i; ?>" class="sound_only">상품</label>
					<input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked">
				</td>
				<td class="tac"><a href="<?php echo $it_href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 100, 100); ?></a></td>
				<td class="td_name">
					<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
					<a href="<?php echo $it_href; ?>"><?php echo $gs['gname']; ?></a>
					<?php echo $it_name.$mod_options; ?>
				</td>
				<td class="tac"><?php echo number_format($sell_qty); ?></td>
				<!-- <td class="tac item_qty">
					<button type="button" class="defbtn_minus">감소</button>
					<input type="text" name="" value="<?php echo number_format($sell_qty); ?>" class="inp_opt" size="2">
					<button type="button" class="defbtn_plus">증가</button>
				</td> -->
				<td class="tar price_td price_td<?php echo $i; ?>" data-price="<?php echo $sell_price; ?>"><?php echo number_format($sell_price); ?>원</td>
				<td class="tac"><button type="button" data-id="<?php echo $row['gs_id']; ?>" data-cartid="<?php echo $set_cart_id; ?>" data-target="ct_chk_<?php echo $i; ?>" class="cart_del"><i></i></button></td>
				<!-- <td class="tac"><?php echo number_format($sell_qty); ?></td>
				<td class="tar"><?php echo number_format($sell_amt); ?></td>
				<td class="tar"><?php echo number_format($sell_price); ?></td>
				<td class="tar"><?php echo number_format($point); ?></td>
				<td class="tar"><?php echo number_format($info['price']); ?></td> -->
			</tr>
			<?php
				$tot_point		+= $point;
				$tot_sell_price += $sell_price;
				$tot_opt_price	+= $sell_opt_price;
				$tot_sell_qty	+= $sell_qty;
				$tot_sell_amt	+= $sell_amt;

				if(!$is_member) {
					$tot_point = 0;
				}
			} // for

			if($i == 0) {
				echo '<tr><td colspan="8" class="empty_table">장바구니에 담긴 상품이 없습니다.</td></tr>';
			}

			// 배송비 검사
			$send_cost = 0;
			$com_send_cost = 0;
			$sep_send_cost = 0;
			$max_send_cost = 0;

			if($i > 0) {
				$k = 0;
				$condition = array();
				foreach($item_sendcost as $key) {
					list($userid, $bundle, $price) = explode('|', $key);
					$condition[$userid][$bundle][$k] = $price;
					$k++;
				}

				$com_array = array();
				$val_array = array();
				foreach($condition as $key=>$value) {
					if($condition[$key]['묶음']) {
						$com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
						$max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
						$com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
						$val_array[] = max(array_values($condition[$key]['묶음']));// max value
					}
					if($condition[$key]['개별']) {
						$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
						$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
						$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
					}
				}

				$tune = get_tune_sendcost($com_array, $val_array);

				$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
				$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
				$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
				$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액

				$tot_cart_price = $tot_sell_amt + $tot_send_cost;
			}
			?>
			</tbody>
			</table>

			<script>
			//콤마찍기
			function commaIns(str) {
				str = String(str);
				return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
			}

			var checkedEl = $("input[name^=ct_chk]:checked").length;
			var sd_limit_price = "30000";
			var sd_price = <?php echo $tot_send_cost?>;

			var sd_limit_price_arr = new Array("30000"); 
			var sd_price_arr = new Array("<?php echo $tot_send_cost?>");

			$(document).ready(function(){
				$('.sod_bsk_tot_box .checked_cnt').html(checkedEl + '개');
			});

			$("input[name^=ct_all]").click(function() {
				if($(this).is(":checked")) {
					$("input[name^=ct_all]").attr("checked",true);
					$("input[name^=ct_chk]").attr("checked", true);
					$(".sod_bsk_tot_box .checked_cnt").html(checkedEl + '개');
					//$('#cart_list .pd_chkbox').removeClass('pd_chkbox_1');
					//$('#cart_list .pd_chkbox').addClass('pd_chkbox_2');
				} else {
					$("input[name^=ct_all]").attr("checked",false);
					$("input[name^=ct_chk]").attr("checked", false);
					$(".sod_bsk_tot_box .checked_cnt").html('0개');
					//$('#cart_list .pd_chkbox').removeClass('pd_chkbox_2');
					//$('#cart_list .pd_chkbox').addClass('pd_chkbox_1');
				}

				var Price = Point = [];
				var hapPrice = hapPoint = 0;
				var kubun = $("input[name^=ct_chk]");
				for(j=0;j<kubun.length;j++){
					if($('#ct_chk_'+j).is(':checked')){
						Price[j] = parseInt($('.price_td'+j).text().replace(/,/g,''));
						hapPrice += Price[j];
						Point[j] = parseInt($('.price_td'+j).text().replace(/,/g,''));
						hapPoint += Point[j];
					}
				}

				var commaPrice = commaIns(hapPrice);
				var commaPoint = commaIns(hapPoint);
				var sd_limit_price_cnt = sd_limit_price_arr.length; 
				var sdPrice = 0;

				for ( i = 0 ; i < sd_limit_price_cnt ; i++ ) {
					if ( i == 0 ) {
						if ( (hapPrice > 0) && (sd_limit_price_arr[i] > hapPrice) ) {
							sdPrice = parseInt(sd_price_arr[i]);
							break;
						} 
					} else if ( i == sd_limit_price_cnt ) {
						if ( sd_limit_price_arr[i] <= hapPrice ) {
							sdPrice = parseInt(sd_price_arr[i]);
							break;
						} 
					} else {
						if ( (sd_limit_price_arr[i-1] <= hapPrice) && (sd_limit_price_arr[i] > hapPrice) ) {
							sdPrice = parseInt(sd_price_arr[i]);
							break;
						} 
					}
				}

				var resPrice = commaIns(hapPrice + sdPrice);
				$('.amount_pwrap .amount_p .selectPoint').html(commaPoint);
				$('.total_pri .spr').html(commaPrice +'원');
				$('.total_delivery .spr').html(commaIns(sdPrice)+'원');
				$('.sod_bsk_total_box .spr').html(resPrice+'원');
			});

			$("input[name^=ct_chk]").click(function() {
				var Price = Point = [];
				var hapPrice = 0;
				var hapPoint = 0;
				var kubun = $("input[name^=ct_chk]");
				for(j=0;j<kubun.length;j++){
					
					if($('#ct_chk_'+j).is(':checked')){
						Price[j] = parseInt($('.price_td'+j).text().replace(/,/g,''));
						hapPrice += Price[j];
						Point[j] = parseInt($('.price_td'+j).text().replace(/,/g,''));
						hapPoint += Point[j];
					}

				}
				var commaPrice = commaIns(hapPrice);
				var commaPoint = commaIns(hapPoint);
				
				var checkedEl = $("input[name^=ct_chk]:checked").length;
				var sd_limit_price_cnt = sd_limit_price_arr.length; 
				var sdPrice = 0;
				
				var resPrice = commaIns(hapPrice + sdPrice);
				$('.amount_pwrap .amount_p .selectPoint').html(commaPoint);
				$('.total_pri .spr').html(commaPrice +'원');
				$('.total_delivery .spr').html(commaIns(sdPrice)+'원');
				$('.sod_bsk_total_box .spr').html(resPrice+'원');
				$('.sod_bsk_tot_box .checked_cnt').html(checkedEl + '개');
				
			});
			</script>
			<!-- 반영 안됨 -->
			<div class="sod_bsk_tot_box">
				<div class="chk_cnt_wrap">
					<?php
					if($i == 0){
						echo $str."<span></span>";
					}
					else{
						echo $str."<span>선택상품(<span class=\"checked_cnt\"></span>/".number_format($cart_count)."개)</span>";
					}
					?>
				</div>
				<p class="total_pri">상품금액 <?php echo display_price2($tot_sell_price); ?></p>
				<i>+</i>
				<p class="total_delivery">배송비 <?php echo display_price2($tot_send_cost); ?></p>
				<i>=</i>
				<p class="sod_bsk_total_box">총 <?php echo display_price2($tot_price); ?></p>
			</div>
		</div>
	</div>

	<?php if($i > 0) { ?>
	<!-- <div id="sod_bsk_btn">
		<div class="palt"><button type="button" onclick="return form_check('seldelete');" class="btn_lsmall bx-red">선택상품 삭제</button></div>
		<div class="part"><button type="button" onclick="return form_check('alldelete');" class="btn_lsmall bx-white">장바구니 비우기</button></div>
	</div> -->

	<div class="cart_r">
		<div id="sod_bsk_tot">
			<p>전체합계</p>

			<div class="sod_bsk_tot_box">
				<dl>
					<dt>총 상품금액</dt>
					<dd class="total_pri" data-price="<?php echo $tot_sell_amt; ?>"><?php echo display_price2($tot_sell_amt); ?></dd>
				</dl>		
				<dl>
					<dt>배송비</dt>
					<dd class="total_delivery"><?php echo display_price2($tot_send_cost); ?></dd>
				</dl>	
			</div>

			<div class="sod_bsk_total_box">
				<dl>
					<dt>전체주문금액</dt>
					<dd class="fc_red"><?php echo display_price2($tot_price); ?></dd>
				</dl>		
			</div>
		</div>

		<!-- <div id="sod_bsk_tot">
			<table class="wfull">
			<tr>
				<td class="w50p">
					<h2 class="anc_tit">장바구니에 담긴 상품통계</h2>
					<div class="tbl_frm01 tbl_wrap">
						<table>
						<colgroup>
							<col class="w140">
							<col class="w140">
							<col>
						</colgroup>
						<tr>
							<th scope="row">포인트</th>
							<td class="tar">적립 포인트</td>
							<td class="tar bl"><?php echo display_point($tot_point); ?></td>
						</tr>
						<tr>
							<th scope="row" rowspan="3">상품</th>
							<td class="tar">상품금액 합계</td>
							<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
						</tr>
						<tr>
							<td class="tar">옵션금액 합계</td>
							<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
						</tr>
						<tr>
							<td class="tar">주문수량 합계</td>
							<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
						</tr>
						<tr>
							<td class="list2 tac bold" colspan="2">현재 포인트 보유잔액</td>
							<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
						</tr>
						</table>
					</div>
				</td>
				<td class="w50p">
					<h2 class="anc_tit">결제 예상금액 통계</h2>
					<div class="tbl_frm01 tbl_wrap">
						<table>
						<colgroup>
							<col class="w140">
							<col class="w140">
							<col>
						</colgroup>
						<tr>
							<th scope="row">주문</th>
							<td class="tar">(A) 주문금액 합계</td>
							<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
						</tr>
						<tr>
							<th scope="row" rowspan="3">배송비</th>
							<td class="tar">상품별 배송비합계</td>
							<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
						</tr>
						<tr>
							<td class="tar">배송비할인</td>
							<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
						</tr>
						<tr>
							<td class="tar">(B) 최종배송비</td>
							<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
						</tr>
						<tr>
							<td class="list2 tac bold" colspan="2">결제예정금액 (A+B)</td>
							<td class="list2 tar bold fc_red"><?php echo display_price2($tot_price); ?></td>
						</tr>
						</table>
					</div>
				</td>
			</tr>
			</table>
		</div> -->
		<?php } ?>

		<div class="btn_confirm">
			<?php if($i == 0) { ?>
			<a href="<?php echo TB_URL; ?>" class="btn_large">쇼핑계속하기</a>
			<?php } else { ?>
			<input type="hidden" name="url" value="./orderform.php">
			<input type="hidden" name="records" value="<?php echo $i; ?>">
			<input type="hidden" name="act">
			<button type="button" onclick="return form_check('buy');" class="btn_large wset">주문하기</button>
			<!-- <a href="<?php echo TB_SHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn_large bx-white">쇼핑계속하기</a> -->
			<?php if($naverpay_button_js) { ?>
			<div class="cart-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
</form>

<script>
$(function() {
	var close_btn_idx;

	// 선택사항수정
	$(".mod_options").click(function() {
		var gs_id = $(this).closest("tr").find("input[name^=gs_id]").val();
		var $this = $(this);
		close_btn_idx = $(".mod_options").index($(this));

		$.post(
			tb_shop_url+"/cartoption.php",
			{ gs_id: gs_id },
			function(data) {
				$("#mod_option_frm").remove();
				$this.after("<div id=\"mod_option_frm\"></div>");
				$("#mod_option_frm").html(data);
				price_calculate();
			}
		);
	});

    // 모두선택
    $("input[name=ct_all]").click(function() {
        if($(this).is(":checked"))
            $("input[name^=ct_chk]").attr("checked", true);
        else
            $("input[name^=ct_chk]").attr("checked", false);
    });

	$(".all_select_btn").click(function() {
        if($('input[name=ct_all]').is(":checked"))
            $("input[name^=ct_chk]").attr("checked", true);
        else
            $("input[name^=ct_chk]").attr("checked", false);
    });

    // 옵션수정 닫기
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });

    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });
});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("구매하실 상품을 하나이상 선택해 주십시오.");
        return false;
    }

    return true;
}

function form_check(act) {
    var f = document.frmcartlist;
    var cnt = f.records.value;

    if(act == "buy")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("주문하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }
    else if(act == "alldelete")
    {
        f.act.value = act;
        f.submit();
    }
    else if(act == "seldelete")
    {

		
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("삭제하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }
    return true;
}



// 각각 상품 삭제
$(".cart_del").on('click',function (e){
	$('input:checkbox').prop("checked",false);
	$("#"+$(this).data('target')).prop("checked", true);
   
	if(confirm('선택하신 상품을 삭제하시겠습니까?') == true){
		form_check('seldelete');   
	}
});
</script>

<?php
$sql_search = " and recomm_use ";
$sql_common = sql_goods_list($sql_search);

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * $sql_common ";
$result = sql_query($sql);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, rank desc, index_no desc ";
else
	$sql_order = " order by rank desc, index_no desc ";

//20200518 수정 limit 1,5 >> 0,5 (추천상품중 첫번째 노출한 상품 노출되지않는현상 개선(limit가 1로되어있어 노출되지않았음)) 
$sql = " select * $sql_common $sql_order limit 0, 5";
$result = sql_query($sql);
/*$row=sql_fetch_array($result);
if(count($row) <= 1) {
	$item_in = 'style="display:none;"';
}*/
?>

<div class="cart_wrap" >
	<div class="tab_sort sub_tab_sort">
		<span class="total">장바구니 추천상품</span>
	</div>
	<?php echo get_cart_listtype_skin("5", '327', '327', '5', 'wli5'); ?>
 
<?php 
//20200910 강력추천상품으로 대체 주석
/*
	 <div class="pr_desc sub_pr_desc wli5">
		<ul>
		<?php
		$inc = 0;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
			$it_image = get_it_image($row['index_no'], $row['simg1'], 278, 278);
			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}
		?>
			<li>
				<a href="<?php echo $it_href; ?>">
				<dl>
					<dt><?php echo $it_image; ?></dt>
					<dd class="pname"><?php echo $it_name; ?></dd>
					<dd class="price"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
					<?php if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) { ?>
					<dd class="petc">
						<?php if($row['gpoint']) { ?>
						<span class="fbx_small fbx_bg6"><?php echo $it_point; ?> 적립</span>
						<?php } ?>
						<?php if($is_free_baesong) { ?>
						<span class="fbx_small fbx_bg4">무료배송</span>
						<?php } ?>
						<?php if($is_free_baesong2) { ?>
						<span class="fbx_small fbx_bg4">조건부무료배송</span>
						<?php } ?>
					</dd>
					<?php } ?>
				</dl>
				</a>
				<!-- 20191104 찜 주석처리
				<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php //echo $row['index_no']; ?>');" id="<?php //echo $row['index_no']; ?>" class="<?php //echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php //echo $it_href; ?>" target="_blank" class="nwin"></a></p>
				-->
			</li>
		<?php } ?>
		</ul>
	</div>
	*/ ?>
</div>
<!-- } 장바구니 끝 -->
