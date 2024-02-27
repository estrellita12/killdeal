<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if(!defined("_ORDERPHPExcel_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// (2021-01-06) 컴마표시

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '가맹점ID')
	->setCellValue($char++.'1', '주문번호')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '수량')
	->setCellValue($char++.'1', '판매총액')
	->setCellValue($char++.'1', '포인트')
	->setCellValue($char++.'1', '실결제금액')
	->setCellValue($char++.'1', '총주문금액')
	->setCellValue($char++.'1', '결제방법')	
	->setCellValue($char++.'1', '주문자명')
	->setCellValue($char++.'1', '주문채널')
	->setCellValue($char++.'1', '주문일시')
	->setCellValue($char++.'1', '주문상태')
	->setCellValue($char++.'1', '최종처리일')
	->setCellValue($char++.'1', '정산처리');


for($i=2; $row=sql_fetch_array($result); $i++)
{
	$gs = unserialize($row['od_goods']);
	$amount = get_order_spay($row['od_id']);
	$sodr = excel_order_list($row, $amount);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $sodr['od_pt_id'], PHPExcel_Cell_DataType::TYPE_STRING) //가맹점 ID
		->setCellValueExplicit($char++.$i, $row['od_id'].$sodr['od_test'], PHPExcel_Cell_DataType::TYPE_STRING) //주문번호
		->setCellValueExplicit($char++.$i, $gs['gname'], PHPExcel_Cell_DataType::TYPE_STRING) //상품명 
		->setCellValueExplicit($char++.$i, $row['sum_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //수량
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //판매가
		->setCellValueExplicit($char++.$i, ($row['use_point']+$row['use_point2']), PHPExcel_Cell_DataType::TYPE_NUMERIC) //포인트
		->setCellValueExplicit($char++.$i, $row['use_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //실결제
		->setCellValueExplicit($char++.$i, $row['goods_price']+$row['baesong_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //총주문금액
		->setCellValueExplicit($char++.$i, $sodr['od_paytype'], PHPExcel_Cell_DataType::TYPE_STRING) //결제 방법
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING) //주문자명 
		->setCellValueExplicit($char++.$i, $sodr['od_mobile'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_time'], PHPExcel_Cell_DataType::TYPE_STRING) //주문일
		->setCellValueExplicit($char++.$i, $gw_status[$row['dan']], PHPExcel_Cell_DataType::TYPE_STRING) //주문상태
		->setCellValueExplicit($char++.$i, $row['rcent_time'], PHPExcel_Cell_DataType::TYPE_STRING) //최종처리일
		->setCellValueExplicit($char++.$i, $row['calculate'], PHPExcel_Cell_DataType::TYPE_STRING); //정산 처리
    $excel->getActiveSheet()->getStyle("D$i:H$i")->getNumberFormat()->setFormatCode('#,##0');
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('정산리스트');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(21);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(71);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(19);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(9);


$excel->getActiveSheet()->getStyle("A1:L1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="정산리스트-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
