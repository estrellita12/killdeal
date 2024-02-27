<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/json.lib.php');

if(!$mod_type) die('Error');

switch($mod_type):
	case "2": $len = 6;  break;
	case "3": $len = 9;  break;
	case "4": $len = 12; break;
	case "5": $len = 15; break;
endswitch;

$data = array();

if($ca_id) {
	$sql = " select catecode, catename
			   from shop_category
			  where length(catecode) = '$len'
				and catecode like '{$ca_id}%'
			  order by caterank, catecode ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$data[$i]['optionValue'] = $row['catecode'];
		$data[$i]['optionText']  = $row['catename'];
	}
}

die(json_encode($data));
?>