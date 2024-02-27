<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo TB_MJS_URL; ?>/shop.js"></script>

<form name="fbuyform" method="post" id="fbuyform">
<input type="hidden" name="gs_id[]" value="<?php echo $gs_id; ?>">
<input type="hidden" id="it_price" value="<?php echo get_sale_price($gs_id); ?>">
<input type="hidden" name="ca_id" value="<?php echo $gs['ca_id']; ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="d_jjim" value="" id="d_jjim">

<div class="sp_wrap">
	<div class="subject">
		<?php echo get_text($gs['gname']); ?>
		<?php if($gs['explan']) { ?>
		<p class="sub_txt"><?php echo get_text($gs['explan']); ?></p>
		<?php } ?>
	</div>
	<div class="sp_sub_wrap">
		<div class="v_cont">
			<ul class="v_horiz">
				<li><?php echo get_it_image($gs_id, $gs['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], 'id="slideshow"'); ?></li>
			</ul>
		</div>
		<!--20191029 기능오류 비활성화
		<a class="sp_b_a fa fa-angle-left" href="javascript:chgimg(-1)"></a>
		<a class="sp_b_a fa fa-angle-right" href="javascript:chgimg(1)"></a>
		-->
	</div>
	

	<!-- <div class="sp_sns">
		<?php echo $sns_share_links; ?>
	</div> -->
	<!-- 20200519 주석 (상품만족도/상품평 비노출로 변경요청) -->
	<!-- <div class="sp_sns review_box">
		<ul>
			<li>상품 만족도 <span><?php echo $aver_score; ?>%</span></li>
			<li>상품평 <span><?php echo number_format($item_use_count); ?></span>건</li>
		</ul>
	</div> -->

	<?php if($is_social_end) { ?>
	<div class="sp_tol">
		<div class="sp_fpg">
			<span class="sp_s_n"> <?php echo $is_social_txt; ?> </span>
		</div>
	</div>
	<?php } ?>

	<?php if($is_social_ing || $is_timesale) { //2021-08-10 ?>
	<div class="sp_tol">
		<div class="social">
            <?php //include_once(TB_MTHEME_PATH.'/time.skin.php'); ?>
            <?php include_once(TB_MTHEME_PATH.'/timer.skin.php'); ?>
		</div>
	</div>
	<?php } ?>

	<?php if(!$is_only) { ?>
		<?php if(!$is_pr_msg && !$is_buy_only && !$is_soldout && $gs['normal_price']) { ?>
		<div class="sp_tbox">
			<ul>
				<li class='tlst'>시중가격</li>
				<li class='trst fc_137 tl'><?php echo display_price2($gs['normal_price']); ?></li>
			</ul>
		</div>
		<?php } ?>
		<div class="sp_tbox">
			<ul>
				<li class='tlst'>판매가격</li>
				<li class='trst'>
					<div class='trst-amt'><?php echo mobile_price($gs_id); ?></div>
				</li>
			</ul>
			<?php if(is_partner($member['id']) && $config['pf_payment_yes']) { ?>
			<ul class="mart3">
				<li class='tlst'>판매수익</li>
				<li class="trst"><?php echo display_price2(get_payment($gs_id)); ?></li>
			</ul>
			<?php } ?>
	</div>
	<?php } ?>

	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>상품코드</li>
			<li class='trst'><?php echo $gs['gcode']; ?></li>
		</ul>
	</div> -->
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>포인트</li>
			<li class='trst strong'><?php echo $gpoint; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $cp_used) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>쿠폰발급</li>
			<li class='trst-cp'><?php echo $cp_btn; ?></li>
		</ul>
	</div>
	<?php } ?>

	<?php if($gs['maker']) { ?>
	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>제조사</li>
			<li class='trst'><?php echo $gs['maker']; ?></li>
		</ul>
	</div> -->
	<?php } ?>
	<?php if($gs['origin']) { ?>
	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>원산지</li>
			<li class='trst'><?php echo $gs['origin']; ?></li>
		</ul>
	</div> -->
	<?php } ?>
	<?php if($gs['brand_nm']) { ?>
	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>브랜드</li>
			<li class='trst'><?php echo $gs['brand_nm']; ?></li>
		</ul>
	</div> -->
	<?php } ?>
	<?php if($gs['model']) { ?>
	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>모델명</li>
			<li class='trst'><?php echo $gs['model']; ?></li>
		</ul>
	</div> -->
	<?php } ?>
	<?php if($gs['odr_min']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>최소구매수량</li>
			<li class='trst'><?php echo display_qty($gs['odr_min']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['odr_max']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>최대구매수량</li>
			<li class='trst'><?php echo display_qty($gs['odr_max']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php
	$sc_class = "sp_tbox";
	if(in_array($gs['sc_type'], array('2','3')) && $gs['sc_method'] == '2') {
		$sc_class = "sp_obox";
	}
	?>
	<div class="<?php echo $sc_class; ?>">
		<ul>
			<li class='tlst'>배송비</li>
			<li class='trst'><?php echo mobile_sendcost_amt(); ?></li>
		</ul>
	</div>
    <style>
        #shortUrl:focus {
            outline:none;
            -webkit-tap-highlight-color : transparent;
        }
    </style>
    <!--
    <div class="sp_tbox">
        <ul>
            <li class='tlst'>공유하기</li>
            <li class='trst'>
                <span id="shortUrlRes" class="curp" style="color:#006daa">링크복사 <i class="fa fa-share-alt" aria-hidden="true"></i></span>
                <span id="shortUrlBox" class="dn marl5"><input type="text" id="shortUrl" class="w150 tal vat"></span>
            </li>
        </ul>
    </div>
    -->

	<!-- 20200519주석 비노출로 변경요청 주석 -->
	<!-- <div class="sp_tbox">
		<ul>
			<li class='tlst'>배송가능지역</li>
			<li class='trst padt2'><?php echo $gs['zone']; ?> <?php echo $gs['zone_msg']; ?></li>
		</ul>
	</div> -->
	
	<?php 
		$ipn = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		if($pt_id == 'thegolfshow' && $ipn>1){
			echo "<div class=\"buy-btn_cont buy-btn_tgs\" style=\"margin-bottom:36px;\">";
		}else {
			echo "<div class=\"buy-btn_cont\">";
		}
	?>
		<p class="btn-arrow">
			<a href="javascript:"><i></i></a>
		</p>

		<div class="buy-btn_cont_wrap" style="display:none;">
			<?php //20191108 !$is_soldout 조건문에서 삭제 css 깨지는 문제 때문  && !$is_soldout
			if(!$is_only && !$is_pr_msg && !$is_buy_only) { ?>
			<?php if(($pt_id != 'honggolf' && $pt_id != 'maniamall') && $option_item) { ?>
			<div class="sp_tbox">
				<ul>
					<li class='trst padt2'>옵션을 선택해주세요.</li>
				</ul>
			</div>
			<?php echo $option_item; ?>
			<?php } else if((($pt_id == 'honggolf' || $pt_id == 'maniamall') && $is_member) && $option_item) { ?>
			<dl>
			<div class="sp_tbox">
				<ul>
					<li class='trst padt2'>옵션을 선택해주세요.</li>
				</ul>
			</div>
				<?php echo $option_item; ?>
			</dl>
			<?php } ?>

			<?php if($supply_item) { ?>
			<div class="sp_tbox">
				<ul>
					<li class='tlst strong'>추가구성</li>
					<li class='trst padt2'>추가구매를 원하시면 선택하세요</li>
				</ul>
			</div>
			<?php echo $supply_item; ?>
			<?php } ?>

			<!-- 선택된 옵션 시작 { -->
			<div id="option_set_list">
				<?php if(!$option_item) { ?>
				<ul id="option_set_added">
					<li class="sit_opt_list">
						<div class="sp_tbox">
						<input type="hidden" name="io_type[<?php echo $gs_id; ?>][]" value="0">
						<input type="hidden" name="io_id[<?php echo $gs_id; ?>][]" value="">
						<input type="hidden" name="io_value[<?php echo $gs_id; ?>][]" value="<?php echo $gs['gname']; ?>">
						<input type="hidden" class="io_price" value="0">
						<input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
							<ul>
								<li class='tlst padt5'>
									<span class="sit_opt_subj">수량</span>
									<span class="sit_opt_prc"></span>
								</li>
								<li class='trst'>
									<dl>
										<dt class='fl padr3'><button type="button" class="btn_small grey">감소</button></dt>
										<dt class='fl padr3'><input type="text" name="ct_qty[<?php echo $gs_id; ?>][]"
										value="<?php echo $odr_min; ?>" title="수량설정"></dt>
										<dt class='fl padr3'><button type="button" class="btn_small grey">증가</button><dt>
										<dt class='fl padt4 tx_small'> (남은수량 : <?php echo $gs['stock_mod'] ? $gs['stock_qty'].'개' : '무제한'; ?>)</dt>
									</dl>
								</li>
							</ul>
						</div>
					</li>
				</ul>
				<script>
				$(function() {
					price_calculate();
				});
				</script>
				<?php } ?>
			</div>
		
		</div>
		<!-- } 선택된 옵션 끝 -->

		<!-- 총 구매액 -->
		<div id="sit_tot_views" class="dn on">
			<div class="sp_tot">
				<ul>
					<li class='tlst strong'>총 합계금액</li>
					<li class='trst'><span id="sit_tot_price" class="trss-amt"></span><span class="trss-amt">원</span></li>
				</ul>
			</div>
		</div>
		<?php } ?>

		<div>
			<a href="javascript:;" class="wset buy-open_btn">구매하기</a>
			<?php if(!$is_pr_msg) { ?>
			<div class="sp_vbox tac"  style="display:none;">
				<?php echo mobile_buy_button($script_msg, $gs_id); ?>
				<?php if($naverpay_button_js) { ?>
				<div class="naverpay-item"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>

<!-- 	<?php
	$sql = " select b.*
			   from shop_goods_relation a left join shop_goods b ON (a.gs_id2=b.index_no)
			  where a.gs_id = '{$gs_id}'
				and b.shop_state = '0'
				and b.isopen < 3 ";
	$res = sql_query($sql);
	$rel_count = sql_num_rows($res);
	if($rel_count > 0) {
	?>
	<div class="sp_rel">
		<h3><span>현재상품과 연관된 상품</span></h3>
		<div>
			<?php
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
				$it_name = cut_str($row['gname'], 50);
				$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], 400, 400);
				$it_price = mobile_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);
	
				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
					$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
					$it_sprice = display_price2($row['normal_price']);
				}
			?>
			<dl>
			<a href="<?php echo $it_href; ?>">
				<dt><img src="<?php echo $it_imageurl; ?>"></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $it_price; ?>&nbsp;<?php echo $it_sprice; ?></dd>
			</a>
			</dl>
			<?php } ?>
		</div>
		<?php if($rel_count > 3) { ?>
		<script>
		$(document).ready(function(){
			$('.sp_rel div').slick({
				autoplay: false,
				dots: false,
				arrows: true,
				infinite: false,
				slidesToShow: 3,
				slidesToScroll: 1
			});
		});
		</script>
		<?php } ?>
	</div>
	<?php } ?> -->

