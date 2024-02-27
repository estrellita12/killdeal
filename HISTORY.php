<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
?>

/*******************************************************************************
/* 2019-03-05 (분양몰 2.1.7)
/*******************************************************************************
(가맹점 쇼핑몰 상단메뉴 오작동 오류 수정)
	수정) /public_html/lib/partner.lib.php
			#변수오타
			수정 전) if($partner['de_pname_'.$seq]) {
			수정 후) if($pt['de_pname_'.$seq]) {

	수정) /public_html/common.php
			#extend_file 아래로 위치변경
			include_once(TB_PATH.'/partner.config.php');
			include_once(TB_LIB_PATH.'/db_table.optimize.php');

/*******************************************************************************
/* 2019-02-02 (분양몰 2.1.6)
/*******************************************************************************
(이니시스 모바일 결제 모바일 노티 IP 추가)
	수정) /public_html/m/shop/inicis/settle_common.php

(기능패치)
	패치) 본인인증 모듈 새롭게 교체
	패치) 회원 및 공급사 일괄메일발송 새롭게 기능추가
	패치) 회원 폼메일 새롭게 기능추가
	패치) 포인트일괄지급/차감 기능추가
	패치) 회원가입 로직 변경

(구매확정 검색필드 값 오류 수정)
	수정) /public_html/admin/order/order_list.php
	수정) /public_html/admin/order/order_5.php
	수정) /public_html/admin/pop_sellerorder.php
	수정) /public_html/admin/pop_memberorder.php
	수정) /public_html/mypage/partner_odr_list.php
	수정) /public_html/mypage/partner_odr_5.php
	수정) /public_html/mypage/seller_odr_5.php
	수정) /public_html/mypage/seller_odr_list.php

/*******************************************************************************
/* 2019-01-13 (분양몰 2.1.5)
/*******************************************************************************
(주문완료 후 "ss_orderview_uid" 세션체크 오류 수정)
	수정) /public_html/shop/orderformresult.php
	수정) /public_html/m/shop/orderformresult.php

	수정전)	$uid = md5($od_id.TB_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
	수정후)	$uid = md5($od_id.$od['od_time'].$_SERVER['REMOTE_ADDR']);

/*******************************************************************************
/* 2019-01-02 (분양몰 2.1.4)
/*******************************************************************************
(주문통계 "가장작은 년도 구하기" 오류 수정)
	수정) /public_html/admin/visit/visit_order1.php
	수정) /public_html/admin/visit/visit_order2.php
	수정) /public_html/admin/visit/visit_cancel.php
	수정) /public_html/admin/visit/visit_refund.php
	수정) /public_html/admin/visit/visit_return.php
	수정) /public_html/admin/visit/visit_change.php

/*******************************************************************************
/* 2018-12-26 (분양몰 2.1.3)
/*******************************************************************************
(옵션 엑셀일괄등록 예시파일 업데이트)
	삭제) /public_html/lib/Excel/itemoptionexcel.xls
	생성) /public_html/lib/Excel/itemoptionexcel.zip

/*******************************************************************************
/* 2018-12-08 (분양몰 2.1.2)
/*******************************************************************************
(상품쿼리속도 mysql 패치)
	수정) /public_html/config.php
	수정) /public_html/lib/global.lib.php
	수정) /public_html/shop/price/daum.php
	수정) /public_html/shop/price/daum_summary.php
	수정) /public_html/shop/price/naver.php
	수정) /public_html/shop/price/naver_summary.php
	수정) /public_html/shop/timesale.php
	수정) /public_html/m/shop/timesale.php
	수정) /public_html/shop/list.php
	수정) /public_html/theme/basic/list.skin.php
	수정) /public_html/m/shop/list.php
	수정) /public_html/shop/brandlist.php
	수정) /public_html/m/shop/brandlist.php
	수정) /public_html/shop/coupon_goods.php
	수정) /public_html/shop/planlist.php
	수정) /public_html/m/shop/planlist.php
	수정) /public_html/shop/search.php
	수정) /public_html/m/shop/search.php

(상품인기검색어 소스 수정)
	삭제) shop_keyword 테이블
	생성) shop_popular 테이블

(문자설정 본사와 가맹점 개별적으로 설정가능하도록 수정)
	수정) shop_sms 테이블에 mb_id 필드추가
	수정) shop_config 테이블에 pf_auth_sms 필드추가

(게시판 첨부파일 다운로드 에러 수정)
	수정) /public_html/bbs/skin/gallery/read.php
	수정) /public_html/bbs/skin/webzine/read.php
	수정) /public_html/bbs/skin/basic/read.php

/*******************************************************************************
/* 2018-12-04 (분양몰 2.1.1)
/*******************************************************************************
(주문업데이트시 새로고침방지 패치)
	수정) /public_html/shop/orderform.php
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/m/shop/orderform.php
	수정) /public_html/m/shop/orderformupdate.php

/*******************************************************************************
/* 2018-11-26 (분양몰 2.1.0)
/*******************************************************************************
	수정) 다중분류 삭제(3개의 카테고리만 등록가능-과부화 방지때문)
	수정) 카테고리(가맹점은 생성못함-과부화 방지때문)_숨김기능만 가능
	수정) 카테고리(5단 ajax 처리-과부화 방지때문)
	패치) 회원 포인트일괄등록 추가
	패치) 기획전(가맹점에서도 등록가능)

/*******************************************************************************
/* 2018-11-25 (분양몰 2.0.10)
/*******************************************************************************
(구매후기 필드 오타수정)
	수정) /public_html/theme/basic/review.skin.php
	수정) /public_html/theme/basic/view_user.skin.php
	수정) /public_html/m/theme/basic/review.skin.php
	수정) /public_html/admin/admin_topmenu.php
	수정) /public_html/config.php

(작성된 분양몰에서만 게시글노출(가맹점/공급사관리자에서는 예외))
	수정) /public_html/mypage/board_head.php

(모바일게시판 공지만 있을경우 목록 노출안되는 오류 수정)
	수정) /public_html/m/bbs/board_list.php
	수정) /public_html/m/theme/basic/board_gallery.skin.php
	수정) /public_html/m/theme/basic/board_list.skin.php

(가격비교 본사카테고리로 고정되어있던 오류 수정)
	수정) /public_html/shop/price/daum.php
	수정) /public_html/shop/price/daum_summary.php
	수정) /public_html/shop/price/naver.php
	수정) /public_html/shop/price/naver_summary.php

(최근본상품 이미지노출 안되는 오류 수정)
	수정) /public_html/theme/basic/quick.skin.php

/*******************************************************************************
/* 2018-11-04 (분양몰 2.0.9)
/*******************************************************************************
(공급사 신청 보안패치)
	수정) /public_html/m/bbs/seller_reg.php
	수정) /public_html/m/bbs/seller_reg_from.php
	수정) /public_html/bbs/seller_reg.php
	수정) /public_html/bbs/seller_reg_from.php

(관리자페이지 회원정보수정시 shop_member 테이블 mb_id -> id 로 필드 오타수정)
	수정) /public_html/admin/pop_memberformupdate.php

(무통장 입금 때 고객에게 계좌정보 보냄 기능패치)
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/m/shop/orderformupdate.php

(주문상태에 따른 합계 금액 - 금액합계 오류수정)
	수정) /public_html/lib/common.lib.php
		  admin_order_status_sum() 함수 수정

(모바일 회원가입시 sns로그인 사용안함일때 박스노출 부분 오류수정)
	수정) /public_html/m/theme/basic/register.skin.php

(상품테이블 선택필드 노출 오류수정)
	수정) /public_html/lib/global.lib.php
	function get_goods($gs_id, $fileds='*')


/*******************************************************************************
/* 2018-10-05 (분양몰 2.0.8)
/*******************************************************************************
(상품등록시 최소/최대구매수량 콤마 오류 수정)
	수정) /public_html/admin/goods/goods_form.php
	수정) /public_html/admin/goods/goods_form_update.php
	수정) /public_html/mypage/partner_goods_form.php
	수정) /public_html/mypage/partner_goods_form_update.php
	수정) /public_html/mypage/seller_goods_form.php
	수정) /public_html/mypage/seller_goods_form_update.php

/*******************************************************************************
/* 2018-09-04 (분양몰 2.0.7)
/*******************************************************************************
(회원정보 수정시 새비밀번호 최소입력하라는 오류 수정)
	수정) /public_html/theme/basic/register_mod.skin.php
	수정) /public_html/m/theme/basic/register_form.skin.php

/*******************************************************************************
/* 2018-09-04 (분양몰 2.0.6)
/*******************************************************************************
(쿠폰관리 (인쇄용) 대량으로 쿠폰번호 생성시 중복방지 패치)
	수정) /public_html/lib/common.lib.php
	get_gift() 삭제
	get_coupon_id() 생성

	수정) /public_html/admin/goods/goods_gift_form_update.php
	수정) /public_html/admin/goods/goods_gift_serial.php
	수정) /public_html/config.php

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.5)
/*******************************************************************************
(쿠폰관리 (인쇄용) 엑셀저장시 한글깨짐 현상 오류 수정)
	수정) /public_html/admin/goods/goods_gift_excel.php

(모바일 인스타그램 userId 쌍따옴표 누락 수정)
	수정) /public_html/m/theme/basic/tail.skin.php

(모바일 검색창 추가)
	수정) /public_html/m/bbs/board_list.php
	수정) /public_html/m/theme/basic/board_list.skin.php
	수정) /public_html/m/theme/basic/board_gallery.skin.php
	수정) /public_html/m/theme/basic/style.css
	삭제) /public_html/m/js/jquery.lazyload.min.js
	추가) /public_html/m/js/imagesloaded.pkgd.min.js

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.4)
/*******************************************************************************
(배송완료 후 쿠폰발급 오류수정)
	수정) /public_html/bbs/register_form_update.php
	수정) /public_html/lib/global.lib.php
	수정) /public_html/m/shop/pop_coupon_update.php
	수정) /public_html/m/shop/orderformupdate.php
	수정) /public_html/m/bbs/register_form_update.php
	수정) /public_html/plugin/login-oauth/oauth_check.php
	수정) /public_html/shop/pop_coupon_update.php
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/config.php
	수정) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-05 (분양몰 2.0.3)
/*******************************************************************************
(sns공유 오타수정)
	/public_html/shop/view.php
	수정전) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$gs_id;
	수정후) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$index_no;

(약관 nl2br() 태그 삭제)
	/public_html/theme/basic/seller_reg_from.skin.php
	/public_html/theme/basic/partner_reg.skin.php
	/public_html/theme/basic/register.skin.php
	/public_html/m/theme/basic/seller_reg_from.skin.php
	/public_html/m/theme/basic/partner_reg.skin.php

(define 선언 추가)
	/public_html/config.php
	추가) define('TB_VERSION', '분양몰 v2.0.3');

(업데이트 히스토리 파일추가)
	추가) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-03 (분양몰 2.0.2)
/*******************************************************************************
(카테고리 인덱스값 추가)
	/public_html/admin/category/category_sql.php
	/public_html/install/sql_db.sql

/*******************************************************************************
/* 2018-07-31 (분양몰 2.0.1)
/*******************************************************************************
(가맹점카테고리 설정오류 수정)
	/public_html/extend/shop.extend.php
	삭제) // 기본값 본사 카테고리 테이블명
	삭제) $tb['category_table'] = 'shop_cate';

	/public_html/partner.config.php
	추가) // 기본값 본사 카테고리 테이블명
	추가) $tb['category_table'] = 'shop_cate';