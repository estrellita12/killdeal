<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_media.skin.php');

?>
<div id="con_lf" >
    <h2 class="pg_tit">
        <?php echo $board['boardname']; ?>
        <span class="pg_nav">HOME<i>&gt;</i>미디어컨텐츠<i>&gt;</i><?php echo $board['boardname']; ?></span>
    </h2>

    <?php if($board['fileurl1']) { ?>
    <p class="marb10"><img src="<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></p>
    <?php } ?>
