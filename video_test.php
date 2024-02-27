<?php
include_once('./common.php');

$indexNo = $_GET['indexNo'];
if($indexNo=="" || !isset($indexNo) ){
    $indexNo = "(0)";
}
$sql = "select * from shop_goods where index_no in $indexNo order by index_no desc";
$res = sql_query($sql);
?>
<div class="scale06" style="margin: 0px auto; outline: 0px; vertical-align: baseline; overflow: hidden; width: 530px; font-family: 'Noto Sans KR','맑은고딕','Malgun Gothic','gulim','arial', 'Dotum', 'AppleGothic', sans-serif "><?php
for($i=0; $row=sql_fetch_array($res); $i++) {
        $it_href = '/shop/view.php?index_no='.$row['index_no'];
        $it_image = get_it_image($row['index_no'], $row['simg1'], 160, 150);
        $it_name = cut_str($row['gname'], 100);
        $it_price = get_price($row['index_no']);
        $it_normal_price = display_price2($row['normal_price']);
?><div style="margin: 1px auto; padding: 1px; border: 1px solid #bbb; height:150px" > <a href="<?php echo $it_href; ?>" style="color:black; text-decoration:none" target="_self"> <div style="float:left; width:160px; height:150px;"><?php echo $it_image;?></div><div style="float:left; width:355px; padding:25px 3px"><div style="font-weight:700; text-align:center; font-size:19px"><?php echo $it_name; ?></div><div style="font-weight:bold; text-align:center; "><span style="color:#bbb; font-size:23px; text-decoration:line-through"><?php echo $it_normal_price ?></span>&nbsp;&nbsp;<span style="font-size:27px;"><?php echo $it_price ?></span></div></div></a></div><?php }?></div>

