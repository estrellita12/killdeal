<?php

header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Origin: http://www.dodamchon.co.kr/member/emoney_dodamgolf');


// 20191125 도담골프 키코드 값 생성
function gen_keycode()
{
    $cur_time = date("YmdHis");
    $org_time = 19900101000001;
    echo $cur_time;
    echo"</br>";
    $temp = ($cur_time - $org_time) * 8;
    $trans = sprintf("%.0f",$temp);
    return 'CD'.$trans;
}

// 20191125 도담골프 멤버 정보 호출
function get_member_info($keycode, $uid)
{
    $data = array(
        'usr' => $uid ,
        'kc' => $keycode
    );
    //echo http_build_query($data);

    $url = "https://www.dodamchon.co.kr/member/check_dodamgolf/?".http_build_query($data);

    $ch = curl_init();                                 //curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
     
    $response = curl_exec($ch);
    curl_close($ch);

    $temp = to_han($response);
    $res = str_replace('\\','',$temp);
    //echo($res);

    var_dump($response);
    $jsonde = json_decode($res);
    return $jsonde;
}

// 20191125 JSON 한글화 디코딩 함수
function han ($s) { return reset(json_decode('{"s":"'.$s.'"}')); }
function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); }
 
$keycode = gen_keycode();

echo $keycode;
echo "</br>";
$mem_info = get_member_info($keycode, "ssw5541");
echo "덤프";
var_dump($mem_info);
echo ($mem_info->mem_id);
echo "</br>";
echo ($mem_info->mem_nm);
echo "</br>";
echo ($mem_info->mem_gd);
echo "</br>";
echo ($mem_info->point);
echo "</br>";
//sleep(5);
$keycode2 = gen_keycode();
echo $keycode2;
?>
<body>
<form name="point_check" method="post" action="http://www.dodamchon.co.kr/member/emoney_dodamgolf">
<!--<form name="point_check" method="post" action="http://127.0.0.1/post_test.php">-->
    <input type="hidden" name="usr" value="<?php echo "ssw5541"; ?>">
    <input type="hidden" name="kc" value="<?php echo $keycode2; ?>">
    <input type="hidden" name="em" value="<?php echo "100"; ?>">
    <input type="hidden" name="md" value="<?php echo "plus"; ?>">
    <input type="hidden" name="mm" value="<?php echo "test"; ?>">
    <input type="submit">
<form>
</body>
<script>
    window.onload = function() {
        //alert('ssss');
		//document.point_check.submit();
	};
    
</script>
<script src="http://code.jquery.com/jquery-latest.min.js">
    /*
    document.getElementById("pointcheck").submit();

	window.onload = function() {
		document.pointcheck.submit();
	};
    */
    $("form").submit(function() {
        <?php $keycod2 = gen_keycode()?> 
    });


</script>