<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 퀵메뉴 좌측날개 시작 { -->
<!-- <div id="qcl">
    <?php echo display_banner_rows(90, $pt_id); ?>
</div> -->

<!-- <div id="qcl">
    <div>
<?php
$sql_search = " and sidebanner ";
$sql_common = sql_goods_list($sql_search);

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * $sql_common limit 0, 11";
$result = sql_query($sql);

?>
        <div id="side_wrap">
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
    $it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
    $it_image = get_it_image($row['index_no'], $row['simg1'], 100, 100);
    $it_name = cut_str($row['gname'], 100);
    $it_price = get_price($row['index_no']);
    $it_amount = get_sale_price($row['index_no']);
    $it_point = display_point($row['gpoint']);
    $it_sidebanner = $row['sidebanner'];

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
    if($it_main_use !== '0'){
?>
            <div>
                <div>
                    <a href="<?php echo $it_href; ?>">
                        <dl>
                            <dt><?php echo $it_image; ?></dt>
                            <dd class="pname"><?php echo $it_name; ?></dd>
                            <dd class="price"><span class="price_box"><?php echo $it_price; ?></span></dd>
                        </dl>
                    </a>
                </div>
            </div>
<?php
    }
}
?>
        </div>
        <?php if($pr_tot_count > 0){ ?>
        <div class="stv_wrap">
            <img src="<?php echo TB_IMG_URL; ?>/bt_qcr_prev.gif" id="up">
            <span id="stv_pg"></span>
            <img src="<?php echo TB_IMG_URL; ?>/bt_qcr_next.gif" id="down">
        </div>
    <?php } ?>
    </div>
</div> -->


<!--
<div id="qcl">
<?php
    // (2021-04-06)
     $timesale_img_file = TB_PATH."/".TB_IMG_DIR."/".$pt_id."_sale_title.png";
     if( file_exists($timesale_img_file) ){ ?>
        <h2 class="qcl_name"><img src="<?php echo "/img/".$pt_id."_sale_title.png"; ?>" alt="타임특가"></h2>
    <?php }else{ ?>
        <h2 class="qcl_name"><img src="/img/killdeal_sale_title.png" alt="타임특가" ></h2>
    <?php } ?>

    <div class="slide">
        <ul>
<?php


// 2021-08-11
$sql_search = " and 1!=1 ";
$sql_order = "";
$ts = sql_fetch("select * from shop_goods_timesale where ts_sb_date <= NOW() and ts_ed_date >= NOW() ");
if( isset($ts) ){
    $ts_list_code = explode(",", $ts[ts_it_code]); // 배열을 만들고
    $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
    $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
    $ts_list_code = implode(",",$ts_list_code );
    $sql_search = " and index_no in ( $ts_list_code )";
    $sql_order = " order by field ( index_no, $ts_list_code ) ";
}

$sql_common = sql_goods_list($sql_search);

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if( $total_count > 0 ){
    $sql = " select * $sql_common $sql_order ";
    $result = sql_query($sql);
    $row=sql_fetch_array($result);
    $it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
    $it_image = get_it_image($row['index_no'], $row['simg1'], 130, 130);
    $it_name = cut_str($row['gname'], 100);
    $it_price = get_price($row['index_no']);
?>
    <li class="n<?php echo $k; ?>" style="position:relative">
        <?php if(strpos($it_price,"품절")){ ?>
        <div style="position:absolute; z-index:100; width:100%;height:100%; text-align:center; background-color:rgba(100,100,100,0.1)"><img src="/img/timesale_soldout.png" style="width:100%"></div>
        <?php } ?>
        <a href="<?php echo $it_href; ?>">
            <?php echo $it_image; ?>
            <span class="pname"><?php echo $it_name; ?></span>
            <span class="price"><span class="price_box ">특별회원가</span></span>
        </a>
<?php
}else{
    echo '<p class="no_item">없음</p>';
}
?>
    </li>
    </ul>
    <div class="stv_wrap wsetbg">
        <span><a href="/shop/timesale.php" class="fc_wht">더보기</a></span>
    </div>
    </div>
    <?php if($pt_id == 'baksajang'){ // (2021-01-20)박사장몰 카카오채널   ?>
    <div id="hong_link_wrap" style="text-align:center;margin-top:5px;">
        <a href="https://pf.kakao.com/_hwBgxb" style="text-decoration:none" target="_blank">
            <img src="/img/kakao_baksajang.png" style="width:120px;">
        </a>
    </div>
    <?php } ?>
</div>
-->
<!-- } 퀵메뉴 좌측날개 끝 -->

