<?php
if(!defined('_TUBEWEB_')) exit;

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
<style>
@font-face{
    font-family:'S-Core_Dream';
    font-style:normal;
    font-weight:500;
    src:url('/plugin/font/SCDream5.otf') format('opentype');
}

.timesale2 .timer .num{
    font-family:'S-Core_Dream';
}
</style>
<script>
var targetDate = new Date(<?=$t[year];?>,<?=$t[mon]-1;?>,<?=$t[mday];?>,<?=$t[hours];?>,<?=$t[minutes];?>,<?=$t[seconds];?>);
var targetInMS = targetDate.getTime();

var oneSec = 1000;
var oneMin = 60 * oneSec;
var oneHr = 60 * oneMin;
var oneDay = 24 * oneHr;

function countDown() {
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
    setImages(daysLeft, hrsLeft, minsLeft, secsLeft);
}

function setImages(days, hrs, mins, secs) {
    if(days > 99){ 
        days = 99;
    }
    days = formatNum(days, 2);
    $('.days1').html( parseInt(days/10) ) ;
    $('.days2').html(days%10);

    hrs = formatNum(hrs, 2);
    $('.hours1').html( parseInt(hrs/10) );
    $('.hours2').html(hrs%10);

    mins = formatNum(mins, 2);
    $('.minutes1').html( parseInt(mins/10));
    $('.minutes2').html(mins%10);

    secs = formatNum(secs, 2);
    $('.seconds1').html(parseInt(secs/10));
    $('.seconds2').html(secs%10);
}

function formatNum(num, len) {
    var numStr = "" + num;
    while (numStr.length < len) {
        numStr = "0" + numStr;
    }
    return numStr
}

$(document).ready(function(){
	var timesaleEl = $('.timesale li dt img');
	console.log(timesaleEl);
});
</script>

<div class="timesale2">
    <div class="main_banner gradient"><img src="/img/timesale_header.png"></div>
    <?php if(!$total_count || $total_count==0 ) { ?>
    <!-- (2020-12-24) 타임 세일 이미지 추가 -->
    <div class="empty_list bb">
        <img src="/img/timesale_soldout.png">
    </div>
    <?php } ?>

    <?php if( $total_count > 0 ) { ?>
    <div class="timer">
        <div class="timebox">
            <div class="num days1">0</div>
            <div class="num days2">0</div>
            <div class="d_txt"> DAY</div>
            <div class="num hours1">0</div>
            <div class="num hours2">0</div>
            <div class="colbox">:</div>
            <div class="num minutes1">0</div>
            <div class="num minutes2">0</div>
            <div class="colbox">:</div>
            <div class="num seconds1">0</div>
            <div class="num seconds2">0</div>
        </div>
        <div class="txtbox">
            <div class="d_txt"></div>
            <div class="h_txt">HOURS</div>
            <div class="m_txt">MINUTES</div>
            <div class="s_txt">SECONDS</div>
        </div>
    </div>

    <script>
        setInterval('countDown()', 1000);
    </script>
    <?php } ?>
	<ul class="main_box">
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 345,345);
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
        <li class="item_box">
            <a href="<?php echo $it_href; ?>">
            <?php if( strpos($it_price,"품절") || strpos($it_price,"중지") ){ ?>
            <div class="soldout_layer"></div>
            <?php } ?>
            <div class="hover_layer"></div>
                <div class="pimg"> <?php echo $it_image; ?> </div>
                <div class="pname"><?php echo $it_name; ?></div>
                <div class="price">
                    <table>
                        <tr>
                            <td class="psale tal" rowspan="2"><?php echo $sale ?> </td>
                            <td class="tar"><?php echo $it_sprice; ?></td>
                        </tr>
                        <tr>
                            <td class="tar"><?php echo $it_price; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="wsetbg btn">구매하러 가기</div>
            </a>
        </li>

    <?php } ?>
    </ul>
<br><br><br>
<br><br><br>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>
