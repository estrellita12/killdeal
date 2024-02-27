<?php
// 2023-09-20 test
if( $_SERVER['REMOTE_ADDR']=="1.234.59.107" ){
    return;
}

//2022-01-06 JS console.log() 사용
function console_log($data){
    echo "<script>console.log(" . json_encode($data) . ");</script>";
}

// (2021-01-02) 박사장몰 한글 주소 리다이렉트
if($_SERVER['HTTP_HOST'] == 'xn--352bnzz4h91i.com' || $_SERVER['HTTP_HOST'] == 'www.xn--352bnzz4h91i.com'){ //박사장몰
    header( 'Location: https://baksajangmall.com' );
}

/*******************************************************************************
 ** 공통 변수, 상수, 코드
 *******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!defined('TB_SET_TIME_LIMIT')) define('TB_SET_TIME_LIMIT', 0);
@set_time_limit(TB_SET_TIME_LIMIT);

//===========================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//-----------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
    'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
    'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if(isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if(isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//===========================================================================================================

function tb_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
    $result['path'] = str_replace('\\', '/', $chroot.dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$tb_path = tb_path();

include_once($tb_path['path'].'/config.php');   // 설정 파일

unset($tb_path);

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('TB_ESCAPE_PATTERN') && defined('TB_ESCAPE_REPLACE')) {
        $pattern = TB_ESCAPE_PATTERN;
        $replace = TB_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if(get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(TB_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(TB_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(TB_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(TB_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config  = array();
$default = array();
$super	 = array();
$member  = array();
$partner = array();
$seller	 = array();
$tb		 = array();

//==============================================================================
// 항상 "www" 를 타고 들어오는 도메인은 "www" 를 제거
if(preg_match("/www\./i", $_SERVER['HTTP_HOST'])) {
    header("Location:https://".preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
}

//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = TB_DATA_PATH.'/'.TB_DBCONFIG_FILE;
if(file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once(TB_LIB_PATH."/partner.lib.php"); // 가맹점 라이브러리
    include_once(TB_LIB_PATH."/global.lib.php"); // PC+모바일 공통 라이브러리
    include_once(TB_LIB_PATH."/common.lib.php"); // PC전용 라이브러리
    include_once(TB_LIB_PATH."/mobile.lib.php"); // 모바일전용 라이브러리
    include_once(TB_LIB_PATH."/thumbnail.lib.php"); // 썸네일 라이브러리
    include_once(TB_LIB_PATH."/editor.lib.php"); // 에디터 라이브러리
    include_once(TB_LIB_PATH."/login-oauth.php"); // SNS 로그인
    include_once(TB_LIB_PATH."/mirae.lib.php"); // 추가 라이브러리

    $connect_db = sql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(TB_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // 20191113 더골프쇼 DB연결
    // 20201005 더골프쇼 DB사용하지않음
    //$connect_db_tgs = tgs_sql_connect(TGS_TB_MYSQL_HOST, TGS_TB_MYSQL_USER, TGS_TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    //$select_db_tgs  = sql_select_db(TGS_TB_MYSQL_DB, $connect_db_tgs) or die('MySQL DB Error!!!');

    // mysql connect resource $tb 배열에 저장 - 명랑폐인님 제안
    $tb['connect_db'] = $connect_db;
    //$tb['connect_db_tgs'] = $connect_db_tgs;

    sql_set_charset('utf8', $connect_db);
    //sql_set_charset('utf8', $connect_db_tgs);
    if(defined('TB_MYSQL_SET_MODE') && TB_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if(defined(TB_TIMEZONE)) sql_query(" set time_zone = '".TB_TIMEZONE."'");
} else {
    header('Content-Type: text/html; charset=utf-8');

    die($dbconfig_file.' 파일을 찾을 수 없습니다.');
}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0); // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

session_save_path(TB_SESSION_PATH);

if(isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 30); // 세션 캐쉬 보관시간 (분) : 180
ini_set("session.gc_maxlifetime", 1800); // session data의 garbage collection 존재 기간을 지정 (초) :10800
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", TB_COOKIE_DOMAIN);


if( ! class_exists('XenoPostToForm') ){
    class XenoPostToForm
    {
        public static function check() {
            return !isset($_COOKIE['PHPSESSID']) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']) || ! isset($_SERVER['HTTP_REFERER']) ));
        }

        public static function submit($posts) {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<form id="f" name="f" method="post">';
            echo self::makeInputArray($posts);
            echo '</form>';
            echo '<script>';
            echo 'document.f.submit();';
            echo '</script></body></html>';
            exit;
        }

        public static function makeInputArray($posts) {
            $res = array();
            foreach($posts as $k => $v) {
                $res[] = self::makeInputArray_($k, $v);
            }
            return implode('', $res);
        }

        private static function makeInputArray_($k, $v) {
            if(is_array($v)) {
                $res = array();
                foreach($v as $i => $j) {
                    $res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
                }
                return implode('', $res);
            }
            return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
        }
    }
}

if( !function_exists('shop_check_is_pay_page') ){
    function shop_check_is_pay_page(){
        $shop_dir = 'shop';
        $mobile_dir = TB_MOBILE_DIR;

        // PG 결제사의 리턴페이지 목록들
        // 2022-11-30 리턴 페이지 추가
        $pg_checks_pages = array(
            $shop_dir.'/inicis/INIStdPayReturn.php',
            $mobile_dir.'/'.$shop_dir.'/inicis/pay_return.php',
            $mobile_dir.'/'.$shop_dir.'/inicis/pay_approval.php',
            $shop_dir.'/lg/returnurl.php',
            $mobile_dir.'/'.$shop_dir.'/lg/returnurl.php',
            $mobile_dir.'/'.$shop_dir.'/lg/xpay_approval.php',
            $mobile_dir.'/'.$shop_dir.'/kcp/order_approval_form.php',

            $shop_dir.'/orderformresult.php',
            $mobile_dir.'/'.$shop_dir.'/orderformresult.php',
        );

        $server_script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

        // PG 결제사의 리턴페이지이면
        foreach( $pg_checks_pages as $pg_page ){
            if( preg_match('~'.preg_quote($pg_page).'$~i', $server_script_name) ){
                return true;
            }
        }

        return false;
    }
}

// PG 결제시에 세션이 없으면 내 호출페이지를 다시 호출하여 쿠키 PHPSESSID를 살려내어 세션값을 정상적으로 불러오게 합니다.
// 위와 같이 코드를 전부 한페이지에 넣은 이유는 이전 버전 사용자들이 패치시 어려울수 있으므로 한페이지에 코드를 다 넣었습니다.
if(XenoPostToForm::check()) {
    if ( shop_check_is_pay_page() ){	// PG 결제 리턴페이지에서만 사용
        XenoPostToForm::submit($_POST); // session_start(); 하기 전에
    }
}
//==============================================================================

//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
$super = get_member('admin');
$super_hp = $super['cellphone'];

//------------------------------------------------------------------------------
// Chrome 80 버전부터 아래 이슈 대응
// https://developers-kr.googleblog.com/2020/01/developers-get-ready-for-new.html?fbclid=IwAR0wnJFGd6Fg9_WIbQPK3_FxSSpFLqDCr9bjicXdzy--CCLJhJgC9pJe5ss
if(!function_exists('session_start_samesite')) {
    function session_start_samesite($options = array())
    {
        $res = @session_start($options);

        // IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None 을 설정하지 않습니다.
        if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) ){
            return $res;
        }

        $headers = headers_list();
        krsort($headers);
        foreach ($headers as $header) {
            if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
            $header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
            header($header, false);
            break;
        }
        return $res;
    }
}

session_start_samesite();
//------------------------------------------------------------------------------

// 보안서버주소 설정
if(TB_HTTPS_DOMAIN) {
    define('TB_HTTPS_BBS_URL', TB_HTTPS_DOMAIN.'/'.TB_BBS_DIR);
    define('TB_HTTPS_MBBS_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_BBS_DIR);
    define('TB_HTTPS_SHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_SHOP_DIR);
    define('TB_HTTPS_MSHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_SHOP_DIR);
} else {
    define('TB_HTTPS_BBS_URL', TB_BBS_URL);
    define('TB_HTTPS_MBBS_URL', TB_MBBS_URL);
    define('TB_HTTPS_SHOP_URL', TB_SHOP_URL);
    define('TB_HTTPS_MSHOP_URL', TB_MSHOP_URL);
}

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if(isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(TB_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if(isset($_REQUEST['set'])) {
    $set = trim($_REQUEST['set']);
    $qstr .= '&set=' . urlencode($set);
}

if(isset($_REQUEST['sca'])) {   // 승인상태
    $sca = trim($_REQUEST['sca']);
    $qstr .= '&sca=' . urlencode($sca);
}

if(isset($_REQUEST['sfl'])) {   // 검색 조건
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    $qstr .= '&sfl=' . urlencode($sfl); // search field (검색 필드)
}

if(isset($_REQUEST['stx'])) {   // 검색 조건에 해당하는 검색어
    $stx = trim($_REQUEST['stx']);
    $qstr .= '&stx=' . urlencode($stx);
}

if(isset($_REQUEST['sst'])) {   // 레벨(grade)
    $sst = trim($_REQUEST['sst']);
    $qstr .= '&sst=' . urlencode($sst);
}

if(isset($_REQUEST['sod'])) {
    $sod = trim($_REQUEST['sod']);
    $qstr .= '&sod=' . urlencode($sod);
}

if(isset($_REQUEST['sop'])) {
    $sop = trim($_REQUEST['sop']);
    $qstr .= '&sop=' . urlencode($sop);
}

if(isset($_REQUEST['spt'])) {   // 날짜조건, sel_field 도 있음
    $spt = trim($_REQUEST['spt']);
    $qstr .= '&spt=' . urlencode($spt);
}

if(isset($_REQUEST['ca_id'])) {
    $ca_id = trim($_REQUEST['ca_id']);
    $qstr .= '&ca_id=' . urlencode($ca_id);
}

if(isset($_REQUEST['fr_date'])) {   // 시작 날짜
    $fr_date = trim($_REQUEST['fr_date']);
    $qstr .= '&fr_date=' . urlencode($fr_date);
}

if(isset($_REQUEST['to_date'])) {   // 종료 날짜
    $to_date = trim($_REQUEST['to_date']);
    $qstr .= '&to_date=' . urlencode($to_date);
}

if(isset($_REQUEST['filed'])) {
    $filed = trim($_REQUEST['filed']);
    $qstr .= '&filed=' . urlencode($filed);
}

if(isset($_REQUEST['orderby'])) {
    $orderby = trim($_REQUEST['orderby']);
    $qstr .= '&orderby=' . urlencode($orderby);
}

if(isset($_REQUEST['calculate_yn'])) {
    $calculate_yn = trim($_REQUEST['calculate_yn']);
    $qstr .= '&calculate_yn=' . urlencode($calculate_yn);
}

// URL ENCODING
if(isset($_REQUEST['url'])) {
    $url = strip_tags(trim($_REQUEST['url']));
    $urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if(TB_DOMAIN) {
        $p = @parse_url(TB_DOMAIN);
        $urlencode = TB_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}
//===================================

// 자동로그인 부분에서 첫로그인에 포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if($_SESSION['ss_mb_id']) { // 로그인중이라면
    $member = get_member($_SESSION['ss_mb_id']);

    // 차단된 회원이면 ss_mb_id 초기화
    if($member['intercept_date'] && $member['intercept_date'] <= date("Ymd", TB_SERVER_TIME)) {
        if(!get_session('admin_ss_mb_id')) { // 관리자 강제접속이 아닐때만.
            set_session('ss_mb_id', '');
            $member = array();
        }
    } else {
        // 오늘 처음 로그인 이라면
        if(substr($member['today_login'], 0, 10) != TB_TIME_YMD) {
            // 첫 로그인 포인트 지급
            insert_point($member['id'], $config['login_point'], TB_TIME_YMD.' 첫로그인', '@login', $member['id'], TB_TIME_YMD);

            // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
            // 해당 회원의 접근일시와 IP 를 저장
            $sql = " update shop_member set login_sum = login_sum + 1, today_login = '".TB_TIME_YMDHIS."', login_ip = '{$_SERVER['REMOTE_ADDR']}' where id = '{$member['id']}' ";
            sql_query($sql);
        }
    }
} else {
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if($tmp_mb_id = get_cookie('ck_mb_id')) {

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if(strtolower($tmp_mb_id) != 'admin') {
            $sql = " select passwd, intercept_date from shop_member where id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['passwd']);
            // 쿠키에 저장된 키와 같다면
            $tmp_key = get_cookie('ck_auto');
            if($tmp_key === $key && $tmp_key) {
                // 차단, 인트로 사용이 아니라면
                if($row['intercept_date'] == '' && !$config['shop_intro_yes'] ) {
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session('ss_mb_id', $tmp_mb_id);



                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}

if($boardid) {
    $board = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
    if($board['index_no']) {
        $write_table = 'shop_board_'.$boardid; // 게시판 테이블 전체이름
        if(isset($index_no) && $index_no)
            $write = sql_fetch(" select * from $write_table where index_no = '$index_no' ");
    }
}

// 비회원구매를 위해 쿠키를 1년간 저장

if(!get_cookie("ck_guest_cart_id"))
    set_cookie("ck_guest_cart_id", TB_SERVER_TIME, 86400 * 365);

//$set_cart_id = get_cookie('ck_guest_cart_id');
// 2024-02-18
$set_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', get_cookie('ck_guest_cart_id'));


// 회원, 비회원 구분
$is_admin = $mb_no = '';
if($member['id']) {
    $is_member = 1;
    $is_admin = get_admin($member['id']);
    $partner = get_partner($member['id']);
    $seller = get_seller($member['id']);
    $mb_no = $member['index_no'];
} else {
    $is_member = 0;
    $member['id'] = '';
    $member['grade'] = 10; // 비회원의 경우 회원레벨을 가장 낮게 설정


}

if(!is_admin()) {
    // 접근가능 IP
    $possible_ip = trim($config['possible_ip']);
    if($possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $possible_ip);
        for($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if(empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if($is_possible_ip)
                break;
        }
        if(!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['intercept_ip']));
    for($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if(empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}

//==============================================================================
// 사용기기 설정
// config.php TB_SET_DEVICE 설정에 따라 사용자 화면 제한됨
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('TB_SET_DEVICE')) {
    switch(TB_SET_DEVICE) {
    case 'pc':
        $is_mobile  = false;
        $set_device = false;
        break;
    case 'mobile':
        $is_mobile  = true;
        $set_device = false;
        break;
    default:
        break;
    }
}
//==============================================================================


//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
// TB_MOBILE_AGENT : config.php 에서 선언
//------------------------------------------------------------------------------
if(TB_USE_MOBILE && $set_device) {
    if($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if($_REQUEST['device']=='mobile')
        $is_mobile = true;
    else if(defined('TB_USERIN_MOBILE'))
        $is_mobile = true;
    else if(isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if(is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('TB_IS_MOBILE', $is_mobile);
define('TB_DEVICE_BUTTON_DISPLAY', $set_device);
if(TB_IS_MOBILE) {
    $tb['mobile_path'] = TB_PATH.'/'.$tb['mobile_dir'];
}
//==============================================================================

// common.php 파일을 수정할 필요가 없도록 확장합니다.
$extend_file = array();
$tmp = dir(TB_EXTEND_PATH);
while($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(TB_EXTEND_PATH.'/'.$file);
    }
}
unset($extend_file);

// 가맹점 쇼핑몰설정
include_once(TB_PATH.'/partner.config.php');

// 일정 기간이 지난 DB 데이터 삭제 및 최적화
include_once(TB_LIB_PATH.'/db_table.optimize.php');





ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

$html_process = new html_process();


if($pt_id == 'golf') //현대리바트
{
    if(get_session('mem_no'))
    {
        $is_member = 1;
        //현대리바트 전용 id생성
        $member['id'] = "hwelf".get_session('mem_no');
        $member['name'] = get_session('mem_nm'); // (2021-02-19) 이름도 member 배열에 추가 저장
        $member['grade'] = 8;
        //$member['pt_id'] = 'golf';
    }
}
else if($pt_id == 'golfpang')//골팡_20190919
{
    if($_COOKIE['gp_id'])
    {
        if (($_SESSION['ss_mb_id'] == '') || (!isset($_SESSION['ss_mb_id']))) {
            set_session('ss_mb_id', $_COOKIE['gp_id']);
        }
    }
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = "gp_".get_session('ss_mb_id');  //20191224_ss_mb_id수정
        $member['grade'] = 9;//일반회원
        $member['name'] = $_COOKIE['gp_name'];
        $member['cellphone'] = $_COOKIE['gp_phone'];    // (2021-01-26)
        $member['pt_id'] = "golfpang";                  //가맹점정보
    }

}
else if($pt_id == 'golfu')//골프유닷넷_20191024
{
    if($_COOKIE['GolfuID'])
    {
        if (($_SESSION['ss_mb_id'] == '') || (!isset($_SESSION['ss_mb_id']))) {
            set_session('ss_mb_id', "golfu_".$_COOKIE['GolfuID']);
        }
    }
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');    //20191224_ss_mb_id수정
        $member['grade'] = 9;                       //일반회원
        //$member['name'] = $_COOKIE['UserName'];
        $member['cellphone'] = $_COOKIE['UserMobile']; // (2021-01-26)
        $member['pt_id'] = "golfu";                 //가맹점정보
        //login_sso_log($pt_id, $mkey , $uuid, $_COOKIE['UserName'], $email, $cellphone, $member['id'],'Y',$_COOKIE['GolfuID']);
        set_session('ss_mb_name', $_COOKIE['UserName']);
        if(get_session('ss_mb_name') == '') {
            $agent = "GOLFUNET";
            $pass = "GOLFUNET!@#$";
            $exec = "memberAddress";
            $memId = substr(get_session('ss_mb_id'),6);
            $data = "exec=memberAddress&memId=".$memId."&pass=".$pass;
            $postdata = golfu_Encrypt_EnCode($data, $agent);
            $senddata = "agent=".$agent."&postdata=".urlencode($postdata);
            $host = "https://www.uscore.co.kr/agent/golfunet_point_proc.php";
            $result = golfu_HTTP_CURL($host, $senddata);
            $res_dec = json_decode($result);
            //var_dump($res_dec);

            set_session('zip', $res_dec->addressInfo->zipcode);
            set_session('addr1', $res_dec->addressInfo->addr1);
            set_session('addr2', $res_dec->addressInfo->addr2);
            set_session('ss_mb_name', $res_dec->addressInfo->memname);
        }
        $member['zip'] = get_session('zip');
        $member['addr1'] = get_session('addr1');
        $member['addr2'] = get_session('addr2');
        $member['name'] = get_session('ss_mb_name');

    }
}

// 20191112 더골프쇼 세션확인 후 member변수에 세션 저장
// 20200813 더골프쇼 웹 회원 정보 member변수에 세션 저장
else if($pt_id == 'thegolfshow')
{
    $mkey = $_POST['mkey'];
    //setcookie('mkey', $mkey, time() + 86400); //하루
    $mkey_log= $_POST['mkey'];

    //login_sso_log($pt_id, $mkey , '', '', '', '', '','N',$mkey_log);

    if(isset($mkey) ) {
        //복호화
        $mkey = decrypt_mcrypt_thegolfshow($mkey);
        $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
        $mkey_value = json_decode($mkey,true);


        //$uuid_id = explode('@',$mkey_value["email"]);
        //$uuid_id = $uuid_id[0];

        //$uuid = $uuid_id;
        $uuid =  $mkey_value["uuid"];
        $md_id = 'tgs_'.$uuid;
        $name =  $mkey_value["name"];
        //$email = $mkey_value["email"];
        $cellphone = $mkey_value["cell_phone"];
        $mkey_encode = $_COOKIE['sso_login_token'];

        if(!$uuid)
        {
            //로그 정보를 쌓는다.
            login_sso_log($pt_id, $mkey , $uuid, $name, '', $cellphone, $md_id,'N',$mkey_log);
            alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'https://www.thegolfshow.co.kr/');

            return false;
        }

        //login_sso_log($pt_id, $mkey, $uuid, $name, '', $cellphone, $md_id,'Y',$mkey_log);
        set_session('ss_mb_id', 'tgs_'.$uuid);
        set_session('ss_mb_nm', $name);
        set_session('ss_mb_phone', $cellphone);
        //set_session('ss_mb_email', $email);
    }



    if(get_session('ss_mb_id'))
    {
        // $is_member = 1;
        // $member['id'] = get_session('ss_mb_id');
        //	$member['name'] = get_session('ss_mb_name');
        //   $member['email'] = get_session('ss_mb_email');
        //  $member['cellphone'] = get_session('ss_mb_phone');
        //  $member['grade'] = 9;//**왜 8인지 확인필요
        //  $member['pt_id'] = "thegolfshow";//가맹점정보

        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['grade'] = 9;//**왜 8인지 확인필요
        $member['pt_id'] = "thegolfshow";//가맹점정보
        $member['name'] = get_session('ss_mb_nm');
        $member['grade'] = '9';
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');

    }

    //var_dump($_SESSION);
    if(isset($mkey)) {
        //성공 로그 정보를 쌓는다.
        login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_log);
    }

}

// 20191217 도담골프 로그인세션 member변수에 정보 저장
else if($pt_id == 'dodamgolf')
{
    //var_dump($_SESSION);
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_nm');
        $member['grade'] = get_session('ss_mb_gd');
        $member['point'] = get_session('ss_mb_point');
        $member['pt_id'] = "dodamgolf";//가맹점정보

        if($member['id'] == 'dd_ssw5541')
        {
            //echo "주소";
            $member['zip'] = "11914";
            $member['cellphone'] = "01089705541";
            $member['email'] = "ssw5541@naver.com";
            //echo $member['zip'];
            $member['addr1'] = "경기 구리시 동구릉로 85번길 63";
            $member['addr2'] = "404동 1006호";
            $member['addr3'] = "(인창동, 아름마을인창래미안아파트)";
        }

    }

}
// 20200128 이츠골프 세션확인 후 member변수에 세션 저장
else if($pt_id == 'itsgolf')
{
    //var_dump($_SESSION);
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['grade'] = 9;//**왜 8인지 확인필요
        $member['pt_id'] = "itsgolf";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');
    }

}

// 20200220 골프야 세션확인 후 member변수에 세션 저장
else if($pt_id == 'golfya')
{

    //var_dump($_SESSION);
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['grade'] = 9;//**왜 8인지 확인필요
        $member['pt_id'] = "golfya";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');
    }

}

// 20200327 골프잼 세션확인 후 member변수에 세션 저장
else if($pt_id == 'golfjam')
{
    //var_dump($_SESSION);
    if(get_session('ss_mb_id'))
    {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['grade'] = 9;//**왜 8인지 확인필요
        $member['pt_id'] = "golfjam";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');
    }

}


else if($pt_id=='golfrock'){
    //if( !get_session('ss_mb_id') && $_POST['uid'] )
    if( isset( $_POST['uid'] )  && $_POST['uid'] != "" )
    {
        $uid = $_POST['uid'];
        $name = $_POST['name'];
        $cellphone = $_POST['cellphone'];

        set_session('ss_mb_id', 'rock_'.$uid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $cellphone);

        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "golfrock";  //가맹점정보

        // pt_it, mkey, uid, name, email, cellphone, md_id, Y/N, mkey_encode
        login_sso_log($pt_id, '' , $uid, $name, '', $cellphone, $member['id'] ,'Y','');

    }else if( get_session('ss_mb_id') ){
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "golfrock";//가맹점정보        
    }
}

else if($pt_id=='teeluv'){
    //if( !get_session('ss_mb_id') && $_POST['uid'] )
    if( isset( $_POST['mkey'] ) && $_POST['mkey'] != "" )
    {
        $mkey = $_POST['mkey'];
        $mkey_encode = decrypt_mcrypt_teeluv($mkey);
        $mkey_value = json_decode($mkey_encode,true);

        $uid = $mkey_value["uuid"];
        $name =  $mkey_value["name"];
        $email = $mkey_value["email"];
        $cellphone = $mkey_value["cellphone"];

        set_session('ss_mb_id', 'teeluv_'.$uid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_email', $email);
        set_session('ss_mb_phone', $cellphone);

        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['email'] = get_session('ss_mb_email');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "teeluv";  //가맹점정보

        // pt_it, mkey, uid, name, email, cellphone, md_id, Y/N, mkey_encode
        login_sso_log($pt_id, $mkey , $uid, $name, $email, $cellphone, $member['id'] ,'Y');

    }else if( get_session('ss_mb_id') ){
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['email'] = get_session('ss_mb_email');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "teeluv";//가맹점정보        
    }
}

else if($pt_id=='lgcare'){
    if( isset( $_POST['mkey'] ) && $_POST['mkey'] != "" )
    {
        $mkey = $_POST['mkey'];
        $mkey_encode = decrypt_mcrypt_lgcare($mkey);
        $mkey_value = json_decode($mkey_encode,true);

        $uid = $mkey_value["uuid"];
        $name =  $mkey_value["name"];
        $email = $mkey_value["email"];
        $cellphone = $mkey_value["cellphone"];

        if( isset($uid) && $uid!="" ){
        set_session('ss_mb_id', 'lgcare_'.$uid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_email', $email);
        set_session('ss_mb_phone', $cellphone);

        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['email'] = get_session('ss_mb_email');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "lgcare";  //가맹점정보

        // pt_it, mkey, uid, name, email, cellphone, md_id, Y/N, mkey_encode
        login_sso_log($pt_id, $mkey , $uid, $name, $email, $cellphone, $member['id'] ,'Y');
        }
    }else if( get_session('ss_mb_id') ){
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['email'] = get_session('ss_mb_email');
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['grade'] = 9;
        $member['pt_id'] = "lgcare";//가맹점정보
    }
}

//리프레쉬클럽 -- 핫딜에서 바로 들어와야 할때 index.php를 타지 않고 바로 넘어와야 함. 20200611
else if($pt_id == 'refreshclub') {
    $mkey = $_GET['mkey'];

    if(isset($_GET['mkey'])) {
        //복호화
        $mkey = decrypt_mcrypt($mkey);
        $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
        $mkey_value = json_decode($mkey,true);



        $refreshid = $mkey_value["uuid"];
        $uuid = $mkey_value["uuid"];
        $md_id = 'refreshclub_'.$uuid;
        $name =  $mkey_value["name"];
        $email = $mkey_value["email"];
        $cellphone = $mkey_value["cell_phone"];
        $mkey_encode = $_GET['mkey'];

        if(!$uuid)
        {
            //로그 정보를 쌓는다.
            login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
            alert("로그인 정보가 올바르지 않습니다.  문제가 계속되면 고객센터(1566-6933)으로 연락해주시기 바랍니다.", TB_URL);
            return false;
        }

        set_session('ss_mb_id', 'refreshclub_'.$refreshid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $cellphone);
        set_session('ss_mb_email', $email);
    }

    if(get_session('ss_mb_id')) {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['grade'] = 9;//**왜 8인지 확인필요
        $member['pt_id'] = "refreshclub";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');

        if(isset($mkey)) {
            //성공 로그 정보를 쌓는다.
            //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
        }
    }
    //var_dump($_SESSION);
}
/*
else if($pt_id == 'maniamall') {
    $mkey = $_COOKIE['sso_login_token'];

//	$mkey1 = decrypt_mcrypt_maniamall($mkey);


    if(isset($mkey) ) {
    //복호화
    $mkey = decrypt_mcrypt_maniamall($mkey);
    $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
    $mkey_value = json_decode($mkey,true);

        $uuid = $mkey_value["uuid"];
        $md_id = 'maniamall_'.$uuid;
        $name =  $mkey_value["name"];
        $email = $mkey_value["email"];
        $cellphone = $mkey_value["cell_phone"];
        $mkey_encode = $_COOKIE['sso_login_token'];

        if(!$uuid)
        {
            //로그 정보를 쌓는다.
            login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
            alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'http://maniamall.kr');
            return false;
        }


        set_session('ss_mb_id', 'maniamall_'.$uuid);
        set_session('ss_mb_name', $name);
        set_session('ss_mb_phone', $cellphone);
        set_session('ss_mb_email', $email);
    }

    if(get_session('ss_mb_id')) {
        $is_member = 1;
        $member['id'] = get_session('ss_mb_id');
        $member['name'] = get_session('ss_mb_name');
        $member['grade'] = 9;
        $member['pt_id'] = "maniamall";//가맹점정보
        $member['cellphone'] = get_session('ss_mb_phone');
        $member['email'] = get_session('ss_mb_email');
        //var_dump($_SESSION);
        //성공 로그 정보를 쌓는다.
        if(isset($mkey) ) {
            //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
        }
    }

}
         */

    /*
        //홍골프
    else if($pt_id == 'honggolf') {
        //var_dump($_SESSION);
        $mkey = $_GET['mkey'];

        if(isset($mkey)) {
            //복호화
            $mkey = decrypt_mcrypt_hong($mkey);
            $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
            $mkey_value = json_decode($mkey,true);

            $uuid = $mkey_value["uuid"];
            $md_id = 'honggolf_'.$uuid;
            $name =  $mkey_value["name"];
            $email = $mkey_value["email"];
            $cellphone = $mkey_value["cell_phone"];
            $mkey_encode =$_GET['mkey'];

            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'https://www.honggolf.com');
                return false;
            }

            set_session('ss_mb_id', 'honggolf_'.$uuid);
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
            //			var_dump($_SESSION);
            if(isset($mkey)) {
                //성공 로그 정보를 쌓는다.
                //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }


    }
    */
    //아이멤버스
    else if($pt_id == 'imembers') {

        $mkey = $_POST['mkey'];



        if(isset($mkey) ) {
            //복호화
            $mkey = decrypt_mcrypt_imembers($mkey);
            $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
            $mkey_value = json_decode($mkey,true);
            //	 var_dump($mkey_value);

            $uuid = $mkey_value["uuid"];
            $md_id = 'imembers_'.$uuid;
            $name =  $mkey_value["name"];
            $email = $mkey_value["email"];
            $cellphone = $mkey_value["cell_phone"];
            $mkey_encode = $_COOKIE['sso_login_token'];



            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'https://imembers.co.kr/');
                return false;
            }


            set_session('ss_mb_id', 'imembers_'.$uuid);
            set_session('ss_mb_name', $name);
            set_session('ss_mb_phone', $cellphone);
            set_session('ss_mb_email', $email);



        }

        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "imembers";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
            //var_dump($_SESSION);
            if(isset($mkey)) {
                //성공 로그 정보를 쌓는다.
                //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }else {
            //goto_url('https://www.imembers.co.kr/bbs/login.php');
        }



    }

    // (2020-12-09) uscore 연동
    else if($pt_id == 'uscore'){
        if( isset( $_COOKIE[md5('mem_id')] ) ){
            $uuid = get_cookie_uscore('mem_id');
            //$md_id = 'uscore_'.get_cookie_uscore('mem_id');
            $md_id = 'uscore_'.get_cookie_uscore('idx');
            $name = get_cookie_uscore('mem_name');
            $hp = get_cookie_uscore('mem_hp');
            $email = get_cookie_uscore('mem_email');
            //$idx = get_cookie_uscore('idx');

            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                return false;
            }
            set_session('ss_mb_id', $md_id);
            set_session('ss_mb_name', $name);
            set_session('ss_mb_phone', $hp);
            set_session('ss_mb_email', $email);
        }

        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "uscore";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
        }
    }
    
    
    else if($pt_id == 'cokgolf'){
        if(get_session('ss_mb_id')) {
            //console_log($member);
            //console_log($is_member);
        }
    }

    //원불교 복지몰
    else if($pt_id == 'wonmall') { //2022-07-25 원불교 복지몰 연동 준비
        $mkey = $_POST['mkey'];
        if(isset($mkey) ) {
            //복호화
            $mkey = decrypt_mcrypt_imembers($mkey); 
            $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
            $mkey_value = json_decode($mkey,true);
            //	 var_dump($mkey_value);

            $uuid = $mkey_value["uuid"];
            $md_id = 'wonmall_'.$uuid;
            $name =  $mkey_value["name"];
            $email = $mkey_value["email"];
            $cellphone = $mkey_value["cell_phone"];
            $mkey_encode = $_COOKIE['sso_login_token'];

            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'http://wonmall.co.kr/');
                return false;
            }

            set_session('ss_mb_id', 'wonmall_'.$uuid);
            set_session('ss_mb_name', $name);
            set_session('ss_mb_phone', $cellphone);
            set_session('ss_mb_email', $email);

        }
        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "wonmall";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
            //var_dump($_SESSION);
            if(isset($mkey)) {
                //성공 로그 정보를 쌓는다.
                //login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }else {
            //goto_url('https://www.wonmall.co.kr/bbs/login.php');
        }
    }

    //김캐디
    else if($pt_id == 'kimcaddie') { //2023-01-04 김캐디 연동 준비
        $mkey = $_POST['mkey'];
        if(isset($mkey) ) {
            //복호화
            $mkey = decrypt_mcrypt_kimcaddie($mkey); 
            $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
            $mkey_value = json_decode($mkey,true);
            //	 var_dump($mkey_value);

            $uuid = $mkey_value["uuid"];
            $md_id = 'kimcaddie_'.$uuid;
            $name =  $mkey_value["name"];
            $email = $mkey_value["email"];
            $cellphone = $mkey_value["cell_phone"];
            $mkey_encode = $_COOKIE['sso_login_token'];

            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey, $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",'https://kimcaddie.com');
                return false;
            }
            set_session('ss_mb_id', 'kimcaddie_'.$uuid);
            set_session('ss_mb_name', $name);
            set_session('ss_mb_phone', $cellphone);
            set_session('ss_mb_email', $email);
        }

        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "kimcaddie";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
            //var_dump($_SESSION);
            if(isset($mkey)) {
                //성공 로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }else {
            //goto_url('https://kimcaddie.com');
        }
    }    
    //골프몬
    else if($pt_id == 'golfmon') { //2023-01-05 골프몬 연동 준비
        $mkey = $_POST['mkey'];

        //golfmon logout logic test
        if($mkey == 'sessionLogout'){
            unset( $_SESSION['ss_mb_id'] );
            unset( $_SESSION['ss_mb_name'] );
            unset( $_SESSION['ss_mb_phone'] );
            unset( $_SESSION['ss_mb_email'] );
        }

        if(isset($mkey) ) {
            //복호화
            $mkey = decrypt_mcrypt_golfmon($mkey); 
            $mkey =  substr($mkey,0, (strpos($mkey,'}')+1) );
            $mkey_value = json_decode($mkey,true);
            //var_dump($mkey_value);

            $uuid = $mkey_value["uuid"];
            $md_id = 'golfmon_'.$uuid;
            $name =  $mkey_value["name"];
            $email = $mkey_value["email"];
            $cellphone = $mkey_value["cell_phone"];
            $mkey_encode = $_COOKIE['sso_login_token'];

            if(!$uuid)
            {
                //로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey, $uuid, $name, $email, $cellphone, $md_id,'N',$mkey_encode);
                if(TB_IS_MOBILE){
                    alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",$pt_link_data['mlogin_url']);
                }else{
                    alert("잘못된 접근입니다. 해당 문제가 계속 발생시 관리자에게 문의해 주세요.",$pt_link_data['login_url']);
                }
                
                
                return false;
            }else{
                set_session('ss_mb_id', 'golfmon_'.$uuid);
                set_session('ss_mb_name', $name);
                set_session('ss_mb_phone', $cellphone);
                set_session('ss_mb_email', $email);
            }
        }

        if(get_session('ss_mb_id')) {
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "golfmon";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
            //var_dump($_SESSION);
            if(isset($mkey)) {
                //성공 로그 정보를 쌓는다.
                login_sso_log($pt_id, $mkey , $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }else {
            //goto_url('https://m.golfmon.net/#!/login/');
        }
    } 
    
    //티샷
    else if($pt_id == 'teeshot') { //2023-08-03 티샷 기본세팅 (실연동 시 암호화 함수 및 리다이렉트 URL 변경 필요)
        $uuid = $_POST['uuid'];
        $md_id = 'teeshot_'.$uuid;
        $name = $_POST['name'];
        $email = $_POST['email'];
        $cellphone = $_POST['cellphone'];
        $mkey_encode = $_COOKIE['sso_login_token'];

        if($uuid){
            login_sso_log($pt_id, null, $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            set_session('ss_mb_id', 'teeshot_'.$uuid);
            set_session('ss_mb_name', $name);
            set_session('ss_mb_phone', $cellphone);
            set_session('ss_mb_email', $email);
        }

        if(get_session('ss_mb_id')){
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = "teeshot";//가맹점정보
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
            //var_dump($_SESSION);
            if(isset($uuid)) {
                //성공 로그 정보를 쌓는다.
                login_sso_log($pt_id, null, $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
            }
        }else {
            //goto_url('https://m.golfmon.net/#!/login/');
        }
    }         

    else if($pt_id == "mjg"){
        if( !empty($_GET['userid']) ){
            $sitecode = $_GET['sitecode'];
            $sitename = $_GET['sitename'];
            $userid = $_GET['userid'];
            $username =  urldecode(iconv('euc-kr','utf-8',$_GET['username']));
            $benefit_id = $_GET['benefit_id'];
            $tknKey = $_GET['tknKey'];
            $uuid = $userid;

            if(!empty($benefit_id)){
                //login_sso_log($pt_id, null, $uuid, $name, $email, $cellphone, $md_id,'Y',$mkey_encode);
                //set_session('ss_mb_id', 'mjg_'.$uuid);
                set_session('ss_mb_id', 'mjg_'.$benefit_id);
                set_session('ss_mb_name', $username);
                set_session('ss_mb_phone', '');
                set_session('ss_mb_email', '');
                set_session('site_name',$sitename);//2월22일 peter 추가 -> 베네피아 특정 사이트에서 로그인하면 사이트네임을 세션화한다.
            }
        }
        if(get_session('ss_mb_id')){
            $is_member = 1;
            $member['id'] = get_session('ss_mb_id');
            $member['name'] = get_session('ss_mb_name');
            $member['grade'] = 9;
            $member['pt_id'] = $pt_id;
            $member['cellphone'] = get_session('ss_mb_phone');
            $member['email'] = get_session('ss_mb_email');
        }
    }
    //echo "isset:".isset($_SESSION['ss_mb_id']);

?>
