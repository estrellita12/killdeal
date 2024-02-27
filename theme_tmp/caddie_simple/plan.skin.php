<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- <h2 class="pg_title">
	<div class="inner">
		<dl class="txt_bx">
			<dt><?php echo $default['de_pname_7']; ?></dt>
			<dd>특별한 <?php echo $default['de_pname_7']; ?> 상품을 만나보세요</dd>
		</dl>
	</div>
</h2> -->

<p class="subpg_nav">홈<i class="ionicons ion-ios-arrow-right"></i><?php echo $default['de_pname_7']; ?></p>

<h2 class="pg_tit subpg_tit">
	<?php echo $default['de_pname_7']; ?>
	<span>특별한 <?php echo $default['de_pname_7']; ?> 상품을 만나보세요</span>
</h2>

<!-- pl_order -> pl_no --> 
<ul class="plan2">
	<?php
	//$sql = "select * from shop_goods_plan where mb_id IN('admin','$pt_id') and pl_use = '1' order by pl_order ";

    // 2021-10-29
    $timer = " pl_sb_date <= NOW() and pl_ed_date >= NOW() ";
    $sql = "select * from shop_goods_plan where mb_id IN('admin','$pt_id') and pl_use = '1' and {$timer} order by pl_order ";

	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$href = TB_SHOP_URL.'/planlist.php?pl_no='.$row['pl_no'];
		$bimg = TB_DATA_PATH.'/plan/'.$row['pl_limg'];
		if(is_file($bimg) && $row['pl_limg']) {
			$pl_limgurl = rpc($bimg, TB_PATH, TB_URL);
		} else {
			$pl_limgurl = TB_IMG_URL.'/plan_noimg.gif';
		}
	?>
	<li>
		<a href="<?php echo $href; ?>">
		<p class="plan_img"><img src="<?php echo $pl_limgurl; ?>" alt="기획전상품대표이미지"></p>
		<p class="plan_tit"><?php echo $row['pl_name']; ?></p>
		</a>
	</li> 
	<?php } ?>
</ul>
