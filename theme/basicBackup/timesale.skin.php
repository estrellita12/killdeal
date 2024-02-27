<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'page_rows='.$page_rows;
$qstr3 = 'sort='.$sort.'&sortodr='.$sortodr;

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

<script>
function CountDownTimer(dt, id)
{
	var end = new Date(dt);

	var _second = 1000;
	var _minute = _second * 60;
	var _hour = _minute * 60;
	var _day = _hour * 24;
	var timer;

	function showRemaining() {
		var now = new Date();
		var distance = end - now;
		if (distance < 0) {
			clearInterval(timer);
			document.getElementById(id).innerHTML = 'EXPIRED!';
			return;
		}
		var days = Math.floor(distance / _day);
		var hours = Math.floor((distance % _day) / _hour);
		var minutes = Math.floor((distance % _hour) / _minute);
		var seconds = Math.floor((distance % _minute) / _second);
		var str = "";
		str += '<span class="num">'+days + '</span> 일 ';
		str += '<span class="num">'+pad(hours,2) + '</span> 시간 ';
		str += '<span class="num">'+pad(minutes,2) + '</span> 분 ';
		str += '<span class="num">'+pad(seconds,2) + '</span> 초';
		document.getElementById(id).innerHTML = str;
	}

	timer = setInterval(showRemaining, 1000);
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

$(document).ready(function(){
	var timesaleEl = $('.timesale li dt img');
	console.log(timesaleEl);
});
</script>

<!-- <h2 class="pg_title">
	<div class="inner">
		<dl class="txt_bx">
			<dt><?php echo $default['de_pname_8']; ?></dt>
			<dd>한정된 시간동안만 누릴 수 있는 특가세일을 만나보세요</dd>
		</dl>
	</div>
</h2> -->

<p class="subpg_nav">홈<i class="ionicons ion-ios-arrow-right"></i><?php echo $default['de_pname_8']; ?></p>

<h2 class="pg_tit subpg_tit">
	<!-- <span><?php echo $default['de_pname_8']; ?></span> -->
	<?php echo $default['de_pname_8']; ?>
	<span>가격을 말도 안되게 내린 착한가격 좋은 상품을 만나보세요</span>
</h2>


<div class="timesale pr_desc sub_pr_desc wli5">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 500, 500);
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

		$eb_date = date("Y-m-d",strtotime("+1 day", strtotime($row['eb_date'])));
		$yy = substr($eb_date, 0, 4);
		$mm = substr($eb_date, 5, 2);
		$dd = substr($eb_date, 8, 2);
	?>
	<!--20191231 타임세일 ui 수정. css외부로 못뺌. 임시방편 스타일 직접 삽입-->
		<li style="width:50%">
			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt style="width:500px; height:500px;"><?php echo $it_image; ?></dt>
				<dd class="ptime" style="margin-top: 10px"><span id="countdown_<?php echo $i; ?>"></span></dd>
				<dd class="pname" style="text-align:center"><?php echo $it_name; ?></dd>
				<dd class="price" style="text-align:center"><?php echo $sale; ?><span class="price_box"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
                <?php if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) { ?>
				<dd class="petc" style="text-align:center">
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
			<script>
			CountDownTimer("<?php echo $mm; ?>/<?php echo $dd; ?>/<?php echo $yy; ?> 00:00 AM", "countdown_<?php echo $i; ?>");
			</script>
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
