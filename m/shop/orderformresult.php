<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/mailer.lib.php');

$od_id = $_POST['od_id'];

// 2022-06-29 dan 조건 추가
$od = sql_fetch("select * from shop_order where od_id='$od_id' and dan = 0 ");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.", TB_MURL);
}


$stotal = get_order_spay($od_id); // 총계

$i_price = (int)$stotal['useprice']; // 결제금액
$i_usepoint = (int)$stotal['usepoint']; // 포인트결제액

if(!$i_price) {
    alert("결제할 금액이 없습니다.", TB_MURL);
}

if(in_array($od_settle_case, array('무통장','포인트'))) {
    alert("올바른 방법으로 이용해 주십시오.", TB_MURL);
}


// 삼성페이 요청으로 왔다면 현재 삼성페이는 이니시스 밖에 없으므로 $default['de_pg_service'] 값을 변경한다.
if( $od_settle_case == '삼성페이' && !empty($_POST['P_HASH']) ){
    $default['de_pg_service'] = 'inicis';
}

// 결제등록 완료 체크
if($od_settle_case != 'KAKAOPAY') {
    if($default['de_pg_service'] == 'kcp' && ($_POST['tran_cd'] == '' || $_POST['enc_info'] == '' || $_POST['enc_data'] == ''))
        alert('결제등록 요청 후 주문해 주십시오.', TB_MSHOP_URL.'/orderkcp.php?od_id='.$od_id);

    if($default['de_pg_service'] == 'lg' && !$_POST['LGD_PAYKEY'])
        alert('결제등록 요청 후 주문해 주십시오.', TB_MSHOP_URL.'/orderlg.php?od_id='.$od_id);

    if($default['de_pg_service'] == 'inicis' && !$_POST['P_HASH'])
        alert('결제등록 요청 후 주문해 주십시오.', TB_MSHOP_URL.'/orderinicis.php?od_id='.$od_id);
}

$od_tno = '';

