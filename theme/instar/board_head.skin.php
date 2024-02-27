<?php
if(!defined('_TUBEWEB_')) exit;

//  사이드 메뉴
//include_once(TB_THEME_PATH.'/aside_cs.skin.php');

?>
<!--
<div id="con_lf" <?php if ($board['boardname'] == '공지사항') echo "class='notice_width'"?>>

	<h2 class="pg_tit">
		<?php echo $board['boardname']; if($board['boardname'] != '공지사항'){
			echo "<span class=\"pg_nav\">HOME<i>&gt;</i>미디어컨텐츠<i>&gt;</i>".$board['boardname']."</span>";
		}?>
	</h2>
    
	
	<?php if($board['fileurl1']) { ?>
	<p class="marb10"><img src="<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></p>
	<?php } ?>
-->