<!-- 퀵메뉴 우측날개 시작 { -->
<div id="qcr">
    <p class="qcr_tit">오늘 본 상품<br><span style="font-size:12px;"></span></p>
    <ul>
        <li>
<?php
    $pr_tmp = get_cookie('ss_pr_idx');
$pr_idx = explode('|',$pr_tmp);
$pr_tot_count2 = 0;
$k2 = 0;
$mod2 = 10;
foreach($pr_idx as $idx)
{
    $rowx = get_goods($idx, 'index_no, simg1');
    if(!$rowx['index_no'])
        continue;

    $href = TB_SHOP_URL.'/view.php?index_no='.$idx;

    if($pr_tot_count2 % $mod2 == 0) $k2++;

    $pr_tot_count2++;

    if($pr_tot_count2 < 10){
?>

        <p class="dn c<?php echo $k2; ?>">
        <a href="<?php echo $href; ?>"><?php echo get_it_image($idx, $rowx['simg1'], 80, 80); ?></a>
        </p>
<?php
    }
}
if(!$pr_tot_count2)
    echo '<p style="height:80px;line-height:6">없음</p>';
echo '<p class="no_item"></p>'
?>
        </li>
    </ul>

    <a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="qcr_btn qcr_btn_cart"><div class="idiv"><span class="ic_num"><?php echo get_cart_count(); ?></span></div>장바구니</a>
    <!-- 주문/배송조회  -->
    <a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" class="qcr_btn qcr_btn_order"><i></i>주문/배송조회</a>
    <button type="button" id="anc_up" class="qcr_btn qcr_btn_top"><i></i>TOP</button>



</div>
<!-- } 퀵메뉴 우측날개 끝 -->

<!-- <div class="qbtn_bx">
    <button type="button" id="anc_up">TOP</button>
    <button type="button" id="anc_dw">DOWN</button>
</div> -->

    <script>
    $(function() {
        var items;
        var items_pos = 1;

        items = $('#qcl .slide ul');

        var itemQty = <?php echo $i; ?>; // 총 아이템 수량
        var itemShow = <?php echo $mod; ?>; // 한번에 보여줄 아이템 수량
        var Flag = 1; // 페이지
        var EOFlag = parseInt(itemQty/itemShow); // 전체 리스트를 나눠 페이지 최댓값을 구하고
        var itemRest = parseInt(itemQty%itemShow); // 나머지 값을 구한 후
        if(itemRest > 0) // 나머지 값이 있다면
        {
            EOFlag++; // 페이지 최댓값을 1 증가시킨다.
        }
        $('.c'+Flag).css('display','block');
        $('#stv_pg').text(Flag+' / '+EOFlag); // 페이지 초기 출력값


        $('#prev').click(function() {
            if(Flag == 1)
            {
                //alert('목록의 처음입니다.');
            } else {
                pageBack();
                Flag--;
            }
            $('#stv_pg').text(Flag+' / '+EOFlag); // 페이지 값 재설정
        })


            $('#next').click(function() {
                if(Flag == EOFlag)
                {
                    //alert('더 이상 목록이 없습니다.');
                } else {
                    pageNext();
                    Flag++;
                }
                $('#stv_pg').text(Flag+' / '+EOFlag); // 페이지 값 재설정
            });

        function pageNext(){
            if(itemQty>items_pos){
                items.animate({left:'-=130px'},200);
                items_pos++;
            }
        }

        function pageBack(){
            if(1<itemQty){
                items.animate({left:'+=130px'},200);
                items_pos--;
            }
        }

        $(window).scroll(function () {
            var pos = $("#ft").offset().top - $(window).height();
            if($(this).scrollTop() > 0) {
                $(".qbtn_bx").fadeIn(300);
                if($(this).scrollTop() > pos) {
                    $(".qbtn_bx").addClass('active');
                }else{
                    $(".qbtn_bx").removeClass('active');
                }
            } else {
                $(".qbtn_bx").fadeOut(300);
            }
        });

        // 퀵메뉴 상위로이동
        $("#anc_up").click(function(){
            $("html, body").animate({ scrollTop: 0 }, 400);
        });

        // 하위로이동
        $("#anc_dw").click(function(){
            $("html, body").animate({ scrollTop: $(document).height() }, 400);
        });

        // 좌/우 퀵메뉴 높이 자동조절
        <?php if(defined('_INDEX_')) { ?>
        var Theight = ($("#header").height() + $("#maniamall_hd").height()) - $("#gnb").height();
        var ptop = 30;
        <?php } else { ?>
        var Theight = ($("#header").height() + $("#maniamall_hd").height()) - $("#gnb").height();
        var ptop = 20;
        <?php } ?>
        $("#qcr, #qcl").css({'top':ptop + 'px'});

        $(window).scroll(function () {
            if($(this).scrollTop() > Theight) {
                $("#qcr, #qcl").css({'position':'fixed','top':'67px','z-index':'999'});
            } else {
                $("#qcr, #qcl").css({'position':'absolute','top':ptop + 'px'});
            }
        });

        /*
    // (2021-01-26) 퀵메뉴 하단 제한
     $(window).scroll(function () {
         if($(this).scrollTop() > Theight) {
             if($(this).scrollTop() > 4420) {
                 $("#qcr, #qcl").css({'position':'absolute','top':3680 + 'px'});
             }else{
                 $("#qcr, #qcl").css({'position':'fixed','top':'67px','z-index':'900'});
             }
         } else {
             $("#qcr, #qcl").css({'position':'absolute','top':ptop + 'px'});
         }
     });

        */

    });
/*
// 서브페이지 킬딜특가 배경 흰색변경
var thisPage = $(location).attr('href').split('/');

if(thisPage[3]){
    $('#qcl .qcl_name').css({"background-color":"#fff"});
}
*/
</script>
<!-- } 우측 퀵메뉴 끝 -->
