<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<dl class="aside_cs">	
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_media' order by index_no ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$bo_href = TB_BBS_URL.'/list.php?boardid='.$row['index_no'];
			echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
		}
		?>
	</dl>
</aside>
<!-- } 좌측메뉴 끝 -->
