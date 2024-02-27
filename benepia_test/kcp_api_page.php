<?php
header("Content-type: text/html; charset=utf-8");
/* ============================================================================== */
/* =   API URL                                                                  = */
/* = -------------------------------------------------------------------------- = */	

$target_URL = "https://stg-spl.kcp.co.kr/gw/hub/v1/payment";  // 개발서버
$target_URL = "https://spl.kcp.co.kr/gw/hub/v1/payment";    // 운영서버


$target_URL2 = "https://stg-spl.kcp.co.kr/gw/mod/v1/cancel";  // 개발서버
//$target_URL2 = "https://spl.kcp.co.kr/gw/mod/v1/cancel";    // 운영서버
/* ============================================================================== */
/* =  요청정보                                                                                                                                                         = */
/* = -------------------------------------------------------------------------- = */    
$req_tx             = $_POST[ "req_tx"  ]; // 요청구분값
$site_cd            = $_POST[ "site_cd"  ]; // 사이트코드
// 인증서 정보(직렬화)


$kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==-----END CERTIFICATE-----";

$kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDjDCCAnSgAwIBAgIHBzDTvVomGjANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yNDAxMDcyMzA5MTdaFw0yOTAxMDUyMzA5MTdaMHsxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTEWMBQGA1UECgwNTkhOIEtDUCBDb3JwLjEXMBUGA1UECwwOUEdXRUJERVYgVGVhbS4xGTAXBgNVBAMMEDIwMjQwMTA4MTAwMDY2NjcwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCek590CbOfFcvUeqvy1H+XfSCLzTrD2p/Ts9Q3YyHRX1gdjQooenCplfBngpSZDPQcgOD5rXDnBJNnybjewVqyn/rKNYOSSFdsD/XLQwpB21ckAUS8mHhtJZzOWUdBAm6eXOeYCNj8EcXL08HkOPbYTGutliWpmbGaC81IaqVhs1c3rM31sDk+WnsnP7VQx4EcqaTW9PgCnRB4mShTav5wjXikb4EmnRPjmVCmYaKLdS2IQ5/wnfA6eFJS06cia6COaDBUSqHljU8kOIejEvPlQLFWeVK8YBJG9K0d7xfzMYn6vF3T8wmDKNp9vxMth2SS4ZTjpr53OeN9N93IwK7DAgMBAAGjHTAbMA4GA1UdDwEB/wQEAwIHgDAJBgNVHRMEAjAAMA0GCSqGSIb3DQEBCwUAA4IBAQDAgvO7lQOeQ66BSUG+x5E18dcTYGacsl5VxyzeqDHn+Rwc56XQZ/9aJV66/Jh8u+G/81/qiyJn2YHe9P991e0DWlOuL12YnPrYgBV5s6AheZnwRRKvboSLoLY1Q2MXMhTQJPWMgrCgbKRNGxkgbk2QC3hefMvwtIya1fHkvCTNIQDBsOaQaSSuHpPs01x0GJSEnuWh2TmlJOocRvK2ljy7xVsuyyMbmAv6tFHfj6dTVX2V2yh/cY6UyJ6Fs++CSJdGQ9pwPQZa19JcuxQV1wn5w+42J2k4hNfuhEpy4F0c0zXjrBMfGNN/qxQJObLHN8hyWnuHh+dVRwBACZscXO2z-----END CERTIFICATE-----";


$pay_method           = $_POST[ "pay_method"  ]; // 결제수단
$amount               = $_POST[ "amount"  ];     // 총결제금액    
$currency             = "";                      // 화폐단위
$cust_ip              = $_POST[ "cust_ip"  ];    // 결제 고객 ip


$pt_issue              = $_POST[ "pt_issue"  ];       // 포인트기관
$pt_txtype             = "";                          // 포인트전문유형    
$pt_idno               = $_POST[ "pt_idno"  ];        // 포인트계정 아이디
$pt_pwd                = $_POST[ "pt_pwd"  ];         // 포인트계정 비밀번호
$pt_memcorp_cd         = $_POST[ "pt_memcorp_cd"  ];  // 기관할당 코드
$pt_paycode            = "";                          // 결제코드

$ordr_idxx            = $_POST[ "ordr_idxx"  ];  // 주문번호
$good_name            = $_POST[ "good_name"  ];  // 상품명
$buyr_name            = $_POST[ "buyr_name"  ];  // 구매자명
$buyr_mail            = $_POST[ "buyr_mail"  ];  // 구매자이메일
$buyr_tel2            = $_POST[ "buyr_tel2"  ];  //구매자 휴대폰번호


