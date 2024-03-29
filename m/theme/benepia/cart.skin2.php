<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 장바구니 시작 { -->
<script src="<?php echo TB_MJS_URL; ?>/shop.js"></script>

<!-- <div class="stit_txt">
	※ 총 <?php echo number_format($cart_count); ?>개의 상품이 담겨 있습니다.
</div> -->

<div id="sod_bsk">
	<form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">	

    <?php if($cart_count) { ?>
    <div id="sod_chk">        
        <input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
		<label for="ct_all">전체상품 (<?php echo number_format($cart_count); ?>개)</label>
    </div>
    <?php } ?>

	 <div  class="btn_confirm_top">
        <?php if($i == 0) { ?>
        <?php } else { ?>
        <div><button type="button" onclick="return form_check('seldelete');" class="btn01">선택삭제</button>
        <button type="button" onclick="return form_check('alldelete');" class="btn01">비우기</button></div>
        <?php } ?>
    </div>


    <ul class="sod_list">
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

			$it_options = mobile_print_item_options($row['gs_id'], $set_cart_id);

			$point = $sum['point'];
			$sell_price = $sum['price'];
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 배송비
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>
        <li class="sod_li">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
            <div class="li_chk">
                <label for="ct_chk_<?php echo $i; ?>" class="sound_only">상품</label>
                <input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked">
            </div>
            <div class="li_name">
                <a href="<?php echo $href; ?>"><?php echo stripslashes($gs['gname']); ?></a>
				<?php if($it_options) { ?>
				<div class="sod_opt"><?php echo $it_options; ?></div>
				<?php } ?>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span> 
				<div class="li_mod" style="padding-left:100px;">
					<?php if($it_options) { ?>
					<button type="button" id="mod_opt_<?php echo $row['gs_id']; ?>" class="mod_btn mod_options">옵션변경/추가</button>
					<?php } ?>
				</div>				
            </div>

			<div class="li_total_box">
				<strong><?php echo number_format($sell_price); ?>원 + 배송비 <?php echo number_format($info['price']); ?>원 = <span class="price_td price_td<?php echo $i; ?>"><?php echo number_format($sell_amt); ?>원</span></strong>	
				<span class="point">적립포인트 <?php echo number_format($point); ?></span>
			</div>


            <!-- <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>판매가</span>
            				<?php echo number_format($sell_amt); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>수량</span>
            				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>배송비</span>
            				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>소계</span>
            				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>적립포인트</span>
            				<strong><?php echo number_format($point); ?></strong></span>
            </div> -->
        </li>
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

		}

		if($i == 0) {
			echo '<li class="empty_list">장바구니에 담긴 상품이 없습니다.</li>';
		}
		?>
    </ul>



    <?php if($i > 0) { ?>

	<script>
	//콤마찍기
	function commaIns(str) {
		str = String(str);
		return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
	}

	var sd_limit_price = "30000";
	var sd_price = <?php echo $tot_send_cost?>;

	var sd_limit_price_arr = new Array("30000"); 
	var sd_price_arr = new Array("<?php echo $tot_send_cost?>");

	$("input[name^=ct_all]").click(function() {
		if($(this).is(":checked")) {
			$("input[name^=ct_all]").attr("checked",true);
			$("input[name^=ct_chk]").attr("checked", true);
		} else {
			$("input[name^=ct_all]").attr("checked",false);
			$("input[name^=ct_chk]").attr("checked", false);
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
		$('.sod_bsk_cnt strong').html(commaPrice +'원');
		$('.sod_bsk_dvr strong').html(commaIns(sdPrice)+'원');
		$('.sod_bsk_cnt_tot strong').html(resPrice+'원');
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
		

		var sd_limit_price_cnt = sd_limit_price_arr.length; 
		var sdPrice = 0;

		var resPrice = commaIns(hapPrice + sdPrice);
		$('.amount_pwrap .amount_p .selectPoint').html(commaPoint);
		$('.sod_bsk_cnt strong').html(commaPrice +'원');
		$('.sod_bsk_dvr strong').html(commaIns(sdPrice)+'원');
		$('.sod_bsk_cnt_tot strong').html(resPrice+'원');
	});
	</script>

    <div id="sod_bsk_tot" class="bsk_w">
		<dl>
			<?php if($tot_price > 0) { ?>
			<dt class="sod_bsk_cnt"><span>총 상품금액</span></dt>
			<dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_sell_price); ?> 원</strong></dd>
			<dt class="sod_bsk_dvr"><span>총 배송비</span></dt>
			<dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> 원</strong></dd>
			<?php } ?>
		</dl>
		<div>
			<dl>
				<?php if($tot_price > 0) { ?>
				<dt class="sod_bsk_cnt sod_bsk_cnt_tot"><span>전체주문금액</span></dt>
				<dd class="sod_bsk_cnt sod_bsk_cnt_tot"><strong><?php echo number_format($tot_price); ?> 원</strong></dd>
				<?php } ?>
			</dl>
		</div>
    </div>
	
    <?php } ?>

