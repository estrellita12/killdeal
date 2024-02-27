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
        goto_url(TB_MURL);
}

$tb['title'] = "로그인";
include_once("./_head.php");

$login_url        = login_url($url);
$login_action_url = TB_HTTPS_MBBS_URL."/login_check.php";

/*
// 20191112 더골프쇼 별도 로그인스킨 삭제
// 20200907 더골프쇼 회원연동체재 변경
// 더골프쇼 로그인페이지 이동
if($pt_id == 'thegolfshow') 
{
//    include_once(TB_MTHEME_PATH.'/thegolfshow.login.skin.php');
    goto_url('https://www.thegolfshow.co.kr/m');
}

if($pt_id == 'itsgolf')
{
    include_once(TB_MTHEME_PATH.'/its.login.skin.php');
}
else if($pt_id == 'dodamgolf')
{
    include_once(TB_MTHEME_PATH.'/dodam.login.skin.php');
}
else if($pt_id == 'golfya')
{
    include_once(TB_MTHEME_PATH.'/golfya.login.skin.php');
}
else if($pt_id == 'golfjam')
{
    include_once(TB_MTHEME_PATH.'/golfjam.login.skin.php');
}
else if($pt_id == 'golfpang')
{
    include_once(TB_MTHEME_PATH.'/golfpang.login.skin.php');
}
else if($pt_id == 'honggolf')
{
    include_once(TB_MTHEME_PATH.'/honggolf_login.skin.php');
}
else if($pt_id == 'imembers')
{
    goto_url('https://www.imembers.co.kr/bbs/login.php');
}
else
{
    include_once(TB_MTHEME_PATH.'/login.skin.php');
}
 */

// (2021-02-05) 연동몰 규격화를 위한 작업
if($pt_link_data['db_link_yes']){
    if($pt_id == 'itsgolf'){
        include_once(TB_MTHEME_PATH.'/its.login.skin.php');
    }else{
        if($pt_link_data['mlogin_url']!="" && !$pt_link_data['non_mem_allow']){

            // 골프유닷넷, 더골프쇼만 되돌아오는 페이지 설정 가능
            if($pt_id=='golfu' && preg_match("/orderform.php/", $url ) ){
                goto_url('https://www.golfu.net/Mobile/Main/Default.aspx?strPrevUrl=https://shopping.golfu.net/m/shop/orderform.php');
            }else if($pt_id=='golfu' && preg_match("/orderinquiry.php/", $url ) ){
                goto_url("https://www.golfu.net/Mobile/Main/Default.aspx?strPrevUrl=https://shopping.golfu.net/m/shop/orderinquiry.php");
            }else if($pt_id=='thegolfshow' && preg_match("/orderform.php/", $url ) ){
                alert("로그인후 사용해 주시기 바랍니다.",'https://www.thegolfshow.co.kr/m/login.php?url='.urlencode('https://thegolfshowmarket.com/shop/orderform.php'));
            }else if($pt_id=='thegolfshow' && preg_match("/orderinquiry.php/", $url ) ){
                alert("로그인후 사용해 주시기 바랍니다.",'https://www.thegolfshow.co.kr/m/login.php?url='.urlencode('https://thegolfshowmarket.com/shop/orderinquiry.php'));
            }else{
                goto_url($pt_link_data['mlogin_url']);
            }

        }else{
            include_once(TB_MTHEME_PATH.'/pt_login.skin.php');
        }
    }
}else{
    if($pt_id == 'cokgolf'){
        include_once(TB_MTHEME_PATH.'/cokgolf.login.skin.php');
    }else{
        include_once(TB_MTHEME_PATH.'/login.skin.php');
    }
    
}


include_once("./_tail.php");
?>
