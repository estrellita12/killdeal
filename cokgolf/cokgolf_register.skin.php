<?php
    $session_chk = $c_uuid;
    if(empty($session_chk)){
        echo "<script>
                function WinClose(){
                    top.window.open('about:blank','_self').close();
                    top.window.opener=self;
                    top.self.close();
                }
                WinClose();
                window.close();
            </script>";
    }
?>

<body>
	<div id="list">
		<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" method="post" autocomplete="off">
		<input id="c_uuid" type="hidden" name="c_uuid" value="<?php echo $c_uuid ?>">
        <input id="c_url" type="hidden" name="c_url" value="<?php echo $c_url; ?>">
			<div id="wrap" class="">
				<h2 class="terms_tit">약관 동의</h2>
				<div class="terms_area">
					<ul>
						<li>
							<label><input type="checkbox" class="inp_chk inp_chk_total" />전체약관동의</label>
							<ul>
								<li>
									<a href="javascript:void(0)">
										<label><input id="chk1" type="checkbox" class="inp_chk" />회원가입 약관(필수)<span id="term1"></span></label>
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<label><input id="chk2" type="checkbox" class="inp_chk" />개인정보처리방침(필수)<span id="term2"></span></label>
									</a>
								</li>
								<li>
									<a href="javascript:void(0)">
										<label><input id="chk3" type="checkbox" class="inp_chk" />개인정보 제3자 제공 동의(필수)<span id="term3"></span></label>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="footer_btn_area ">
					<a id="close_embed" href="javascript:void(0);" class="btn btn_bg_grey close">취소</a>
					<a id="submit" class="btn btn_bg_green">다음</a>
				</div>
			</div>
		</form>
	</div>

	<div id="desc1" class="desc">
		<div id="wrap" class="">
			<div class="terms_detail_area">
				<?php echo nl2br($config['shop_provision']); ?>
			</div>
			<div class="footer_btn_area">
				<a href="javascript:void(0);" id="agree1" class="btn btn_bg_green">동의</a>
			</div>
		</div>
	</div>	
	<div id="desc2" class="desc">
		<div id="wrap" class="">
			<div class="terms_detail_area">
				<?php echo nl2br($config['shop_private']); ?>
			</div>
			<div class="footer_btn_area">
				<a href="javascript:void(0);" id="agree2" class="btn btn_bg_green">동의</a>
			</div>
		</div>
	</div>	
	<div id="desc3" class="desc">
		<div id="wrap" class="">
			<div class="terms_detail_area">
개인정보 제3자 제공동의
<br>
<br>
1. 제공받는 자 : 농업협동조합중앙회
<br>
<br>
2. 제공받는 자의 이용목적 
<br>
&nbsp;&nbsp;○ 콕뱅크 내 골프용품 쇼핑몰서비스 제공
<br>
<br>
3. 제공할 개인정보의 항목 : 개인식별정보 (고객아이디)
<br>
<br>
4. 제공받는 자의 개인정보 보유
<br>
•이용기간
원칙적으로 개인정보 제공에 관한 동의일로부터 제공 목적이 달성될 때까지 보유•이용됩니다. 다만, 동의를 철회하거나 위 기간이 경과한 후에는 위에 기재된 이용 목적과 관련된 분쟁해결, 민원처리, 법령상 의무이행만을 위하여 동의를 철회한 날 또는 위 기간이 만료된 날부터 최장 3년의 범위 내에서만 「개인정보 보호법」 제21조제3항에 따라 다른 개인정보 파일과 분리하여 보유•이용됩니다.
<br>
<br>
5. 동의를 거부할 권리 및 그에 따른 불이익의 내용
위 개인정보의 제공에 대한 동의를 거부할 권리가 있습니다. 다만, 위 개인정보의 제공에 관한 동의는 서비스 이용을 위한 필수적인 사항이므로 동의하지 않으시는 경우 서비스 이용이 불가합니다.
<br>
<br>

			</div>
			<div class="footer_btn_area">
				<a href="javascript:void(0);" id="agree3" class="btn btn_bg_green">동의</a>
			</div>
		</div>
	</div>			

    <!--TEST ///////////////////////////////////////////////////////////////////////////////////////////////-->
    <div style="display:none">
    <br>
    <br>
    TEST FORM
    <br>
    <br>
    <form name="test" action="https://cokfarm.nonghyup.com:8990/parksajang/api/reciveId.do" method="POST" entype="application/x-www-form-urlencoded">
        UUID: <input type="text" name="uuid">
        <br>
        <br>
        DATA: <input type="text" name="data">
        <br>
        <br>
        <button type="submit">SUBMIT</button>
        
    </form>
    </div>
    <!--TEST ///////////////////////////////////////////////////////////////////////////////////////////////-->



