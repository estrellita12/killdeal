<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '가맹점명')
	->setCellValue($char++.'1', '가맹점ID')
	->setCellValue($char++.'1', '레벨')
	->setCellValue($char++.'1', '홈페이지')
	->setCellValue($char++.'1', '만료일')
	->setCellValue($char++.'1', '현재잔액')
	->setCellValue($char++.'1', '총적립액')
	->setCellValue($char++.'1', '총차감액')
	->setCellValue($char++.'1', '판매')
	->setCellValue($char++.'1', '추천')
	->setCellValue($char++.'1', '접속')
	->setCellValue($char++.'1', '본사')
    ->setCellValue($char++.'1', '세액공제')
    ->setCellValue($char++.'1', '실수령액')
    ->setCellValue($char++.'1', '은행명')
    ->setCellValue($char++.'1', '계좌번호')
    ->setCellValue($char++.'1', '예금주명');


for($i=2; $row=sql_fetch_array($result); $i++)
{
	$expire_date = '무제한';

	// 관리비를 사용중인가?
	if($config['pf_expire_use']) {			
		if($row['term_date'] < TB_TIME_YMD)
			$expire_date = '만료'.substr(conv_number($row['term_date']), 2);
		else
			$expire_date = $row['term_date'];
	}

	$info  = get_pay_sheet($row['id']); // 누적
	$sale  = get_pay_status($row['id'], 'sale'); // 판매
	$anew  = get_pay_status($row['id'], 'anew'); // 추천
	$visit = get_pay_status($row['id'], 'visit'); // 접속
	$admin = get_pay_status($row['id'], 'passive'); // 본사

    $paytax = 0;
    if($config['pf_payment_tax']) { // 세액공제
        $paytax = floor(($row['balance'] * $config['pf_payment_tax']) / 100);
    }

    $paynet = $row['balance'] - $paytax;

    $pt = get_partner($row['id'], 'bank_name, bank_account, bank_holder');


	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, get_grade($row['grade']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['homepage'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $expire_date, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $info['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $info['usepay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $sale['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $anew['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $visit['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $admin['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit($char++.$i, $paytax, PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit($char++.$i, $paynet, PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit($char++.$i, $pt['bank_name'], PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit($char++.$i, $pt['bank_account'], PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit($char++.$i, $pt['bank_holder'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel -> getActiveSheet()->getStyle("F$i:N$i") -> getNumberFormat() -> setFormatCode("#,##0");
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점수수료');

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
$excel->getActiveSheet()->getStyle("A1:Q1")->applyFromArray($TD_COLOR);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);




// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점수수료-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