<!-- 고정배너 시작 -->
<?php
	$sql = sql_banner_rows(101,'admin');
	$res = sql_query($sql);
	$result = sql_num_rows($res);
	if($result){
	?>
		<div id="fix_banner_wrap" style="width:100%;box-sizing:border-box;">
	<?php
		for($i=0;$row=sql_fetch_array($res);$i++){
			$str = '<a href='.TB_URL.$row['bn_link'].' style="display:block;margin-bottom:3px;"><img src='.TB_DATA_URL.'/banner/'.$row['bn_file'].' style="width:100%"></a>';
			echo $str;
		}
	?>
</div>
<?php	} ?>
<!-- 고정배너 끝 -->

	<div class="sp_tab">
		<nav role="navigation">
			<ul>
				<li id='d1' class="active"> <a href="javascript:chk_tab(1);">상품정보</a> </li>
				<li id='d2'> <a href="javascript:chk_tab(2);">상품리뷰</a> </li>
				<li id='d3'> <a href="javascript:chk_tab(3);">상품문의</a> </li>
				<li id='d4'> <a href="javascript:chk_tab(4);">반품/교환</a> </li>
			</ul>
		</nav>
	</div>
      
	<div class="sp_msgt">아래 상품정보는 옵션 및 사은품 정보 등 실제 상품과 차이가 있을수 있습니다</div>
	<div id="v1">
		<div class="sp_vbox_mr">
			<ul>
				<li class='tlst'>전자상거래 등에서의 상품정보제공 고시</li>
				<li class='trst'><a href="javascript:chk_show('extra');" id="extra">보기 <span class='im im_arr'></span></a></li>
			</ul>
		</div>

		<?php
		if($gs['info_value']) {
			$info_data = unserialize(stripslashes($gs['info_value']));
			if(is_array($info_data)) {
				$gubun = $gs['info_gubun'];
				$info_array = $item_info[$gubun]['article'];
		?>
		<div class="sp_vbox" id="ids_extra" style="display:none;">
			<?php
			foreach($info_data as $key=>$val) {
				$ii_title = $info_array[$key][0];
				$ii_value = $val;
			?>
			<ul>
				<li class='tlst<?php echo $pd_t2; ?>'>&#183;&nbsp;&nbsp;<?php echo $ii_title; ?></li>
				<li class='trst<?php echo $pd_t2; ?>'><?php echo $ii_value; ?></li>
			</ul>
			<?php
				$pd_t2 = ' padt2';
			} //foreach
			?>
		</div>
		<?php
			} //array
		} //if
		?>

		<div class="sp_vbox">
			<?php echo get_image_resize($gs['memo']); ?>
		</div>
	</div>

	<div id="v2" style="display:none;">
		<?php //echo mobile_goods_review("상품후기", $item_use_count, $gs_id); ?>
        <?php $index_no = $gs_id;  include_once(TB_MTHEME_PATH.'/view_user.skin.php'); ?>
	</div>

	<div id="v3" style="display:none;">
		<?php echo mobile_goods_qa("상품문의", $itemqa_count, $gs_id); ?>
	</div>

	<div id="v4" style="display:none;">
		<div class="sp_vbox">
			<?php echo get_policy_content($gs_id); ?>
		</div>
	</div>
