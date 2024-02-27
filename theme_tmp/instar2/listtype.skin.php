<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'type='.$type.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'type='.$type.'&page_rows='.$page_rows;
$qstr3 = 'type='.$type.'&sort='.$sort.'&sortodr='.$sortodr;

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
<p class="subpg_nav">홈<i class="ionicons ion-ios-arrow-right"></i><?php echo $tb['title']; ?></p>


<h2 class="pg_tit subpg_tit">
	<?php echo $tb['title']; ?>
	<!-- <p class="pg_nav">HOME<i>&gt;</i><?php echo $tb['title']; ?></p> -->
</h2>

<div class="tab_sort sub_tab_sort">
<?php if($type!== '2') { ?>
	<div class="tab_sort_box">
		<ul>
			<?php echo $sort_str; // 탭메뉴 ?>
		</ul>
	</div>
<?php } else { ?>

	<?php 
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);

	$qstr1 = 'type='.$type.'&page_rows='.$page_rows.'&cat='.$cat;
	$qstr2 = 'type='.$type.'&page_rows='.$page_rows;
	$qstr3 = 'type='.$type.'&cat='.$cat;

	for($i=0; $i<$list_count; $i++) {
		$tcat = '00'.$i;
		if($i == '0') {
			$tcat = '';
		} else if ($i == '2'){
			$tcat = '002';
		} else if ($i == '3') {
			$tcat = '003';
		}
		$cat_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&cat='.$tcat;

		$active = '';
		if($cat == $tcat)
			$active = ' class="active"';
		if($i==0 && !($cat))
			$active = ' class="active"';

		$cat_str .= '<li '.$active.'><a href="'.$cat_sort_href.'">'.trim($list_best[$i]['subj']).'</a></li>'.PHP_EOL;
	}
	//echo $cat_sort_href;

	 ?>
	<div class="tab_sort_box tab_sort_box2">
		<ul class="bestca_tab1">
			<?php echo $cat_str; // 탭메뉴 ?>
		</ul>
	</div>
<?php } ?>

<?php if($type!== '2') { ?>  <!-- BEST100은 정렬을하지않음 -->
	<span class="total">전체상품 <b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개</span>
	<!-- 20200416 상품의수가 20개를 넘지않아 필요없는기능이므로 주석처리 -->
	<!-- <label>
		<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;" <?php if($type== '2') { echo 'style="margin-top: 20px;"';}?>>
			<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
			<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
			<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
		</select>
	</label> -->
<?php } else { ?>
	<span class="total">전체상품 <b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개</span>
<?php } ?>
</div>

<div class="pr_desc sub_pr_desc wli5 <?php if($type== '2') { echo 'sub_best_desc';}?>">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 290, 290);
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
            <?php if(strpos($it_price,"품절") || strpos($it_price,"중지")){ ?>
            <div class="soldout_layer"></div>
            <?php } ?>
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
