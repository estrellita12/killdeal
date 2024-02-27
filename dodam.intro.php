<?php

include_once('./common.php');

// 모바일접속인가?
if($pt_id == 'dodamgolf')
{
	if(TB_IS_MOBILE) 
	{
		//도담골프 모바일 form 전송
	?>
		<form name="userinfo" method="post" action="m/index.php">
		<input type="hidden" name="uid" value="<?php echo $_GET['uid'];  ?>">
		</form>
	<?php
	}
	else
	{
		?>
		<form name="userinfo" method="post" action="/index.php">
		<input type="hidden" name="uid" value="<?php echo $_GET['uid'];  ?>">
		</form>
<?php
	}
?>

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

?>