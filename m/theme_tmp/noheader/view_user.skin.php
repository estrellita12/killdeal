<?php
if(!defined('_TUBEWEB_')) exit;

$sql_common = " from shop_goods_review ";
$sql_search = " where gs_id = '$index_no' ";
if($default['de_review_wr_use']) {
    $sql_search .= " and pt_id = '$pt_id' ";
}

$sql_order  = " order by index_no desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$res = sql_query($sql);

?>
<style>
.review_box div{
    overflow:hidden;
    word-break:break-all;
}
.review_box .bm{
    width : 60px;
    position: relative;
}

.review_box .bm:after {
    content: '';
    position: absolute;
    top : 6px;
    right : 1px;
    width: 20px;
    height: 8px;
    background: url(/img/btn/m_btn_sidem_arrow.png) no-repeat 0 -8px;
    background-size: 20px 16px;
}

.review_box .bm.active:after {        
    transform: rotate(180deg);
    background-position: 0 0px;
}

.review_box .img_box img{
    max-width : 500px;
    width : 99%;

}
</style>
<div class=sp_vbox_qa>
<ul>
    <li class="tlst">상품리뷰 <span class=cate_dc> <?php echo $item_use_count; ?>건</span></li>
    <li class="trst">
        <?php  //echo "<li class='trst'><a href=\"javascript:window.open('".TB_MSHOP_URL."/view_user_form.php?gs_id=$gs_id','_self');\" class='btn_lsmall black'>상품리뷰 작성</a></li>\n"; ?>
        <li class='trst'><a href="<?php echo TB_MSHOP_URL ?>/view_user_form.php?gs_id=<?php echo $gs_id?>" target="_self" class='btn_lsmall black'>상품리뷰 작성</a></li>
    </li>
</ul>
</div>
<ul class="lst_w">

<?php
for($i=0; $row=sql_fetch_array($res); $i++) {
    $wr_id = substr($row['mb_id'],0,3).str_repeat("*",strlen($row['mb_id']) - 3);
    $wr_time = substr($row['reg_time'],0,10);


    $age = "선택없음";
    switch($row['age']){
    case 1: $age = "10대"; break;
    case 2: $age = "20대"; break;
    case 3: $age = "30대"; break;
    case 4: $age = "40대"; break;
    case 5: $age = "50대"; break;
    case 6: $age = "60대"; break;
    case 7: $age = "70대"; break;
    case 8: $age = "80대이상"; break;
    }

    $level = "선택없음";
    switch($row['level']){
    case 1: $level = "입문자"; break;
    case 2: $level = "초급자"; break;
    case 3: $level = "중급자"; break;
    case 4: $level = "상급자"; break;
    case 5: $level = "프로"; break;
    }


    $gender = "선택없음";
    switch($row['gender']){
    case 'M': $gender = "남성"; break;
    case 'W': $gender = "여성"; break;
    }
?>

<div class="review_box bt pad10">
    <table class="wfull viewuser">
        <tbody>
        <tr>
            <td> 
                <?php for($i=0;$i<(int)$row['score'];$i++) { ?><img src="<?php echo TB_IMG_URL; ?>/sub/comment_start.jpg" align="absmiddle"><?php } ?> &nbsp;&nbsp;&nbsp; 
            </td>
            <td rowspan="4" class="w110 tar img_box"> 
<?php
    $rimg = TB_DATA_PATH.'/review/'.$row['re_file'];
    if(is_file($rimg) && $row['re_file']) {
        $size = @getimagesize($bimg);
        $width = 100;
        $rimg = rpc($rimg, TB_PATH, TB_URL);
        echo '<img src="'.$rimg.'" width="'.$width.'"><br><br>';
    }
?>
            </td>
        </tr>
        <tr>
            <td class="fc_666 padb5"> 
                <div><?php echo $wr_id; ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo $wr_time; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(is_admin() || ($member['id'] == $row['mb_id'])) { ?><a href="javascript:tdel('<?php echo TB_SHOP_URL; ?>/view_user_update.php?gs_id=<?php echo $index_no; ?>&it_mid=<?php echo $row['index_no']; ?>&mode=d');"><img src="<?php echo TB_IMG_URL; ?>/icon/icon_x.gif" width="15" height="15" align="absmiddle">삭제</a>&nbsp;&nbsp;<?php } ?></div>
            </td>
        </tr>
        <tr>
            <td> 
                <div class="fc_888"><?php echo $gender;?> /  <?php echo $age;?> /  <?php echo $level;?> </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="memo_box  lineclamp2">
                    <?php echo str_replace("\n","<br>",$row['memo']); ?>
                </div>
                <br>
                <div class="img_box dn"> 
<?php
    $rimg = TB_DATA_PATH.'/review/'.$row['re_file'];
    if(is_file($rimg) && $row['re_file']) {
        $size = @getimagesize($bimg);
        $width = 500;
        $rimg = rpc($rimg, TB_PATH, TB_URL);
        echo '<img src="'.$rimg.'" width="'.$width.'"><br><br>';
    }
?>
                </div>
                <div class="add_box curp fs13 bm">더보기 </div>
            </td>
        </tr>
    </table>
</div>

<?php } ?>

    <script>
    $(function(){
        $(".review_box").on("click", function() {
            $(this).find('.memo_box').toggleClass('lineclamp2');
            $(this).find('.img_box').toggleClass('dn');
            $(this).find('.add_box').toggleClass('active');
        });
    });


    function tdel(url){
        if(confirm('삭제 하시겠습니까?')){
            location.href = url;
        }
    }

    </script>
