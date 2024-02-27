<?php
if(!defined('_TUBEWEB_')) exit;

// 상품 종료 기간
$time_y = substr($ed_date, 0, 4);
$time_m = substr($ed_date, 5, 2);
$time_d = substr($ed_date, 8, 2);
$time_h = substr($ed_date, 11, 2);
$time_i = substr($ed_date, 14, 2);
$time_s = "00";

if($is_social_ing){
    $time_h = "23";
    $time_i = "59";
    $time_s = "59";
}

$eb_date = mktime($time_h, $time_i, $time_s, $time_m, $time_d, $time_y);
$t = getdate($eb_date);
?>

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
	if(days > 98){ // 999보다 높을때 오류남 2017 01 03
		days = 99;
	}
    days = formatNum(days, 2);
	$('.days').html(days);
    hrs = formatNum(hrs, 2);
	$('.hours').html(hrs);
    mins = formatNum(mins, 2);
	$('.minutes').html(mins);
    secs = formatNum(secs, 2);
	$('.seconds').html(secs);
}

function formatNum(num, len) {
    var numStr = "" + num;
    while (numStr.length < len) {
        numStr = "0" + numStr;
    }
    return numStr
}
</script>

<div class="time">
	<span class="tit">남은시간</span>
	<span class="days">00</span>
	<span class="day">일</span>
	<span class="hours">00</span>
	<span class="day">시간</span>
	<span class="minutes">00</span>
	<span class="day">분</span>
	<span class="seconds">00</span>
	<span class="day">초</span>
</div>

<script>
setInterval('countDown()', 1000);
</script>
