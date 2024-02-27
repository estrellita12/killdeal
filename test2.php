
<!-- solution default //-->


<!--
	<script type="text/javascript" src="https://birdiechance.com/jscript/common.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/embed.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/misc.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/Ajax.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/JSON.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/Rollover.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/cookie.js"></script>
-->

	<!-- jquery //-->

<!--
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery-ui-1.10.3.min.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery.browser.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery.adjustImage.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery.laybox.js"></script>

	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery.rollover.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery/ui.js"></script>
	<script type="text/javascript" src="https://birdiechance.com/jscript/jquery.slimscroll.min.js"></script>
-->


<!-- 우하단 플로팅 이벤트 -->
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<style type="text/css">
.popupbanner1 .smallbanner, .popupbanner1 .popbanner {
	position: fixed;
    bottom: 20px;
    right: 10px;
    padding: 7px 12px;
    z-index: 2500;
}

.popupbanner1 .closingarea {
	top: -5px;
    right: 53px;
    z-index: 1050;
}

.popupbanner .closingarea {
	position: absolute;
	width: 165px;
	font-size: 14px;
}
.popupbanner .cookiecheck {
	position: relative;
	float: left;
	margin: 0px;
	padding: 0px;
}
.popupbanner .closebtn {
	cursor: pointer;
    float: right;
    width: 41px;
    height: 39px;
    margin: 0px 0 0 10px;
    position: absolute;
    top: -5px;
    right: -18px;
    font-size: 21px;
}
.clickimg {
  -webkit-filter: drop-shadow(5px 5px 5px #ccc);
  filter: drop-shadow(5px 5px 5px #ccc);
}
</style>
<!-- 우하단 플로팅 이벤트 -->
<div class="popupbanner popupbanner1" id="popupbanner1">
	<div class="popbanner" style="display: block;">
		<div class="closingarea" style="/*background-color:#fff;*/padding:2px 5px;">
			<input type="checkbox" class="cookiecheck" name="popbancookiecheck1" id="cookiecheck1" style="width: 20px;height: 20px;">
			<label for="cookiecheck1"><span class="nottoday" style="font-size: 14px; margin-left: 5px; line-height: 20px;"><span class="nottodaytxt">하루동안 열지않기</span></span></label>
			<span class="closebtn" style="margin-top: 8px;"><i class="far fa-times-circle"></i></span>
		</div>
		<a href="#"><img src="https://birdiechance.com/images/common/pop_9773.png" class="clickimg" alt="9773" width="500" height="152"></a>
	</div>
</div>
<script>
    $('.closeSmall').click(function(){
		$('.smallbanner').animate({right:"-200px",opacity:"0"},1500);
		$('#popupbanner1').fadeOut(2000);
		$('.smallbanner').addClass('rot');
    });
    </script>
<style>
.rot {
	transform: rotate(360deg);
	transition: transform 2s ease;
}
</style>

<script src="https://birdiechance.com/jscript/lazyload/lazyload.js?v=190510" type="text/javascript"></script>

<script type="text/javascript">
!function(e){e.fn.popbanner=function(o){function n(){return 1==r?!1:"closed"==s(i.cookiecheck)?!1:(e(a+" "+i.popbanner).show(i.openspeed),r=1,void("yes"==i.autoclosing&&(clearTimeout(u),u=setTimeout(t,i.autoclosingterm))))}function t(){return 0==r?!1:(e(a+" "+i.popbanner).hide(i.closespeed),r=0,"yes"==i.autopop&&(clearTimeout(u),u=setTimeout(n,i.autopopterm)),void(e("input:checkbox[name='"+i.cookiecheck+"']").is(":checked")&&c(i.cookiecheck,"closed",1)))}function c(e,o,n){var t=new Date;t.setDate(t.getDate()+n),cookies=e+"="+escape(o)+"; path=/ ","undefined"!=typeof n&&(cookies+=";expires="+t.toGMTString()+";"),document.cookie=cookies}function s(e){e+="=";var o=document.cookie,n=o.indexOf(e),t="";if(-1!=n){n+=e.length;var c=o.indexOf(";",n);-1==c&&(c=o.length),t=o.substring(n,c)}return unescape(t)}var i=e.extend({},e.fn.popbanner.defaults,o),r=0,a=this.selector,u=setTimeout(n,i.firstterm);e(a+" "+i.smallbanner).click(function(e){"click"==i.clickormouseenter&&(e.preventDefault(),n())}),e(a+" "+i.smallbanner).mouseenter(function(e){"mouseenter"==i.clickormouseenter&&(e.preventDefault(),n())}),e(a+" "+i.closebtn).click(function(e){e.preventDefault(),t()})},e.fn.popbanner.defaults={firstterm:3e3,autoclosing:"yes",autoclosingterm:7e3,autopop:"yes",autopopterm:15e3,openspeed:"slow",closespeed:"fast",clickormouseenter:"mouseenter",cookiecheck:"popbancookiecheck",smallbanner:".smallbanner ",popbanner:".popbanner",closebtn:".closebtn"}}(jQuery);
$(document).ready(function(){
	$("img.lazy").lazyload();

	//var PBL = {"images" :["9779","25805","30683","37416","38515","38747","39699"]};
    var PBL = {
        "images" :["01","02","03","04","05","06","07","08","09","10"],
        "links" : [
            "/search?q=%EB%AC%B4%EA%B2%8C%EC%B6%94",
            "/brand/128/%EC%8A%A4%EC%B9%B4%ED%8B%B0%EC%B9%B4%EB%A9%94%EB%A1%A0",
            "/brand/122/%ED%88%AC%EC%96%B4%EC%97%90%EC%9D%B4%EB%94%94",
            "/goods/searchfilter.asp?scate=2&sHighPrice=100000",
            "/brand/223/%EC%A7%80%ED%8F%AC%EC%96%B4",
            "/brand/115/%EC%A0%9C%EC%9D%B4%EB%A6%B0%EB%93%9C%EB%B2%84%EA%B7%B8",
            "/brand/95/%ED%83%80%EC%9D%B4%ED%8B%80%EB%A6%AC%EC%8A%A4%ED%8A%B8?scate=14",
            "/brand/93/%ED%94%BC%EC%97%91%EC%8A%A4%EC%A7%80",
            "/goods/searchfilter.asp?scate=157&sbrand=122",
            "/goods/searchfilter.asp?scate=400"
        ]
    };

	$(document).on("popImageChange", function(){

		if ( $(".popbanner").css("display") == "none"){
			var rndIdx = Math.floor(Math.random() * PBL.images.length);
			//$(".clickimg").attr("src", "/images/common/pop_"+ PBL.images[rndIdx] +".png").attr("alt",PBL.images[rndIdx]);
            $(".clickimg").attr("src", "/images/floating/footer_"+ PBL.images[rndIdx] +".png");
            $(".clickimg").closest("a").attr("href", PBL.links[rndIdx]);
		}
	});

	$(".smallbanner>img ").on("click", function(){
//		if(getCookie("popbancookiecheck1") == "closed") location.href=$(this).attr("data-link");
		if(getCookie("popbancookiecheck1") == "closed") location.href= '/member/join_simple.asp'
        //console.log($(this).attr("data-link"));
	});
/*
	$(".clickimg").click(function(e){
		e.preventDefault();
		location.href="/goods/detail.asp?gno="+$(this).attr("alt");
	});
*/
	$('#popupbanner1').popbanner({
		'firstterm' : 5000,
		'autoclosing' : "yes",		// no: 자동으로닫히지않음. yes:자동닫힘.
		'autoclosingterm' :5000,	// 열린상태 지속시간
		'autopop' : "yes",			// no: 자동으로 팝열리지 않음. yes:자동열림.
		'autopopterm' : 3000,		// 닫힌 후, 다시 열리기까지 시간
		'openspeed' : "normal",		// 팝열리는 빠르기
		'closespeed' : "normal",		// 팝닫히는 빠르기
		'clickormouseenter' : "click",		// click: 클릭시 팝. mouseenter:마우스 오버시 팝.
		'cookiecheck' : 'popbancookiecheck1',
		'smallbanner' : '.smallbanner ',
		'popbanner' : '.popbanner',
		'closebtn' : '.closebtn'
	});
	if(getCookie("popbancookiecheck1") != "closed") setInterval(function(){ $(document).trigger('popImageChange'); }, 2000);
});
</script>

	<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script>
	<script type="text/javascript">
	if(!wcs_add) var wcs_add = {};
	wcs_add["wa"] = "13460d1d469d44";
	//wcs_do();
	
	//B.유입추적함수
	if (!_nasa) var _nasa={};
	wcs.inflow();
	// C. 로그 수집 함수 호출
	wcs_do(_nasa);
	</script>
	
<!--
1. 상품상세페이지
2. 장바구니 저장 (onclick 이벤트)
3. 장바구니 삭제 (onclick 이벤트)
3. 구매시작
4. 구매완료
5. 키워드검색결과목록
6. 상품목록 보기
7. 회원가입 : member/join_simple.asp, /mw/member/join_simple.asp
8. 로그인 : member/login.asp / mw/memmber/login.asp
9. 환불
10. 위시리스트 저장

99. 상기 구분외 전체 페이지
-->
<script>


    //gtag('event','page_view');

</script>

