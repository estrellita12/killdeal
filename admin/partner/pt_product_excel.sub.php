<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if(!defined("_ORDERPHPExcel_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '가맹점ID')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '조회수')
	->setCellValue($char++.'1', '판매가')
	->setCellValue($char++.'1', '판매수량')
	->setCellValue($char++.'1', '판매총액');


for($i=2; $row=sql_fetch_array($result); $i++)
{

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['pt_id'], PHPExcel_Cell_DataType::TYPE_STRING) //가맹점 ID
		->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING) //상품명 
		->setCellValueExplicit($char++.$i, $row['readcount'], PHPExcel_Cell_DataType::TYPE_STRING) //조회수 
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //판매가
		->setCellValueExplicit($char++.$i, $row['sum_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC) // 판매수량
		->setCellValueExplicit($char++.$i, $row['goods_price_sum'], PHPExcel_Cell_DataType::TYPE_NUMERIC); //판매 총액
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('상품별판매통계');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);


$excel->getActiveSheet()->getStyle("A1:L1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="상품별판매통계_'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>