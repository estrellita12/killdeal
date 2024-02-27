<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'ss_tx='.$ss_tx.'&ca_id='.$ca_id.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ss_tx='.$ss_tx.'&ca_id='.$ca_id.'&page_rows='.$page_rows;
$qstr3 = 'ss_tx='.$ss_tx.'&ca_id='.$ca_id.'&sort='.$sort.'&sortodr='.$sortodr;

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

<h2 class="pg_tit"><span><?php echo $tb['title']; ?></span></h2>

<div class="bd list1 marb10">
	<fieldset style="padding:0 10px;">
		<legend>검색</legend>
		<form name="fsearch2" id="fsearch2" action="<?php echo TB_SHOP_URL; ?>/search.php" method="post">
		<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
        <input type="hidden" name="ss_tx_ori" value="<?php echo $ss_tx ?>">
		<table class="wfull">
		<tr height="40">
			<td class="tal padr10 padl10 bb">
				<strong class="fc_255">"<?php echo $ss_tx; ?>"</strong> 검색 결과 총 <strong class="fc_255"><?php echo number_format($allCnt); ?></strong>개의 상품이 검색 되었습니다.
			</td>
		</tr>
		<tr height="40">
			<td class="tal padr10 padl10">
                <input type="checkbox" name="research" value="1">
				<span class="padr5">검색 내 재검색</span>
				<input name="ss_tx" value="" size="40" class="frm_input">
				<input type="submit" value="검색" class="btn_small grey">
			</td>
		</tr>
		</table>
		</form>
	</fieldset>
</div>

<!--  2021-11-26 -->
<!--  카테고리 탭 시작{ -->
<?php if($allCnt > 0 ){ ?>
<div class="category-type-list mart20 marb20">
    <ul class="depth1">
<?php
$ts = " select a.ca_id as ca_id, count(a.index_no) as cnt $sql_query group by a.ca_id order by a.ca_id ";
$tr = sql_query($ts,false);
$cateCnt = array();
for($i=0; $tmp=sql_fetch_array($tr); $i++) {
    $cateCnt[$tmp['ca_id']] = $tmp['cnt'];
}

$res = sql_query_cgy('all');
for($i=0; $row=sql_fetch_array($res); $i++) {
    $href = $_SERVER['SCRIPT_NAME']."?".$qstr1."&ca_id=".$row['catecode'];

    // 테이블의 전체 레코드수만 얻음
    $s1 = " select count(a.index_no) as cnt $sql_query and ca_id like ('{$row['catecode']}%')";
    $r1 = sql_fetch($s1);
    $t1 = $r1['cnt'];

    $arr = preg_grep("/^".$row['catecode']."/", array_keys($cateCnt));
    $t1 = 0;
    foreach($arr as $key){ $t1 += $cateCnt[$key]; }


?>
        <li>
        <div class="title"><a href="<?php echo $href; ?>" class="cate_tit " ><?php echo $row['catename']?><?php  echo $t1!=0?"(".$t1.")":""; ?></a></div>
        <div class="inner">
            <ul class="depth2">
<?php
    $res2 = sql_query_cgy($row['catecode']);
    while($row2 = sql_fetch_array($res2)) {
        $href2 = $_SERVER['SCRIPT_NAME']."?".$qstr1."&ca_id=".$row2['catecode'];

        $arr = preg_grep("/^".$row2['catecode']."/", array_keys($cateCnt));
        $t2 = 0;
        foreach($arr as $key){ $t2 += $cateCnt[$key]; }
        if($t2==0) continue;
?>
                <li>
                <div class="title">
                    <a href="<?php echo $href2; ?>" class="<?php echo $row2['catecode']==$ca_id?"fc_red":""; ?>"><?php echo $row2['catename'] ?> (<?php  echo $t2; ?>)</a>
                </div>
                <ul class="depth3">
<?php
        $res3 = sql_query_cgy($row2['catecode']);

        $r = sql_query_cgy($row2['catecode'], 'COUNT');
        if($r['cnt'] <= 0) { ?>
            <li><a href="<?php echo $href2; ?>" class="<?php echo $row2['catecode']==$ca_id?"fc_red":""; ?>"><?php echo $row2['catename'] ?> (<?php  echo $t2; ?>)</a    ></li>
        <?php }

        while($row3 = sql_fetch_array($res3)) {
            $href3 = $_SERVER['SCRIPT_NAME']."?".$qstr1."&ca_id=".$row3['catecode'];

            $arr = preg_grep("/^".$row3['catecode']."/", array_keys($cateCnt));
            $t3 = 0;
            foreach($arr as $key){ $t3 += $cateCnt[$key]; }
            if($t3==0) continue;
?>

                    <li>
                    <a href="<?php echo $href3; ?>"  class="<?php echo $row3['catecode']==$ca_id?"fc_red":""; ?>"><?php echo $row3['catename'] ?> (<?php  echo $t3; ?>)</a>
                    </li>
                    <?php } ?>
                </ul>
                </li>
                <?php } //while ?>
            </ul>
        </div>
        </li>
        <?php } //for ?>
        </li>
    </ul>
</div>
<!-- 카테고리 탭 종료 -->
<?php } ?>

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

<div class="pr_desc sub_pr_desc wli5">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 235, 235);
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
			<dl class="marb10" style="border:none">
				<dt><?php echo $it_image; ?></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $sale." ";?><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
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
