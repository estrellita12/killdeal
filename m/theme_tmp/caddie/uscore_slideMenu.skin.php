<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>
    <dd>
	    <?php if($is_member) { ?>
            <a href="<?php echo TB_MBBS_URL; ?>/logout.php" class="btn_medium">로그아웃</a> 
		<?php } else { ?>  
		    <a href="https://www.uscore.co.kr/mapp/login.php?https://shop.uscore.co.kr/m/" class="btn_medium">로그인</a>
		<?php } ?>
	</dd>
	<dd>
	    <?php if($is_member) {  //로그인 ?>

		<?php } else {   //비로그인 ?>

		<?php  } ?>
	</dd>