</div>
</form>

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
				$('.sp_wrap .sp_btn .wish_btn').addClass("active");			
			}else{
				$('.sp_wrap .sp_btn .wish_btn').removeClass("active");
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
	
	if($('.sp_wrap .sp_btn .wish_btn').hasClass("active")){
		$("#d_jjim").val("del");
	}else{
		$("#d_jjim").val("");
	}

	var formData = $('#fbuyform').serialize();
	console.log(formData);
    $.ajax({
      type: "POST",
      url: "./wishupdate.php",
      data: formData,
      dataType: "text",
      success: function (data) {
		$('.sp_wrap .sp_btn .wish_btn').toggleClass("active");
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

	/*if(sw_direct == "cart") {
		f.sw_direct.value = 0;
	} else { // 바로구매
		f.sw_direct.value = 1;
	}*/

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
	f.submit();
}

// 전자상거래 등에서의 상품정보제공 고시
var old = '';
function chk_show(name) {
	submenu=eval("ids_"+name+".style");

	if(old!=submenu) {
		if(old) { old.display='none'; }

		submenu.display='';
		eval("extra").innerHTML = "닫기";
		old = submenu;

	} else {
		submenu.display='none';
		eval("extra").innerHTML = "보기";
		old = '';
	}
}

// 상품문의
var qa_old = '';
function qna(name){
	qa_submenu = eval("qna"+name+".style");

	if(qa_old!=qa_submenu) {
		if(qa_old) { qa_old.display='none'; }

		qa_submenu.display='block';
		qa_old=qa_submenu;

	} else {
		qa_submenu.display='none';
		qa_old='';
	}
}

// 상품문의 삭제
$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });
});

