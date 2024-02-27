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
	->setCellValue($char++.'1', '상품코드')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '진열')
	->setCellValue($char++.'1', '소비자가')
	->setCellValue($char++.'1', '판매가')
	->setCellValue($char++.'1', '전체조회수')
	->setCellValue($char++.'1', '총주문건')
	->setCellValue($char++.'1', '총주문수량')	
	->setCellValue($char++.'1', '판매수량')
	->setCellValue($char++.'1', '취소수량')
	->setCellValue($char++.'1', '환불수량')
	->setCellValue($char++.'1', '반품수량')
	->setCellValue($char++.'1', '교환수량')
	->setCellValue($char++.'1', '판매총액');


for($i=2; $row=sql_fetch_array($result); $i++)
{
	//$gs = unserialize($row['od_goods']);
	//$amount = get_order_spay($row['od_id']);
	//$sodr = excel_order_list($row, $amount);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['index_no'], PHPExcel_Cell_DataType::TYPE_STRING) //상품코드
		->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING) //상품명
		->setCellValueExplicit($char++.$i, $gw_isopen[$row['isopen']], PHPExcel_Cell_DataType::TYPE_STRING) //진열
		->setCellValueExplicit($char++.$i, $row['normal_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //소비자가
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //판매가
		->setCellValueExplicit($char++.$i, $row['readcount'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //전체조회수
		->setCellValueExplicit($char++.$i, $row['total_od_id'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //총주문건
		->setCellValueExplicit($char++.$i, $row['total_sum_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //총주문수량
		->setCellValueExplicit($char++.$i, $row['buy_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //판매수량
		->setCellValueExplicit($char++.$i, $row['cancel_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //취소수량
		->setCellValueExplicit($char++.$i, $row['refund_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //환불수량
		->setCellValueExplicit($char++.$i, $row['return_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //반품수량
		->setCellValueExplicit($char++.$i, $row['change_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) //교환수량
		->setCellValueExplicit($char++.$i, $row['total_use_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC); //판매총액
    $excel->getActiveSheet()->getStyle("D$i:N$i")->getNumberFormat()->setFormatCode('#,##0');
}

// Rename worksheet
$excel->getActiveSheet()->setTitle(' 상품별 판매 통계');

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
$excel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($TD_COLOR);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);

$excel->getActiveSheet()->getStyle("A1:N1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="상품별판매통계-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