if($od_settle_case == "계좌이체")
{
    // (2022-11-25) 결제 누락 방지를 위해 dan값 추가
    $tmp_dan = "update shop_order set dan = '1' where od_id = '$od_id' ";
    sql_query($tmp_dan,false);

    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        $bank_name  = iconv("cp949", "utf-8", $bank_name);
        break;
    }

    $od_tno             = $tno;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_deposit_name    = $od_name;
    $od_bank_account    = $bank_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "가상계좌")
{
    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        $bankname   = iconv("cp949", "utf-8", $bankname);
        $depositor  = iconv("cp949", "utf-8", $depositor);
        break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_bank_account    = $bankname.' '.$account;
    $od_deposit_name    = $depositor;
    $pg_price           = $amount;
    $od_status			= '1';
}
else if($od_settle_case == "휴대폰")
{
    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        break;
    }

    $od_tno             = $tno;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $commid.' '.$mobile_no;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "신용카드")
{

    // (2022-06-28) 결제 누락 방지를 위해 dan값 추가
    $tmp_dan = "update shop_order set dan = '1' where od_id = '$od_id' ";
    sql_query($tmp_dan,false);

    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        $card_name  = iconv("cp949", "utf-8", $card_name);
        break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';

}
else if($od_settle_case == "복지카드")
{

    //복지카드 설정_20200507
    $default['de_kcp_mid'] = "A8HRJ";
    $default['de_kcp_site_key'] = '3Bbeo5luAlZqUwvsowTZ-y6__';
    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        $card_name  = iconv("cp949", "utf-8", $card_name);
        break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "간편결제")
{
    switch($default['de_pg_service']) {
    case 'lg':
        include TB_SHOP_PATH.'/lg/xpay_result.php';
        break;
    case 'inicis':
        include TB_MSHOP_PATH.'/inicis/pay_result.php';
        break;
    case 'kcp':
        include TB_MSHOP_PATH.'/kcp/pp_ax_hub.php';
        $card_name  = iconv("cp949", "utf-8", $card_name);
        break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "삼성페이")
{
    // 이니시스에서만 지원
    include TB_MSHOP_PATH.'/inicis/pay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "KAKAOPAY")
{
    include TB_SHOP_PATH.'/kakaopay/kakaopay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else
{
    die("od_settle_case Error!!!");
}

$od_pg = $default['de_pg_service'];
if($od_settle_case == 'KAKAOPAY')
    $od_pg = 'KAKAOPAY';

// 주문금액과 결제금액이 일치하는지 체크
if($tno) {
    if((int)$i_price !== (int)$pg_price) {
        $cancel_msg = '결제금액 불일치';
        switch($od_pg) {
        case 'lg':
            include TB_SHOP_PATH.'/lg/xpay_cancel.php';
            break;
        case 'inicis':
            include TB_SHOP_PATH.'/inicis/inipay_cancel.php';
            break;
        case 'KAKAOPAY':
            $_REQUEST['TID']               = $tno;
            $_REQUEST['Amt']               = $amount;
            $_REQUEST['CancelMsg']         = $cancel_msg;
            $_REQUEST['PartialCancelCode'] = 0;
            include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
            break;
        case 'kcp':
            include TB_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
            break;
        }

        // (2020-12-21) 오류 발생시 주문 취소
        $sql = " update shop_order set dan = '6' , cancel_date = '".TB_TIME_YMDHIS."' , od_mod_history='$cancel_msg' where od_id = '$od_id' ";
        sql_query($sql);

        die("Receipt Amount Error");
    }
}

$od_escrow = 0;
if($escw_yn == 'Y')
    $od_escrow = 1;

// 복합과세 금액
$od_tax_mny = round($i_price / 1.1);
$od_vat_mny = $i_price - $od_tax_mny;
$od_free_mny = 0;
if($default['de_tax_flag_use']) {
    $od_tax_mny = (int)$_POST['comm_tax_mny'];
    $od_vat_mny = (int)$_POST['comm_vat_mny'];
    $od_free_mny = (int)$_POST['comm_free_mny'];
}

// 주문서에 UPDATE
$sql = " update shop_order
    set deposit_name = '$od_deposit_name'
    , receipt_time = '$od_receipt_time'
    , bank		 = '$od_bank_account'
    , dan			 = '$od_status'
    , od_pg		 = '$od_pg'
    , od_tno		 = '$od_tno'
    , od_app_no	 = '$od_app_no'
    , od_escrow	 = '$od_escrow'
    , od_tax_mny	 = '$od_tax_mny'
    , od_vat_mny	 = '$od_vat_mny'
    , od_free_mny	 = '$od_free_mny'
    where od_id = '$od_id'";
$result = sql_query($sql, false);

if($result) {
    // 장바구니 상태변경
    $sql = " update shop_cart set ct_select = '1' where od_id = '$od_id' ";
    sql_query($sql, false);
} else {
    // 주문정보 UPDATE 오류시 결제 취소
    if($tno) {
        $cancel_msg = '주문상태 변경 오류';
        switch($od_pg) {
        case 'lg':
            include TB_SHOP_PATH.'/lg/xpay_cancel.php';
            break;
        case 'inicis':
            include TB_SHOP_PATH.'/inicis/inipay_cancel.php';
            break;
        case 'KAKAOPAY':
            $_REQUEST['TID']               = $tno;
            $_REQUEST['Amt']               = $amount;
            $_REQUEST['CancelMsg']         = $cancel_msg;
            $_REQUEST['PartialCancelCode'] = 0;
            include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
            break;
        case 'kcp':
            include TB_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
            break;
        }
    }

    // (2020-12-21) 오류 발생시 주문 취소
    $sql = " update shop_order set dan = '6' , cancel_date = '".TB_TIME_YMDHIS."' where od_id = '$od_id' ";
    sql_query($sql);

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>'.strtoupper($od_pg).'를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

// 회원이면서 포인트를 사용했다면 테이블에 사용을 추가
if($is_member && $i_usepoint) {
    insert_point($member['id'], (-1) * $i_usepoint, "주문번호 $od_id 결제");
}

// 쿠폰사용내역기록
if($is_member) {
    $sql = "select * from shop_order where od_id='$od_id'";
    $res = sql_query($sql);
    for($i=0; $row=sql_fetch_array($res); $i++) {
        if($row['coupon_price']) {
            $sql = "update shop_coupon_log
                set mb_use = '1',
                od_no = '$row[od_no]',
                cp_udate	= '".TB_TIME_YMDHIS."'
                where lo_id = '$row[coupon_lo_id]' ";
            sql_query($sql);
        }
    }
}

// 주문완료 문자전송
icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 2);

if($od_settle_case == "신용카드")
{
    //dreamline_order_sms_send($od_id, 1);
    dabonem_order_sms_send($od_id, 2);
}
else if($od_settle_case == "계좌이체")
{
    //dreamline_order_sms_send($od_id, 1);
    dabonem_order_sms_send($od_id, 2);
}
else if($od_settle_case == "가상계좌")
{
    //dreamline_order_sms_send($od_id, 3);
    dabonem_order_sms_send($od_id, 1);
}

// 메일발송
if($od['email']) {
    $subject1 = get_text($od['name'])."님 주문이 정상적으로 처리되었습니다.";
    $subject2 = get_text($od['name'])." 고객님께서 신규주문을 신청하셨습니다.";

    ob_start();
    include_once(TB_SHOP_PATH.'/orderformupdate_mail.php');
    $content = ob_get_contents();
    ob_end_clean();

    // 주문자에게 메일발송
    if($pt_id == 'itsgolf')
    {
        mailer("이츠골프", $super['email'], $od['email'], $subject1, $content, 1);
    }
    else
    {
        mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);
    }

    // 관리자에게 메일발송
    if($super['email'] != $od['email']) {
        mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
    }
}

// 주문 정보 임시 데이터 삭제
$sql = " delete from shop_order_data where od_id = '$od_id' and dt_pg = '$od_pg' ";
sql_query($sql);

// 주문번호제거
set_session('ss_order_id', '');

// 장바구니 session 삭제
set_session('ss_cart_id', '');

// orderinquiryview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id.$od['od_time'].$_SERVER['REMOTE_ADDR']);
set_session('ss_orderview_uid', $uid);

//현대리바트 기본금 사용호출 start
if($pt_id == "golf")
{
    $stotal = get_order_spay($od_id); // 총계

    if($stotal['usepoint2'] > 0) //기본금 사용액이 있니?
    {
        $mem_no = base64_encode(get_session("mem_no"));
        $mem_no2 = jsonfy2($mem_no);

        $shopevent_no = base64_encode(get_session("shopevent_no"));
        $shopevent_no2 = jsonfy2($shopevent_no);
        $shop_no = "6831A9DA1B37FA0E34799E99601BB6FE";//**추후 세션에 저장했다가 불러오는 방식 교체 필요

        $proc_code = base64_encode("200");//포인트 사용
        $proc_code2 = jsonfy2($proc_code);

        $mem_nm = iconv("UTF-8","EUC-kr",get_session("mem_nm"));//charset변환 
        $mem_nm2 = base64_encode($mem_nm);
        $mem_nm3 = jsonfy2($mem_nm2);

        $u_point = base64_encode($stotal['usepoint2']);
        $u_point2 = jsonfy2($u_point);

        $order_no = base64_encode($od_id); //$od_id
        $order_no2 = jsonfy2($order_no);

        $hdata = array(
            'mem_id' => $mem_no2,
            'shopevent_no' => $shopevent_no2,
            'proc_code' => $proc_code2,
            'chk_data' => $mem_nm3,
            'point' => $u_point2,
            'order_no' => $order_no2,
            'media_cd' => 'MW'
        );
        $url = "https://mgift.e-hyundai.com/hb2efront_new/pointOpenAPI.do?".http_build_query($hdata);
        //echo("url:".$url."<br>");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent  Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; MAAU)");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);

        $load_string = simplexml_load_string($res);
        $ma_result = $load_string->return_code;
        $value['c_type'] = "101";//포인트사용
        $value['url'] = $url; 
        $value['return_code'] = $ma_result; //응답코드
        $value['call_date'] = TB_TIME_YMDHIS;
        insert("hwelfare_log", $value);//DB에 insert하기

        curl_close($ch);

        // 2022-06-07
        if($ma_result != "000") {
            if($tno) {
                $cancel_msg = '포인트 사용 오류';
                switch($od_pg) {
                case 'lg':
                    include TB_SHOP_PATH.'/lg/xpay_cancel.php';
                    break;
                case 'inicis':
                    include TB_SHOP_PATH.'/inicis/inipay_cancel.php';
                    break;
                case 'KAKAOPAY':
                    $_REQUEST['TID']               = $tno;
                    $_REQUEST['Amt']               = $amount;
                    $_REQUEST['CancelMsg']         = $cancel_msg;
                    $_REQUEST['PartialCancelCode'] = 0;
                    include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                    break;
                case 'kcp':
                    include TB_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                    break;
                }
            }

            $sql = " update shop_order set dan = '6' , cancel_date = '".TB_TIME_YMDHIS."' , od_mod_history='$cancel_msg' where od_id = '$od_id' ";
            sql_query($sql);

            die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>'.strtoupper($od_pg).'를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
        }




    } //기본금 사용액 > 0 close

}else if($pt_id == "golfu"){

    //1.포인트 사용[차감] 호출

    if($i_usepoint > 0){
        $agent = "GOLFUNET";
        $pass = "GOLFUNET!@#$";
        $memId = substr(get_session('ss_mb_id'),6);
        $data = "exec=point&memId=".$memId."&pass=".$pass;
        $data .= "&ptype=3&point=".$i_usepoint."&pcode=14&orderId=".$od_id."&memo=구매 차감";
        $postdata = golfu_Encrypt_EnCode($data, $agent);
        $senddata = "agent=".$agent."&postdata=".urlencode($postdata);
        $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
        $result = golfu_HTTP_CURL($host, $senddata);
        $res_dec = json_decode($result);
        if($res_dec->success){//true or false
            $ma_result = "success";
        }else {
            $ma_result = "fail";
        }
        $value['c_type'] = "102";//포인트 차감
        $value['url'] = $data; 
        $value['return_code'] = $ma_result; //응답코드
        $value['call_date'] = TB_TIME_YMDHIS;
        insert("agency_log", $value);//DB에 insert하기
    }


}


//20191223 도담골프 결제후 포인트 사용
else if($pt_id == "dodamgolf")
{
    //파라미터 id, 키코드값, 포인트, 증가/차감, 내용
    $usr = str_replace("dd_","",get_session('ss_mb_id')); 
    $kc = gen_keycode();
    $em = $i_usepoint;
    $md = "minus";
    $mm = "[차감]도담골프 주문(".$od_id.")에 의한 적립금 차감";
    if(dodam_point($usr, $kc ,$em, $md, $mm) != null)
    {
        //포인트 차감 성공시 로그 구현, 보류
    }
    else
    {
        echo "post_curl_error";
        exit;
    }

    //회원 정보 다시 가져오기 point 값 다시 호출
    $keycode = gen_keycode();
    $mem_info = get_member_info($keycode, $usr);
    set_session('ss_mb_gd', $mem_info->mem_gd);
    set_session('ss_mb_point', $mem_info->point);
}

// 2021-08-09
else if($pt_id == 'golfrock'){
    if($i_usepoint > 0){
        $member_id = explode("_",$member['id'] );
        $res_dec = golfrock_point('point_use', $member_id[1], $member['name'], $od_id,'골프용품', $i_usepoint );
    }
}




goto_url(TB_MSHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
?>
