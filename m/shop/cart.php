<?php
include_once("./_common.php");
include_once(TB_SHOP_PATH.'/settle_naverpay.inc.php');

$tb['title'] = '장바구니';
include_once("./_head.php");

//기존에 담긴 상품의 가격이 현재 가격과 다를 경우 현재 가격으로 업데이트 해준다. --20200728
$sql1 = " select * 
		   from shop_cart 
		  where ct_direct = '$set_cart_id'
		    and ct_select = '0' 		 
		  order by index_no ";

$result1 = sql_query($sql1);
global $member;
for($i=0; $row=sql_fetch_array($result1); $i++) {
	$sql2 = " select *
				from shop_goods as a
   				inner join shop_goods_option as b
	  			on a.index_no = b.gs_id
   				where a.index_no = '$row[gs_id]'
				 and b.io_id = '$row[io_id]' 
				 order by a.index_no ";
	$result2 = sql_fetch($sql2);


$gs_id = $row['gs_id'];
$gs = get_goods($gs_id);
$gs['goods_price'] = get_sale_price($gs_id);

	//현재 가격과 기존 장바구니에 담긴 상품 가격이 다르면 변경시킴 
	if ( ( $row['ct_price'] != $gs['goods_price'] ) || ($row['io_price'] != $result2['io_price'] ) ){
		$sql3 = " update shop_cart
					set ct_price = '$gs[goods_price]'
					,ct_supply_price= ' $gs[supply_price]' 
					,io_price = '$result2[io_price]' 
					where  index_no = '$row[index_no]' ";

		sql_query($sql3);
	}
}

$sql = " select * 
		   from shop_cart 
		  where ct_direct = '$set_cart_id' 
		    and ct_select = '0' 
		  group by gs_id 
		  order by index_no ";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);

$cart_action_url = TB_MSHOP_URL.'/cartupdate.php';

include_once(TB_MTHEME_PATH.'/cart.skin.php');

include_once("./_tail.php");
?>