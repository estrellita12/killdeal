<?php
include_once("./_common.php");

check_demo();

$pt_sql = "select group_concat(mb_id) as pt_list from shop_partner";
$pt_res = sql_query($pt_sql);
$pt_row = sql_fetch_array($pt_res);
$pt_list = explode(",",$pt_row['pt_list']);

define("_ORDERPHPExcel_", true);

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

/*
// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
      ->setCellValue($char++.'1', '가맹점')
      ->setCellValue($char++.'1', '날짜')
      ->setCellValue($char++.'1', '총 주문수')
      ->setCellValue($char++.'1', '총 주문수량')
      ->setCellValue($char++.'1', '총 매출액');

$sql = "select  date_format(od_time,'%Y-%m') as date_msg,pt_id,count(distinct od_id) as od_cnt,sum(goods_price+baesong_price) as buy_price,sum(sum_qty) as qty_cnt from shop_order where dan in ('2','3','4','5','8','12','13') group by date_msg,pt_id order by buy_price desc";
$result = sql_query($sql);

for($i=2; $row=sql_fetch_array($result); $i++)
{
    $char = 'A';
    $excel->setActiveSheetIndex(0)
          ->setCellValueExplicit($char++.$i, trans_pt_name($row['pt_id']) , PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['date_msg'], PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit($char++.$i, $row['od_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit($char++.$i, $row['qty_cnt'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
          ->setCellValueExplicit($char++.$i, $row['buy_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $excel->getActiveSheet()->getStyle("C$i:E$i")->getNumberFormat()->setFormatCode('#,##0');
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
*/

$char = 'A';
$excel->setActiveSheetIndex(0) ->setCellValue($char++.'1', "날짜");
foreach($pt_list as $pt){
    $excel->setActiveSheetIndex(0) ->setCellValue($char++.'1', trans_pt_name($pt));
}
$sql = "select date_msg ";
foreach($pt_list as $pt){
    $sql .= ", ifnull( MAX( case when pt_id='{$pt}' then buy_price end) ,0 ) as {$pt} ";
}
$sql .= "from ( select pt_id, date_format(od_time,'%Y-%m-%d') as date_msg, count(distinct od_id) as od_cnt, sum(goods_price+baesong_price) as buy_price, sum(sum_qty) as qty_cnt from shop_order where dan in ('2','3','4','5','8','12','13')  group by date_msg,pt_id order by date_msg ) as res group by date_msg;";
$result = sql_query($sql);
for($i=2; $row=sql_fetch_array($result); $i++)
{
    $char = 'A';
    $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row['date_msg'], PHPExcel_Cell_DataType::TYPE_STRING);
    foreach($pt_list as $pt){
        $excel->setActiveSheetIndex(0)->setCellValueExplicit($char++.$i, $row[$pt], PHPExcel_Cell_DataType::TYPE_NUMERIC);
    }
    $excel->getActiveSheet()->getStyle("B$i:Z$i")->getNumberFormat()->setFormatCode('#,##0');
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점별판매통계');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet

$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
$excel->getActiveSheet()->getStyle("A1:Z1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점별판매통계_'.date("ymd", time()).'.xlsx"');
//header("Content-Type:text/html;charset=utf-8");
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


?>
