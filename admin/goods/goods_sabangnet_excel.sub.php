<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가


/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
      ->setCellValue($char++.'1', '상품명'.chr(10).'[필수]')
      ->setCellValue($char++.'1', '상품약어')
      ->setCellValue($char++.'1', '모델명')
      ->setCellValue($char++.'1', '모델No')
      ->setCellValue($char++.'1', '브랜드명')
      ->setCellValue($char++.'1', '자체상품코드')
      ->setCellValue($char++.'1', '사이트검색어')
      ->setCellValue($char++.'1', '상품구분'.chr(10).'[필수]')
      ->setCellValue($char++.'1', '카테고리')
      ->setCellValue($char++.'1', '매임처ID')
      ->setCellValue($char++.'1', '물류처ID')
      ->setCellValue($char++.'1', '제조사')
      ->setCellValue($char++.'1', '원산지(제조국)')
      ->setCellValue($char++.'1', '생산연도')
      ->setCellValue($char++.'1', '제조일')
      ->setCellValue($char++.'1', '시즌')
      ->setCellValue($char++.'1', '남녀구분')
      ->setCellValue($char++.'1', '상품상태')
      ->setCellValue($char++.'1', '판매지역')
      ->setCellValue($char++.'1', '세금구분')
      ->setCellValue($char++.'1', '배송비구분')
      ->setCellValue($char++.'1', '배송비')
      ->setCellValue($char++.'1', '반품지구분')
      ->setCellValue($char++.'1', '원가')
      ->setCellValue($char++.'1', '판매가')
      ->setCellValue($char++.'1', 'TAG가')
      ->setCellValue($char++.'1', '옵션제목(1)')
      ->setCellValue($char++.'1', '옵션상세명칭(1)')
      ->setCellValue($char++.'1', '옵션제목(2)')
      ->setCellValue($char++.'1', '옵션상세명칭(2)')
      ->setCellValue($char++.'1', '대표이미지')
      ->setCellValue($char++.'1', '종합몰(JPG)이미지')
      ->setCellValue($char++.'1', '부가이미지2')
      ->setCellValue($char++.'1', '부가이미지3')
      ->setCellValue($char++.'1', '부가이미지4')
      ->setCellValue($char++.'1', '부가이미지5')
      ->setCellValue($char++.'1', '부가이미지6')
      ->setCellValue($char++.'1', '부가이미지7')
      ->setCellValue($char++.'1', '부가이미지8')
      ->setCellValue($char++.'1', '부가이미지9')
      ->setCellValue($char++.'1', '부가이미지10')
      ->setCellValue($char++.'1', '상품상세설명')
      ->setCellValue($char++.'1', '추가상품그룹코드')
      ->setCellValue($char++.'1', '인증번호')
      ->setCellValue($char++.'1', '인증유효시작일')
      ->setCellValue($char++.'1', '인증유효마지막일')
      ->setCellValue($char++.'1', '발급일자')
      ->setCellValue($char++.'1', '인증일자')
      ->setCellValue($char++.'1', '인증기관')
      ->setCellValue($char++.'1', '인증분야')
      ->setCellValue($char++.'1', '재고관리사용여부')
      ->setCellValue($char++.'1', '유효일')
      ->setCellValue($char++.'1', '식품 재료/원산지')
      ->setCellValue($char++.'1', '원가2')
      ->setCellValue($char++.'1', '부가이미지11')
      ->setCellValue($char++.'1', '부가이미지12')
      ->setCellValue($char++.'1', '부가이미지13')
      ->setCellValue($char++.'1', '합포시 제외 여부')
      ->setCellValue($char++.'1', '부가이미지14')
      ->setCellValue($char++.'1', '부가이미지15')
      ->setCellValue($char++.'1', '부가이미지16')
      ->setCellValue($char++.'1', '부가이미지17')
      ->setCellValue($char++.'1', '부가이미지18')
      ->setCellValue($char++.'1', '부가이미지19')
      ->setCellValue($char++.'1', '부가이미지20')
      ->setCellValue($char++.'1', '부가이미지21')
      ->setCellValue($char++.'1', '부가이미지22')
      ->setCellValue($char++.'1', '관리자메모')
      ->setCellValue($char++.'1', '옵션수정여부')
      ->setCellValue($char++.'1', '영문 상품명')
      ->setCellValue($char++.'1', '출력 상품명')
      ->setCellValue($char++.'1', '인증서이미지')
      ->setCellValue($char++.'1', '추가 상품상세설명_1')
      ->setCellValue($char++.'1', '추가 상품상세설명_2')
      ->setCellValue($char++.'1', '추가 상품상세설명_3')
      ->setCellValue($char++.'1', '원산지 상세지역')
      ->setCellValue($char++.'1', '수입신고번호')
      ->setCellValue($char++.'1', '수입면장이미지')
      ->setCellValue($char++.'1', '속성분류코드')
      ->setCellValue($char++.'1', '속성값1')
      ->setCellValue($char++.'1', '속성값2')
      ->setCellValue($char++.'1', '속성값3')
      ->setCellValue($char++.'1', '속성값4')
      ->setCellValue($char++.'1', '속성값5')
      ->setCellValue($char++.'1', '속성값6')
      ->setCellValue($char++.'1', '속성값7')
      ->setCellValue($char++.'1', '속성값8')
      ->setCellValue($char++.'1', '속성값9')
      ->setCellValue($char++.'1', '속성값10')
      ->setCellValue($char++.'1', '속성값11')
      ->setCellValue($char++.'1', '속성값12')
      ->setCellValue($char++.'1', '속성값13')
      ->setCellValue($char++.'1', '속성값14')
      ->setCellValue($char++.'1', '속성값15')
      ->setCellValue($char++.'1', '속성값16')
      ->setCellValue($char++.'1', '속성값17')
      ->setCellValue($char++.'1', '속성값18')
      ->setCellValue($char++.'1', '속성값19')
      ->setCellValue($char++.'1', '속성값20')
      ->setCellValue($char++.'1', '속성값21')
      ->setCellValue($char++.'1', '속성값22')
      ->setCellValue($char++.'1', '속성값23')
      ->setCellValue($char++.'1', '속성값24')
      ->setCellValue($char++.'1', '속성값25')
      ->setCellValue($char++.'1', '속성값26')
      ->setCellValue($char++.'1', '속성값27')
      ->setCellValue($char++.'1', '속성값28');