</body>

</html>

<script>
	$(document).ready(function(){	
		//init
		const title = $('.title').html();
		const all_chk = document.querySelectorAll('input[class="inp_chk"]');

		//function
        // IS NOT WINDOW
        /*
		function agreement_chk(){
            return $("input:checkbox[class=inp_chk]:checked").length >= 3;
		};
        */

        // IS WINDOW
		function agreement_chk(){
            let chk_arr = new Array();
            $("input:checkbox").each(function(){
                chk_arr.push(this.checked);
            });
            return chk_arr;
		};

		//execute
		$('.inp_chk_total').on('click', () => {
			all_chk.forEach((checkbox) => {
				checkbox.checked = $('.inp_chk_total').prop('checked');
			});
		});

        // IS NOT WINDOW
        /*
		$('.inp_chk').on('click', () => {
            $('.inp_chk_total').prop("checked", agreement_chk());
		});
        */
        // IS WINDOW
        $('.inp_chk').on('click', () => {
            let all_chk = agreement_chk();
            $('.inp_chk_total').prop("checked", all_chk[1]&&all_chk[2]&&all_chk[3]);
		});
        
        
        // IS WINDOW
        $('#term1').on('click', (e) => {
            e.preventDefault()
            const now_chk = agreement_chk();
            const all_chk = now_chk[2] && now_chk[3];
            window.open(`./cokgolf_term.php?no=1&all=${all_chk}`);
        });
        $('#term2').on('click', (e) => {
            e.preventDefault()
            const now_chk = agreement_chk();
            const all_chk = now_chk[1] && now_chk[3];
            window.open(`./cokgolf_term.php?no=2&all=${all_chk}`);
        });
        $('#term3').on('click', (e) => {
            e.preventDefault()
            const now_chk = agreement_chk();
            const all_chk = now_chk[1] && now_chk[2];
            window.open(`./cokgolf_term.php?no=3&all=${all_chk}`);
        });
        
        /*
         //IS NOT WINDOW
		$('#term1').on('click', () => {
			$('#desc1').css('display','block');
			$('#list').css('display','none');
		});
		$('#agree1').on('click', () => {
			$('#list').css('display','block');
            $('.desc').css('display','none');
            $('#chk1').prop("checked", true);
            $('.inp_chk_total').prop("checked", agreement_chk());
		});

		$('#term2').on('click', () => {
			$('#desc2').css('display','block');
			$('#list').css('display','none');
		})
        $('#agree2').on('click', () => {
			$('#list').css('display','block');
            $('.desc').css('display','none');
            $('#chk2').prop("checked", true);
            $('.inp_chk_total').prop("checked", agreement_chk());
		});
		$('#term3').on('click', () => {
			$('#desc3').css('display','block');
			$('#list').css('display','none');
		})
        $('#agree3').on('click', () => {
			$('#list').css('display','block');
            $('.desc').css('display','none');
            $('#chk3').prop("checked", true);
            $('.inp_chk_total').prop("checked", agreement_chk());
		});				
        */

        // IS NOT WINDOW
        /*
		$('#submit').on('click', () => {
			if(!agreement_chk()){
				alert('필수 약관에 동의 해 주세요.');
				return false;
			}
			$("#fregisterform").submit();
		});
        */
       //IS WINDOW
       $('#submit').on('click', () => {
			if(!agreement_chk()[0]){
				alert('필수 약관에 동의 해 주세요.');
				return false;
			}
			$("#fregisterform").submit();
		});       
        
		$('.close').on('click', () => {
			console.log('cancel');
			//- 아이폰
			//alert 메시지를 이용하여 호출
			//alert("nhcokbank:///#{\"method\":\"closeEmbedWebview\",\"callback\":\"\"}");

			//- 안드로이드
			/*
			window.ActionCode.runFunc("nhcokbank:///#" + jsonString);
			jsonString
			{
			"method":"closeEmbedWebview",
			"callback":""
			}
			*/			
		})

	})

</script>
