<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="wrapper">
    <div id="header">
        <div id="hd" class="mart30">
        <?php 
        // (2020-12-10) 각 연동사별 head 스킨파일 분리
        $head_skin_file = TB_THEME_PATH."/".$pt_id."_head.skin.php";
        if( file_exists($head_skin_file) ){
            include_once($head_skin_file);
        }else{
            include_once(TB_THEME_PATH."/admin_head.skin.php");
        }
        ?>            
            <!--
            <div id="hd_inner">
                <div class="hd_bnr">
                    <span><?php echo display_banner(2, $pt_id); ?></span>
                </div>
                <h1 class="fl hd_logo" >
                    <?php echo display_logo(); ?>
                </h1>
                <ul class="tnb_member">
                    <li class="cart"><a href="<?php echo TB_SHOP_URL?>/cart.php"><i></i>장바구니</a></li>
                    <li class="order"><a href="<?php echo TB_SHOP_URL;?>/orderinquiry.php"><i></i>주문/배송조회</a></li>
                </ul>
            </div>
            -->
        </div>
    </div>

    <div id="gnb">
                <div id="gnb_inner">
                    <div class="all_cate">
                       <!-- 전체카테고리 -->
                        <span class="allc_bt"><i class="fa fa-bars"></i> 전체카테고리</span>

                    </div> 
                    <div class="gnb_li">
                        <ul>
<?php
$mod = 5;
$res = sql_query_cgy('all');
for($i=0; $row=sql_fetch_array($res); $i++) {
    $href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];
?>
                            <li class="main_gnb">
                                <a class="wseta" href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
                            </li>
<?php
}
?>
                    <span class= "show_count" style="display:none;">
                        <select name="show_count_select" onchange="change_show_count(this.value)">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5" selected>5</option>
                        </select>
                    <span>
                        </ul>
                    </div>


                    
                    <div id="hd_sch">
                        <fieldset class="sch_frm">
                            <legend>사이트 내 전체검색</legend>
                            <form name="fsearch" id="fsearch" method="post" action="<?php echo TB_SHOP_URL; ?>/search.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
                            <input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
                            <label><input type="text" name="ss_tx" class="sch_stx" maxlength="100" placeholder=""></label>
                            <button type="submit" class="sch_submit fa fa-search" value="검색"></button>
                            </form>
<script>
$(function(){
    // 상단메뉴 따라다니기
    var elem1 = $("#hd_banner").height() + $("#maniamall_hd").height() + $("#tnb").height() + $("#hd_inner").height();
    var elem2 = $("#hd_banner").height() + $("#maniamall_hd").height() + $("#tnb").height() + $("#hd").height();
    var elem3 = $("#gnb").height();
    $(window).scroll(function () {
        if($(this).scrollTop() > elem1) {
            $("#gnb").addClass('gnd_fixed');
            $("#hd").css({'padding-bottom':elem3})
        } else if($(this).scrollTop() < elem2) {
            $("#gnb").removeClass('gnd_fixed');
            $("#hd").css({'padding-bottom':'0'})
        }
    });
});

function change_show_count(a){
    $('#bstab_c0').css('display','grid'); //그리드 모드 변환
    $('#bstab_c0').css('grid-template-columns',`repeat(${a}, 1fr)`); //열 개수 변환
    var now_width = $('.wli5').children('li').width(); //현재 넓이
    console.log(now_width);
    //$('.wli5').css('width',now_width + now_width/a);
}

function fsearch_submit(f){
    if(!f.ss_tx.value){
        alert('검색어를 입력하세요.');
        return false;
    }
    return true;
}
</script>
                        </fieldset>
                    </div>
                </div>
                <div class="con_bx">
                        <ul>
<?php
$mod = 5;
$res = sql_query_cgy('all');
for($i=0; $row=sql_fetch_array($res); $i++) {
    $href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

    if($i && $i%$mod == 0) echo "</ul>\n<ul>\n";
?>
                            <li class="c_box">
                                <a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
<?php
    $r = sql_query_cgy($row['catecode'], 'COUNT');
    if($r['cnt'] > 0) {
?>
                                <ul>
<?php
        $res2 = sql_query_cgy($row['catecode']);
        while($row2 = sql_fetch_array($res2)) {
            $href2 = TB_SHOP_URL.'/list.php?ca_id='.$row2['catecode'];
?>
                                    <li><a href="<?php echo $href2; ?>"><?php echo $row2['catename']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </li>
<?php
}
$li_cnt = ($i%$mod);
if($li_cnt) { // 나머지 li
    for($i=$li_cnt; $i<$mod; $i++)
        echo "<li></li>\n";
}
?>
                        </ul>
                    </div>
<script>
$(function(){
    $('.all_cate .allc_bt').click(function(){
        if($('.con_bx').css('display') == 'none'){
            $('.con_bx').show();
            $(this).html('<i class="ionicons ion-ios-close-empty"></i> 전체카테고리');
        } else {
            $('.con_bx').hide();
            $(this).html('<i class="fa fa-bars"></i> 전체카테고리');
        }
    });
});
</script>
            </div>




    <div id="container">
        <?php  //include_once(TB_THEME_PATH.'/quick.skin.php'); // 퀵메뉴 ?>

<?php
if(!defined('_INDEX_')) { // index가 아니면 실행
    $gp_string = $_SERVER['REQUEST_URI'];
    $gp_find = "?";
    $pos = strpos($gp_string, $gp_find);

    $gp_string_val = substr($gp_string, 0, $pos);

    if('/shop/list.php' != $gp_string_val && '/shop/listtype.php?'  != $gp_string_val && '/shop/cart.php' != $_SERVER['REQUEST_URI'] && '/shop/orderform.php' != $_SERVER['REQUEST_URI']) {
        echo '<div class="sub_cont"><div class="cont_inner">'.PHP_EOL;
    } else {
        echo '<div class="list_sub"><div class="cont_inner">'.PHP_EOL;
    }
}
?>