//시트 생성 및 이름 지정 
$excel->createSheet();  

$char = 'A';
$excel->setActiveSheetIndex(1)
      ->setCellValue($char++.'1', '사방넷상품코드'.chr(10).'[수정불가]')
      ->setCellValue($char++.'1', '바코드')
      ->setCellValue($char++.'1', '상품명'.chr(10).'[수정불가]')
      ->setCellValue($char++.'1', '옵션상세명칭')
      ->setCellValue($char++.'1', '안전재고')
      ->setCellValue($char++.'1', '가상재고')
      ->setCellValue($char++.'1', '공급상태')
      ->setCellValue($char++.'1', '단품추가금액');
 
$k=2;
for($i=2; $row=sql_fetch_array($result); $i++)
{

    // 킬딜 : 진열 1, 품절 2, 단종 3, 중지 4
    // 사방넷 : 대기중 1, 공급중 2, 일시중지 3, 완전품절 4, 미사용 5, 삭제 6, 자료없음 7
    if($row['isopen'] == 1) $isopen = "2";
    else if($row['isopen'] == 4) $isopen = "3";
    else $isopen = 7;

    // 킬딜 : 과세 1, 면세 0
    // 사방넷 : 과세 1, 면세2, 자료없음 3, 비과세 4, 영세 5
    if($row['notax'] == 1) $notax = "1";  
    else if($row['notax'] == 0) $notax = "2";  
    else $notax = 3;

    // 킬딜 : 공통설정 0, 무료배송 1, 조건부무료배송 2, 유료배송 3
    // 킬딜 : 선불 0, 착불 1, 사용자선택 2
    // 사방넷 : 무료 1, 착불 2, 선결제 3, 착불/선결제 4
    if($row['sc_type'] == 0 || $row['sc_type'] == 1){
        $sc_type = "1"; 
    }else{ 
        if($row['sc_method'] == 0 ) $sc_type = "3"; 
        else if($row['sc_method'] == 1 ) $sc_type = "2"; 
        else $sc_type = "4"; 
    }

    $opt_1 = array();
    $opt_2 = array();
    if( $row['opt_subject'] != "" ){
        $opt_subject = explode(",",$row['opt_subject']);
        $sql1 = " select * from shop_goods_option where io_type = '0' and gs_id = '{$row['index_no']}' order by io_no asc ";
        $res1 = sql_query($sql1);
        for($j=0; $opt=sql_fetch_array($res1); $j++) {
            $opt_id = $opt['io_id'];
            $opt_val = explode(chr(30), $opt_id);
            if( !empty($opt_val[0]) && !in_array($opt_val[0],$opt_1) )  array_push( $opt_1, $opt_val[0] );
            if( !empty($opt_val[1]) && !in_array($opt_val[1],$opt_2) )  array_push( $opt_2, $opt_val[1] );

            // 킬딜 : 사용 1, 미사용 0
            // 사방넷 : 판매 1, 품절 2, 미사용 3
            if($opt['io_use'] == '1') $io_use = 1;
            else $io_use = 3;
            $char = 'A';
            $excel->setActiveSheetIndex(1)
                ->setCellValueExplicit($char++.$k, '', PHPExcel_Cell_DataType::TYPE_STRING)      
                ->setCellValueExplicit($char++.$k, '', PHPExcel_Cell_DataType::TYPE_STRING)     
                ->setCellValueExplicit($char++.$k, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING)      
                ->setCellValueExplicit($char++.$k, $opt['io_id'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit($char++.$k, '0', PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit($char++.$k, $opt['io_stock_qty'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit($char++.$k, $io_use, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit($char++.$k, $opt['io_price'], PHPExcel_Cell_DataType::TYPE_STRING);
            $k++;
        }
    }

    $opt_subject_1 = isset($opt_subject[0])?$opt_subject[0]:'';
    $opt_subject_2 = isset($opt_subject[1])?$opt_subject[1]:'';
    $opt_1 = !empty($opt_1)?implode(",",$opt_1):'';
    $opt_2 = !empty($opt_2)?implode(",",$opt_2):'';

    $simg1 = !empty( $row['simg1'] )?'https://killdeal.co.kr/data/goods/'.$row['simg1']:'';
    $simg2 = !empty( $row['simg2'] )?'https://killdeal.co.kr/data/goods/'.$row['simg2']:'';
    $simg3 = !empty( $row['simg3'] )?'https://killdeal.co.kr/data/goods/'.$row['simg3']:'';

    $char = 'A';
    $excel->setActiveSheetIndex(0)
          ->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING)      // 상품명
          ->setCellValueExplicit($char++.$i, $row['explan'], PHPExcel_Cell_DataType::TYPE_STRING)     // 상품약어
          ->setCellValueExplicit($char++.$i, $row['model'], PHPExcel_Cell_DataType::TYPE_STRING)      // 모델명
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 모델NO
          ->setCellValueExplicit($char++.$i, $row['br_name'], PHPExcel_Cell_DataType::TYPE_STRING)    // 브랜드명
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 자체상품코드
          ->setCellValueExplicit($char++.$i, $row['keywords'], PHPExcel_Cell_DataType::TYPE_STRING)   // 사이트검색어
          ->setCellValueExplicit($char++.$i, '5', PHPExcel_Cell_DataType::TYPE_STRING)                // 상품구분
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 카테고리
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 매입처ID
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 물류처ID
          ->setCellValueExplicit($char++.$i, $row['maker'], PHPExcel_Cell_DataType::TYPE_STRING)      // 제조사
          ->setCellValueExplicit($char++.$i, $row['origin'], PHPExcel_Cell_DataType::TYPE_STRING)     // 원산지(제조국)
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 생산연도
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 제조일
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 시즌
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 남녀구분
          ->setCellValueExplicit($char++.$i, $isopen, PHPExcel_Cell_DataType::TYPE_STRING)            // 상품상태
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 판매지역 
          ->setCellValueExplicit($char++.$i, $notax, PHPExcel_Cell_DataType::TYPE_STRING)             // 세금구분
          ->setCellValueExplicit($char++.$i, $sc_type, PHPExcel_Cell_DataType::TYPE_STRING)           // 배송비구분
          ->setCellValueExplicit($char++.$i, $row['sc_amt'], PHPExcel_Cell_DataType::TYPE_STRING)     // 배송비
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)                 // 반품지구분
          ->setCellValueExplicit($char++.$i, $row['supply_price'], PHPExcel_Cell_DataType::TYPE_STRING)   // 원가
          ->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_STRING)    // 판매가
          ->setCellValueExplicit($char++.$i, $row['normal_price'], PHPExcel_Cell_DataType::TYPE_STRING)   // TAG가
          ->setCellValueExplicit($char++.$i, $opt_subject_1, PHPExcel_Cell_DataType::TYPE_STRING)        // 옵션제목(1)
          ->setCellValueExplicit($char++.$i, $opt_1, PHPExcel_Cell_DataType::TYPE_STRING)    // 옵션상세명칭(1)
          ->setCellValueExplicit($char++.$i, $opt_subject_2, PHPExcel_Cell_DataType::TYPE_STRING)        // 옵션제목(2)
          ->setCellValueExplicit($char++.$i, $opt_2, PHPExcel_Cell_DataType::TYPE_STRING)    // 옵션상세명칭(2)
          ->setCellValueExplicit($char++.$i, $simg1, PHPExcel_Cell_DataType::TYPE_STRING) // 대표이미지
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 종합몰(JPG)이미지
          ->setCellValueExplicit($char++.$i, $simg2, PHPExcel_Cell_DataType::TYPE_STRING) // 부가 이미지2
          ->setCellValueExplicit($char++.$i, $simg3, PHPExcel_Cell_DataType::TYPE_STRING) // 부가 이미지3
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지4
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지5
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지6
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지7
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지8
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지9
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가 이미지10
          ->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)       // 상품상세설명
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 추가상품그룹코드
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증번호
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증유효시작일
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증유효마지막일
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 발급일자
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증일자
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증기관
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증분야
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 재고관리사용여부
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 유효일
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 식품 재료/원산지
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 원가2
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지11
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지12
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지13
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 합포시 제외 여부
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지14
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지15
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지16
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지17
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지18
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지19
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지20
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지21
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 부가이미지22
          ->setCellValueExplicit($char++.$i, $row['admin_memo'], PHPExcel_Cell_DataType::TYPE_STRING)     // 관리자메모
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 옵션수정여부
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 영문 상품명  
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 출력 상품명
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 인증서이미지
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 추가 상품상세설명_1
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 추가 상품상세설명_2
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 추가 상품상세설명_3
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 원산지 상세지역
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 수입신고번호
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 수입면장이미지
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성분류코드
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값1
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값2
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값3
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값4
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값5
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값6
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값7
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값8
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값9
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값10
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값11
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값12
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값13
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값14
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값15
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값16
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값17
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값18
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값19
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값20
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값21
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값22
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값23
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값24
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값25
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값26
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)     // 속성값27
          ->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING);    // 속성28

}



