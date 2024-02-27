<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

?>
<dd>
    <?php if($is_member) { ?>
        <?php if(!$pt_link_data['db_link_yes']){ ?>
            <a href="<?php echo TB_MBBS_URL; ?>/logout.php" class="btn_medium">로그아웃</a> 
        <?php } ?>
    <?php } else { ?>  
        <a href="<?php echo TB_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">로그인</a>
    <?php } ?>
</dd>
<dd>
    <?php if(!$pt_link_data['db_link_yes']){ ?>
        <?php if($is_member) {  //로그인 ?>
            <a href="<?php echo TB_MBBS_URL; ?>/member_confirm.php?url=register_form.php" class="btn_medium bx-white">정보수정</a>
        <?php } else {   //비로그인 ?>
            <a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white">회원가입</a>
        <?php } ?>
    <?php  } ?>
</dd>

