<?php
if(!defined('_TUBEWEB_')) exit;
if(!defined('_GOODS_COPY_')) exit; // 개별 페이지 접근 불가

if(!function_exists("goods_copy")) {

    // 상품복사
    function goods_copy($gs_id)
    {
		$sql = " select * from shop_goods where index_no = '$gs_id' limit 1 ";
		$cp = sql_fetch($sql);

		// 상품테이블의 필드가 추가되어도 수정하지 않도록 필드명을 추출하여 insert 퀴리를 생성한다.
		$sql_common = "";
		$fields = sql_field_names("shop_goods");
		foreach($fields as $fld) {
			if(in_array($fld, array('index_no', 'gcode', 'readcount', 'rank', 'm_count', 'sum_qty', 'reg_time', 'update_time'))) continue;

			$sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
		}

		$sql = " insert into shop_goods
					set reg_time = '".TB_TIME_YMDHIS."',
						update_time = '".TB_TIME_YMDHIS."'
						$sql_common ";
		sql_query($sql);
		$insert_id = sql_insert_id();

		$sql_img = "";
		for($g=1; $g<=6; $g++) {
			if($cp['simg'.$g] && preg_match("/^(http[s]?:\/\/)/", $cp['simg'.$g]) == false) {
				$file = TB_DATA_PATH.'/goods/'.$cp['simg'.$g];
				$dstfile = TB_DATA_PATH."/goods/{$insert_id}_".$cp['simg'.$g];
				$filename = basename($dstfile);

				@copy($file, $dstfile);
				@chmod($dstfile, TB_FILE_PERMISSION);
				$sql_img .= " , simg{$g} = '$filename' ";
			}
		}

		$new_code = TB_SERVER_TIME + $insert_id;

		$sql = " update shop_goods
					set gcode = '$new_code'
						$sql_img
				  where index_no = '$insert_id' ";
		sql_query($sql);

		// 옵션 copy
		$opt_sql = " insert ignore into shop_goods_option
							( io_id, io_type, gs_id, io_price, io_stock_qty, io_noti_qty, io_use )
					 select io_id, io_type, '$insert_id', io_price, io_stock_qty, io_noti_qty, io_use
					   from shop_goods_option
					  where gs_id = '$gs_id'
					  order by io_no asc ";
		sql_query($opt_sql);
	}
}

goods_copy($gs_id);
?>