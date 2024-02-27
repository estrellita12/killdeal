<html>
<body>

<form name="frmSMS" method="post" enctype="multipart/form-data" action="http://dabonem.com/b2c/w
eburl_mms.do">
<input type="hidden" name="id" value="test">
<input type="hidden" name="pw" value="password">
<input type="hidden" name="kind" value=“MMS">
<input type="hidden" name="rcvNum" value="01012341234">
<input type="hidden" name="sendNum" value="01043214321">
<input type="hidden" name=“deliveryTime" value=“2016-06-30 15:22">
<input type="hidden" name="subject" value="제목">
<Input type="hidden" name="msg" value="전송할 내용">
<input type="hidden" name="returnURL" value="http://dabonem.com/test.do">
<input type=“file" name=“file”>
</form>
</body>
</html>
<?php
function post($url, $fields)
{
    $post_field_string = http_build_query($fields, '', '&');
    $ch = curl_init();                                                            // curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);                                 // url 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);              // 요청결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);               // connection timeout : 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                 // 원격 서버의 인증서가 유효한지 검사 여부
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);      // POST DATA
    curl_setopt($ch, CURLOPT_POST, true);                               // POST 전송 여부
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
}

TA = array(
    'MY_EMAIL'=>$MY_EMAIL, 
    'MY_KEY'=>$MY_KEY, 
    'MY_NAME'=>$MY_NAME
);

result = post('https://www.도메인.com/submit.php', $DATA);





?>
