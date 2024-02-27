<?php

function youtube_pt_check($pt_id,$cnt){
    if($pt_id=='baksajang' || $pt_id=='honggolf'){
        $sql = "select * from shop_board_video where pt_id='$pt_id' order by get_time desc,index_no  limit $cnt";
    }else{
        $sql = "select * from shop_board_video where pt_id='killdeal' order by get_time desc,index_no  limit $cnt";
    }
    return $sql;
}

function youtube_video($pt_url){
    include_once 'Snoopy.class.php';
    $snoopy=new snoopy;
    $snoopy->fetch("https://www.youtube.com/channel/$pt_url/videos?view=0&sort=dd");

    $html_txt = $snoopy->results;
    $result = "";
    $rex = "/\"url\":\"\/watch.([a-zA-Z0-9\_\-\=]*)/";

    preg_match_all($rex,$html_txt,$result);

    $video_list = array();
    $i = 1;
    foreach( $result[1] as $x){
        $tmp_list = explode("=",$x);
        if(isset($tmp_list[1])){
            array_push($video_list,$tmp_list[1]);
        }
    }
    return $video_list;
}

function get_youtube_video($boardid,$pt_id,$pt_url){

    include_once 'Snoopy.class.php';
    $snoopy=new snoopy;
    $snoopy->fetch("https://www.youtube.com/channel/$pt_url/videos?view=0&sort=dd");

    $html_txt = $snoopy->results;
    preg_match_all("|\"title\":{\"runs\":\[{\"text\":\"(.*)\"}\],|U", $html_txt, $title, PREG_SET_ORDER);
    //preg_match_all("/\"gridVideoRenderer\":{\"videoId\":\"([A-z0-9_-]*)\"/", $html_txt, $v_code, PREG_SET_ORDER);
    preg_match_all("/\"videoRenderer\":{\"videoId\":\"([A-z0-9_-]*)\"/", $html_txt, $v_code, PREG_SET_ORDER);

    /*
    print_r($title);
    echo "<br>";
    print_r($v_code);
    echo "<br>";
    */
    
    for($i=count($title)-1; $i>=0;$i--){
        $subject = addslashes($title[$i][1]);

        $row = sql_fetch("select count(*) as cnt from shop_board_{$boardid} where fileurl1='{$v_code[$i][1]}'  ");
        $cnt = $row['cnt'];
        if($cnt == 0){
            $fid = get_next_num("shop_board_{$boardid}");
            $sql_commend = " , btype = '2' , ca_name  = ' ' , issecret = 'N' , havehtml = 'N' , writer = '1' , writer_s = '관리자' , subject = '{$subject}' , memo = '<iframe width=\"1200\" height=\"720\" src=\"https://www.youtube.com/embed/{$v_code[$i][1]}\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen=\"\"></iframe>' , passwd   = ' ' , average  = ' ' , main_use = '1' , main_order = '5' , product  = ' ' , pt_id    = '$pt_id' , fileurl1='{$v_code[$i][1]}' ";
            $sql = " insert into shop_board_{$boardid} set fid='{$fid}', wdate   = '".TB_SERVER_TIME."' , wip = '127.0.0.1' , thread  = 'A' {$sql_commend} ";
            sql_query($sql);
        }else{
            $row1 = sql_fetch("select count(*) as cnt from shop_board_{$boardid} where fileurl1='{$v_code[$i][1]}' and subject = '{$subject}' ");
            $cnt1 = $row1['cnt'];
            if($cnt1==0){
                $sql = " update shop_board_{$boardid} set  memo = '<iframe width=\"1200\" height=\"720\" src=\"https://www.youtube.com/embed/{$v_code[$i][1]}\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope;picture-in-picture\" allowfullscreen=\"\"></iframe>', subject = '{$subject}' where fileurl1='{$v_code[$i][1]}' ";
                sql_query($sql);
            }
        }
    }
}



