<?php
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

require "./PHPMailer.php";
require "./SMTP.php";
//require "./php_mailer/src/Exception.php";

$mail = new PHPMailer(true);

try {

    // 서버세팅
    $mail -> SMTPDebug = 2;    // 디버깅 설정
    $mail -> isSMTP();        // SMTP 사용 설정

    $mail -> Host = "smtp.gmail.com";                // email 보낼때 사용할 서버를 지정
    $mail -> SMTPAuth = true;                        // SMTP 인증을 사용함
    $mail -> Username = "k.dealhelp@gmail.com";    // 메일 계정
    $mail -> Password = "major1133!";                // 메일 비밀번호
    $mail -> SMTPSecure = "ssl";                    // SSL을 사용함
    $mail -> Port = 465;                            // email 보낼때 사용할 포트를 지정
    $mail -> CharSet = "utf-8";                        // 문자셋 인코딩

    // 보내는 메일
    $mail -> setFrom("k.dealhelp@gmail.com", "transmit");

    // 받는 메일
    $mail -> addAddress("java76@nate.com", "receive01");
    $mail -> addAddress("web@mwd.kr", "receive02");
    
    // 첨부파일
    //$mail -> addAttachment("./test.zip");
    //$mail -> addAttachment("./anjihyn.jpg");

    // 메일 내용
    $mail -> isHTML(true);                                               // HTML 태그 사용 여부
    $mail -> Subject = "PHPMailer 발송 테스트 입니다.";              // 메일 제목
    $mail -> Body = "PHPMailer 발송에 <b>성공</b>하였습니다.";    // 메일 내용

    // Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
    // CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
    $mail -> SMTPOptions = array(
        "ssl" => array(
              "verify_peer" => false
            , "verify_peer_name" => false
            , "allow_self_signed" => true
        )
    );
    
    // 메일 전송
    $mail -> send();
    
    echo "Message has been sent";

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error : ", $mail -> ErrorInfo;
}
?>

