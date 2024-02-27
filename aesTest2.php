<?php 

function my_json_encode($arr){
    array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
    return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

function encrypt_mkey($iv,$key, $msg) {
    $cipherText = openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv);
    return urlencode(  base64_encode( $cipherText ) );
}

$iv = "0987654321654321";
$key = "1234567890123456";
$value = array('uuid'=>'hoi', 'name'=>'', 'email'=>'test@mwd.kr', 'cellphone'=>'01012341234'); // PHP 배열
$msg = my_json_encode($value);
$mkey = encrypt_mkey($iv,$key, $msg);

?>

<html>
<head>
    <style>
        table{
            width : 80%;
            max-width : 800px;
            margin : 0 auto;
            border : none;
        }

        td{
            word-break : break-all;
            padding : 15px 5px;
            border-bottom : 1px solid lightgray;
            font-size : 15px;
        }
    </style>
</head>
<body>
<br><br>
<form action="./aesDecode.php" method="post">
    <table>
        <tr>
            <td colspan="2" style="border:none;"><h1 style="text-align:center">암호화 테스트</h1></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">JSON 데이터</td>
            <td><?php echo $msg; ?></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">AES-128-CBC 데이터</td>
            <td><?php echo openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv); ?></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">Base64 데이터</td>
            <td><span><?php echo base64_encode(openssl_encrypt($msg, 'AES-128-CBC', $key, false, $iv) ); ?></span></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">mkey 데이터</td>
            <td><span><?php echo $mkey; ?></span></td>
        </tr>
        <tr>
            <td colspan="2" style="border:none; text-align:right">
                <input type="hidden" name="mkey" value="<?php echo $mkey ?>">
                <input type="hidden" name="iv" value="<?php echo $iv ?>">
                <input type="hidden" name="key" value="<?php echo $key ?>">
                <input type="submit" value="전송"  style="width:100px; font-weight:bold">
            </td>
        </tr>
    </table>
</form>
</body>
</html>