$titStyle = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '808080'),
        'size'  => 10,
        'name'  => '굴림체'
    )
);


$thStyle = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'ffffff'),
        'size'  => 10,
        'name'  => '맑은고딕'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '993300')
    ),    
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);


$bdStyle = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 9,
        'name'  => '맑은고딕'
    ),
     'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'ffffdd')
    ),    
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);


$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->getStyle('A1:DC1')->applyFromArray($thStyle);
$excel->getActiveSheet()->getStyle('A2:DC'.(--$i))->applyFromArray($bdStyle);
$excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
for($char='A';$char!='CA';$char++){
    $excel->getActiveSheet()->getColumnDimension($char)->setWidth(30);
}
for($j='2';$j<=$i;$j++){
    $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(15);
}
$excel->getActiveSheet()->getStyle("A1:DC".$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle ("A1:DC".$i)->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->getStyle('A1:DC'.$i)->getAlignment()->setWrapText(true);
$excel->getActiveSheet()->setTitle('사방넷상품대량등록');


$excel->setActiveSheetIndex(1);
$excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($thStyle);
$excel->getActiveSheet()->getStyle('A2:H'.(--$k))->applyFromArray($bdStyle);
$excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
for($char='A';$char!='I';$char++){
    $excel->getActiveSheet()->getColumnDimension($char)->setWidth(30);
}
for($j='2';$j<=$k;$j++){
    $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(15);
}
$excel->getActiveSheet()->getStyle("A1:H".$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle ("A1:H".$k)->getAlignment ()->setVertical (PHPExcel_Style_Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->getStyle('A1:H'.$k)->getAlignment()->setWrapText(true);
$excel->getActiveSheet()->setTitle('사방넷단품대량수정');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="사방넷_상품_등록_'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

?>