// 탭메뉴 컨트롤
function chk_tab(n) {
	for(var i=1; i<=4; i++) {
		if(eval("d"+i).className == "" && i == n) {
			eval("d"+i).className = "active";
			eval("v"+i).style.display = "";
		} else {

			if(i != n) {
				eval("d"+i).className = "";
				eval("v"+i).style.display = "none";
			}
		}
	}
}

// 미리보기 이미지
var num = 0;
var img_url = '<?php echo $slide_url; ?>';
var img_max = '<?php echo $slide_cnt; ?>';
var img_arr = img_url.split('|');
var slide   = new Array;
for(var i=0 ;i<parseInt(img_max);i++) {
	slide[i] = img_arr[i];
}

var cnt = slide.length-1;

function chgimg(ergfun) {
	if(document.images) {
		num = num + ergfun;
		if(num > cnt) { num = 0; }
		if(num < 0) { num = cnt; }

		document.slideshow.src = slide[num];
	}
}

$(document).ready(function(){
	$('#ft').css('padding-bottom', '80px');
	$('.btn_top').css('bottom', '95px');
	$('.btn_bottom').css('bottom', '60px');

	$('.btn-arrow').click(function(){
		$('.buy-open_btn,.buy-open_btn_golfya,.buy-open_btn_golfjam').toggleClass('on');
		$(this).toggleClass('on');
		$('.buy-open_btn,.buy-open_btn_golfya,.buy-open_btn_golfjam').toggle();
		$('.buy-btn_cont_wrap').slideToggle();
		$('.buy-btn_cont .sp_vbox').toggle();
		$('.buy-btn_cont #sit_tot_views').toggleClass('on');
	});


	$('.buy-open_btn,.buy-open_btn_golfya,.buy-open_btn_golfjam', ).click(function(){
		$('.btn-arrow').addClass('on');
		$(this).addClass('on');
		$(this).hide();
		$('.buy-btn_cont_wrap').slideDown();
		$('.buy-btn_cont .sp_vbox').show();
		$('.buy-btn_cont #sit_tot_views').removeClass('on');
	});
			
	// 장바구니클릭시 팝업열림 20200407
	$('.open_popup').click(function() {
		$('.fbuyform_pop').show();
		$('.fbuyform_pop .back').click(function(){
			$('.fbuyform_pop').hide();
		});
	});

	var iframeEl = $(".sp_vbox iframe");
	iframeEl.attr('title','상품소개영상');
});
</script>


