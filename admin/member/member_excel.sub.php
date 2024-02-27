<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '회원명')
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '레벨')
	->setCellValue($char++.'1', '추천인')
	->setCellValue($char++.'1', '핸드폰')
	->setCellValue($char++.'1', '이메일')
	->setCellValue($char++.'1', '우편번호')
	->setCellValue($char++.'1', '주소')
	->setCellValue($char++.'1', '회원가입일')
	->setCellValue($char++.'1', '로그인횟수')
	->setCellValue($char++.'1', '구매횟수')
	->setCellValue($char++.'1', '총구매금액')
	->setCellValue($char++.'1', '메일수신')
	->setCellValue($char++.'1', 'SMS수신')
	->setCellValue($char++.'1', '최근IP')
	->setCellValue($char++.'1', '가입IP')
	->setCellValue($char++.'1', '포인트');

for($i=2; $row=sql_fetch_array($result); $i++)
{
    $pt_name = trans_pt_name($row['pt_id']);
    $order_cnt = shop_count($row['id']);
    $order_sum = shop_price($row['id']);
    $mb_grade = get_grade($row['grade']);
	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $mb_grade, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $pt_name, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['cellphone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['email'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['zip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, print_address($row['addr1'], $row['addr2'], $row['addr3'], $row['addr_jibeon']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['reg_time'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['login_sum'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $order_cnt, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $order_sum, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['mailser'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['smsser'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['login_ip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_ip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['point'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $excel -> getActiveSheet()->getStyle("L$i:L$i") -> getNumberFormat() -> setFormatCode("#,##0");
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('회원목록');

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
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(8);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="회원목록-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
