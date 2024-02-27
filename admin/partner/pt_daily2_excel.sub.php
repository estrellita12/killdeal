<?php
include_once("./_common.php");

check_demo();


define("_ORDERPHPExcel_", true);

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)->mergeCells("A1:A2")->setCellValue("A1",  "날짜" ); $char++;
foreach($pt_list as $pt){
    $pre = $char++;
    $char++;
    $excel->setActiveSheetIndex(0)->mergeCells($pre."1:".$char++."1")->setCellValue($pre."1",  trans_pt_name($pt) );
    $excel->setActiveSheetIndex(0) ->setCellValue($pre++.'2', "총 주문수");
    $excel->setActiveSheetIndex(0) ->setCellValue($pre++.'2', "총 주문수량");
    $excel->setActiveSheetIndex(0) ->setCellValue($pre.'2', "총 매출액");
}

for($i=3; $row=sql_fetch_array($result); $i++)
{
    $char = 'A';
    $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row['date_msg'], PHPExcel_Cell_DataType::TYPE_STRING);
    foreach($pt_list as $pt){
        $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row[$pt."_od_cnt"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row[$pt."_qty_cnt"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row[$pt."_buy_price"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    }
    //$excel->getActiveSheet()->getStyle("B$i:".$char.$i)->getNumberFormat()->setFormatCode('#,##0');
}

$columnNumber = PHPExcel_Cell::columnIndexFromString($char) - 1;
$last = PHPExcel_Cell::stringFromColumnIndex($columnNumber - 1);
$excel->setActiveSheetIndex(0) ->setCellValue("A".$i, "총계");
for($k='B'; $k != $char; $k++)
{
    $excel->setActiveSheetIndex(0) ->setCellValue($k.$i, "=SUM(".$k."3:".$k.($i-1).")");
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점별판매통계');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet


$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
for($k='B'; $k!=$char; $k++){
    $excel->getActiveSheet()->getColumnDimension($k)->setWidth(10);
}

$excel->getActiveSheet()->getStyle("B2:".$last.$i)->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle ( "A1:A2" )->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER );
$excel->getActiveSheet()->getStyle("A1:".$last."1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$TD_COLOR = array(
    //배경색 설정
    'fill' => array(
     'type' => PHPExcel_Style_Fill::FILL_SOLID,
     'color' => array('rgb'=>'444444'),
    ),

    //테두리 설정
    'borders' => array(
     'inside' => array(
      'style' => PHPExcel_Style_Border::BORDER_THICK,
      'color' => array('argb'=>'ffffff')
     )
    ),

    //글자색 설정
    'font' => array(
     'bold' => 'false',
     'size' => '9',
     'color' => array('rgb'=>'ffffff')
    )

);
$excel->getActiveSheet()->getStyle("A1:".$last."2")->applyFromArray($TD_COLOR);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점별판매통계_'.date("ymd", time()).'.xlsx"');
//header("Content-Type:text/html;charset=utf-8");
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


?>
