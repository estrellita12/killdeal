<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'ca_id='.$ca_id.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ca_id='.$ca_id.'&page_rows='.$page_rows;
$qstr3 = 'ca_id='.$ca_id.'&sort='.$sort.'&sortodr='.$sortodr;

$sort_str = '';
for($i=0; $i<count($gw_psort); $i++) {
	list($tsort, $torder, $tname) = $gw_psort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	$active = '';
	if($sort == $tsort && $sortodr == $torder)
		$active = ' class="active"';
	if($i==0 && !($sort && $sortodr))
		$active = ' class="active"';

	$sort_str .= '<li><a href="'.$sct_sort_href.'"'.$active.'>'.$tname.'</a></li>'.PHP_EOL;
}
?>
<p class="subpg_nav">홈<?php echo get_move($ca_id); ?></p>

<h2 class="pg_tit subpg_tit">
	<?php echo $ca['catename']; ?>
	<!-- <p class="pg_nav">HOME<?php echo get_move($ca_id); ?></p> -->
</h2>


<?php
$cgy = get_category_head_image2($ca_id);

echo tree_category($ca_id); // 하위분류
?>

<?php echo $cgy['headimg']; // 분류별 상단이미지 ?>

<!--
<div class="tab_sort sub_tab_sort">
	<div class="tab_sort_box">
		<ul>
			<?php echo $sort_str; // 탭메뉴 ?>
		</ul>
	</div>
	<span class="total">전체상품 (<b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개)</span>
	<label>
		<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
			<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
			<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
			<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
		</select>
	</label>
</div>
-->

<div class="tab_sort">
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
		<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
		<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
		<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
	</select>
</div>
<div class="tab_sort sub_tab_sort">
	<span class="total">전체상품 (<b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개)</span>
</div>



<div class="pr_desc sub_pr_desc wli5">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 278, 278);
		$it_name = cut_str($row['gname'], 100);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
			$it_sprice = display_price2($row['normal_price']);
		}
	?>
		<li>
			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt><?php echo $it_image; ?></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
                <?php if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) { ?>
				<dd class="petc">
                    <?php if($row['gpoint']) { ?>
					<span class="fbx_small fbx_bg6"><?php echo $it_point; ?> 적립</span>
                    <?php } ?>
                    <?php if($is_free_baesong) { ?>
					<span class="fbx_small fbx_bg4">무료배송</span>
                    <?php } ?>
                    <?php if($is_free_baesong2) { ?>
					<span class="fbx_small fbx_bg4">조건부무료배송</span>
                    <?php } ?>
                </dd>
				<?php } ?>
			</dl>
			</a>
			<!-- 20191104 찜 주석처리
			<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php //echo $row['index_no']; ?>');" id="<?php //echo $row['index_no']; ?>" class="<?php //echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php //echo $it_href; ?>" target="_blank" class="nwin"></a></p>
			-->
		</li>
	<?php } ?>
	</ul>
</div>

<?php if(!$total_count) { ?>
<div class="empty_list bb">자료가 없습니다.</div>
<?php } ?>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>
