<?php
if(!defined('_TUBEWEB_')) exit;
?>


<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    #standing_point_form_wrap {
      text-align:center;
      padding: 10px;
    }

    #text_box {
      width: 100%;
      height: 200px;
    }

    #stp_tbl tr {
      height: 50px;
      border: 1px solid #ddd;
    }

    #stp_tbl th {
      font-size: 18px;
      padding: 0 15px;
      font-weight: 700;
      border-right: 1px solid #ddd;
    }

    #stp_tbl td {
      padding: 0 5px;
    }

    #stp_tbl td:last-child {
      padding: 15px 5px;
    }

    #stp_tbl input {
      width: 100%;
      border: 1px solid #999;
      height: 30px;
    }
    #stp_agree tr{height:50px;}
    #stp_agree td{border:1px solid #666;}
    #stp_agree th{padding:15px 0;background:#ddd;border:1px solid #666;}
    #stp_agree_box{width:15px;height:15px;margin-left:5px;vertical-align:baseline;}
    #stp_agree{margin-top:25px;}
    #stp_agree h3{font-size:20px;}
    .ch_box_wrap{margin-top:15px;}
    #stp_submit{margin-top:25px;width:150px;height:40px;background:#666;color:#fff;border:none;cursor:pointer;}
  </style>
</head>

<body>
  <div id="standing_point_form_wrap">
    <h2 style="font-size:28px;">입점 신청</h2>
    <form enctype="multipart/form-data" id="stp_form" name="stp_form"  method="post" onsubmit="return form_check(this);" >
      <table style="margin-top:20px;" id="stp_tbl">
        <caption>입점신청서 작성 폼</caption>
        <colgroup>
          <col class="w120">
          <col class="wfull">
        </colgroup>
        <tbody>
          <tr>
            <th>관리자 E-mail </th>
            <td class="tal">&nbsp;sweet@mwd.kr</td>
          </tr>
          <tr>
            <th>이름</th>
            <td><input type="text" name="user_name"></td>
          </tr>
          <tr>
            <th>보내는 E-mail</th>
            <td><input type="text" name="email"></td>
          </tr>
          <tr>
            <th>제목</th>
            <td><input type="text" name="title"></td>
          </tr>
          <tr>
            <th>내용</th>
            <td>
              <div class="std_txt_box"><textarea name="textbox" cols="40" id="text_box">
  ---- 입점/제휴 문의 ----
  - 회사명 :
  - 홈페이지 :
  - 주상품 품목 :
  - 담당자명 :
  - 연락처 :
  
  * 회사 소개서 및 상품 관련 자료 있을 경우 첨부 부탁드립니다. *
  
  -해당 메일로 인입되는 CS 관련 건은 처리가 불가합니다. 이점 양해 부탁드립니다.
              </textarea></div>
            </td>
          </tr>
          <tr>
            <th>파일첨부<p style="font-size:14px;color:#999;font-weight:400;margin-top:3px;">30MB 이상은 전송이 불가합니다.</p></th>
            <td>
              <div class="stp_file">
                <input type="file" id="user_file" name="user_file" style="border:none;">
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div id="stp_agree">
        <h3>개인정보 수집 및 이용</h3>
        <table style="width:100%; margin-top:15px;">
          <colgroup>
            <col class="w30p">
            <col class="w30p">
            <col class="w30p">
          </colgroup>
          <thead>
            <tr>
              <th>목적</th>
              <th>항목</th>
              <th>보유기간</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="tac">문의사항 답변</td>
              <td class="tac">이메일 및 기재한 내용</td>
              <td class="tac">답변완료 후 5일</td>
            </tr>
          </tbody>
        </table>
        <div class="ch_box_wrap">
          <span style="font-size:16px;">개인정보 수집 및 이용 동의</span>
          <label>
            <input type="checkbox" name="stp_agree_box" id="stp_agree_box" onclick="agree_chk()">
          </label>
          <p style="font-size:13px;color:#888">* 비동의시 서비스 이용이 제한됩니다.</p>
        </div>
      </div>
        <input type="submit" id="stp_submit" value='이메일보내기' style="display:none;margin:40px auto 0;">
    </form>
  </div>
</body>
<script>
  var stp_agr_bx = document.getElementById("stp_agree_box");
  var submitEl = document.getElementById("stp_submit");

  function agree_chk(){
	if(stp_agr_bx.checked != false){
		submitEl.style.display = "block";
	} else{
		submitEl.style.display = "none";
		}
  };

//형식 체크
function form_check(frm) {
	var f = frm;
	var user_name = f.user_name;
	var email = f.email;
	var title = f.title;
	var fileCheck_val = document.getElementById("user_file");
	// 이메일이 적합한지 검사할 정규식
	var re2 = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;

	//이름 체크 
	if (user_name.value == "" ) {
		alert("이름을 입력해 주세요.");
		user_name.focus();
		return false ;
	//이메일 체크
	}else if(email.value == "" ){
		alert("이메일을 입력해 주세요.");
		email.focus();
		return false;
	//제목 체크
	}else if (title.value == "") {
		alert("제목을 입력해 주세요.");
		title.focus();
		return false;
	}


	//이메일 유효성 검사
	  if(!check(re2, email, "적합하지 않은 이메일 형식입니다.")) {
			   return false;
		}
	
	//파일 용량 체크 
	if(fileCheck_val.value){
	
		var file_value = fileCheck(fileCheck_val);

		if (!file_value){
			return false;
		}
		
	}

  frm.action="/shop/standing_point_send_mailer.php";
}


//형식 체크
function check(re, what, message) {
       if(re.test(what.value)) {
           return true;
       }
       alert(message);
       what.value = "";
       what.focus();
       //return false;
}

/* 업로드 체크 */
function fileCheck( file )
{

	if(file.value){

        // 사이즈체크
        var maxSize  = 31457280;   //30MB
        var fileSize = 0;

	// 브라우저 확인
	var browser=navigator.appName;
	
	// 익스플로러일 경우
	if (browser=="Microsoft Internet Explorer")
	{
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( file.value ).size;
	}
	// 익스플로러가 아닐경우
	else
	{
		fileSize = file.files[0].size;
	}


	//alert("파일사이즈 : "+ fileSize +", 최대파일사이즈 : 30MB");

        if(fileSize > maxSize)
        {
            alert("첨부파일 사이즈는 30MB 이내로 등록 가능합니다.    ");
            return false;
        }
	}
	return true;
    
}
  


</script>
</html>
