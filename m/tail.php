<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

// (2021-02-08) 테일 표준화
include_once(TB_MTHEME_PATH.'/tail.skin.php'); // 하단

// BODY 내부 메시지
if($config['tail_script']) {
	echo $config['tail_script'].PHP_EOL;
}

include_once(TB_MPATH."/tail.sub.php");
?>