//취소 전문
$kcp_sign_data  = ""; // 서명데이터
$mod_type       = $_POST["mod_type" ]; // 변경유형	
$tno            = $_POST["tno" ];      // NHN KCP 거래번호
$mod_desc       = $_POST["mod_desc" ]; // 변경 사유
$mod_mny        = $_POST["mod_mny" ];  // 재승인 요청금액
$mod_ordr_goods = $_POST["mod_ordr_goods" ];// 재승인시 상품명
$mod_ordr_idxx  = $_POST["mod_ordr_idxx" ]; // 재승인시 주문번호


if( $req_tx == "query")
{

    $data = array(
        'site_cd'        => $site_cd, 
        'kcp_cert_info'  => $kcp_cert_info,
        'pay_method'     => $pay_method,
        'amount'         => "1004",
        'currency'       => "410",
        //'cust_ip'        => $cust_ip, 
        'pt_issue'       => $pt_issue,
        'pt_txtype'      => "97000000",		
        'pt_idno'        => $pt_idno, 
        'pt_pwd'         => $pt_pwd,
        'pt_memcorp_cd'  => $pt_memcorp_cd,
        'ordr_idxx'      => $ordr_idxx,			  
    );

};


if( $req_tx == "pay")
{

    $data = array(
        'site_cd'        => $site_cd, 
        'kcp_cert_info'  => $kcp_cert_info,
        'pay_method'     => $pay_method,
        'amount'         => $amount,
        'currency'       => "410",
        'cust_ip'        => $cust_ip, 
        'pt_issue'       => $pt_issue,
        'pt_txtype'      => "91200000",		
        'pt_idno'        => $pt_idno, 
        'pt_pwd'         => $pt_pwd,
        'pt_memcorp_cd'  => $pt_memcorp_cd,  
        'pt_paycode'      => "04", 
        'pt_mny'         => $amount,
        'ordr_idxx'      => $ordr_idxx,
        'good_name'      => $good_name,
        'buyr_name'      => $buyr_name,
        'buyr_mail'      => $buyr_mail,
        'buyr_tel2'      => $buyr_tel2,
    );
}

$req_data = json_encode($data);

$header_data = array( "Content-Type: application/json", "charset=utf-8" );

// API REQ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $target_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// API RES
$res_data  = curl_exec($ch); 

/* ============================================================================== */
/* =  응답정보                                                                                                          = */
/* = -------------------------------------------------------------------------- = */
// 공통
$res_cd = "";
$res_msg = "";   
$rsv_pnt = "";


// RES JSON DATA Parsing
$json_res = json_decode($res_data, true);


if( $req_tx == "query")
{
    $res_cd = $json_res["res_cd"];
    $res_msg = $json_res["res_msg"];
    $rsv_pnt = $json_res["rsv_pnt"];
}


if( $req_tx == "pay")
{
    $res_cd = $json_res["res_cd"];
    $res_msg = $json_res["res_msg"];
    $tno = $json_res["tno"];
    $pnt_amount = $json_res["pnt_amount"];
    $pnt_app_time = $json_res["pnt_app_time"];
    $pnt_app_no = $json_res["pnt_app_no"];
    $rsv_pnt = $json_res["rsv_pnt"];

}



curl_close($ch); 



