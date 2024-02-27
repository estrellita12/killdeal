<?php
    include_once("../common.php");

    $c_uuid = $_POST['c_uuid'];
    $url = $_POST['c_url'];
    if(!$c_uuid){
        alert("uuid 만료.");
        return false;
    }
    
    login_sso_log($pt_id, null, $c_uuid, null, null, null, null,$url,'level1');
    // REAL------------------------------------------------------------------------------
    $c_data = uniqid();
    $chk_url = "https://cokfarmdev.nonghyup.com:8990/parksajang/api/reciveId.do"; //dev
    //$chk_url = "https://cokfarm.nonghyup.com:8990/parksajang/api/reciveId.do"; //real
    //$chk_url = "http://ethanklocked.cafe24.com/confirm.php";
    $chk_mb = get_member($c_data);
    login_sso_log($pt_id, null, $c_uuid, null, null, null, null,'Y','level2');
    if(isset($chk_mb['id'])){
        alert("아이디 생성 과정에서 오류가 발생하였습니다. 다시 시도 해 주세요.");
        login_sso_log($pt_id, null, $c_uuid, null, null, null, null,'Y','fail1');
        return false;
    }
    
    // TEST------------------------------------------------------------------------------
    /*
    $c_data = 'ID5';
    $chk_mb = get_member($c_data);
    if(isset($chk_mb['id'])){
        alert("아이디 생성 과정에서 오류가 발생하였습니다. 다시 시도 해 주세요.");
        return false;
    }
    $chk_url = "http://ethanklocked.cafe24.com/confirm.php";
    */

    unset($value);
    $value['id'] = $c_data; 
	$value['today_login']	= TB_TIME_YMDHIS;
	$value['reg_time']		= TB_TIME_YMDHIS;
    $value['login_sum']		= 1; 
	$value['grade']			= '9';
	$value['pt_id']			= 'cokgolf';
	$value['login_ip']		= $_SERVER['REMOTE_ADDR'];
    login_sso_log($pt_id, null, $c_uuid, null, null, null, null,'Y','level3');
    $encoded = cok_encode($c_data);
    login_sso_log($pt_id, null, $c_uuid, null, null, null, null,'Y','level4');

    $param = "uuid={$c_uuid}&data={$encoded}";
    /*
    $param = array(
        "uuid" => "{$c_uuid}",
        "data" => "{$encoded}"
    );
    */

    $semi_result = call_curl($chk_url,$param);
    login_sso_log($pt_id, null, $c_uuid, null, null, null, $member['id']?$member['id']:null,'Y',$semi_result);
    
    $result = $semi_result ? json_decode($semi_result, true) : false;

    if($result['code']==1){
        insert("shop_member", $value);
        set_session('ss_mb_id', $c_data);
        goto_url($url);
        //alert("회원가입에 성공하였습니다.", $url);
    }else{
        alert($result['detailMessage']);
        login_sso_log($pt_id, null, $c_uuid, null, null, null, $member['id']?$member['id']:null,'Y',$result['detailMessage']);
        login_sso_log($pt_id, null, $c_uuid, null, null, null, $member['id']?$member['id']:null,'Y','fail2');
        alert("회원가입에 실패하였습니다. 관리자에게 문의 해 주세요.");
    }
?>