function get_mania_news($boardid){
    include_once 'Snoopy.class.php';
    $snoopy=new snoopy;
    $snoopy->fetch("https://www.maniareport.com/nprss_json.php");
    $html_txt = $snoopy->results;
    $tmp = json_decode($html_txt,true);
    $mania = $tmp['data'];

    for($i=count($mania)-1; $i>=0; $i--){
        $orgurl = $mania[$i]['orgurl'];
        //$thumbimg = $mania[$i]['thumbimg'];
        $orgimg = $mania[$i]['orgimg'];
        $fd_wname = $mania[$i]['fd_wname'];
        $fd_title = addslashes($mania[$i]['fd_title']);
        $fd_outdate = $mania[$i]['fd_outdate'];
        $y = substr($fd_outdate,0,4);
        $m = substr($fd_outdate,4,2);
        $d = substr($fd_outdate,6,2);
        $hh = substr($fd_outdate,8,2);
        $mm = substr($fd_outdate,10,2);
        $ss = substr($fd_outdate,12,2);
        $wdate = mktime($hh,$mm,$ss,$m,$d,$y);
        $fd_realcontent = addslashes($mania[$i]['fd_realcontent']);
        $row = sql_fetch("select count(*) as cnt from shop_board_{$boardid} where fileurl2='{$orgurl}'  ");
        $cnt = $row['cnt'];
        if($cnt == 0){
            $fid = get_next_num("shop_board_{$boardid}");
            $sql_commend = " , btype = '2' , ca_name  = ' ' , issecret = 'N' , havehtml = 'N' , writer = '1' , writer_s = '{$fd_wname}' , subject = '{$fd_title}' , memo = '{$fd_realcontent}' , passwd = ' ' , average  = ' ' , main_use = '1' , main_order = '5' , product  = ' ' , pt_id    = 'admin' , fileurl1='{$orgimg}' ,fileurl2='{$orgurl}' ";
            $sql = " insert into shop_board_{$boardid} set fid='{$fid}', wdate = '{$wdate}' , wip = '127.0.0.1' , thread  = 'A' {$sql_commend} ";
            sql_query($sql);
        }
    }

}



function get_partner_list(){
    $pt_list = array();
    array_push($pt_list,'admin');

    $sql = "select id from shop_member where grade between 2 and 6";
    $res = sql_query($sql);
    for($i=0; $row=sql_fetch_array($res); $i++) {
        array_push($pt_list,$row['id']);
    }
    return $pt_list;
}


// 가맹점 목록
function get_search_partner($field, $value )
{
    $str = "<select name='$field'>";
    $str .= option_selected( '',$value, '전체');

    $sql = " select * from shop_member where id='admin' or ( grade between 2 and 6 )";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $str .= option_selected($row['id'],$value,trans_pt_name($row['id']));
    }

    $str .= "</select>";
    return $str;
}

// (2021-03-04)
function order_change_update($od_id='', $od_no='', $mb_id='', $cu_status='', $ch_status='' , $memo=''  ) {
    if($mb_id=='')   $mb_id='anonymous';
    $up_sql = " insert into shop_order_change_log SET od_id='$od_id',od_no='$od_no', mb_id='$mb_id' , current_status='$cu_status',change_status='$ch_status' ,change_date =NOW() ,memo = '$memo' ";
    sql_query($up_sql);
}

// (2021-04-05)
function get_order_change_log($od_id='', $od_no='' ) {
    $sql_search = "where od_no = '$od_no' ";
    if($od_id){
        $sql_search .= " and od_id = '$od_id' ";
    }
    $lsql = "select * from shop_order_change_log $sql_search ";
    $lres = sql_query($lsql);
    for($i=0; $lrow=sql_fetch_array($lres); $i++){
        $str .= "[";
        $str .= $lrow['change_date'];
        $str .= "]";
        $str .= "(";
        $str .= $lrow['mb_id'];
        $str .= ")";
        $str .= $lrow['current_status'];
        $str .= "->";
        $str .= $lrow['change_status'];
        $str .= "&#10;";
    }
    return $str;
}



// (2021-03-09)
function get_io_stock_sum($gs_id){
    $row = sql_fetch("select sum(io_stock_qty) as sum from shop_goods_option where gs_id=$gs_id ");
    return (int)$row['sum'];
}

// 골프매거진 리스트 가져오기
function board_video_latest2($boardid, $len, $rows, $pt_id){
    global $default;


    if($pt_id=='baksajang' || $pt_id=='teeluv' || $pt_id=='maniamall' || $pt_id == 'dukhomall'){
        $sql_search = " and pt_id = '$pt_id' ";
    }else{
        $sql_search = " and pt_id = 'admin' ";
    }

    $sql = " select * from shop_board_{$boardid} where main_use = '1' {$sql_search} and issecret = 'N' order by main_order,index_no desc limit $rows ";
    $str = '';
    $res = sql_query($sql);
    for($i=0;$row=sql_fetch_array($res);$i++){
        $subject = cut_str($row['subject'],$len);
        $wdate = date('Y-m-d',intval($row['wdate'],10));
        $href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
        //$u_url = split_youtube_tag($row['memo']);
        //$url_th = main_merge_thumnail_url($u_url[0]);
        $url_th = "https://i.ytimg.com/vi/{$row['fileurl1']}/maxresdefault.jpg";
        $str .= " <div class='video_box'>\n";
        $str .= " <a href=\"{$href}\" class=\"video_play\" >\n";
        $str .= " <img src=$url_th style=\"width:640px;\">\n";
        $str .= " </a>\n";
        $str .= " </div>\n";
    }

    return $str;
}


