<?php

include_once('./common.php');

// 모바일접속인가?
if(TB_IS_MOBILE) {
	
	if($pt_id == 'thegolfshow')
	{
	    //더골프쇼 앱에서 주는 인자를 모바일페이지 이동시 값을 넘긴다_20191126
?>
	<form name="userinfo" method="post" action="/m/index.php">
		<input type="hidden" name="memberidx" value="<?php echo $_GET['memberidx'];  ?>">
	</form>

	<script>
		window.onload = function() {
			document.userinfo.submit();
		};
	</script>
<?php
	}
	else
	{
		echo "정상 적인 방법으로 접속하세요";
	?>

	<script>
		self.opener = self;
		window.close();
	</script>
	<?php
	}
}
else {
	echo "정상 적인 방법으로 접속하세요";
	?>

	<script>
		self.opener = self;
		window.close();
	</script>
	<?php

}



?>