<!-- 관련상품 시작 -->
<div class="vi_rel_sort">
	<div class="tab_sort">
		<span class="total marb5">관련상품</span>
	</div>

	<div class="">
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

	$mod = 4; // 가로 출력 수
	$rows = $page_rows ? (int)$page_rows : ($mod*1);
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = " select * $sql_common $sql_order limit 0, 5";
	$result = sql_query($sql);
	//if($rel_count > 0) {
	?>
		<div id="vi_rel_slide">
		<?php
		$inc = 0;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
			$it_image = get_it_image($row['index_no'], $row['simg1'], 278, 278);
			$it_name = cut_str($row['gname'], 26);
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

			if($i % $mod == 0) $inc++;
		?>
			<div class="inc">
				<a href="<?php echo $it_href; ?>">
					<dl>
						<dt><img src="<?php echo $file ?>" alt="관련상품이미지"></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $sale; ?>&nbsp;&nbsp;<span class="price_box"><?php echo $it_price; ?>&nbsp;&nbsp;<?php echo $it_sprice; ?></span></dd>
					</dl>
				</a>
			</div>
		<?php } ?>
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
			//20200407주석(장바구니클릭시 팝업열림으로 변경)
			// $('.fbuyform_pop').show();
		}
			//20200407주석		
		// $('.fbuyform_pop .back').click(function(){
		// 	$('.fbuyform_pop').hide();
		// });
		// $('.fbuyform_pop .close').click(function(){
		// 	$('.fbuyform_pop').hide();
		// });
		</script>
	</div>
</div>
<!-- 관련상품 끝 -->

<script>
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


