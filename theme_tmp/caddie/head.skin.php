<?php
if(!defined('_TUBEWEB_')) exit;

if(defined('_INDEX_')) { // index에서만 실행
    include_once(TB_LIB_PATH.'/popup.inc.php'); // 팝업레이어
}
// (2020-12-10)마니아몰 링크타고 들어오면 모든 페이지에서 팝업
else if($pt_id=='maniamall' && $_GET['popup']=='yes'){
    include_once(TB_LIB_PATH.'/maniamall_popup.inc.php'); // 팝업레이어
}

?>

<div id="wrapper">
    <div id="header">
        <!--
        (2020-12-10) 배너 슬라이드 코드 삭제
        <div id="hd_banner">
        </div>
        -->
        <div id="hd">
            <!-- 상단부 영역 시작 { -->
            <!-- <div id="hd_inner"> 헤더영역 시작 -->
<?php 
// (2020-12-10) 각 연동사별 head 스킨파일 분리
$head_skin_file = TB_THEME_PATH."/".$pt_id."_head.skin.php";
if( file_exists($head_skin_file) ){
    include_once($head_skin_file);
}else{
    include_once(TB_THEME_PATH."/admin_head.skin.php");
}
?>
            <!-- </div> 헤더영역 끝 -->
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
<?php
// 2021-08-13
$gw_menu_list = array(2, 8, 5, 6, 7, 1, 3,4,9 );
foreach( $gw_menu_list as $i ){
    $seq = ($i-1);
    $page_url = TB_URL.$gw_menu[$seq][1];
    if( ($default['de_pname_use_'.$i] != 1 && $default['de_pname_use_'.$i] != 2)  || !$default['de_pname_'.$i])
        continue;

    $img = "";
    if($i==8){$img = "<img src='/img/clock.png' style='width:21px'>";}
    echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$i].$img.'</a></li>'.PHP_EOL;
    
}

                        /*
                        for($i=0; $i<count($gw_menu); $i++) {
                            $seq = ($i+1);
                            $page_url = TB_URL.$gw_menu[$i][1];
                            // (2021-03-17) 1:전체표시, 2:PC만표시
                            //if(!$default['de_pname_use_'.$seq] || !$default['de_pname_'.$seq])
                            if( ($default['de_pname_use_'.$seq] != 1 && $default['de_pname_use_'.$seq] != 2)  || !$default['de_pname_'.$seq])
                                continue;

                            echo '<li><a href="'.$page_url.'">'.$default['de_pname_'.$seq].'</a></li>'.PHP_EOL;
                        }
                         */
?>
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
            <!-- } 상단부 영역 끝 -->
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
</script>
        </div>

<?php
if(defined('_INDEX_')) { // index에서만 실행
    $sql = sql_banner_rows(0, $pt_id);
    $res = sql_query($sql);
    $mbn_rows = sql_num_rows($res);
    if($mbn_rows) {
?>

<?php 
    }
?>
    </div>
<? }// srixon close ?>
</div>

    <div id="container">
<?php
    if(!is_mobile()) { // 모바일접속이 아닐때만 노출

        if($pt_id == 'srixon')//스릭슨 서브페이지만 퀵메뉴 출력_20190727
        {
            if(!defined('_INDEX_')) {
                include_once(TB_THEME_PATH.'/quick.skin.php'); // 퀵메뉴
            }
        }
        else
        {
            include_once(TB_THEME_PATH.'/quick.skin.php'); // 퀵메뉴
        }
    }

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
