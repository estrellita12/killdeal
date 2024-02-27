<?php
if(!defined('_TUBEWEB_')) exit;


//일단 임시처리_추후 바인딩 처리 필요_20201024
if(strlen($boardid) > 3){
    alert("비정상적 접근입니다.");
}

?>
<style>
.video_play{
    width : 80%;
    margin:0 auto;
}
.video_play:before {
    content: '';
    transition: all 0.2s;
    position: absolute;
    opacity: 0.9;
    left: 50%;
    top: 42%;
    transform: translateX(-50%);
    background: url(/img/youtube_play_btn.png) no-repeat;
    width: 84px;
    height: 60px;
}
.video_play:hover:before{
    opacity : 0;
}
.video_play:hover:after{
    opacity : 1;
}
.video_play:after {
    content: '영상보러가기';
    position: absolute;
    top: 0;
    left: 0;
    font-size: 30px;
    color: #fff;
    font-weight: 700;
    transition: all 0.3s;
    opacity: 0;
    background-size: 30px 30px;
    background: rgba(0, 0, 0, 0.7);
    width: 100%;
    height: 100%;
    line-height: 10;
}
.video_play img{
    width : 100%;
}
</style>
<ul class="webzine">
<?php 
$sql = youtube_pt_check($pt_id,30);
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
?>
    <li style="position:relative">
        <a href='https://www.youtube.com/watch?v=<?php echo $row[v_code] ?>' class="video_play" target='_blank'>
            <img src='http://i.ytimg.com/vi/<?php echo $row[v_code] ?>/mqdefault.jpg'>
        </a>
    </li>
<?php } ?>
</ul>

