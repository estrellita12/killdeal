<?php 
    include_once("../common.php");
    include_once('./cokgolf_head.skin.php');
?>

<body>
	<div id="wrap" class="">
		<div class="terms_detail_area">
		</div>
		<div class="footer_btn_area">
			<a href="javascript:void(0);" class="btn btn_bg_green">동의</a>
		</div>
	</div>
</body>
<input id='term1' type='hidden' value = <?php echo nl2br($config['shop_provision']); ?>>
<input id='term2' type='hidden' value = <?php echo nl2br($config['shop_private']); ?>>
<input id='term3' type='hidden' value = <?php echo nl2br(
    `
    개인정보 제3자 제공동의

1. 제공받는 자 : 농업협동조합중앙회

2. 제공받는 자의 이용목적 

○ 콕뱅크 내 골프용품 쇼핑몰서비스 제공

3. 제공할 개인정보의 항목 : 개인식별정보 (고객아이디)

4. 제공받는 자의 개인정보 보유

•이용기간
원칙적으로 개인정보 제공에 관한 동의일로부터 제공 목적이 달성될 때까지 보유•이용됩니다. 다만, 동의를 철회하거나 위 기간이 경과한 후에는 위에 기재된 이용 목적과 관련된 분쟁해결, 민원처리, 법령상 의무이행만을 위하여 동의를 철회한 날 또는 위 기간이 만료된 날부터 최장 3년의 범위 내에서만 「개인정보 보호법」 제21조제3항에 따라 다른 개인정보 파일과 분리하여 보유•이용됩니다.

5. 동의를 거부할 권리 및 그에 따른 불이익의 내용
위 개인정보의 제공에 대한 동의를 거부할 권리가 있습니다. 다만, 위 개인정보의 제공에 관한 동의는 서비스 이용을 위한 필수적인 사항이므로 동의하지 않으시는 경우 서비스 이용이 불가합니다.
`
); ?>>
</html>

<script>
	$(document).ready(function(){
        const url = new URL(location.href);
        const urlParams = url.searchParams;
        const no = urlParams.get('no');
        const all_chk = urlParams.get('all');

		const content = document.getElementById(`term${no}`).value;
        alert(content);
		document.getElementsByClassName('terms_detail_area')[0].innerHTML = content;
		document.getElementsByClassName('btn_bg_green')[0].onclick = () => {  
        
			opener.document.getElementById(`chk${no}`).checked=true;
            opener.document.getElementsByClassName(`inp_chk_total`)[0].checked = all_chk=='true' ? true:false;
            window.close();
            
		}
	});
</script>