// 골프비디오 썸네일 가져오기 20200729
function m_board_video_latest2($boardid, $len, $rows, $pt_id){

    global $default;

    $sql_where = "";
    if($pt_id=='baksajang' || $pt_id=='teeluv' || $pt_id=='maniamall' || $pt_id=='dukhomall'){
        $sql_search = " and pt_id = '$pt_id' ";
    }else{
        $sql_search = " and pt_id = 'admin' ";
    }

    $str = '';
    $sql = " select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N'  {$sql_search} order by main_order,index_no desc limit $rows ";

    $res = sql_query($sql);
    for($i=0;$row=sql_fetch_array($res);$i++){
        $subject = cut_str($row['subject'],$len);
        $wdate = date('Y-m-d',intval($row['wdate'],10));
        $href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
        $url_th = "https://i.ytimg.com/vi/{$row['fileurl1']}/maxresdefault.jpg";
        $str .= "   <div class='video_box'>\n";
        $str .= " <a href=\"{$href}\" class=\"video_play\">\n";
        $str .= " <img src=\"${url_th}\" style=\"width:100%;\">\n";
        $str .= " </a>\n";
        $str .= "   </div>\n";
    }

    return $str;

}

// 골프뉴스 리스트 가져오기
function board_news_latest($boardid, $len, $rows, $pt_id)
{
    global $default;

    $sql_where = "";
    if($default['de_board_wr_use']) {
        $sql_where = " where pt_id = '$pt_id' ";
    }

    $str = '';

    if($_SESSION["ss_mb_id"] != 'admin')
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N' order by main_order,index_no desc limit $rows";
    }
    else
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1'  order by main_order,index_no desc limit $rows";
    }
    $res = sql_query($sql);

    for($i=0;$row=sql_fetch_array($res);$i++){
        $subject = cut_str($row['subject'],$len);
        $wdate = date('Y-m-d',intval($row['wdate'],10));
        $href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
        $memo = $row['memo'];
        $hit = $row['readcount'];
        $main_use = $row['main_use'];
        $main_order = $row['main_order'];
        $main_img = $row['fileurl1'];
        //preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);
        preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $memo, $matches);
        if($main_use !== '0'){
            $str .= "<li>\n";
            $str .= "   <a href=\"{$href}\">\n";
            $str .= "       <p class=\"plan_img\" style=\"width:205px;height:123px;background-image:url('$main_img'); background-size : cover\"></p>\n";
            $str .= "       <div class=\"mgz_txt_wrap\">\n";
            $str .= "           <p class=\"mgz_tit\">마니아타임즈</p>\n";
            $str .= "           <p class=\"plan_tit\">{$subject}</p>\n";
            $str .= "       </div>";
            // $str .= "        <p class=\"plan_hit\">{$hit} 읽음</p>\n";
            $str .= "   </a>\n";
            $str .= "</li>\n";
        }
    }
    return $str;
}

function m_board_news_latest($boardid, $len, $rows, $pt_id)
{
    global $default;

    $sql_where = "";
    if($default['de_board_wr_use']) {
        $sql_where = " where pt_id = '$pt_id' ";
    }

    $str = '';

    if($_SESSION["ss_mb_id"] != 'admin')
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1' and issecret = 'N' order by main_order,index_no desc limit $rows";
    }
    else
    {
        $sql = "select * from shop_board_{$boardid} where main_use = '1'  order by main_order,index_no desc limit $rows";
    }
    $res = sql_query($sql);

    for($i=0;$row=sql_fetch_array($res);$i++){
        $subject = cut_str($row['subject'],$len);
        $wdate = date('Y-m-d',intval($row['wdate'],10));
        $href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";
        $memo = $row['memo'];
        $hit = $row['readcount'];
        $main_use = $row['main_use'];
        $main_order = $row['main_order'];
        $main_img = $row['fileurl1'];
        //preg_match_all("!<iframe(.*?)<\/iframe>!is",$memo,$RESULT);
        preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $memo, $matches);
        if($main_use !== '0'){
            $str .= "<li>\n";
            $str .= "   <a href=\"{$href}\">\n";
            $str .= "       <p class=\"plan_img\" style=\"width:170px;height:95px;background-image:url('$main_img'); background-size : cover\"></p>\n";
            $str .= "       <span class=\"plan_txt\">\n";
            $str .= "           <span class=\"plan_tit\">{$subject}</span>\n";
            //$str .= "     <span class=\"plan_hit\">{$hit} 읽음</span>\n";
            $str .= "       </span>\n";
            $str .= "   </a>\n";
            $str .= "</li>\n";
        }
    }
    return $str;
}

// (2021-04-26)
function exist_pt_mb_email($reg_pt_id, $reg_mb_email, $reg_mb_id)
{
    $row = sql_fetch(" select count(*) as cnt from shop_member where ( email = '$reg_mb_email' and pt_id='$reg_pt_id' ) and id <> '$reg_mb_id' ");
    if($row['cnt'])
        return "이미 사용중인 E-mail 주소입니다.";
    else
        return "";
}


?>
