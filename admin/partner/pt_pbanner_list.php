<?php
if(!defined('_TUBEWEB_')) exit;

$qstr = "&mb_id=$mb_id";
$qstr .= "&bn_device=$bn_device";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q1 = $query_string."&page=$page";


$mb = get_member($mb_id);
$sql_common = " from shop_banner ";
$sql_search = " where bn_device = '{$bn_device}' and bn_theme = '{$mb['theme']}' and mb_id = '{$mb_id}' and bn_code = 0"; // bn_code = 0은 메인 슬라이드
$sql_order = "order by bn_id desc";

// 테이블의 전체 레코드수만 얻음
$total_count = sel_count("shop_banner",$sql_search);

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);


$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner.php?code=pbanner_form&$qstr&page=$page" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;

?>

<h2>가맹점 선택</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
    <table>
    <colgroup>
        <col class="w100">
        <col class="w100">
        <col class="w100">
        <col>
    </colgroup>
    <tbody>
        <tr>
            <td>가맹점명</td>
            <td>
                <select name="mb_id">
                <?php 
                    $row = get_partner_list();
                    foreach($row as $id){
                        echo option_selected($id, $mb_id, trans_pt_name($id));
                    }   
                ?>
            </select>
        <td>
        <td>
            <?php echo radio_checked('bn_device', $bn_device, 'pc', 'PC'); ?>
            <?php echo radio_checked('bn_device', $bn_device, 'mobile', 'Mobile'); ?>
        </td>
    </tr>
    </tbody>
    </table>
</div>
<div class="btn_confirm">
    <input type="submit" value="검색" class="btn_medium">
    <input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fbannerlist" id="fbannerlist" method="post" action="./partner/pt_pbanner_list_update.php" onsubmit="return fbannerlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="bn_device" value="<?php echo $bn_device; ?>">

<h2>[롤링] <?php echo trans_pt_name($mb_id)." ( ". $bn_device." )"; ?> 메인 슬라이드 배너목록</h2>
<div class="local_ov">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<span class="ov_a fc_red">순서는 숫자가 작을수록 우선 순위로 노출됩니다</span>
</div>
<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table class="tablef">
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w60">
		<col>
		<col>
		<col class="w80">
		<col class="w80">
		<col class="w80">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2">코드</th>
		<th scope="col" rowspan="2">노출</th>
		<th scope="col" rowspan="2">순서</th>
		<th scope="col">노출위치</th>
		<th scope="col">링크주소</th>	
		<th scope="col">TARGET</th>	
		<th scope="col">가로사이즈</th>	
		<th scope="col">세로사이즈</th>
		<th scope="col">관리</th>
	</tr>
	<tr class="rows">
		<th scope="col" colspan="6">이미지</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bn_id = $row['bn_id'];		

		$position = $gw_pbanner[$row['bn_theme']];
		foreach($position as $key=>$value) {
			list($pos, $wpx, $hpx, $subj) = $value;
			if($pos == $row['bn_code']) break;
		}

		$bimg_str = '';
		$bimg = TB_DATA_PATH."/banner/{$row['bn_file']}";
		if(is_file($bimg) && $row['bn_file']) {
			$size = @getimagesize($bimg);
			if($size[0] && $size[0] > 700)
				$width = 700;
			else
				$width = $size[0];

			$bimg = rpc($bimg, TB_PATH, TB_URL);
			$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
		}

		$s_upd = "<a href='./partner.php?code=pbanner_form&w=u&bn_id=$bn_id$qstr&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">			
			<input type="hidden" name="bn_id[<?php echo $i; ?>]" value="<?php echo $bn_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2"><?php echo $row['bn_code']; ?></td>
		<td rowspan="2"><input type="checkbox" name="bn_use[<?php echo $i; ?>]" value="1" <?php echo get_checked($row['bn_use'],"1"); ?>></td>
		<td rowspan="2"><input type="text" name="bn_order[<?php echo $i; ?>]" value="<?php echo $row['bn_order']; ?>" class="frm_input"></td>
		<td class="tal"><?php echo $subj; ?></td>	
		<td><input type="text" name="bn_link[<?php echo $i; ?>]" value="<?php echo $row['bn_link']; ?>" placeholder="URL" class="frm_input"></td>
		<td>
			<select name="bn_target[<?php echo $i; ?>]">
				<?php echo option_selected('_self', $row['bn_target'], "현재창"); ?>
				<?php echo option_selected('_blank', $row['bn_target'], "새창"); ?>
			</select>
		</td>	
		<td><input type="text" name="bn_width[<?php echo $i; ?>]" value="<?php echo $row['bn_width']; ?>" class="frm_input"></td>
		<td><input type="text" name="bn_height[<?php echo $i; ?>]" value="<?php echo $row['bn_height']; ?>" class="frm_input"></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td class="td_img_view sbn_img" colspan="6">
			<div class="sbn_image"><?php echo $bimg_str; ?></div>
			<button type="button" class="btn_lsmall bx-blue sbn_img_view">이미지보기</button>
			<button type="button" class="btn_lsmall bx-yellow sbn_all_view">모두보기</button>
			<button type="button" class="btn_lsmall bx-yellow sbn_all_close">모두닫기</button>
		</td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function fbannerlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
    $(".sbn_img_view").click(function(){
        var $con = $(this).closest(".td_img_view").find(".sbn_image");
        if($con.is(":visible")) {
            $con.slideUp("fast");
            $(this).text("이미지보기");
        } else {
            $con.slideDown("fast");
            $(this).text("이미지닫기");
        }
    });

	// 모두보기
    $(".sbn_all_view").click(function(){
        $(".sbn_image").slideDown("fast");
		$(".sbn_img_view").text("이미지닫기");
    });
	
	// 모두닫기
    $(".sbn_all_close").click(function(){
        $(".sbn_image").slideUp("fast");
		$(".sbn_img_view").text("이미지보기");
    });
});
</script>

