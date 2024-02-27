<?php
include_once("./_common.php");

check_demo();

$sql = " select * from shop_event where (1) ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 내역이 없습니다.");

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '응모번호')
	->setCellValue($char++.'1', '이름')
	->setCellValue($char++.'1', '휴대폰번호')
	->setCellValue($char++.'1', '동의여부')
	->setCellValue($char++.'1', '인증번호')
	->setCellValue($char++.'1', '가맹점')
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '참여날짜');

for($i=2; $row=sql_fetch_array($result); $i++){

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['index_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['phone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['agree'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['inzng'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pt_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['date_time'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('이벤트 참여 리스트');
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="이벤트 참여 리스트-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>