<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'page_rows='.$page_rows;

$sort_str = '';
for($i=0; $i<count($gw_msort); $i++) {
	list($tsort, $torder, $tname) = $gw_msort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	if($sort == $tsort && $sortodr == $torder)
		$sort_name = $tname;
	if($i==0 && !($sort && $sortodr))
		$sort_name = $tname;

	$sort_str .= '<li><a href="'.$sct_sort_href.'">'.$tname.'</a></li>'.PHP_EOL;
}

// 상품 종료 기간
$time_y = substr($ed_date, 0, 4);
$time_m = substr($ed_date, 5, 2);
$time_d = substr($ed_date, 8, 2);
$time_h = substr($ed_date, 11, 2);
$time_i = substr($ed_date, 14, 2);
$time_s = "00";

$ed_date = mktime($time_h, $time_i, $time_s, $time_m, $time_d, $time_y);
$t = getdate($ed_date);

?>

<script>
var targetDate = new Date(<?=$t[year];?>,<?=$t[mon]-1;?>,<?=$t[mday];?>,<?=$t[hours];?>,<?=$t[minutes];?>,<?=$t[seconds];?>);
var targetInMS = targetDate.getTime();

var oneSec = 1000;
var oneMin = 60 * oneSec;
var oneHr = 60 * oneMin;
var oneDay = 24 * oneHr;

function formatNum(num, len) {
    var numStr = "" + num;
    while (numStr.length < len) {
        numStr = "0" + numStr;
    }
    return numStr
}

function countDown(id) {
    var nowInMS = new Date().getTime();
    var diff = targetInMS - nowInMS;
    if (diff < 0) { location.reload(); return; }

    var scratchPad = diff / oneDay;
    var daysLeft = Math.floor(scratchPad);
    // hours left
    diff -= (daysLeft * oneDay);
    scratchPad = diff / oneHr;
    var hrsLeft = Math.floor(scratchPad);
    // minutes left
    diff -= (hrsLeft * oneHr);
    scratchPad = diff / oneMin;
    var minsLeft = Math.floor(scratchPad);
    // seconds left
    diff -= (minsLeft * oneMin);
    scratchPad = diff / oneSec;
    var secsLeft = Math.floor(scratchPad);
    // now adjust images
    setImages(daysLeft, hrsLeft, minsLeft, secsLeft, id);
}

function setImages(days, hrs, mins, secs ,id) {
		var str = "";
		str += '<span class="num">'+days + '</span> 일 ';
		str += '<span class="num marl5">'+pad(hrs,2) + '</span> : ';
		str += '<span class="num">'+pad(mins,2) + '</span> : ';
		str += '<span class="num">'+pad(secs,2) + '</span>';
		document.getElementById(id).innerHTML = str;
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}
</script>
<script>
$(function() {
	var mbheight = $(window).height();

	$('#btn_sort').click(function(){
		$('#sort_bg').fadeIn(300);
		$('#sort_li').slideDown('fast');
		$('html').css({'height':mbheight+'px', 'overflow':'hidden'});
	});

	$('#sort_bg, #sort_close').click(function(){
		$('#sort_bg').fadeOut(300);
		$('#sort_li').slideUp('fast');
		$('html').css({'height':'100%', 'overflow':'scroll'});
	});
});
</script>
<!-- } 상품 정렬 선택 끝 -->

<?php
if(!$total_count) {
	echo "<p class=\"empty_list\">게시글이 없습니다.</p>";
} else {
	echo "<ul class=\"timesale\">";
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
		$it_name = cut_str($row['gname'], 50);
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], 400, 400);
		$it_price = mobile_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<span class="sale" style="font-size:18px; color: #fd0000; font-weight: 800;  ">'.number_format($sett,0).'%</span>';
			$it_sprice = display_price2($row['normal_price']);
		}

	?>
		<li>
            <?php if(strpos($it_price,"품절")){ ?>
            <div style="position:absolute; z-index:100; width:100%; height:100%; text-align:center; background-color:rgba(100,100,100,0.1)"><img src="/img/timesale_soldout.png" style="width:100%;"></div>
            <?php } ?>

			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt><img src="<?php echo $it_imageurl; ?>" alt="상품이미지"></dt>
				<dd class="ptime"><span id="countdown_<?php echo $i; ?>"></span></dd>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $sale; ?><span class="price_box" style="margin-left:10px;"><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
                <?php if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) { ?>
				<?php } ?>
			</dl>
			</a>
			<script>
            setInterval('countDown( "countdown_<?php echo $i; ?>" )', 1000);
			</script>
		</li>
	<?php
	}
	echo "</ul>";
}

echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>
