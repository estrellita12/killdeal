<?php
if(!defined('_TUBEWEB_')) exit;
?>
<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

<form name="fbuyform" id="fbuyform" method="post">
<input type="hidden" name="gs_id[]" value="<?php echo $index_no; ?>">
<input type="hidden" id="it_price" value="<?php echo get_sale_price($index_no); ?>">
<input type="hidden" name="ca_id" value="<?php echo $gs['ca_id']; ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="d_jjim" id="d_jjim" value="">

<p class="subpg_nav" >홈<?php echo get_move($gs['ca_id']); ?></p>

<div class="vi_info">
	<div class="vi_img_bx" style="width:<?php echo $default['de_item_medium_wpx']; ?>px">
		<?php if( $is_social_ing || $is_timesale ) { include_once(TB_THEME_PATH.'/timer.skin.php'); } //2021-08-10  ?>
		<?php if($is_social_end) { ?><div class="t_social"><?php echo $is_social_txt; ?></div><?php } ?>

		<div class="bimg">
			<?php echo get_it_image($index_no, $gs['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], "id='big'"); ?>
		</div>
<!-- 20200722 해당기능 비활성 요청 -->
<!-- 		<div class="simg_li"> -->
<!-- 			<ul> -->
				<?php
				for($i=2; $i<=6; $i++) {
					$it_image = $gs['simg'.$i];
					if(!$it_image) continue;

					$thumbnails = get_it_image_url($index_no, $it_image, $default['de_item_medium_wpx'], $default['de_item_medium_hpx']);
				?>
<!-- 				<li><img src="<?php echo $thumbnails; ?>" onmouseover="document.all['big'].src='<?php echo $thumbnails; ?>'" alt="상품이미지small"></li> -->
				<?php } ?>
<!-- 			</ul> -->
			<!-- 20200518 해당기능 비활성 요청 -->
			<!-- <div class="share_box">
				<div class="review_box">
					<div><img src="<?php echo TB_IMG_URL; ?>/sub/review_star<?php echo $star_score; ?>.png" alt="상품리뷰점수"></div>
					<div class="review_cnt">리뷰수 <em><?php echo $item_use_count; ?></em></div>
				</div>
				<div><?php echo $sns_share_links; ?></div>
			</div> -->
<!-- 		</div> -->
	</div>
	<div class="vi_txt_bx">
		<h2 class="tit">
			<?php echo $gs['gname']; ?>
			<?php if(is_admin()) { ?><a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $index_no; ?>" target="_blank" class="btn_small red">수정</a><?php } ?>
			<?php if($gs['explan']) { ?>
			<span class="stxt"><?php echo $gs['explan']; ?></span>
			<?php } ?>
			<span class="item_code">상품코드 <?php echo $gs['gcode']; ?></span>
		</h2>
		<?php if(!$is_only) { ?>

  


		<div class="price_bx">
			<?php 
            $gs['normal_price'] = $gs['normal_price'] ? $gs['normal_price'] : get_sale_price($gs['index_no']);
            if(!$is_pr_msg && !$is_buy_only && !$is_soldout && $gs['normal_price']) { 
                $real_price = get_sale_price($gs['index_no']);
                $sett = ($gs['normal_price'] - $real_price) / $gs['normal_price'] * 100;
                if($gs['normal_price'] == $real_price){
                    $gs['normal_price'] = round($gs['normal_price']/0.9,-3);
                    $sett = 10;
                }                
                $sale = '&nbsp;&nbsp;<span class="sale" style="font-weight:bold; color:black;">'.number_format($sett,0).'<span>%</span></span>';                
            ?>
			<dl>
				<dt>시중가격</dt>
				<dd><span class="f_price"><?php echo display_price2($gs['normal_price']);?></span> <?php echo $sale;?></dd>
                
			</dl>
			<?php } ?>
			<dl>
				<dt class="padt5">판매가격</dt>
				<dd class="price"><?php echo get_price($index_no); ?></dd>
			</dl>
			<dl>
				<dt class="padt5">공유하기</dt>
				<dd class="pr_code">
                    <span id="shortUrlRes" class="curp" style="color:#006daa">링크 복사 <i class="fa fa-share-alt" aria-hidden="true"></i></span>
                    <span id="shortUrlBox" class="dn marl5"><input type="text" id="shortUrl" class="w200 frm_input vat"></span>
                </dd>
			</dl>

			<?php if(is_partner($member['id']) && $config['pf_payment_yes']) { ?>
			<dl>
				<dt class="padt5">판매수익</dt>
				<dd class="pay"><?php echo display_price2(get_payment($index_no)); ?></dd>
			</dl>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="vi_txt_li vi_noline">
			<!-- <dl>
				<dt>상품코드</dt>
				<dd><?php echo $gs['gcode']; ?></dd>
			</dl> -->
			<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?>
			<dl>
				<dt>포인트</dt>
				<dd><?php echo $gpoint; ?></dd>
			</dl>
			<?php } ?>
			<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $cp_used) { ?>
			<dl>
				<dt>쿠폰발급</dt>
				<dd><?php echo $cp_btn; ?></dd>
			</dl>
			<?php } ?>
<!-- 			<?php if($gs['maker']) { ?> --> <!-- 20200617 제조사/원산지 주석요청 -->
<!-- 			<dl> -->
<!-- 				<dt>제조사</dt> -->
<!-- 				<dd><?php echo $gs['maker']; ?></dd> -->
<!-- 			</dl> -->
<!-- 			<?php } ?> -->
<!-- 			<?php if($gs['origin']) { ?> -->
<!-- 			<dl> -->
<!-- 				<dt>원산지</dt> -->
<!-- 				<dd><?php echo $gs['origin']; ?></dd> -->
<!-- 			</dl> -->
<!-- 			<?php } ?> -->
			<?php if($gs['brand_nm']) { ?>
			<dl>
				<dt>브랜드</dt>
				<dd><?php echo $gs['brand_nm']; ?></dd>
			</dl>
			<?php } ?>
			<?php if($gs['model']) { ?>
			<dl>
				<dt>모델명</dt>
				<dd><?php echo $gs['model']; ?></dd>
			</dl>
			<?php } ?>
			<dl>
				<dt>배송비<?php if($gs['sc_amt'] != 0) { echo "(".$gs['sc_amt']."원)"; } ?></dt> <!-- 20200617 배송비있는경우 배송비노출 -->
				<dd><?php echo get_sendcost_amt(); ?></dd>
			</dl>
            <!-- 20200506 배송가능지역 주석처리
			<dl>
				<dt>배송가능지역</dt>
				<dd><?php echo $gs['zone']; ?> <?php echo $gs['zone_msg']; ?></dd>
			</dl>
			-->
			<!-- <dl>
				<dt>고객상품후기</dt>
				<dd>상품후기 : <?php echo $item_use_count; ?>건, 평점 : <img src="<?php echo TB_IMG_URL; ?>/sub/view_score_<?php echo $star_score; ?>.gif"></dd>
			</dl>
			<dl>
				<dt>상품URL 소셜 공유</dt>
				<dd><?php echo $sns_share_links; ?></dd>
			</dl> -->
			<?php if($gs['odr_min']) { ?>
            <!--
			<dl>
				<dt>최소구매수량</dt>
				<dd><?php echo display_qty($gs['odr_min']); ?></dd>
			</dl>
            -->
			<?php } ?>
			<?php if($gs['odr_max']) { ?>
			<dl>
				<dt>최대구매수량</dt>
				<dd><?php echo display_qty($gs['odr_max']); ?></dd>
			</dl>
			<?php } ?>
		</div>

		<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout) { ?>
		<?php if($option_item || $supply_item) { ?>
		<div class="vi_txt_li">
			<?php if(($pt_id != 'honggolf' && $pt_id != 'maniamall') && $option_item) { ?>
			<dl>
				<dt>주문옵션</dt>
				<!-- <dd>아래옵션은 필수선택 옵션입니다</dd> -->
				<?php echo $option_item; ?>
			</dl>

			<?php } else if((($pt_id == 'honggolf' || $pt_id == 'maniamall') && $is_member) && $option_item) { ?>
			<dl>
				<dt>주문옵션</dt>
				<!-- <dd>아래옵션은 필수선택 옵션입니다</dd> -->
				<?php echo $option_item; ?>
			</dl>
			<?php }?>

			<?php if($supply_item) { ?>
			<dl>
				<dt>추가구성</dt>
				<!-- <dd>추가구매를 원하시면 선택하세요</dd> -->
				<?php echo $supply_item; ?>
			</dl>

			<?php } ?>
		</div>
		<?php } ?>

		<!-- 선택된 옵션 시작 { -->
		<div id="option_set_list">
			<?php if(!$option_item) { ?>
			<ul id="option_set_added">
				<li class="sit_opt_list vi_txt_li">
					<dl>
						<input type="hidden" name="io_type[<?php echo $index_no; ?>][]" value="0">
						<input type="hidden" name="io_id[<?php echo $index_no; ?>][]" value="">
						<input type="hidden" name="io_value[<?php echo $index_no; ?>][]" value="<?php echo $gs['gname']; ?>">
						<input type="hidden" class="io_price" value="0">
						<input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
						<dt>
							<span class="sit_opt_subj">수량</span>
							<span class="sit_opt_prc"></span>
						</dt>
						<dd class="li_ea">
							<span>
								<button type="button" class="defbtn_minus">감소</button><input type="text" name="ct_qty[<?php echo $index_no; ?>][]" value="<?php echo $odr_min; ?>" class="inp_opt" title="수량설정" size="2"><button type="button" class="defbtn_plus">증가</button>
							</span>
							<span class="marl7">(재고수량 : <?php echo $gs['stock_mod'] ? display_qty($gs['stock_qty']) : '무제한'; ?>)</span>
						</dd>
					</dl>
				</li>
			</ul>
			<script>
			$(function() {
				price_calculate();
			});
			</script>
			<?php } ?>
		</div>
		<!-- } 선택된 옵션 끝 -->
		<div id="sit_tot_views" class="dn">
			<span class="fl">총 합계금액</span>
			<span id="sit_tot_price" class="prdc_price"></span>
		</div>
		<?php } ?>
		<?php if(!$is_pr_msg) { ?>
		<div class="vi_btn">
			<?php echo get_buy_button($script_msg, $index_no); ?>
		</div>
		<?php if($naverpay_button_js) { ?>
		<div class="naverpay-item"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
		<?php } ?>
		<?php } ?>
	</div>
</div>


</form>
<!-- 관련상품 시작 -->
<div class="vi_rel_sort marb30">
	<div class="tab_sort sub_tab_sort view_sub_tab">
		<span class="total marb5">관련상품</span>

		<div class="paging_btn">
			<span class="pg_prev">이전</span>
			<span class="pg_next">다음</span>
		</div>
	</div>

	<div class="pr_desc sub_pr_desc wli5 wli5_1">
	<?php
	$ca_id = $gs['ca_id'];
	$sql = " select *
		   from shop_category
		  where catecode = '$ca_id'
		    and cateuse = '0'
			and find_in_set('$pt_id', catehide) = '0' ";
	$sql_search = " and (ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%') ";
	$sql_common = sql_goods_list($sql_search);

	// 상품 정렬
	if($sort && $sortodr)
		$sql_order = " order by {$sort} {$sortodr}, rank desc, index_no desc ";
	else
		$sql_order = " order by rank desc, index_no desc ";

	// 테이블의 전체 레코드수만 얻음
	$sql = " select count(*) as cnt $sql_common ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$mod = 5; // 가로 출력 수
	$rows = $page_rows ? (int)$page_rows : ($mod*1);
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

    // 2021-11-18
    if($total_count > 15) $total_count = 15;

	$sql = " select * $sql_common $sql_order limit $total_count";
	$result = sql_query($sql);
	//if($rel_count > 0) {
	?>
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
			}else{
                $sale = '<p class="sale">10<span>%</span></p>';
                $it_sprice = display_price2(round($it_amount/0.9,-3));                
            }

			if($i % $mod == 0) $inc++;
		?>
			<li class="dn inc<?php echo $inc; ?>">
				<a href="<?php echo $it_href; ?>">
				<dl>
					<dt><?php echo $it_image; ?></dt>
					<dd class="pname"><?php echo $it_name; ?></dd>
					<dd class="price"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?></br><span class="recommendation">특별할인★</span>&nbsp;&nbsp;<?php echo $it_price; ?></span></dd>
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

		<script>
$(function() {
	var itemQty1 = <?php echo $total_count ; ?>; // 총 아이템 수량
	var itemShow1 = <?php echo $mod; ?>; // 한번에 보여줄 아이템 수량
	var Flag1 = 1; // 페이지
	var EOFlag1 = parseInt(itemQty1/itemShow1); // 전체 리스트를 나눠 페이지 최댓값을 구하고
	var itemRest1 = parseInt(itemQty1%itemShow1); // 나머지 값을 구한 후
	if(itemRest1 > 0) // 나머지 값이 있다면
	{
		EOFlag1++; // 페이지 최댓값을 1 증가시킨다.
	}
	$('.inc'+Flag1).css('display','block');
	//$('.c'+Flag1).css('display','block');

	$('.paging_btn .pg_prev').click(function() {
		if(Flag1 == 1)
		{
			alert('목록의 처음입니다.');
		} else {
			Flag1--;
			$('.inc'+Flag1).css('display','block');
			$('.inc'+(Flag1+1)).css('display','none');
		}
	});

	$('.paging_btn .pg_next').click(function() {
		if(Flag1 == EOFlag1)
		{
			alert('더 이상 목록이 없습니다.');
		} else {
			Flag1++;
			$('.inc'+Flag1).css('display','block');
			$('.inc'+(Flag1-1)).css('display','none');
		}
	});


	});
</script>
	</div>
</div>
<!-- 관련상품 끝 -->



<a id="tab1"></a>
<section class="mart20">
	<h3 class="blind">상품정보탭</h3>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')" class="on">상품정보</li>
			<li onclick="javascript:pg_anchor('tab4')">배송/교환/반품안내</li>
		</ul>
	</div>

	<div class="ofh tac padt10 padb10">
	    <!--  <img src="../data/banner/item_info/detail_notice.jpg"> -->
		<?php echo get_view_thumbnail(conv_content($gs['memo'], 1), 1000); ?>
	</div>

	<?php
	if($gs['info_value']) {
		$info_data = unserialize(stripslashes($gs['info_value']));
		if(is_array($info_data)) {
			$gubun = $gs['info_gubun'];
			$info_array = $item_info[$gubun]['article'];
	?>
	<div class="mart20 marb30">
		<h2 class="anc_tit">전자상거래 등에서의 상품정보제공고시</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
				<caption>전자상거래 등에서의 상품정보제공고시</caption>
			<colgroup>
				<col width="25%">
				<col width="75%">
			</colgroup>
			<?php
			foreach($info_data as $key=>$val) {
				$ii_title = $info_array[$key][0];
				$ii_value = $val;
			?>
			<tr>
				<th scope="row"><?php echo $ii_title; ?></th>
				<td><?php echo $ii_value; ?></td>
			</tr>
			<?php } //foreach ?>
			</table>
		</div>
	</div>
	<?php
			} //array
		} //if
	?>
</section>
<a id="tab2"></a>
<br>

<a id="tab4"></a>
<br>
<section class="mart60">
	<h3 class="blind">배송/교환/반품안내 탭</h3>
	<div class="vi_tab">
		<ul>
			<li onclick="javascript:pg_anchor('tab1')">상품정보</li>
			<li onclick="javascript:pg_anchor('tab4')" class="on">배송/교환/반품안내</li>
		</ul>
	</div>
	<div class="mart15">
		<?php echo get_view_thumbnail(conv_content(get_policy_content($index_no), 1), 1000); ?>
	</div>
</section>

<script>

$(document).ready(function() {
    ready_zzim();
});

function ready_zzim(){
	var formData = $('#fbuyform').serialize();

	$.ajax({
		type: "POST",
		url: "./ajax_wish_sel.php",
		data: formData,
		dataType: "text",
		success: function (data) {
			//찜 목록이 있으면,
			if(data == "ok"){
				$('.vi_btn span.wish_btn span').addClass("active");			
			}else{
				$('.vi_btn span.wish_btn span').removeClass("active");
			}

		}
	});
}

// 상품보관
function item_wish(f)
{
	f.action = "./wishupdate.php";
	f.submit();
}

//ajax_찜처리
function ajax_item_wish(){
	
	if($('.vi_btn span.wish_btn span').hasClass("active")){
		$("#d_jjim").val("del");
	}else{
		$("#d_jjim").val("");
	}

	var formData = $('#fbuyform').serialize();

    $.ajax({
      type: "POST",
      url: "./wishupdate.php",
      data: formData,
      dataType: "text",
      success: function (data) {
				//alert(data);
				$('.vi_btn span.wish_btn span').toggleClass("active");
      }
    });
	
}


function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

	if($(".sit_opt_list").size() < 1) {
		alert("주문옵션을 선택해주시기 바랍니다.");
		return false;
	}

    var val, io_type, result = true;
    var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fbuyform_submit(sw_direct)
{
	var f = document.fbuyform;
	f.sw_direct.value = sw_direct;

	if(sw_direct == "cart") {
		f.sw_direct.value = 0;
	} else if(sw_direct == "cartback") {
		f.sw_direct.value = 2;
	} else { // 바로구매
		f.sw_direct.value = 1;
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


	f.action = "./cartupdate.php";

	//alert(f.sw_direct.value);

	f.submit();
}

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
// iframe태그 title속성 넣어줌
$(document).ready(function(){
	$('.ofh iframe').attr('title','상품소개동영상');
});

$(function(){
function copyUrl(url){
    navigator.clipboard.writeText(url)
        .then(() => {
            console.log('Text copied to clipboard');
        })
        .catch(err => {
            console.error('Error in copying text: ', err);
    });
}

$("#shortUrlRes").on("click",function(){
    $.ajax({
        type: "POST",
        url: "./ajax_short_url.php",
        data : {"preUrl":window.location.href},
        dataType: "json",
        success: function (data) {
            $("#shortUrlBox").css("display","revert");
            var obj = JSON.parse(data);
            var url = obj.result.url;

            $("#shortUrl").val(url);
            $("#shortUrl").select();
            copyUrl(url);
        },
        error:function(data){
            console.log("error : "+data)
        }
    });
});

});


</script>
<?php
// 인플루언서 전용 판매가격 계산식 수정, css 일부 변경
echo
    '<script>
			$(".price_bx .price .mpr").css("font-weight","normal");            
			$(".wish_btn").css("display","none");
			$(".price_bx .price .mpr").append(`&nbsp;&nbsp;<span class="extra_sale_price" style="font-weight:lighter; font-size : 15px; padding:1px; background:black; color:white;">특별할인★</span>`);
    </script>';
?>
