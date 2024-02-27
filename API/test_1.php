<?php 
include_once('../common.php');

//header('Location: http://honggolf.killdeal.co.kr/');
header('Content-Type: text/html; charset=utf-8');

function index_db(){
	$sql = "select * from shop_member where index_no = '108' ";
	$res = sql_fetch($sql);
	
  $d_ar = array(
    $res['name'],
    $res['id'],
    $res['email'],
    $res['cellphone']
    );
	
	return $d_ar;
}

$idd = index_db();

?>
<script>
function intro(){
	var f = document.index_form;
	f.action = "http://honggolf.killdeal.co.kr/";
	f.method = "post";
	f.submit();
}
//window.onload = function(){
//console.log('자식');
//window.parent.postMessage({childData : 'Data'},'http://csktp95.dothome.co.kr/burgerKing/test.html');


window.addEventListener('message', function(e) {
  console.log('child message');
  console.log(e.data);
  console.log("e.origin : " + e.origin);

  if(e.data.parentData === 'testData'){
	html.text("전송완료");
	console.log('데이터전송완료');
  }
});

</script>
<html>
<body>
<form name="index_form" id="index_form">
	<input type="text" name="index_nm" id="index_no" value="<?php echo $idd[0] ?>">
	<input type="text" name="index_id" id="index_no" value="<?php echo $idd[1] ?>">
	<input type="text" name="index_em" id="index_no" value="<?php echo $idd[2] ?>">
	<input type="text" name="index_ph" id="index_no" value="<?php echo $idd[3] ?>">
	<a href="#" onclick="intro()">SHOP</a>
</form>

</body>
</html>