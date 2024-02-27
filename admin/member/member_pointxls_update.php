<?php
include_once("./_common.php");

// 자료가 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

check_demo();

check_admin_token();

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(TB_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/

    /***
    * Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*
	$data->sheets[0]['numRows'] - count rows
	$data->sheets[0]['numCols'] - count columns
	$data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

	$data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

	$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
	if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
	$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
	$data->sheets[0]['cellsInfo'][$i][$j]['colspan']
	$data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $total_count = 0; // 총회원수
    $fail_count = 0; // 실패건수
    $succ_count = 0; // 완료건수

	for($i=4; $i<=$data->sheets[0]['numRows']; $i++)
	{
		if(trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

        $j = 1;

		$mb_id		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$po_point	= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$po_content	= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));

		if($mb_id == "" || $po_point == "" ||  $po_content == "") {
            $fail_count++;
            continue;
        }

        $mb = get_member($mb_id, 'id, point');
		if(!$mb['id']) {
            $fail_count++;
            continue;
		}

		// 포인트를 차감하는 경우 현재 포인트보다 작으면 안됩니다.
		if(($po_point < 0) && ($po_point * (-1) > $mb['point'])) {
            $fail_count++;
            continue;
		}

		insert_point($mb_id, $po_point, $po_content, '@passive', $mb_id, $member['id'].'-'.uniqid(''));

		$succ_count++;
	}
}
?>

<h2>총 건수</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">총회원수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">완료건수</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">실패건수</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=pointxls" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong>로 저장하셔야 합니다.</p>
			<p>ㆍ엑셀데이터는 4번째 라인부터 저장되므로 샘플파일 설명글과 타이틀은 지우시면 안됩니다.</p>
		</div>
	 </div>
</div>

<script>
$(function() {
	// 새로고침(F5) 막기
	$(document).keydown(function (e) {
		if(e.which === 116) {
			if(typeof event == "object") {
				event.keyCode = 0;
			}
			return false;
		} else if(e.which === 82 && e.ctrlKey) {
			return false;
		}
	});
});
</script>
