<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '상품번호')
	->setCellValue($char++.'1', '상품코드')
	->setCellValue($char++.'1', '업체코드')
	->setCellValue($char++.'1', '대표분류')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '모델명')
	->setCellValue($char++.'1', '브랜드')
	->setCellValue($char++.'1', '제조사')
	->setCellValue($char++.'1', '판매여부')
	->setCellValue($char++.'1', '공급가격')
	->setCellValue($char++.'1', '시중가격')
	->setCellValue($char++.'1', '판매가격')
	->setCellValue($char++.'1', '재고수량')
	->setCellValue($char++.'1', '옵션합재고수량')
	->setCellValue($char++.'1', '판매기간 시작일')
	->setCellValue($char++.'1', '판매기간 종료일');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	if(is_null_time($row['sb_date'])) $row['sb_date'] = '';
	if(is_null_time($row['eb_date'])) $row['eb_date'] = '';

    $io_sum = get_io_stock_sum($row['index_no']);
	$char = 'A';

	$excel->setActiveSheetIndex(0)
	->setCellValueExplicit($char++.$i, $row['index_no'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gcode'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['ca_id'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['model'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['brand_uid'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['maker'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['isopen'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['supply_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['normal_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['stock_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $io_sum, PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['sb_date'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['eb_date'], PHPExcel_Cell_DataType::TYPE_STRING);

    $excel -> getActiveSheet()->getStyle("J$i:L$i") -> getNumberFormat() -> setFormatCode("#,##0");

}

// Rename worksheet
$excel->getActiveSheet()->setTitle('상품재고관리');

$TD_COLOR = array(
    //배경색 설정
    'fill' => array(
     'type' => PHPExcel_Style_Fill::FILL_SOLID,
     'color' => array('rgb'=>'444444'),
    ),

    //글자색 설정
    'font' => array(
     'bold' => 'true',
     'size' => '10',
     'color' => array('rgb'=>'ffffff')
    )

);
$excel->getActiveSheet()->getStyle("A1:P1")->applyFromArray($TD_COLOR);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="상품재고관리-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