if( $req_tx == "mod")
{
    if( $mod_type == "STSC")
    {

        $data = array(
            'site_cd'        => $site_cd, 
            'kcp_cert_info'  => $kcp_cert_info,
            'kcp_sign_data'  => $kcp_sign_data, 
            'mod_type'       => $mod_type,
            'tno'            => $tno,
            'mod_desc'       => $mod_desc,			  
        );

    }


    if( $mod_type == "STRA")
    {

        $data = array(
            'site_cd'        => $site_cd, 
            'kcp_cert_info'  => $kcp_cert_info,
            'kcp_sign_data'  => $kcp_sign_data, 
            'mod_type'       => $mod_type,
            'tno'            => $tno,
            'mod_desc'       => $mod_desc,
            'mod_mny'        => $mod_mny,
            'mod_ordr_idxx'  => $mod_ordr_idxx,
            'mod_ordr_goods' => $mod_ordr_goods,			  
        );
    }


    $req_data = json_encode($data);
    $header_data = array( "Content-Type: application/json", "charset=utf-8" );

    // API REQ
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target_URL2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // API RES
    $res_data  = curl_exec($ch); 

    /* ============================================================================== */
    /* =  응답정보                                                                                                          = */
    /* = -------------------------------------------------------------------------- = */
    // 공통
    $res_cd = "";
    $res_msg = "";   
    $rsv_pnt = "";


    // RES JSON DATA Parsing
    $json_res = json_decode($res_data, true);

    $res_cd = $json_res["res_cd"];
    $res_msg = $json_res["res_msg"];
    $tno = $json_res["tno"];

    $pnt_amount = $json_res["pnt_amount"];
    $pnt_app_no = $json_res["pnt_app_no"];

    curl_close($ch); 
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>*** NHN KCP API SAMPLE ***</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
    <link href="./static/css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body oncontextmenu="return false;">
    <div class="wrap">
        <!-- header -->
        <div class="header">
            <a href="index.html" class="btn-back"><span>뒤로가기</span></a>
            <h1 class="title">TEST SAMPLE</h1>
        </div>

                <!-- //header -->
        <!-- contents -->
        <div id="skipCont" class="contents">
            <h2 class="title-type-3">요청  DATA</h2>
            <ul class="list-type-1">
                <li>
                    <div class="left">
                        <p class="title"></p>
                    </div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-3">
                            <textarea style="height:200px; width:450px" readonly><?=$req_data ?></textarea>
                        </div>
                    </div>
                </li>
            </ul>
            <h2 class="title-type-3">응답  DATA </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left">
                        <p class="title"></p>
                    </div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-3">
                            <textarea style="height:200px; width:450px" readonly><?=$res_data ?></textarea>
                        </div>
                    </div>
                </li>
            </ul>
            <h2 class="title-type-3">응답  DATA </h2>
            <ul class="list-type-1">
                <li>
                    <div class="left"><p class="title">결과코드</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?= $res_cd ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">결과메세지</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?= $res_msg ?><br/>
                        </div>
                    </div>
                </li>

<?
/* ============================================================================== */
/* =   1. 정상 조회 시 결과 출력 ( res_cd값이 0000인 경우)                  = */
/* = -------------------------------------------------------------------------- = */

if( $req_tx == "query" )
{
    if( $res_cd == "0000" )
    {
?>  
                   <li>
                 <div class="left"><p class="title">사용 가능 포인트</p></div>
                   <div class="right">
                      <div class="ipt-type-1 pc-wd-2">
                         <?= $rsv_pnt ?>
                        </div>
                  </div>
                </li> 

<?
        /* ============================================================================== */
        /* =   1. 정상 결제시 결제 결과 출력 ( res_cd값이 0000인 경우)                  = */
        /* = -------------------------------------------------------------------------- = */
    }
}
if( $req_tx == "pay" )
{
    if( $res_cd == "0000" )
    {

?>           

                    <li>
                    <div class="left"><p class="title">NHN KCP 거래번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$tno ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">총 결제금액</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_amount ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="left"><p class="title">승인시각</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_time ?>
                        </div>
                    </div>
                </li>
                 <li>
                    <div class="left"><p class="title">승인번호</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$pnt_app_no ?>
                        </div>
                    </div>
                </li>
                 <li>
                    <div class="left"><p class="title">잔여포인트</p></div>
                    <div class="right">
                        <div class="ipt-type-1 pc-wd-2">
                            <?=$rsv_pnt ?>
                        </div>
                    </div>
                </li>   
            </ul>

<?
    }
} 	  
/* ============================================================================== */
/* =   1. 정상 취소(재승인)시 결과 출력 ( res_cd값이 0000인 경우)                  = */
/* = -------------------------------------------------------------------------- = */	  
if( $req_tx == "mod" )
{
    if( $res_cd == "0000" )
    {	  
?>          
            <li>
               <div class="left"><p class="title">NHN KCP 거래번호</p></div>
               <div class="right">
                   <div class="ipt-type-1 pc-wd-2">
                       <?=$tno ?>
                   </div>
               </div>
           </li>           
<? 
        if( $pnt_amount != "")
        {

?>  

          <li>
              <div class="left"><p class="title">재승인금액</p></div>
              <div class="right">
                  <div class="ipt-type-1 pc-wd-2">
                      <?=$pnt_amount ?>
                  </div>
              </div>
          </li>
          <li>
              <div class="left"><p class="title">승인번호</p></div>
              <div class="right">
                  <div class="ipt-type-1 pc-wd-2">
                      <?=$pnt_app_no ?>
                  </div>
              </div>
              </li>
          </ul>
<?    
        }
    }
}            
?>

            <ul class="list-btn-2">
                <li class="pc-only-show"><a href="./index.html" class="btn-type-3 pc-wd-2">처음으로</a></li>
            </ul>
        </div>
        <div class="grid-footer">
            <div class="inner">
                <!-- footer -->
                <div class="footer">
                    ⓒ NHN KCP Corp.
                </div>
                <!-- //footer -->
            </div>
        </div>
    </div>
    <!--//wrap-->
</body>
</html>
