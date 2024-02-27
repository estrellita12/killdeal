<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2 class="pg_title">
	<div class="inner">
		<dl class="txt_bx">
			<dt><?php echo $default['de_pname_9']; ?></dt>
			<!-- <dd>특별한 <?php echo $default['de_pname_7']; ?> 상품을 만나보세요</dd> -->
		</dl>
	</div>
</h2>
<ul class="mgz">
	<?php
	$sql = "select * from shop_goods_magazine where mb_id IN('admin','$pt_id') and mgz_use = '1' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$href = TB_SHOP_URL.'/magazinelist.php?mgz_no='.$row['mgz_no'];
		$bimg = TB_DATA_PATH.'/magazine/'.$row['mgz_limg'];
		if(is_file($bimg) && $row['mgz_limg']) {
			$pl_limgurl = rpc($bimg, TB_PATH, TB_URL);
		} else {
			$pl_limgurl = TB_IMG_URL.'/plan_noimg.gif';
		}
	?>
	<li>
		<a href="<?php echo $href; ?>">
		<p class="mgz_img"><img src="<?php echo $pl_limgurl; ?>"></p>
		<p class="mgz_tit"><?php echo $row['mgz_name']; ?></p>
		</a>
	</li>
	<?php } ?>
</ul>
