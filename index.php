<?php
include_once('./common.php');

//콕골프 접근
if($pt_id == 'cokgolf'){
    //세션초기화
    //if(!get_session('ss_mb_id')){
        set_session('ss_mb_id', null);
        set_session('ss_mb_name', null);
        login_sso_log($pt_id, null, $_GET['uuid'], null, $_GET['redirectUrl'], null, $_GET['data'],'Y','Level-1');

        //데이터 세팅
        $uuid = !empty($_GET['uuid']) ? $_GET['uuid'] : null;
        $data = !empty($_GET['data']) ? cok_decode($_GET['data']) : null;
        $redirect_url = !empty($_GET['redirectUrl']) ? $_GET['redirectUrl'] : TB_MURL;
        $exposed_chk = !empty($_GET['exposed']) ? './cokgolf/cokgolf_exposed.php' : null;
        
        //단일 페이지 요청 여부 체크
        if($exposed_chk){
            goto_url($exposed_chk);
        }

        //데이터 체크
        if($data && get_member($data)){ //기존 고객
            login_sso_log($pt_id, null, $uuid, null, null, null, $data,'Y','기존');
            $mb = get_member($data);
            set_session('ss_mb_id', $mb['id']);
            set_session('ss_mb_name', $mb['name']);
            goto_url($redirect_url);
        }else if(!empty($uuid) && !$data){ //신규 고객
            login_sso_log($pt_id, null, $uuid, null, null, null, $data,'Y','Level0');
            goto_url('./cokgolf/cokgolf_register.php?url='.$redirect_url.'&c_uuid='.$uuid);
        }else if(!$uuid && !$data){
            echo "<script type=\"text/javascript\">alert('요청 데이터가 존재하지 않습니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.'); window.close();</script>";
            //goto_url($redirect_url);
            exit;
        }
        //필수값 누락 or 로그인 정보 불일치
        echo "<script type=\"text/javascript\">alert('잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.'); window.close();</script>";
        exit;
    //}else{
    //    goto_url(TB_MURL);
    //}    
}


// 모바일접속인가?
if(TB_IS_MOBILE) {

	if($pt_id == 'golf')
	{
	    //현대리바트에서 주는 인자를 모바일페이지 이동시 값을 넘긴다_20190806
	   $h_tail="?MEM_NM=".$_GET['MEM_NM']."&SHOP_NO=".$_GET['SHOP_NO']."&MEM_NO=".$_GET['MEM_NO']."&SHOPEVENT_NO=".$_GET['SHOPEVENT_NO']."&CASHRECEIPT_YN=".$_GET['CASHRECEIPT_YN'];
       goto_url(TB_MURL.$h_tail);
	}
	else
	{
       goto_url(TB_MURL);
	}
}

//골프몬 PC접근 시 모바일로 리다이렉트
if(!TB_IS_MOBILE && $pt_id == 'golfmon'){
    //goto_url(TB_MURL);   
}

//콕골프 PC접근 차단
if($pt_id == 'cokgolf'){
    return false;
}


define('_INDEX_', true);

// 인트로를 사용중인가?
if(!$is_member && $config['shop_intro_yes']) {
	include_once(TB_THEME_PATH.'/intro.skin.php');
    return;
}

if($pt_id =='golf') //현대리바트
{
	   //1번만 실행함.
	   //서버부하 체크후 쿠키방식으로 교체할수도 있음.

       if(get_session('ss_mb_id'))
       {
	        $is_member = 1;


	   }

}
else if($pt_id == 'golfpang') //골팡
{
	if($_COOKIE['gp_id'])
	{
		//echo "쿠키존재:".$_COOKIE['gp_id']."<br>";
		set_session('ss_mb_id', $_COOKIE['gp_id']);
		if(get_session('ss_mb_id'))
        {
		    $is_member = 1;
	    }
	}

}

// 20191217 도담골프 인트로페이지에서 post값 받아온 걸로 로그인
else if($pt_id == 'dodamgolf')
{
	//echo get_session('ss_mb_gd');
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

else if($pt_id == 'itsgolf')
{

}

// 20200218  post값 받아온 걸로 로그인
else if($pt_id == 'golfya')
{
	if($_POST["uid"] != null)
	{
		//echo "id 잇음";
		$keycode = gen_keycode();
		//echo $keycode;
		$mem_info = get_member_info($keycode, $_POST["uid"]);
		//var_dump($mem_info);
		set_session('ss_mb_id', 'gy_'.$mem_info->mem_id);
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

// 20200218  post값 받아온 걸로 로그인
else if($pt_id == 'golftouro')
{
	if($_POST["uid"] != null)
	{
		//echo "id 잇음";
		$keycode = gen_keycode();
		//echo $keycode;
		$mem_info = get_member_info($keycode, $_POST["uid"]);
		//var_dump($mem_info);
		set_session('ss_mb_id', 'gt_'.$mem_info->mem_id);
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

//홍골프
/*
else if($pt_id == 'honggolf') {
	$mkey = $_GET['mkey'];

		if(isset($_GET['mkey'])) {
			//복호화
			$mkey = decrypt_mcrypt_hong($mkey);
			$mkey_value = json_decode($mkey,true);

				$refreshid = $mkey_value["uuid"];
				$name =  $mkey_value["name"];
				$email = $mkey_value["email"];
				$cellphone = $mkey_value["cell_phone"];

				set_session('ss_mb_id', 'honggolf_'.$refreshid);
				set_session('ss_mb_name', $name);
				set_session('ss_mb_phone', $cellphone);
				set_session('ss_mb_email', $email);
		}

        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 5;
            $member['pt_id'] = "honggolf";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
			//var_dump($_SESSION);
        }

}
*/
/*
else if($pt_id == 'golfu') //골프유닷넷
{
	if($_COOKIE['GolfuID'])
	{

		set_session('ss_mb_id', "golfu_".$_COOKIE['GolfuID']);
		if(get_session('ss_mb_id'))
        {
		    $is_member = 1;
	    }
	}

}
*/

//리프레쉬클럽
/*
else if($pt_id == 'refreshclub')
{
	$mkey = $_GET['mkey'];

		if(isset($_GET['mkey'])) {
			복호화
			$mkey = decrypt_mcrypt($mkey);
			$mkey_value = json_decode($mkey,true);

				$refreshid = $mkey_value["uuid"];
				$name =  $mkey_value["name"];
				$email = $mkey_value["email"];
				$cellphone = $mkey_value["cell_phone"];

				set_session('ss_mb_id', 'refreshclub_'.$refreshid);
				set_session('ss_mb_name', $name);
				set_session('ss_mb_phone', $cellphone);
				set_session('ss_mb_email', $email);
				$is_member = 1;

		}

		if(get_session('ss_mb_id')){
			$is_member = 1;
		}else{
			$msg = get_session('ss_mb_id');
			$is_member = 1;
			echo "<script type=\"text/javascript\">alert('$msg');</script>";
		}

	var_dump($_SESSION);
}
*/

include_once(TB_PATH.'/head.php'); //상단
if( $pt_id == "kimcaddie"){
    include_once(TB_THEME_PATH.'/main_caddie.skin.php'); // 메인
}else{
    if( isset($pt_mwdeal_chk) && $pt_mwdeal_chk==true ){ // 인스타 가맹점 분기점
        include_once(TB_THEME_PATH.'/main_instar.skin.php'); // 메인
    }else{
        include_once(TB_THEME_PATH.'/main.skin.php'); // 메인
    }
}

include_once(TB_PATH.'/tail.php'); // 하단

?>
