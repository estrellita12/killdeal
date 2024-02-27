<?php
include_once("./_common.php");

$url = $_GET['url'];

// url 체크
check_url_host($url);
// 이미 로그인 중이라면
if($is_member) {
    if($url)
        goto_url($url);
    else
        goto_url(TB_URL);
}

$tb['title'] = '로그인';
include_once("./_head.php"); 

$login_url        = login_url($url);
$login_action_url = TB_HTTPS_BBS_URL."/login_check.php";

// (2021-02-05) 연동몰 규격화를 위한 작업
if($pt_link_data['db_link_yes']){
    if($pt_link_data['login_url']!="" && !$pt_link_data['non_mem_allow']){
        // 골프유닷넷, 더골프쇼만 되돌아오는 페이지 설정 가능
        if($pt_id=='golfu' && preg_match("/orderform.php/", $url ) ){
            goto_url('http://www.golfu.net/member/Login.aspx?strPrevUrl=https://shopping.golfu.net/shop/orderform.php');
        }else if($pt_id=='golfu' && preg_match("/orderinquiry.php/", $url ) ){
            goto_url('http://www.golfu.net/member/Login.aspx?strPrevUrl=https://shopping.golfu.net/shop/orderinquiry.php');
        }else if($pt_id=='golfu' && preg_match("/qna_write.php/", $url ) ){
            goto_url('http://www.golfu.net/member/Login.aspx?strPrevUrl=https://shopping.golfu.net/bbs/qna_write.php');
        }else if($pt_id=='thegolfshow' && preg_match("/orderform.php/", $url ) ){
            alert("로그인후 사용해 주시기 바랍니다.",'https://www.thegolfshow.co.kr/login.php?url='.urlencode(' https://thegolfshowmarket.com/shop/orderform.php'));
        }else if($pt_id=='thegolfshow' && preg_match("/orderinquiry.php/", $url ) ){
            alert("로그인후 사용해 주시기 바랍니다.",'https://www.thegolfshow.co.kr/login.php?url='.urlencode(' https://thegolfshowmarket.com/shop/orderinquiry.php'));
        }else{
            goto_url($pt_link_data['login_url']);
        }
    }else{
        include_once(TB_THEME_PATH.'/pt_login.skin.php');
    }
}else{
    include_once(TB_THEME_PATH.'/login.skin.php');
}


//echo "url:".$login_url;
include_once("./_tail.php");
?>
