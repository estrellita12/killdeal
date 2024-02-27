<?php
if(!defined('_TUBEWEB_')) exit;

$col2 = "index_no,mb_id,mb_name,pt_id,seller_id,gs_id,score,memo,reg_time,gender,age,level,re_file";
$sql_common = " from ( ( select {$col2} from shop_goods_review where gs_id='{$index_no}' ";
if($default['de_review_wr_use']) {
    $sql_common .= " and pt_id = '$pt_id' ";
}
$sql_common .= " ) union all ( select {$col2} from shop_goods_review_2 where visible_yn='y' and gs_id='{$index_no}' ) ) c ";
$sql_search = " ";

/*
$sql_common = " from shop_goods_review ";
$sql_search = " where gs_id = '$index_no' ";
if($default['de_review_wr_use']) {
    $sql_search .= " and pt_id = '$pt_id' ";
}
*/

$sql_order  = " order by reg_time desc ";

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
    max-width : 100%;
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
?>
<li>
<div class="review_box bt pad10">
    <table class="wfull viewuser">
        <tbody>
        <tr>
            <td> 
                <?php for($i=0;$i<(int)$row['score'];$i++) { ?><img src="<?php echo TB_IMG_URL; ?>/sub/comment_start.jpg" align="absmiddle"><?php } ?> &nbsp;&nbsp;&nbsp; 
            </td>
            <td rowspan="3" class="w110 tar img_box"> 
<?php
    $rimg = TB_DATA_PATH.'/review/'.$row['re_file'];
    if(is_file($rimg) && $row['re_file']) {
        $size = @getimagesize($bimg);
        $width = 80;
        $rimg = rpc($rimg, TB_PATH, TB_URL);
        echo '<img src="'.$rimg.'" width="'.$width.'"><br><br>';
    }else if($row['re_file']){
        $width = 80;
        echo '<img src="'.$row['re_file'].'" width="'.$width.'"><br><br>';
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
                <div class="memo_box  lineclamp2">
                    <?php echo str_replace("\n","<br>",$row['memo']); ?>
                </div>
                <br>
                <div class="img_box dn"> 
<?php
    $rimg = TB_DATA_PATH.'/review/'.$row['re_file'];
    if(is_file($rimg) && $row['re_file']) {
        $size = @getimagesize($bimg);
        $rimg = rpc($rimg, TB_PATH, TB_URL);
        echo '<img src="'.$rimg.'" ><br><br>';
    }else if($row['re_file']){
        $width = "100%";
        echo '<img src="'.$row['re_file'].'" ><br><br>';
    }
?>
                </div>
<?php if($row['re_file']){ ?>
                <div class="add_box curp fs13 bm">더보기 </div>
<?php } ?>
            </td>
        </tr>
    </table>
</div>
</li>
<?php } ?>

</ul>
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
