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
      ->setCellValue($char++.'1', '날짜')
      ->setCellValue($char++.'1', '주문번호')
      ->setCellValue($char++.'1', '가맹점')
      ->setCellValue($char++.'1', '아이디')
      ->setCellValue($char++.'1', '이름')
      ->setCellValue($char++.'1', '문의유형')
      ->setCellValue($char++.'1', '질문')
      ->setCellValue($char++.'1', '답변');

for($i=2; $row=sql_fetch_array($result); $i++)
{
    $pt_name=trans_pt_name($row['pt_id']);

    $char = 'A';
    $excel->setActiveSheetIndex(0)
          ->setCellValueExplicit($char++.$i, $row['wdate'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['od_id'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $pt_name, PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['catename'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['reply'], PHPExcel_Cell_DataType::TYPE_STRING);

    $excel->getActiveSheet()->getStyle("A$i:H$i")->getAlignment()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );
    $excel->getActiveSheet()->getStyle("G$i:H$i")->getAlignment()->setWrapText(true);
}

// Rename worksheet
$excel -> getActiveSheet()->setTitle('상담문의내역');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
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
    ),

    /*//테두리 설정
    'borders' => array(
     'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_THICK,
      'color' => array('argb'=>'000000')
     )
    ),*/

);
$excel->getActiveSheet()->getStyle("A1:H1")->applyFromArray($TD_COLOR);

$excel -> setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(60);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(60);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="상담문의내역-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
