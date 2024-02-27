<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_order ";
$sql_search = " where dan != 0 and pt_id = '{$member['id']}' ";

$card_total = 0;
$trans_total = 0;
$gasanag_total = 0;
$other_total = 0;

if($sfl && $stx)
	$sql_search .= " and $sfl like '%$stx%' ";

if($od_settle_case)
	$sql_search .= " and paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
	$sql_search .= " and dan = '$od_status' ";

if($fr_date && $to_date)
    $sql_search .= " and left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left({$sel_field},10) between '$to_date' and '$to_date' ";

$sql_order = " order by od_time desc, index_no asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '가맹점ID')
	->setCellValue($char++.'1', '회원ID')
	->setCellValue($char++.'1', '주문채널')
	->setCellValue($char++.'1', '주문번호')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '옵션')
	->setCellValue($char++.'1', '수량')
	->setCellValue($char++.'1', '상품금액')
	->setCellValue($char++.'1', '쿠폰할인')
	->setCellValue($char++.'1', '포인트')
	->setCellValue($char++.'1', '배송비')
	->setCellValue($char++.'1', '실결제금액')
	->setCellValue($char++.'1', '총주문금액')
	->setCellValue($char++.'1', '결제방법')
	->setCellValue($char++.'1', '주문일시')
	->setCellValue($char++.'1', '최종처리일시')
	->setCellValue($char++.'1', '주문상태')
	->setCellValue($char++.'1', '주문자명')
	->setCellValue($char++.'1', '주문자핸드폰')
	->setCellValue($char++.'1', '수취인')
	->setCellValue($char++.'1', '수취인핸드폰');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$gs = unserialize($row['od_goods']);
	$amount = get_order_spay($row['od_id']);
	$sodr = excel_order_list($row, $amount);
    
	$de = $row['delivery'].$row['delivery_no'];

	$psql = " select pp_pay
				from shop_partner_pay
			   where pp_rel_table = 'sale'
				 and pp_rel_id = '{$row['od_no']}'
				 and pp_rel_action = '{$row['od_id']}'
				 and mb_id = '{$row['pt_id']}' ";
	$psum = sql_fetch($psql);
	$pp_pay = (int)$psum['pp_pay'];

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $sodr['od_pt_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_mobile'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_id'].$sodr['od_test'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gs['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['it_options'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['sum_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['coupon_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['use_point'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['baesong_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['use_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price']+$row['baesong_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $sodr['od_paytype'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_time'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['rcent_time'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gw_status[$row['dan']], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['cellphone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_cellphone'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel -> getActiveSheet()->getStyle("G$i:M$i") -> getNumberFormat() -> setFormatCode("#,##0");
}

$last = $i;

$excel->setActiveSheetIndex(0)		
    ->setCellValueExplicit('X2', '총합', PHPExcel_Cell_DataType::TYPE_STRING)
    ->setCellValueExplicit('X3', '신용카드', PHPExcel_Cell_DataType::TYPE_STRING)
    ->setCellValueExplicit('X4', '계좌이체', PHPExcel_Cell_DataType::TYPE_STRING)
    ->setCellValueExplicit('X5', '가상계좌', PHPExcel_Cell_DataType::TYPE_STRING);

$excel->getActiveSheet()->setCellValue('Y2', '=SUM(L2:L'.($last-1).')');
$excel->getActiveSheet()->setCellValue('Y3', '=SUMIF(N2:N'.($last-1).',"신용카드*",L2:L'.($last-1).')');
$excel->getActiveSheet()->setCellValue('Y4', '=SUMIF(N2:N'.($last-1).',"계좌이체*",L2:L'.($last-1).')');
$excel->getActiveSheet()->setCellValue('Y5', '=SUMIF(N2:N'.($last-1).',"가상계좌*",L2:L'.($last-1).')');

$excel -> setActiveSheetIndex(0);
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(61);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(27);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(19);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(19);
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('R')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('S')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('T')->setWidth(9);
$excel->getActiveSheet()->getColumnDimension('U')->setWidth(9);

// Rename worksheet
$excel->getActiveSheet()->setTitle('주문리스트');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="주문리스트-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
