<?php
if(!defined('_TUBEWEB_')) exit;

$sql_common = "";
$sql_common .= " from (  (";
$sql_common .= "select index_no,mb_id,mb_name,pt_id,seller_id,gs_id,score,memo,reg_time,gender,age,level,re_file from shop_goods_review where gs_id= '$index_no' ";
if($default['de_review_wr_use']) {
    $sql_common .= " and pt_id = '$pt_id' ";
}
$sql_common .= "    ) union all ( ";
$sql_common .= "    select index_no,mb_id,mb_name,pt_id,seller_id,gs_id,score,concat('<span class=\'option\'>',opt_name,'</span>',memo) as memo,reg_time,gender,age,level,re_file from shop_goods_review_2 where gs_id= '$index_no' and visible_yn='y' ";
$sql_common .= "   ) ";
$sql_common .= " ) c ";
$sql_search = "";

$sql_order  = " order by reg_time desc ";

/*
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
*/
$sql = " select * $sql_common $sql_search $sql_order ";
$res1 = sql_query($sql);
$rv_list = array();
for($i=0; $row=sql_fetch_array($res1); $i++) {
   array_push($rv_list, $row);
}


?>
<style>
    .review-li{ display:flex; justify-content: space-between; border-top:1px solid #eee; padding:10px 5px;margin:10px 0}
    .review-li:last-child{border-bottom:1px solid #eee}
    .review-li.fd-column{flex-direction: column;}
    .review-li .left-box{ width:calc(100% - 100px);padding-bottom:5px; }
    .review-li .left-box .score-box{ margin-bottom:5px;}
    .review-li .left-box .top-box{ font-size:12px; letter-spacing : 1px;}
    .review-li .left-box .top-box .score img{vertical-align:inherit !important;}
    .review-li .left-box .top-box span:not(:first-child):before{ display: inline-block; width: 1px; height: 12px; margin: 0 10px; background-color: #d4d4d4; content: ""; }
    .review-li .left-box .memo-box{padding-top:3px; font-size:14px; line-height:22px; letter-spacing : normal;}
    .review-li .left-box .memo-box.line3{overflow: hidden; display: -webkit-box; max-height: 90px; -webkit-line-clamp: 4; -webkit-box-orient: vertical; }
    .review-li .left-box .memo-box .option{display:block; font-size:12px;color:#666; padding-bottom:3px;}
    .review-li .right-box{}
    .review-li .right-box img{ max-width:80%;}
 
    .review-wrapper .bm{width : 10px; position: relative; display:inline-block; height:10px;}
    .review-wrapper .bm:after {  content: ''; position: absolute;/* top : 9px; right : 1px;*/ width: 20px;  height: 8px;  background: url(/img/btn/m_btn_sidem_arrow.png) no-repeat 0 -8px; background-size: 16px 16px;}
    /* .review_box .bm.active:after { transform: rotate(180deg); background-position: 0 0px;} */

#addReview {margin-top:5px; margin-bottom:15px;padding:5px; text-align:center; cursor:pointer; font-weight:bold; font-size:14px;}
</style>
<div class="sp_vbox_qa">
    <ul>
        <li class="tlst">상품리뷰 <span class=cate_dc> <?php echo $item_use_count; ?>건</span></li>
        <li class='trst'><a href="<?php echo TB_MSHOP_URL ?>/view_user_form.php?gs_id=<?php echo $gs_id?>" target="_self" class='btn_lsmall black'>상품리뷰 작성</a></li>
    </ul>
</div>
<div class="review-wrapper">
    <ul id="review-box">
    </ul>
    <?php if($item_use_count > 5){?>
    <div id="addReview" >상품평 더보기<span class="bm"></></div>
    <?php } ?>
</div>
<script>
let todoData = <?php echo json_encode($rv_list)?>;
const countPerPage = 5; // 페이지당 데이터 건수
const totalPage = Math.floor(todoData.length / countPerPage) + (todoData.length % countPerPage == 0 ? 0 : 1);
let pageNum = 1;

$(function() {
    setReviewList(1);
    $("#addReview").click(function(){
        pageNum = pageNum + 1;
        setReviewList(pageNum);
        if(totalPage <= pageNum){
            $("#addReview").css("display","none");
        }
    })
});

function setReviewList(pageNum) {
    //$('#review-box').empty();
    const filteredData = todoData.slice((pageNum - 1) * countPerPage, pageNum * countPerPage);
    let sTbodyHtml = "";
    for (let i = 0; i < filteredData.length; i++) {
        sTbodyHtml += "<li class='review-li' onclick='showData(this)'>";
        sTbodyHtml += "  <div class='left-box'>";
        sTbodyHtml += "     <div class='score-box';>";
        sTbodyHtml += "         <span class='score'>";
        for(let j=0;j < filteredData[i].score;j++){
            sTbodyHtml += "         <img src='<?php echo TB_IMG_URL; ?>/sub/comment_start.jpg'>";
        }
        sTbodyHtml += "         </span>";
        sTbodyHtml += "     </div>";
        sTbodyHtml += "     <div class='top-box';>";
        sTbodyHtml += "         <span class='mb_name'>"+filteredData[i].mb_id+"</span>";
        sTbodyHtml += "         <span class='reg_time'>"+(filteredData[i].reg_time).substr(0,10)+"</span>";
        sTbodyHtml += "     </div>";
        sTbodyHtml += "     <div class='memo-box line3'>"+filteredData[i].memo+"</div>";
        sTbodyHtml += "  </div>";
        sTbodyHtml += "  <div class='right-box'>";
        if( filteredData[i].re_file ){
            let re_file = filteredData[i].re_file;
            re_file = re_file.replace('<?php echo TB_PATH?>','<?php echo TB_URL?>');
            sTbodyHtml += " <img src="+re_file+" class='w80'>";
        }
        sTbodyHtml += "  </div>";
        sTbodyHtml += "</li>";
    }
    $('#review-box').append(sTbodyHtml);
    /*
    $(".review-li").click(function(){
        $(this).toggleClass("fd-column");
        $(this).find(".memo-box").toggleClass("line3");
        $(this).find(".right-box").find("img").toggleClass("w80");
    });
    */
}

function showData(evt){
    if( $(evt).hasClass("fd-column") ){
        $(evt).removeClass("fd-column");
        $(evt).find(".memo-box").addClass("line3");
        $(evt).find(".right-box").find("img").addClass("w80");
    }else{
        $(evt).addClass("fd-column");
        $(evt).find(".memo-box").removeClass("line3");
        $(evt).find(".right-box").find("img").removeClass("w80");
    }
}

</script>
