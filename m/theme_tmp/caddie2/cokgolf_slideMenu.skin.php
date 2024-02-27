<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
$url = TB_MBBS_URL.'/register_form_update.php';
?>
<dd>
    <?php if(!$pt_link_data['db_link_yes']){ ?>
        <?php if($is_member) {  //로그인 ?>
            <a href="<?php echo TB_MBBS_URL; ?>/member_confirm.php?url=register_form.php" class="btn_medium bx-white">정보수정</a>
        <?php }else{ ?>
        <a href="<?php echo TB_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">로그인</a>
        <?php } ?>
    <?php  } ?>
</dd>

