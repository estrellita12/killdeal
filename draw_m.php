<html>
<head>
  <script src="<?php echo TB_JS_URL; ?>/jquery-1.8.3.min.js"></script>
  <script src="<?php echo TB_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
  <script src="<?php echo TB_JS_URL; ?>/common.js?ver=<?php echo TB_JS_VER;?>"></script>
  <style>
    ::placeholder{color:#9d9d9d;text-align:center;font-size:15px;line-height:1.5;}
    .event_title{font-size:24px;margin:0;}
    #event_wrap{position:relative;left:0;top:0;width:100%;margin:0 auto;}
    #input_form_wrap{position:absolute;left:50%;bottom:9%;width:70%;background:#fff;transform:translateX(-50%);padding:15px;border-radius:15px;text-align:center;}
    #input_form_wrap input{width:100%;height:30px;font-size:16px;font-weight:600;margin-top:15px;text-align:center;}
    #input_form_wrap #agree_box{height:80px;width:100%;margin-top:20px;}
    #input_form_wrap input#agree_chk{height:15px;width:20px;vertical-align:sub;}
    #input_form_wrap input:focus{outline:none;}
/*    #event_btn_wrap{margin-top:20px;}*/
    #agree_wrap label{font-size:13px;}
    .agree_notice{font-size:12px;color:#999;margin:5px 0 0;letter-spacing:-0.075em;}
    #eventBtn,#inzngBtn,#woongmoBtn{width:100%;height:40px;box-shadow:3px 3px 0 black;background:#00f200;color:#000;border:none;font-weight:800;transition:all 0.3s;font-size:15px;}
    #eventBtn:focus,#woongmoBtn:focus,#inzngBtn:focus{outline:none;}
  </style>
</head>
<body>
  <div id="event_wrap">
    <div id="event_bg">
      <img src="/img/20200807_event_m.jpg" alt="이벤트이미지" usemap="#event_map">
	  <map name="event_map"><area shape="rect" coords="393,4408,785,4506" href="https://www.youtube.com/channel/UC1vCF_oF13DLh7l5TAQGHMQ" alt="랜선골프유튜브" target="_blank"></map>
    </div>
    <div id="input_form_wrap">
      <h2 class="event_title" style="font-size:28px;">정보 입력</h2>
      <p style="font-size:15px;color:red;font-weight:600;letter-spacing:-0.075em;margin:15px 0 0;">이벤트 참여 및 당첨 결과 안내를 위해 <br>반드시 입력해주세요!</p>
        <input type="text" name="name" id="e_name" placeholder=" 이름을 입력해주세요." autocomplete="off">
        <input type="text" name="phone" id="e_phone" maxlength="11" placeholder=" ' - ' 을 제외한 휴대폰번호를 입력해주세요." autocomplete="off">
        <textarea name="agree_box" id="agree_box" cols="20" readonly>
이벤트 기간(08/05 ~ 08/18)내 본 몰 가입고객으로 추첨하여 진행됩니다.
경품은 추첨을 통해 당첨되며 당첨자 발표는 08/21(금) 몰 내 게재됩니다.
경품은 예시와 실물이 다를 수 있으며, 현금 교환 거래 또는 타상품으로 교환, 타인에게 양도, 매매 또는 기타 거래가 불가합니다.
안심번호 설정 시 당첨 안내 및 경품 발송에 어려움이 있을 수있으니 안심번호 해제 후 참여 바랍니다.
명의도용 및 부적절한 방법으로 이벤트 참여 시 당첨이 취소될 수 있습니다.
이벤트 참여 시 필요한 개인 정보 이용 동의를 거부하실 수 있으며, 거부 시 이벤트 참여가 제한됩니다.

* 이용 항목 : 이름,연락처[보유/이용기간:이벤트 종료 시 까지]

 경품발송 관련 상세 내용은 당첨자에 한하여 쇼핑몰에 등록된 번호로 문자로 안내되며, 안내된 링크에 따라 정확한 경품수령정보(이름,연락처)를 입력 부탁드립니다.
(잘못된 정보기재로 인한 경품 배송 오류는 당사에서 책임지지 않습니다.)
당첨발표일 이후 1주동안 당첨 안내 및 제세공과금의 처리를 위해 쇼핑몰에 등록된(회원정보) 당첨자의 휴대폰으로 개별연락을 드릴 예정이며, 기간내 연락을 받지 않으시면 당첨에서 제외 될 수 있습니다.
경품 발송은 8월24일부터 약 2주간 진행될 예정이며, 한 번 발송된 경품은 재발송 되지 않습니다.(발송 후 주소/연락처/수령인 변경 불가)
본 이벤트 진행일정 및 내용은 당사의 사정에 의해 사전 고지 없이 변동 또는 중단 될 수 있으며, 경품 당첨자 발표 및 경품 배송 일정 또한 당사의 사정에 일정 조정이 될 수 있습니다.
본 이벤트는 당첨자에 한하여 경품 수령 및 세금 납부 목적을 위해 필요한 최소한의 개인정보(이름,연락처)를 위탁사(당사)에 제공합니다.
본 이벤트에 한해 제세 공과금 22%는 당사에서 부담합니다.
개인정보 보유/이용기간
(당첨자)이벤트 종료 후 연락처 입력 유효기간 종료 시 즉시 파기합니다.

* 위탁업무내용: 당첨자고지,경품배송서비스
* 위탁개인정보(수집항목): 성명,휴대폰 번호
* 위탁기간: 3개월
        </textarea>
        <div id="agree_wrap">
          <label>개인정보 수집 및 이용 동의 (필수)
            <input type="checkbox" id="agree_chk" name="agree_chk">
            <p class="agree_notice">※개인정보 수집 및 이용에 동의한 경우만 응모 가능합니다.</p>
          </label>
        </div>
        <input type="hidden" name="inzngNumber" id="inzngNumber">
      <label id="inzng_input" style="display:none">
        <input type="text" name="inzng" id="inzng" maxlength="4" placeholder="인증번호를 입력해주세요" autocomplete="off" style="opacity:0">
      </label><br>
<!--       <button type="button" id="eventBtn" onclick="return eventAjax()">인증번호받기</button> -->
      <div id="event_btn_wrap">
<!--         <button type="button" style="display:none;margin:0 auto;" id="inzngBtn" onclick="inzngGo()">인증하기</button> -->
        <button type="button" style="display:block;margin:0 auto;" id="woongmoBtn" onclick="woongmo()">응모하기</button>
      </div>
    </div>
  </div>
</body>
<script>
  var nameEl = document.getElementById("e_name");
  var phoneEl = document.getElementById("e_phone");
  var inzngEl = document.getElementById("inzngNumber");
  var inputInzng = document.getElementById("inzng");

  // ajax로 php의 sms인증번호 발송함수 실행 후 인증번호값 리턴
  function eventAjax() {

    if (document.getElementById("agree_chk").checked == 0) {
      alert("개인정보활용 및 제3자제공 동의를 하셔야 이벤트참여가 가능합니다.");
      return false;
    }

    var nameValue = nameEl.value;
    var phoneValue = phoneEl.value;

    var params = {
      "name": nameValue,
      "phone": phoneValue
    };

    $.ajax({
      type: "POST",
      url: "./../../draw_sms.php",
      data: params,
      dataType: "json",
      success: function (data) {
        if (!data) {
          alert("필수 입력란을 입력해주세요.") //이름,휴대폰번호 필수입력 체크
          return false;
        } else { // 이름,휴대폰 번호가 정상입력되어 인증번호 return값을 받으면 input hidden에 넣어줌
          inzngEl.value = data;
          $("#eventBtn").css("display","none");
          $("#inzng_input").css("display","block");
          $("#inzng").css("opacity","1");
          $("#inzngBtn").show(500)
        }
      }
    });
    // 인증번호 받기 클릭시 인증번호 입력폼,인증하기 버튼 노출 인증번호 받기버튼 비노출

  }

  //input hidden에 들어온 인증번호값과 고객이 입력한 값 비교
  function inzngGo() {
    if (inputInzng.value == inzngEl.value) {
      alert("인증성공");
//      $("#woongmoBtn").css("display", "block");
//      $("#inzngBtn").css("display", "none");
    } else {
      alert("유효하지 않은 인증번호 입니다.");
      inputInzng.value = "";
      inputInzng.focus();
    }
  }

  function woongmo() {

    var nameVal = nameEl.value;
    var phoneVal = phoneEl.value;
//    var inzngVal = inzngEl.value;

    param = {
      "name": nameVal,
      "phone": phoneVal,
//      "inzng": inzngVal,
      "agree": "Y"
    };


    $.ajax({
      type: "POST",
      url: "./../../draw_update.php",
      data: param,
      dataType: "text",
      success: function (data) {
        if (!data) {
          alert(data);
        } else {
          alert(data);
        }
      }
    });
  }

  //휴대폰번호 유효성검사
  $("#e_phone").on('keydown', function (e) {
    // 숫자만 입력받기
    var trans_num = $(this).val().replace(/-/gi, '');
    var k = e.keyCode;

    if (trans_num.length >= 11 && ((k >= 48 && k <= 126) || (k >= 12592 && k <= 12687 || k == 32 || k == 229 || (
        k >= 45032 && k <= 55203)))) {
      e.preventDefault();
    }
  }).on('blur', function () { // 포커스를 잃었을때 실행합니다.
    if ($(this).val() == '') return;

    // 기존 번호에서 - 를 삭제합니다.
    var trans_num = $(this).val().replace(/-/gi, '');

    // 입력값이 있을때만 실행합니다.
    if (trans_num != null && trans_num != '') {
      // 총 핸드폰 자리수는 11글자이거나, 10자여야 합니다.
      if (trans_num.length == 11 || trans_num.length == 10) {
        // 유효성 체크
        var regExp_ctn = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})([0-9]{3,4})([0-9]{4})$/;
        if (regExp_ctn.test(trans_num)) {
          // 유효성 체크에 성공하면 하이픈을 넣고 값을 바꿔줍니다.
          // trans_num = trans_num.replace(/^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?([0-9]{3,4})-?([0-9]{4})$/, "$1-$2-$3");                  
          // $(this).val(trans_num);
        } else {
          alert("유효하지 않은 전화번호 입니다.");
          $(this).val("");
          $(this).focus();
        }
      } else {
        alert("유효하지 않은 전화번호 입니다.");
        $(this).val("");
        $(this).focus();
      }
    }
  });
</script>

</html>
<!--  추첨이벤트페이지 테스트  -->