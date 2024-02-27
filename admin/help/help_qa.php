<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_qa ";
$sql_search = " where (1) ";
$sql_order  = " order by index_no desc ";

if($sfl && $stx) {
    if($sfl == 'total'){ // (2021-02-19) 제목+내용 검색 추가
        $sql_search .= " and ( subject like '%$stx%' or  memo like '%$stx%' or reply like '%$stx%' ) ";
    }else{
        $sql_search .= " and $sfl like '%$stx%' ";
    }
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="#" id="frmHelpExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
EOF;
?>
<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get" onsubmit="return submitSearch()">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
    <table>
    <colgroup>
        <col class="w100">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">검색어</th>
        <td>
            <select name="sfl" id='sfl' onchange="selectInputChange();">
                <?php echo option_selected('mb_id', $sfl, '작성ID'); ?>
                <?php echo option_selected('name', $sfl, '작성자'); ?>
                <?php echo option_selected('subject', $sfl, '제목'); ?>
                <?php echo option_selected('memo', $sfl, '내용'); ?>
                <?php echo option_selected('total', $sfl, ' 제목+내용'); ?>
                <?php echo option_selected('catename', $sfl, '문의유형'); ?>
            </select>
            <!-- (2021-02-02) 질문유형 select 항목 추가 -->
            <input type="hidden" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
            <span id="input_box" class="<?php if($sfl=='catename'){echo "dn";}?> ">
                <input type="text" value="<?php echo $stx; ?>" class="frm_input" size="30">
            </span>
            <span id="select_box" class="<?php if($sfl!='catename'){echo "dn";}?> ">
                <select>
                    <option value="">문의하실 유형을 선택하세요</option>
<?php
$sql = "select * from shop_qa_cate where isuse='Y'";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
    echo "<option value='$row[catename]'>$row[catename]</option>\n";
}
?>
                </select>
            </span>
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

<form name="fqalist" id="fqalist" method="post" action="./help/help_qa_delete.php" onsubmit="return fqalist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
    <?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
    <table>
    <colgroup>
        <col class="w50">
        <col class="w50">
        <col class="w150">
        <col>
        <col class="w120">
        <col class="w120">
        <col class="w110">
        <col class="w60">
        <col class="w60">
    </colgroup>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
        <th scope="col">번호</th>
        <!-- <th scope="col">문의유형</th> -->
        <th scope="col">주문번호</th>
        <th scope="col">문의유형</th>
        <th scope="col">가맹점</th>
        <th scope="col">작성자</th>
        <th scope="col">작성일</th>
        <th scope="col">답변여부</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
    $s_upd = "<a href=\"./help.php?code=qa_form&w=u&index_no={$row['index_no']}$qstr&page=$page\" class=\"btn_small\">수정</a>";		
    $iq_name = get_sideview($row['mb_id'], $row['name']);

    if($i==0)
        echo '<tbody class="list">'.PHP_EOL;

    $bg = 'list'.($i%2);
?>
    <tr class="<?php echo $bg; ?>">
        <td>
            <input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
        </td>	
        <td><?php echo $num--; ?></td>
        <!-- <td><b><?php echo $row['catename']; ?></b></td> -->
        <!-- (2021-02-02) 주문번호 셀 추가 -->
        <td><a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a> </td>
        <td class="tal">
            <!-- <p><?php echo cut_str($row['subject'],70); ?></p> -->
            <p><?php echo $row['catename']; ?></p>
            <div class="dn" style="background-color:#fdfbf5">
                <table>
                    <colgroup>
                        <col class="w50">
                        <col>
                    </colgroup>

                    <tr>
                        <td class="fs11">질문</td>
                        <td class="fs11" style="text-align:left">
                        <?php
                            $qimg = TB_DATA_PATH.'/qna/'.$row['qna_file'];
                            if(is_file($qimg) && $row['qna_file']) {
                                $size = @getimagesize($qimg);
                                $width = 400;
                                $qimg = rpc($qimg, TB_PATH, TB_URL);
                                echo '<img src="'.$qimg.'" width="'.$width.'"><br>';
                            }
                            echo nl2br($row['memo']); 
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs11">답변</td>
                        <td class="fs11" style="text-align:left"><?php 
                           $rimg = TB_DATA_PATH.'/reply/'.$row['reply_file'];
                            if(is_file($rimg) && $row['reply_file']) {
                                $size = @getimagesize($rimg);
                                $width = 100;
                                $rimg = rpc($rimg, TB_PATH, TB_URL);
                                echo '<img src="'.$rimg.'" width="'.$width.'"><br>';
                            }
                            echo nl2br($row['reply']); ?></td>
                </table>
            </div>
        </td>
        <!-- (2021-02-02) 가맹점 셀 추가 -->
        <td><p><?php echo trans_pt_name($row['pt_id']); ?></p></span></td>
        <td><p><?php echo $iq_name; ?></p><span style="color:#888;">(<?php echo $row['mb_id']; ?>)</span></td>
        <td><?php echo substr($row['wdate'],0,10); ?></td>
        <td><?php echo $row['result_yes']?'YES':'<span style="font-weight:bold">NO</span>'; ?></td>
        <td><?php echo $s_upd; ?></td>
    </tr>
<?php 
}
if($i==0)
    echo '<tbody><tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
?>
    </tbody>
    </table>
</div>
<div class="local_frm02">
    <?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
$(function(){
    $("#frmHelpExcel").on("click", function() {
        var type = $(this).attr("id");
        var help_chk = new Array();
        var help_no = "";
        var $el_chk = $("input[name='chk[]']");

        $el_chk.each(function(index) {
            if($(this).is(":checked")) {
                help_chk.push($("input[name='index_no["+index+"]']").val());
            }
        });

        if(help_chk.length > 0) {
            help_no = help_chk.join();
        }
        if(help_no == "") {
            alert("처리할 자료를 하나 이상 선택해 주십시오.");
            return false;
        }else {
            this.href = "./help/help_excel.php?help_no="+help_no;
            return true;
        }
    });

    $('.tal').click( function() {
        $(this).find('.dn').toggle(100);
    });

});


function fqalist_submit(f)
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

// (2021-02-02) 질문유형 select 추가
function selectInputChange(){
    var data = $('#sfl option:selected').val();
    if(data=='catename'){
        $('#select_box').removeClass('dn');
        $('#input_box').addClass('dn');
    }else{
        $('#input_box').removeClass('dn');
        $('#select_box').addClass('dn');
    }
}
function submitSearch(){
    var data1 = $('#input_box input').val();
    var data2 = $('#select_box option:selected').val();
    if( data1!="" ){
        $('input[name=stx]').val( data1 );
    }
    if( data2!="" ){
        $('input[name=stx]').val( data2 );
    }
}


</script>
