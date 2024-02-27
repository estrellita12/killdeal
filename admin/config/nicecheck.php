<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fnamecheck" id="fnamecheck" action="./config/nicecheck_update.php" method="post">
<input type="hidden" name="token" value="">

<h2>본인인증 / I-PIN 사용여부</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">본인인증</th>
		<td>
			<label><input type="radio" name="cf_cert_use" value="0"<?php echo get_checked($config['cf_cert_use'], "0"); ?>> 사용안함</label>
			<label><input type="radio" name="cf_cert_use" value="1"<?php echo get_checked($config['cf_cert_use'], "1"); ?>> 테스트</label>
			<label><input type="radio" name="cf_cert_use" value="2"<?php echo get_checked($config['cf_cert_use'], "2"); ?>> 실서비스</label>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="cf_cert_ipin">아이핀 본인인증</label></th>
		<td>
			<select name="cf_cert_ipin" id="cf_cert_ipin">
				<?php echo option_selected("",    $config['cf_cert_ipin'], "사용안함"); ?>
				<?php echo option_selected("kcb", $config['cf_cert_ipin'], "코리아크레딧뷰로(KCB) 아이핀"); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="cf_cert_hp">휴대폰 본인인증</label></th>
		<td>
			<select name="cf_cert_hp" id="cf_cert_hp">
				<?php echo option_selected("",    $config['cf_cert_hp'], "사용안함"); ?>
				<?php echo option_selected("kcb", $config['cf_cert_hp'], "코리아크레딧뷰로(KCB) 휴대폰 본인확인"); ?>
				<?php echo option_selected("kcp", $config['cf_cert_hp'], "NHN KCP 휴대폰 본인확인"); ?>
				<?php echo option_selected("lg",  $config['cf_cert_hp'], "LG유플러스 휴대폰 본인확인"); ?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>본인인증 / I-PIN 가입정보 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="cf_cert_kcb_cd">코리아크레딧뷰로<br>KCB 회원사ID</label></th>
		<td>
			<input type="text" name="cf_cert_kcb_cd" value="<?php echo $config['cf_cert_kcb_cd']; ?>" id="cf_cert_kcb_cd" class="frm_input" size="20">
			<a href="http://ok-name.co.kr/acs/non/bizindex.jsp" target="_blank" class="btn_small blue">KCB 서비스 신청</a>
			<?php echo help('KCB 회원사ID를 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, KCB와 계약체결 후 회원사ID를 발급 받으실 수 있습니다.<br>이용하시려는 서비스에 대한 계약을 아이핀, 휴대폰 본인확인 각각 체결해주셔야 합니다.<br>아이핀 본인확인 테스트의 경우에는 KCB 회원사ID가 필요 없으나,<br>휴대폰 본인확인 테스트의 경우 KCB 에서 따로 발급 받으셔야 합니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="cf_cert_kcp_cd">NHN KCP 사이트코드</label></th>
		<td>
			<input type="text" name="cf_cert_kcp_cd" value="<?php echo $config['cf_cert_kcp_cd']; ?>" id="cf_cert_kcp_cd" class="frm_input" size="20">
			<a href="https://www.kcp.co.kr/service.payment.certRequest.do?cmd=certApply" target="_blank" class="btn_small blue">NHN KCP 휴대폰 본인인증 서비스 신청</a>
			<?php echo help('NHN KCP 사이트코드를 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인인증 서비스 신청페이지에서 서비스 신청 후 사이트코드를 발급 받으실 수 있습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="cf_lg_mid">LG유플러스 상점아이디</label></th>
		<td>
			<input type="text" name="cf_lg_mid" value="<?php echo $config['cf_lg_mid']; ?>" id="cf_lg_mid" class="frm_input" size="20">
			<a href="http://ecredit.uplus.co.kr/" target="_blank" class="btn_small blue">LG유플러스 본인인증 서비스 신청</a>
			<?php echo help('LG유플러스 상점아이디를 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인인증 서비스 신청페이지에서 서비스 신청 후 상점아이디를 발급 받으실 수 있습니다.<br><em class="fc_red">LG유플러스 휴대폰본인인증은 ActiveX 설치가 필요하므로 Internet Explorer 에서만 사용할 수 있습니다.</em>'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="cf_lg_mert_key">LG유플러스 MERT KEY</label></th>
		<td>
			<input type="text" name="cf_lg_mert_key" value="<?php echo $config['cf_lg_mert_key']; ?>" id="cf_lg_mert_key" class="frm_input" size="40">
			<em>예) 95160cce09854ef44d2edb2bfb05f9f3</em>
			<?php echo help('LG유플러스 상점MertKey는 <strong>[계약정보 &gt; 상점정보관리 &gt; 시스템연동정보]</strong>에서 확인하실 수 있습니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>당일 이용제한 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="cf_cert_limit">본인인증 이용제한</label></th>
		<td>
			<input type="text" name="cf_cert_limit" value="<?php echo $config['cf_cert_limit']; ?>" id="cf_cert_limit" class="frm_input" size="5"> 회
			<?php echo help('하루동안 아이핀과 휴대폰 본인인증 인증 이용회수를 제한할 수 있습니다.<br>회수제한은 실서비스에서 아이핀과 휴대폰 본인인증 인증에 개별 적용됩니다.<br><em class="fc_084">0 으로 설정하시면 회수제한이 적용되지 않습니다.</em>'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">본인인증 필수</th>
		<td>
			<label><input type="checkbox" name="cf_cert_req" value="1"<?php echo get_checked($config['cf_cert_req'], 1); ?>> 예</label>
			<?php echo help('회원가입시 본인인증을 필수로 할지 설정합니다.<br>필수로 설정하시면 본인인증을 하지 않은 경우 회원가입이 안됩니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ본인인증을 사용하실 경우 회원가입시 휴대폰인증 및 아이핀인증이 작동 됩니다.</p>
			<p>ㆍ실명과 휴대폰번호 그리고 본인인증 당시에 성인인지의 여부를 저장합니다.</p>
			<p>ㆍ게시판의 경우 본인확인 또는 성인여부를 따져 게시물 조회 및 쓰기 권한을 줄 수 있습니다.</p>
			<p>ㆍ본인인증은 <strong>[코리아크레딧뷰로, KCP, LG유플러스]</strong>중에서 서비스업체 계약담당자와 반드시 계약체결 이후 사용하셔야 합니다.</p>
		</div>
		<div class="hd">ㆍ[관련법규]</div>
		<div class="desc01 marl10">
			<p class="fc_red">※ 정보통신망 이용 촉진 및 정보보호 등에 관한 법률 제 23조의 2 (주민등록번호의 사용제한)</p>
			<p>① 정보통신서비스 제공자는 다음 각 호에 해당하는 경우를 제외하고는 이용자의 주민등록번호를 수집.이용할 수 없다.</p>
			<p class="marl10">1. 제 23조의 3에 따라 본인확인기관으로 지정받은 경우</p>
			<p class="marl10">2. 법령에서 이용자의 주민등록번호 수집, 이용을 허용하는 경우</p>
			<p class="marl10">3. 영업상 목적을 위하여 이용자의 주민등록번호 수집.이용이 불가피한 정보통신 서비스 제공자로서 방송통신위원회가 고시하는 경우</p>
			<p>② 제1항 제2호 및 제3호에 따라 주민등록번호를 수집.</p>
			<p class="marl10">이용할 수 있는 경우에도 이용자의 주민등록을 사용하지 아니하고 본인을 확인하는 방법(이하 "대체수단"이라 한다)을 제공하여야 한다.</p>
			<p class="mart15">※ (제76조 과태료) 제23조의2를 위반하여 필요한 조치를 하지 아니한 자는 3천만 원 이하의 과태료를 부과</p>
			<p>※ (부칙 제2조 주민등록번호 수집, 이용 제한에 관한 경과조치) 기존 보유 주민번호는 2014년 8월 17일 까지 파기</p>
		</div>
	 </div>
</div>
</form>

<?php
// 본인확인 모듈 실행권한 체크
if($config['cf_cert_use']) {
    // kcb일 때
    if($config['cf_cert_ipin'] == 'kcb' || $config['cf_cert_hp'] == 'kcb') {
        // 실행모듈
        if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe = TB_OKNAME_PATH.'/bin/okname';
            else
                $exe = TB_OKNAME_PATH.'/bin/okname_x64';
        } else {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe = TB_OKNAME_PATH.'/bin/okname.exe';
            else
                $exe = TB_OKNAME_PATH.'/bin/oknamex64.exe';
        }

        echo module_exec_check($exe, 'okname');
    }

    // kcp일 때
    if($config['cf_cert_hp'] == 'kcp') {
        if(PHP_INT_MAX == 2147483647) // 32-bit
            $exe = TB_KCPCERT_PATH . '/bin/ct_cli';
        else
            $exe = TB_KCPCERT_PATH . '/bin/ct_cli_x64';

        echo module_exec_check($exe, 'ct_cli');
    }

    // LG의 경우 log 디렉토리 체크
    if($config['cf_cert_hp'] == 'lg') {
        $log_path = TB_LGXPAY_PATH.'/lgdacom/log';

        if(!is_dir($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(TB_PATH.'/', '', TB_LGXPAY_PATH).'/lgdacom 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        } else {
            if(!is_writable($log_path)) {
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(TB_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            }
        }
    }
}
?>