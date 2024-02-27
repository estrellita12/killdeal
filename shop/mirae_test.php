<?php

$ip = $_SERVER['REMOTE_ADDR'];
$iplist = array("172.20.100.45", "58.231.24.148");

$flag = false;
foreach($iplist as $x){
    if($ip==$x) $flag=true;
}

if(!$flag){
    echo  "<script> window.alert('접근이 거부 되었습니다.'); location.href='https://naver.com'; exit; </script>";
    exit;
}

function h_en($data){
    $tmp = iconv("UTF-8","EUC-kr",$data);
    $tmp = base64_encode($tmp);
    $str = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar BB ".$tmp);
    return $str;
}

function h_de($data){
    $tmp = exec("/usr/local/java/bin/java -classpath .:hcdesutil.jar AA ".$data);
    $tmp = base64_decode($tmp);
    $str = iconv("EUC-KR","UTF-8",$tmp);
    return $str;
}



$conn = mysqli_connect(
    'localhost',
    'killdeal',
    'killdeal@user@',
    'killdeal');

if( isset($_REQUEST['url']) ){
    $res = $_REQUEST['url']."?";

    if( isset($_REQUEST['mem_id']) ) $res.=("mem_id=".h_en($_REQUEST['mem_id'])."&");
    if( isset($_REQUEST['shopevent_no']) ) $res.=("shopevent_no=".h_en($_REQUEST['shopevent_no'])."&");
    if( isset($_REQUEST['proc_code']) ) $res.=("proc_code=".h_en($_REQUEST['proc_code'])."&");
    if( isset($_REQUEST['chk_data']) ) $res.=("chk_data=".h_en($_REQUEST['chk_data'])."&");
    if( isset($_REQUEST['point']) ) $res.=("point=".h_en($_REQUEST['point'])."&");
    if( isset($_REQUEST['order_no']) ) $res.=("order_no=".h_en($_REQUEST['order_no'])."&");
    if( isset($_REQUEST['media_cd']) ) $res.=("media_cd=".$_REQUEST['media_cd']."&");
    if( isset($_REQUEST['item_nm']) ) $res.=("item_nm=".h_en($_REQUEST['item_nm'])."&");
}

?>
<html>
<body>
<style>
table{width:100%;table-layout:fixed;border-collapse: collapse;}
th{background-color:lightgray;}
table,td,th{border:1px solid lightgray; font-size:12px;word-break:break-all; text-align:center;}
th,.under{border-bottom:1px solid gray;}
input{border:none; width:100%;}
</style>
<h5><?php echo isset($res) ? $res :""; ?></h5>
<table>
    <colgroup>
        <col style="width:50px">
        <col style="width:100px">
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col style="width:50px">
        <col style="width:100px">
        <col style="width:50px">
    </colgroup>
    <tr>
        <th>no</th>
        <th>c_type</th>
        <th>mem_id</th>
        <th>shopevent_no</th>
        <th>proc_code</th>
        <th>chk_data</th>
        <th>order_no</th>
        <th>point</th>
        <th>MEM_IDNT_VAL</th>
        <th>media_cd</th>
        <th>item_nm</th>
        <th>return<br>code</th>
        <th>call<br>date</th>
        <th></th>
    </tr>
<?php
//$sql = "SELECT * FROM hwelfare_log where return_code != '000' and left(c_type,1)!='3' order by no desc limit 10";
//$sql = "SELECT * FROM hwelfare_log where url like '%FB1089D647C32F4232DB9939917051A8%' order by no desc limit 30";
$sql = "SELECT * FROM hwelfare_log order by no desc limit 10";
$result = mysqli_query($conn, $sql);
for($i=0; $row=mysqli_fetch_array($result); $i++){
    $url = explode("?",$row['url']);
    $c_type = "";
    switch($row['c_type']){
    case "100" : $c_type="포인트 조회"; break;
    case "101" : $c_type="포인트 사용"; break;
    case "102" : $c_type="포인트 취소"; break;
    case "201" : $c_type="사용 마감"; break;
    case "202" : $c_type="취소 마감"; break;
    case "401" : $c_type="현금영수증 신청"; break;
    case "402" : $c_type="현금영수증 취소"; break;

    }
    $arr = explode("&",$url[1]);
    $param="";
    foreach($arr as $x){
        $tmp = explode("=",$x);
        $param[$tmp[0]] = $tmp[1];
    }

    $mem_id = isset($param['mem_id'])? h_de($param['mem_id']):"";
    $MEM_NO = isset($param['MEM_NO'])? h_de($param['MEM_NO']):"";
    $shopevent_no = isset($param['shopevent_no'])?h_de($param['shopevent_no']):"";
    $SHOPEVENT_NO = isset($param['SHOPEVENT_NO'])?h_de($param['SHOPEVENT_NO']):"";
    $proc_code = isset($param['proc_code'])?h_de($param['proc_code']):"";
    $PROC_STS = isset($param['PROC_STS'])?$param['PROC_STS']:"";
    $chk_data = isset($param['chk_data'])?h_de($param['chk_data']):"";
    $order_no = isset($param['order_no'])?h_de($param['order_no']):"";
    $ORDER_NO = isset($param['ORDER_NO'])?h_de($param['ORDER_NO']):"";
    $point = isset($param['point'])?h_de($param['point']):"";
    $MEM_IDNT_VAL = isset($param['MEM_IDNT_VAL'])?h_de($param['MEM_IDNT_VAL']):"";
    $media_cd = isset($param['media_cd'])?$param['media_cd']:"";
    $item_nm = isset($param['ITEM_NM'])?h_de($param['ITEM_NM']):"";


?>
    <form action="#" method="post">
        <input type="hidden" name="url" value="<?php echo $url[0]; ?>">
    <tr>
        <td rowspan="2" class="under"><?php echo $row['no'] ?></td>
        <td><input type="c_type" value="<?php echo $row['c_type'] ?>"></td>
        <td colspan="8"><input type="text" value="<?php echo $row['url'] ?>" style="font-size:10px;" readonly></td>
<td></td>
        <td><?php echo $row['return_code'] ?></td>
        <td rowspan="2" class="under"><?php echo $row['call_date'] ?></td>
        <td rowspan="2" class="under"><button type="submit">변환</button></td>
    </tr>

    <tr>
        <td class="under"><?php echo $c_type ?></td>
        <td class="under"><input type="text" name="mem_id" value="<?php echo $mem_id.$MEM_NO ?>"></td>
        <td class="under"><input type="text" name="shopevent_no" value="<?php echo $shopevent_no.$SHOPEVENT_NO ?>"></td>
        <td class="under"><input type="text" name="proc_code" value="<?php echo $proc_code.$PROC_STS ?>"></td>
        <td class="under"><input type="text" name="chk_data" value="<?php echo $chk_data ?>"></td>
        <td class="under"><input type="text" name="order_no" value="<?php echo $order_no.$ORDER_NO ?>"></td>
        <td class="under"><input type="text" name="point" value="<?php echo $point ?>"></td>
        <td class="under"><input type="text" name="MEM_IDNT_VAL" value="<?php echo $MEM_IDNT_VAL ?>"></td>
        <td class="under"><input type="text" name="media_cd" value="<?php echo $media_cd ?>"></td>
        <td class="under"><input type="text" name="item_nm" value="<?php echo $item_nm ?>"></td>
        <td class="under"></td>
    </tr>
    </form>
    <?php } ?>
</table>




</body>
</html>



