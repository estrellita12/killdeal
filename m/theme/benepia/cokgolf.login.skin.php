<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="mb_login">
	<?php 
		$referer = urldecode($_SERVER['REQUEST_URI']);
		
		$gs_id = explode('=', $referer);
		//var_dump($gs_id[2]);
	?>
	<section class="login_fs">
		*콕뱅크 회원계정으로 로그인 후<br> 쇼핑진행하시면됩니다. 
		</br>
		</br>
	</section>
	

	<?php if(preg_match("/orderform.php/", $_GET['url'])) {?>
	<section class="mb_login_od" style="border-top:0px">
		<h3>비회원 구매</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">비회원으로 구매하기</a></p>
	</section>
	<?php } else{ ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>비회원 주문조회</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">주문번호</label>
            <input type="text" name="od_id" id="od_id" placeholder="주문번호">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">비밀번호</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="비밀번호">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">확인</button></p>
	</section>
	</form>
	<?php } ?>
</div>

<script>
function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert('주문번호를 입력하세요.');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert('비밀번호를 입력해주세요.');
		f.od_pwd.focus();
		return false;
	}

    return true;
}
</script>
