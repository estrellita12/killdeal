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
      ->setCellValue($char++.'1', '가맹점')
      ->setCellValue($char++.'1', '날짜')
      ->setCellValue($char++.'1', '총 주문수')
      ->setCellValue($char++.'1', '총 주문수량')
      ->setCellValue($char++.'1', '총 매출액');

for($i=2; $row=sql_fetch_array($result); $i++)
{
    if(isset($pt_list)){
        if ( ($key = array_search($row['pt_id'], $pt_list) ) !== false) {
            unset($pt_list[$key]);
        }
    }
    $char = 'A';
    $excel->setActiveSheetIndex(0)
          ->setCellValueExplicit($char++.$i, trans_pt_name($row['pt_id']) , PHPExcel_Cell_DataType::TYPE_STRING) 
          ->setCellValueExplicit($char++.$i, $row['date_msg'], PHPExcel_Cell_DataType::TYPE_STRING) 
          ->setCellValueExplicit($char++.$i, $row['od_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC) 
          ->setCellValueExplicit($char++.$i, $row['qty_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC)  
          ->setCellValueExplicit($char++.$i, $row['buy_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $excel->getActiveSheet()->getStyle("C$i:E$i")->getNumberFormat()->setFormatCode('#,##0');
}
if(isset($pt_list)){
    foreach($pt_list as $x){
        $char = 'A';
        $excel->setActiveSheetIndex(0)
              ->setCellValueExplicit($char++.$i, trans_pt_name($x) , PHPExcel_Cell_DataType::TYPE_STRING) 
              ->setCellValueExplicit($char++.$i, $date_msg, PHPExcel_Cell_DataType::TYPE_STRING) 
              ->setCellValueExplicit($char++.$i, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC) 
              ->setCellValueExplicit($char++.$i, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC)  
              ->setCellValueExplicit($char++.$i, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $i++;
    }
}
// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점별판매통계');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet

$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getStyle("A1:L1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점별판매통계_'.date("ymd", time()).'.xlsx"');
//header("Content-Type:text/html;charset=utf-8");
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

?>
