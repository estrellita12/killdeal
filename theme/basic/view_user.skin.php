<?php
if(!defined('_TUBEWEB_')) exit;

$rimg_path = TB_DATA_PATH.'/review/';
$sql_common = "";
$sql_common .= " from (  (";
$sql_common .= "select index_no,mb_id,mb_name,pt_id,seller_id,gs_id,score,memo,reg_time,gender,age,level,concat('".$rimg_path."',re_file) as re_file from shop_goods_review where gs_id= '$index_no' ";
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
*/
$sql = " select * $sql_common $sql_search $sql_order ";
$res1 = sql_query($sql);
$rv_list = array();
for($i=0; $row=sql_fetch_array($res1); $i++) {
   array_push($rv_list, $row);
}
?>
<style>
    .review-li{  padding-top:5px; padding-bottom:5px;border-top:1px solid #eee; padding-left:10px; padding-right:10px;}
    .review-li:last-child{border-bottom:1px solid #eee}
    .review-li .left-box{ padding-bottom:5px; }
    .review-li .left-box .top-box{ font-size:14px; letter-spacing:normal;}
    .review-li .left-box .top-box .score img{vertical-align:inherit !important;}
    .review-li .left-box .top-box span:not(:first-child):before{ display: inline-block; width: 1px; height: 12px; margin: 0 10px; background-color: #d4d4d4; content: ""; }
    .review-li .left-box .memo-box{padding-top:3px; font-size:15px; line-height:25px; letter-spacing:normal;}
    /* .review-li .left-box .memo-box.line3{overflow: hidden; display: -webkit-box; max-height: 60px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; } */
    .review-li .left-box .memo-box .option{display:block; font-size:12px;color:#666; padding-bottom:3px;}
    .review-li .right-box{}
    .review-li .right-box img{ max-width:80%;}
    .review-wrapper .paging{ margin-top:10px; display:flex; flex-direction: row;justify-content: center;}
    .review-wrapper .paging .pages{ text-align:center}
    .review-wrapper .paging span { border:1px solid #c8c8c8; background-color:#fbfbfb; cursor:pointer; font-size:12px; margin-left:2px; margin-right:2px; width:27px; height:27px; line-height:24px;box-sizing:border-box }
    .review-wrapper .paging .pages span { display:none; }
    .review-wrapper .paging .pages span.active{font-weight:bold}
    .review-wrapper .paging span:not(.disabled):hover{border-color:black;}
    .review-wrapper .paging span.page_pre,
    .review-wrapper .paging span.page_next
{ display:inline-block;text-decoration:none; background: url(/img/sprite_pg.png) no-repeat; text-indent: -9000px; vertical-align:bottom}
    .review-wrapper .paging span.page_pre{ background-position: -146px -1px;}
    .review-wrapper .paging span.page_pre.disabled{ background-position: -30px -1px;}
    .review-wrapper .paging span.page_next{ background-position: -175px -1px;}
    .review-wrapper .paging span.page_next.disabled{ background-position: -59px -1px;}
</style>
<div class="review-wrapper">
    <div style="display:flex;justify-content:space-between;font-size:15px; margin-bottom:5px;">
        <div style="font-size:15px">총 <b class="fc_red"><?php echo $item_use_count; ?></b>개의 리뷰가 있습니다.</div>
        <div>
            <a href="<?php echo TB_SHOP_URL; ?>/view_user_form.php?gs_id=<?php echo $index_no; ?>" onclick="win_open(this,'view_user_form','700','600','yes');return false" class="btn_lsmall black">상품리뷰 작성</a>
        </div>
    </div>
    <ul id="review-box">
        <li class="review-li">
            <div class="left-box">
                <div class="top-box">
                    <span class="score"><img src="https://killdeal.co.kr/img/sub/comment_start.jpg" align="absmiddle"></span>
                    <span class="mb_name">asd****</span>
                    <span class="reg_time">2023-02-22</span>
                </div>
                <div class="memo-box">
                    asd
                </div>
            </div>
            <div class="right-box">
                <img src="https://phinf.pstatic.net/checkout.phinf/20230222_236/1677055591919Y0dwU_JPEG/1677055579265.jpg?type=f300_300" class="w80">
            </div>
        </li>
    </ul>
    <div class="paging">
        <span class="page_pre disabled"><</span>
        <div class="pages"></div>
        <span class="page_next">></span>
    </div>
</div>    
<script>
/*
    var page = 1;
    $(function(){
        $(".review_box").on("click", function() {
            //$(this).find('.memo_box').toggleClass('lineclamp2');
            $(this).find('.img_box > img').toggleClass('w80');
        });

        $(".viewuser > tbody > tr:nth-child(n+10)").css("display","none")
    });
    
    function showReview(){
        $(".viewuser > tbody > tr:nth-child(n+"+page*10+"):nth-child(-n+"+page*20+")").css("display","table-row")
        page = page + 1;
    }
*/
    function tdel(url){
        if(confirm('삭제 하시겠습니까?')){
            location.href = url;
        }
    }

let todoData = <?php echo json_encode($rv_list)?>;
const countPerPage = 5; // 페이지당 데이터 건수
const totalPage = Math.floor(todoData.length / countPerPage) + (todoData.length % countPerPage == 0 ? 0 : 1);
let pageNum = 1;
$(function() {
    for(let i=0;i<totalPage;i++){
        $(".paging > .pages").append("<span data-idx='"+(i+1)+"'>"+(i+1)+"</span>");
        $(".paging > .pages span[data-idx=1]").addClass("active");
    }
    
    if(totalPage <= 5){
        $(".paging > span.page_next").addClass("disabled");
    }

    setReviewList(1);
    setPagingList(1);
    $(".paging > .pages > span").click(function(){
        pageNum = parseInt($(this).html());
        setReviewList(pageNum);
        setPagingList(pageNum);
        $(this).parent().find('span.active').removeClass('active');
        $(this).addClass('active');
    })

    $(".paging > span.page_next").click(function(){
        if(pageNum+2 >= totalPage){
            return;
        }
        if(pageNum < 3){
            pageNum = 3
        }

        pageNum = pageNum + 1;
        setReviewList(pageNum);
        setPagingList(pageNum);
        $(this).parent().find('span.active').removeClass('active');
        $("[data-idx="+pageNum+"]").addClass('active');
    })

    $(".paging > span.page_pre").click(function(){
        if(pageNum <= 1+2){
            pageNum = 1
            return;
        }
        pageNum = pageNum - 1;
        setReviewList(pageNum);
        setPagingList(pageNum);
        $(this).parent().find('span.active').removeClass('active');
        $("[data-idx="+pageNum+"]").addClass('active');
    })

});

function setPagingList(pageNum){
    let start = 1;
    let end = 5;
      
    if( pageNum >= 3 ){
        start = pageNum-2;
        end = pageNum+2;
    }

    if( end >= totalPage ){
        end = totalPage;
        start = end-4;
        if(start < 1){
            start = 1;
        }
        $(".paging > span.page_next").addClass("disabled");
    }else{
        $(".paging > span.page_next").removeClass("disabled");
    }

    if( start > 1 ){
        $(".paging > span.page_pre").removeClass("disabled");
    }else{
        $(".paging > span.page_pre").addClass("disabled");
    }


    console.log(start,end);
    $(".paging > .pages > span").css("display","none");
    $(".paging > .pages > span:nth-child(n+"+start+"):nth-child(-n+"+end+")").css("display","inline-block");
}

function setReviewList(pageNum) {
    $('#review-box').empty();
    const filteredData = todoData.slice((pageNum - 1) * countPerPage, pageNum * countPerPage);
    let sTbodyHtml = "";
    for (let i = 0; i < filteredData.length; i++) {
        sTbodyHtml += "<li class='review-li'>";
        sTbodyHtml += "  <div class='left-box'>";
        sTbodyHtml += "     <div class='top-box';>";
        sTbodyHtml += "         <span class='score'>";
        for(let j=0;j < filteredData[i].score;j++){
            sTbodyHtml += "         <img src='<?php echo TB_IMG_URL; ?>/sub/comment_start.jpg'>";
        }
        sTbodyHtml += "         </span>";
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
    
    $(".review-li").click(function(){
        $(this).find(".memo-box").toggleClass("line3");
        $(this).find(".right-box").find("img").toggleClass("w80");
    });

}

</script>
