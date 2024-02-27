<?php
if(!defined('_TUBEWEB_')) exit;

//일단 임시처리_추후 바인딩 처리 필요_20201024
if(strlen($boardid) > 3){
    alert("비정상적 접근입니다.");
}
?>

<style>
.m_bo_bg .list{
    width : calc(50% - 20px);   
}

.m_bo_bg .list img{
    max-width : 100%;
    max-height : 300px;
}
.video_play{
    margin:0 auto;
}

</style>
<div class="m_bo_bg">
<ul class="webzine">
<?php 
$sql = youtube_pt_check($pt_id,30);
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
?>
    <li class="list" style="position:relative;float:left">
        <a href='https://www.youtube.com/watch?v=<?php echo $row[v_code] ?>' class="video_play" target='_blank'>
            <img src='http://i.ytimg.com/vi/<?php echo $row[v_code] ?>/mqdefault.jpg'>
        </a>
    </li>
<?php } ?>
</ul>
</div>
