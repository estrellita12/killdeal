<?php
if(!defined('_TUBEWEB_')) exit;

if($sel_ca1) $sca = $sel_ca1;   // 카테고리1
if($sel_ca2) $sca = $sel_ca2;   // 카테고리2
if($sel_ca3) $sca = $sel_ca3;   // 카테고리3
if($sel_ca4) $sca = $sel_ca4;   // 카테고리4
if($sel_ca5) $sca = $sel_ca5;   // 카테고리5

if($sel_ca1)			$qstr .= "&sel_ca1=$sel_ca1";               // 카테고리1
if($sel_ca2)			$qstr .= "&sel_ca2=$sel_ca2";               // 카테고리2
if($sel_ca3)			$qstr .= "&sel_ca3=$sel_ca3";               // 카테고리3
if($sel_ca4)			$qstr .= "&sel_ca4=$sel_ca4";               // 카테고리4
if($sel_ca5)			$qstr .= "&sel_ca5=$sel_ca5";               // 카테고리5

if($q_type1)            $qstr .= "&q_type1=$q_type1";
if($q_type2)            $qstr .= "&q_type2=$q_type2";
if($q_type3)            $qstr .= "&q_type3=$q_type3";
if($q_type4)            $qstr .= "&q_type4=$q_type4";
if($q_type5)            $qstr .= "&q_type5=$q_type5";

if(isset($q_date_field) && $q_date_field)	            $qstr .= "&q_date_field=$q_date_field";     // 날짜 검색 조건 (최근수정일, 최초등록일)
if(isset($q_brand) && $q_brand )			                $qstr .= "&q_brand=$q_brand";               // 브랜드
if(isset($q_zone) && $q_zone)			                $qstr .= "&q_zone=$q_zone";                 // 배송가능지역
if(isset($q_stock_field) && $q_stock_field)	            $qstr .= "&q_stock_field=$q_stock_field";   // 상품 재고 수량 검색 조건 (재고수량, 통보수량)
if(isset($fr_stock) && is_numeric($fr_stock))		    $qstr .= "&fr_stock=$fr_stock";             // 재고수량 시작
if(isset($to_stock) && is_numeric($to_stock))		    $qstr .= "&to_stock=$to_stock";             // 재고수량 끝
if(isset($q_price_field) && $q_price_field)	            $qstr .= "&q_price_field=$q_price_field";   // 상품 가격 검색 조건 (판매가격, 공급가격, 시중가격)
if(isset($fr_price) && is_numeric($fr_price))		    $qstr .= "&fr_price=$fr_price";             // 상품가격 시작
if(isset($to_price) && is_numeric($to_price))		    $qstr .= "&to_price=$to_price";             // 상품 가격 끝
if(isset($q_isopen) && is_numeric($q_isopen))		    $qstr .= "&q_isopen=$q_isopen";             // 판매여부 (1:진열, 2:품절, 3:단종)
if(isset($q_option) && is_numeric($q_option))		    $qstr .= "&q_option=$q_option";             // 필수옵션 (1:사용, 0:미사용)
if(isset($q_supply) && is_numeric($q_supply))		    $qstr .= "&q_supply=$q_supply";             // 추가옵션 (1:사용, 0:미사용)
if(isset($q_notax) && is_numeric($q_notax))			    $qstr .= "&q_notax=$q_notax";               // 과세유형 (1:과세, 0:비과세)
if(isset($q_timesale) && is_numeric($q_timesale))       $qstr .= "&q_timesale=$q_timesale";         // 타임세일 (1:사용, 0:미사용)
if(isset($q_sidebanner) && $q_sidebanner)		                        $qstr .= "&q_sidebanner=$q_sidebanner";     // 사이드 배너
if(isset($q_recomm_use) && $q_recomm_use)		                        $qstr .= "&q_recomm_use=$q_recomm_use";     // 장바구니 추천
if(isset($q_pt_id) && $q_pt_id)		                                $qstr .= "&q_pt_id=$q_pt_id";               // 가맹점 ID
if(isset($shop_state) && is_numeric($shop_state))		                       $qstr .= "&shop_state=$shop_state";
if(isset($use_aff) && is_numeric($use_aff))		                                $qstr .= "&use_aff=$use_aff";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

?>

