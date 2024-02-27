<?php 
function decrypt_aes($iv, $key, $payload) {
    $plainText = openssl_decrypt($payload, 'AES-128-CBC', $key, false, $iv);
    return $plainText;
}

function decrypt_mkey($iv, $key, $msg) {
    $payload = base64_decode( urldecode( $msg ) );
    $plainText = openssl_decrypt($payload, 'AES-128-CBC', $key, true, $iv);
    return $plainText;
}
$iv = $_POST['iv'];
$key = $_POST['key'];
$mkey = $_POST['mkey'];
$res = decrypt_mkey($iv, $key, $mkey);
$member = json_decode($res,true);
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
    <table>
        <tr>
            <td colspan="2" style="border:none;"><h1 style="text-align:center">복호화 테스트</h1></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">전송받은 데이터</td>
            <td><?php echo $mkey; ?></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">urldecode</td>
            <td><span><?php echo urldecode($mkey); ?></span></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">base64_decode</td>
            <td><span><?php echo base64_decode ( urldecode($mkey) ); ?></span></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">복호화된 데이터</td>
            <td><span><?php echo $res; ?></span></td>
        </tr>
        <tr>
            <td style="width:150px; background-color:lightgray;text-align:center; font-weight:bold ">JSON DECODE</td>
            <td><span><?php print_r($member); ?></span></td>
        </tr>
    </table>
</body>
</html>
