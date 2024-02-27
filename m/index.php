<?php
define('_MINDEX_', true);
include_once("./_common.php");

// 인트로를 사용중인지 검사
if(!$is_member && $config['shop_intro_yes']) {
    include_once(TB_MTHEME_PATH.'/intro.skin.php');
    return;
}

if($pt_id =='golf')
{
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
    }

}

// 20191226 도담골프 인트로페이지에서 post값 받아온 걸로 로그인
else if($pt_id == 'dodamgolf')
{
    //var_dump($_POST);
    //20191211 히스토리 초기화 인트로페이지 접근 불가 처리
    /*echo "<script>
            history.pushState(null, null, location.href);
            window.onpopstate = function(event) {
            history.go(1);
            };
        </script>";
     */
    if($_POST["uid"] != null)
    {
        //echo "id 잇음";
        $keycode = gen_keycode();
        //echo $keycode;
        $mem_info = get_member_info($keycode, $_POST["uid"]);
        //var_dump($mem_info);
        set_session('ss_mb_id', 'dd_'.$mem_info->mem_id);
        set_session('ss_mb_nm', $mem_info->mem_nm);
        set_session('ss_mb_gd', $mem_info->mem_gd);
        set_session('ss_mb_point', $mem_info->point);

        if(get_session('ss_mb_id'))
        {
            $is_member = 1;
        }
    }
    else
    {
        //echo"id없음";
    }

}

// 20200120 이츠 골프 id파라미터 세션 생성
else if($pt_id == 'itsgolf' )
{
    if( !get_session('ss_mb_id')  && $_POST['uid'] )
    {

        $itsid = $_POST['uid'];
        $name = $_POST['uname'];
        $itsphone = $_POST['phone'];
        $email = $_POST['email'];

        set_session('ss_mb_id', 'its_'.$itsid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $itsphone);
        set_session('ss_mb_email', $email);

        $is_member = 1;

        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['grade'] = 9;
        $member['pt_id'] = "itsgolf";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');

        login_sso_log($pt_id, '' , $itsid, $name, $email, $itsphone, "its_".$itsid ,'Y','');
    }

    if($_POST['gs_id'] != null)
    {
        echo "<script>document.location.replace('https://itsgolf.killdeal.co.kr/m/shop/view.php?gs_id=".$_POST['gs_id']."')</script>";
    }

}

// 20200218 골프야 사랑해 id파라미터 세션 생성
else if($pt_id == 'golfya')
{
    if( !get_session('ss_mb_id')  && $_POST['uid'] )
    {
        $gyid = $_POST['uid'];
        $name = $_POST['uname'];
        $gyphone = $_POST['uphone'];
        $email = $_POST['email'];

        set_session('ss_mb_id', 'gy_'.$gyid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $gyphone);
        set_session('ss_mb_email', $email);

        $is_member = 1;
        
        $member['id'] = get_session('ss_mb_id');
        $member['grade'] = 9;
        $member['pt_id'] = "golfya";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');

        login_sso_log($pt_id, '' , $gyid, $name, $email, $gyphone, "gy_".$gyid ,'Y','');
    }
}

// 20200327 골프잼 id파라미터 세션 생성
else if($pt_id == 'golfjam')
{
    if( !get_session('ss_mb_id')  && $_POST['uid'] )
    {
        $gjid = $_POST['uid'];
        $name = $_POST['uname'];
        $gjphone = $_POST['phone'];
        $email = $_POST['email'];

        set_session('ss_mb_id', 'gj_'.$gjid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $gjphone);
        set_session('ss_mb_email', $email);
 
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['grade'] = 9;
        $member['pt_id'] = "golfjam";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');

        login_sso_log($pt_id, '' , $gjid, $name, $email, $gjphone, "gj_".$gjid ,'Y','');
    }
}
else if($pt_id == 'golfrock'){
    if($_POST['gs_id'] != null)
    {
        echo "<script>document.location.replace('https://shop.golfrock.co.kr/m/shop/view.php?gs_id=".$_POST['gs_id']."')</script>";
    }

    else if($_POST['ca_id'] != null)
    {
        echo "<script>document.location.replace('https://shop.golfrock.co.kr/m/shop/list.php?ca_id=".$_POST['ca_id']."')</script>";
    }

    else if($_POST['ss_tx'] != null)
    {
        echo "<script>document.location.replace('https://shop.golfrock.co.kr/m/shop/search.php?ss_tx=".$_POST['ss_tx']."')</script>";
    }

}
else if($pt_id == 'teeluv'){
    if($_POST['gs_id'] != null)
    {
        echo "<script>document.location.replace('https://mall.teeluv.co.kr/m/shop/view.php?gs_id=".$_POST['gs_id']."')</script>";
    }
}


include_once(TB_MPATH."/_head.php"); // 상단
// (2020-12-10) head.php 페이지로 해당 코드 이동
//include_once(TB_MPATH."/popup.inc.php"); // 팝업

if($pt_id=="kimcaddie"){
    include_once(TB_MTHEME_PATH.'/main_caddie.skin.php');
}else{
    if(isset($pt_mwdeal_chk) && $pt_mwdeal_chk == true){
        include_once(TB_MTHEME_PATH.'/main_instar.skin.php');
    }else{
        include_once(TB_MTHEME_PATH.'/main.skin.php');
    }
}
include_once(TB_MPATH."/_tail.php"); // 하단
?>