<div class="buy-btn_cont">
    <div id="sod_bsk_act" class="btn_confirm">
        <?php if($i == 0) { ?>
        <a href="<?php echo TB_MURL; ?>" class="btn_medium bx-black">쇼핑 계속하기</a>
        <?php } else { ?>
		
		<div>
			<dl>
				<?php if($tot_price > 0) { ?>
				<dt class="sod_bsk_cnt_tot"><span>전체주문금액</span></dt>
				<dd class="sod_bsk_cnt_tot"><strong><?php echo number_format($tot_price); ?> 원</strong></dd>
				<?php } ?>
			</dl>
		</div>



       
        <input type="hidden" name="url" value="<?php echo TB_MSHOP_URL; ?>/orderform.php">
        <input type="hidden" name="act" value="">
        <input type="hidden" name="records" value="<?php echo $i; ?>">
		<a href="<?php echo TB_MSHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn_medium bx-black">쇼핑 계속하기</a>
        <button type="button" onclick="return form_check('buy');" class="btn_medium wset">주문하기</button>
		
        <?php if($naverpay_button_js) { ?>
        <div class="naverpay-cart"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
        <?php } ?>
        <?php } ?>
    </div>
    </form>
</div>
</div>


<!-- 장바구니 추천상품 시작 -->
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

$sql = " select * $sql_common $sql_order limit 1, 5";
$result = sql_query($sql);

/*$row=sql_fetch_array($result);
if(count($row) <= 1) {
	$item_in = 'style="display:none;"';
}*/
?>
<div class="vi_rel_sort">
	<div class="tab_sort">
		<span class="total marb5">장바구니 추천상품</span>
	</div>

	<div>
		<div id="vi_rel_slide">
		<?php
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

			$file = TB_URL.'/data/goods/'.$row['simg1'];
		?>
			<div class="inc">
				<a href="<?php echo $it_href; ?>">
					<dl>
						<dt><img src="<?php echo $file ?>" alt=""></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
					</dl>
				</a>
			</div>
		<?php }	?>
		</div>

		<script>
		$(document).on('ready', function() {
			$('#vi_rel_slide').slick({
				slidesToShow: 2,
				slidesToScroll: 2,
				dots: true,
				arrows: false
			});
		});

		function fbuyform_submit2(sw_direct)
		{
			var f = document.fbuyform;
			f.sw_direct.value = sw_direct;

			if(sw_direct == "cart") {
				f.sw_direct.value = 0;
			} 

			if($(".sit_opt_list").size() < 1) {
				alert("주문옵션을 선택해주시기 바랍니다.");
				return;
			}

			var val, io_type, result = true;
			var sum_qty = 0;
			var min_qty = parseInt('<?php echo $odr_min; ?>');
			var max_qty = parseInt('<?php echo $odr_max; ?>');
			var $el_type = $("input[name^=io_type]");

			$("input[name^=ct_qty]").each(function(index) {
				val = $(this).val();

				if(val.length < 1) {
					alert("수량을 입력해 주세요.");
					result = false;
					return;
				}

				if(val.replace(/[0-9]/g, "").length > 0) {
					alert("수량은 숫자로 입력해 주세요.");
					result = false;
					return;
				}

				if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
					alert("수량은 1이상 입력해 주세요.");
					result = false;
					return;
				}

				io_type = $el_type.eq(index).val();
				if(io_type == "0")
					sum_qty += parseInt(val);
			});

			if(!result) {
				return;
			}

			if(min_qty > 0 && sum_qty < min_qty) {
				alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
				return;
			}

			if(max_qty > 0 && sum_qty > max_qty) {
				alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
				return;
			}
			
			$('.fbuyform_pop').show();
		}

		$('.fbuyform_pop .back').click(function(){
			$('.fbuyform_pop').hide();
		});
		$('.fbuyform_pop .close').click(function(){
			$('.fbuyform_pop').hide();
		});
		</script>
	</div>
</div>
<!-- 장바구니 추천상품 끝 -->

<script>
$(document).ready(function(){
	$('#ft').css('padding-bottom', '60px');
	$('.btn_top').css('bottom', '95px');
	$('.btn_bottom').css('bottom', '60px');
});

$(function() {
    var close_btn_idx;

    // 선택사항수정
    $(".mod_options").click(function() {
        var gs_id = $(this).attr("id").replace("mod_opt_", "");
        var $this = $(this);
        close_btn_idx = $(".mod_options").index($(this));

        $.post(
            "./cartoption.php",
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

    // 옵션수정 닫기
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $("#win_mask, .window").hide();
        $(".mod_options").eq(close_btn_idx).focus();
    });
    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $("#win_mask").hide();
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
</script>
<!-- } 장바구니 끝 -->
