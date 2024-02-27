<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '판매자ID')
	->setCellValue($char++.'1', '상품코드')
	->setCellValue($char++.'1', '상품코드2')
	->setCellValue($char++.'1', '대표분류')
	->setCellValue($char++.'1', '추가분류2')
	->setCellValue($char++.'1', '추가분류3')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '짧은설명')
	->setCellValue($char++.'1', '검색키워드')
	->setCellValue($char++.'1', '모델명')
	->setCellValue($char++.'1', '브랜드')
	->setCellValue($char++.'1', '과세설정')
	->setCellValue($char++.'1', '판매가능지역')
	->setCellValue($char++.'1', '판매가능지역 추가설명')
	->setCellValue($char++.'1', '원산지')
	->setCellValue($char++.'1', '제조사')
	->setCellValue($char++.'1', '판매여부')
	->setCellValue($char++.'1', '공급가격')
	->setCellValue($char++.'1', '시중가격')
	->setCellValue($char++.'1', '판매가격')
	->setCellValue($char++.'1', '가격대체문구')
	->setCellValue($char++.'1', '재고적용타입')
	->setCellValue($char++.'1', '재고수량')
	->setCellValue($char++.'1', '재고통보수량')
	->setCellValue($char++.'1', '최소주문한도')
	->setCellValue($char++.'1', '최대주문한도')
	->setCellValue($char++.'1', '포인트')
	->setCellValue($char++.'1', '판매기간 시작일')
	->setCellValue($char++.'1', '판매기간 종료일')
	->setCellValue($char++.'1', '구매가능레벨')
	->setCellValue($char++.'1', '가격공개')
	->setCellValue($char++.'1', '배송비유형')
	->setCellValue($char++.'1', '배송비결제')
	->setCellValue($char++.'1', '기본배송비')
	->setCellValue($char++.'1', '조건배송비')
	->setCellValue($char++.'1', '이미지등록방식')
	->setCellValue($char++.'1', '소이미지')
	->setCellValue($char++.'1', '중이미지1')
	->setCellValue($char++.'1', '중이미지2')
	->setCellValue($char++.'1', '중이미지3')
	->setCellValue($char++.'1', '중이미지4')
	->setCellValue($char++.'1', '중이미지5')
	->setCellValue($char++.'1', '상세설명')
	->setCellValue($char++.'1', '관리자메모');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	if(is_null_time($row['sb_date'])) $row['sb_date'] = '';
	if(is_null_time($row['eb_date'])) $row['eb_date'] = '';

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gcode'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['index_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['ca_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['ca_id2'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['ca_id3'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['explan'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['keywords'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['model'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['brand_nm'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['notax'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['zone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['zone_msg'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['origin'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['maker'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['isopen'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['supply_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['normal_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['price_msg'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['stock_mod'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['stock_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['noti_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['odr_min'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['odr_max'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gpoint'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sb_date'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['eb_date'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['buy_level'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['buy_only'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_type'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_method'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_amt'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_minimum'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['simg_type'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['simg1'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg2'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg3'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg4'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg5'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg6'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['admin_memo'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('공급업체 대기상품');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="공급업체 대기상품-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
