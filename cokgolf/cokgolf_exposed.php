<?php 
    include_once("../common.php");    

    //timesale_goods list
    function add_time_list(){
        $sql_search = " and 1!=1 ";
        $ts = sql_fetch("select * from shop_goods_timesale where ts_sb_date <= NOW() and ts_ed_date >= NOW() ");
        if( isset($ts) ){
            $sb_date = $ts['ts_sb_date'];
            $ed_date = $ts['ts_ed_date'];
            $is_timesale    = true;
            $ts_list_code = explode(",", $ts[ts_it_code]); // 배열을 만들고
            $ts_list_code = array_unique($ts_list_code); //중복된 아이디 제거
            $ts_list_code = array_filter($ts_list_code); // 빈 배열 요소를 제거
            $ts_list_code = implode(",",$ts_list_code );
            $sql_search = " and index_no in ( $ts_list_code )";
            $sql_order = " order by field ( index_no, $ts_list_code ) ";
        }
        
        //$sql_common = sql_goods_list($sql_search);
        $sql_common = " from shop_goods where shop_state = '0' ".$sql_search;
        $sql = " select * $sql_common ";
        $result = sql_query($sql);
        $str ='';
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $file = TB_DATA_PATH."/goods/".$row['simg1'];
            if(is_file($file)){
                    $filepath = dirname($file);
                    $filename = basename($file);
                    if($filename) {
                        $savepath = TB_DATA_PATH."/goods/";
                    }
                    $file_url = rpc($savepath, TB_PATH, TB_URL);

                    $img = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';

            } else {
                    $img = TB_IMG_URL.'/noimage.gif';
            }

            //$it_href = 'https://bsjmall.co.kr?data=ID2&redirectUrl='.TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
            $it_href = 'javascript:void(0)';
            $re_url = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];

            $it_name = cut_str($row['gname'], 100);
            $it_price = get_price($row['index_no']);
            $it_amount = get_sale_price($row['index_no']);
            $it_point = display_point($row['gpoint']);

            $is_uncase = is_uncase($row['index_no']);
            $is_free_baesong = is_free_baesong($row);
            $is_free_baesong2 = is_free_baesong2($row);

            // (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
            $it_sprice = $sale = '';
            if($row['normal_price'] > $it_amount && !$is_uncase) {
                $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
                $sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
                $it_sprice = display_price2($row['normal_price']);
            }

            $str .= "
            <li class=\"swiper-slide\">
            <a href=\"$it_href\" onclick=\"sendPostMessage(`{$re_url}`)\">
                <span class=\"\">
                    $img    
                </span>
                <span class=\"time_sale_txtbox\">
                    <p class=\"time_sale_tit\">$it_name</p>
                    <span class=\"sale_price_box\"><span class=\"sale_percent\">$sale</span><span class=\"list_price\">$it_sprice</span></span>
                    <span class=\"sale_price\"><span>$it_price</span> </span>
                </span>
            </a>
            </li>                                
            ";
        }
        return $str;        
    }

    // best_goods list
    function add_best_list($list_best)
    {
        $mod = 4;
        $ul_str = '';

        for($i=0; $i<count($list_best); $i++) {
            $str = '';

            $list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
            $list_code = array_unique($list_code); //중복된 아이디 제거
            $list_code = array_filter($list_code); // 빈 배열 요소를 제거
            $list_code = array_values($list_code); // index 값 주기

            $succ_count = 0;
            for($g=0; $g<count($list_code); $g++) {
                $gcode = trim($list_code[$g]);
                $row = sql_fetch(" select * from shop_goods where index_no = '$gcode' ");
                if(!$row['index_no']) continue;
                if($succ_count >= $mod) break;
                
                //$it_href = 'https://bsjmall.co.kr?uuid=UUID1&redirectUrl='.TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
                $it_href = 'javascript:void(0)';
                $re_url = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];

                
                $file = TB_DATA_PATH."/goods/".$row['simg1'];
                if(is_file($file)){
                        $filepath = dirname($file);
                        $filename = basename($file);
                        if($filename) {
                            $savepath = TB_DATA_PATH."/goods/";
                        }
                        $file_url = rpc($savepath, TB_PATH, TB_URL);

                        $img = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';

                } else {
                        $img = TB_IMG_URL.'/noimage.gif';
                }
                $it_image = '<img src="'.$file_url.'/'.$filename.'" alt="상품이미지">';


                

                $it_name = cut_str($row['gname'], 100);
                $it_price = get_price($row['index_no']);
                $it_amount = get_sale_price($row['index_no']);
                $it_point = display_point($row['gpoint']);

                $is_uncase = is_uncase($row['index_no']);
                $is_free_baesong = is_free_baesong($row);
                $is_free_baesong2 = is_free_baesong2($row);

                // (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
                $it_sprice = $sale = '';
                if($row['normal_price'] > $it_amount && !$is_uncase) {
                    $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
                    $sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
                    $it_sprice = display_price2($row['normal_price']);
                }

                $str .= "<li id='{$row['index_no']}'>\n";
                $str .= "<a href=\"{$it_href}\" onclick=\"sendPostMessage(`{$re_url}`)\">\n";
                    $str .= "<span class=\"\">\n";
                        $str .= "{$it_image}\n";
                    $str .= "</span>\n";
                    $str .= "<span>\n";
                        $str .= "<p class=\"time_sale_tit\">{$it_name}</p>\n";
                        $str .= "<span class=\"sale_price_box\"><span class=\"sale_percent\">{$sale}</span><span class=\"list_price\">{$it_sprice}</span></span>";
                        $str .= "<span class=\"sale_price\">{$it_price}</span>";
                    $str .= "</span>\n";
                $str .= "</a>\n";
                $str .= "</li>\n";

                $succ_count++;
            } // for end

            // 나머지 li
            $cnt = $succ_count%$mod;
            if($cnt) {
                for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
            }

            if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";
            $now_count = $i+1;
            $class_chk = $now_count == 1 ?"on" : "";
            $ul_str .= "
            <div id=\"dataTab01\" class=\"product_tab_content {$class_chk}\">
                <div class=\"best_product_list\">        
                    <ul id=\"dataTab0{$now_count}\">\n{$str}
                    </ul>\n
                </div>        
            </div>        
            ";
        }

        return $ul_str;
    }
?>

<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8">
		<title>약관 | 콕쇼핑 박사장몰</title>
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
		<meta name="keywords" content="NH농협, 콕쇼핑 박사장몰">
		<meta name="description" content="NH농협 콕쇼핑 박사장몰 약관 입니다.">
		<script type="text/javascript" src="./resource/static/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="./resource/static/js/swiper.js"></script>
        <script type="text/javascript" src="./resource/static/js/common.js"></script>
		<link rel="stylesheet" href="./resource/static/css/swiper.min.css">
		<link rel="stylesheet" href="./resource/static/css/font.css">
		<link rel="stylesheet" href="./resource/static/css/jquery-ui.css">
		<link rel="stylesheet" href="./resource/static/css/common.css">
		<link rel="stylesheet" href="./resource/static/css/bsj_iframe.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
	</head>
    <body>
        <div id="wrap" class="">
            <div class="time_sale_area">
                <h3 class="area_tit">타임특가</h3>
                <div class="time_sale_box swiper-container">
                    <div class="hidden_container">
                        <ul class="time_sale_list swiper-wrapper">
                            <?php     
                                echo add_time_list();
                            ?>
                        </ul>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <!-- 베스트 상품 -->
            <div class="best_product_area">
                <h3 class="area_tit">베스트 상품</h3>


            <?php 
            if($default['de_listing_best'] == '1') {
                    if($default['de_maintype_best']) {
                        $list_best = unserialize(base64_decode($default['de_maintype_best']));
                        $list_count = count($list_best);
                        $tab_width = (float)(100 / $list_count);
            ?>
                <div class="best_product_tab">
                    <ul>
                        <?php 
                            for($i=0; $i<$list_count; $i++) { 
                                $j = $i;
                                if($i == '2'){
                                    $j = '2';
                                } else if ($i == '3') {
                                    $j = '3';
                                }
                        ?>
                        <li data-tab="dataTab0<?php echo $j+1; ?>" <?php echo ($j==0)?'class="on"':''?>><a href="javascript:void(0);"><?php echo trim($list_best[$i]['subj']); ?></a></li> 
                        <?php } ?>
                <?php } ?>
            <?php } ?>


                    </ul>
                </div>

                <div>
                    <?php
                        echo add_best_list($list_best);
                    ?>
                    <div class="btn_area">
                        <a href='javascript:void(0)' onclick="sendPostMessage('<?php echo TB_SHOP_URL; ?>/listtype.php?type=2')">더보기</a>
                    </div>                    
                </div>
            </div>
        </div>
    </body>
</html>

<script>
	$(document).ready(function(){
		//init
		var timeSaleBox = new Swiper('.time_sale_box .hidden_container', {
			slidesPerView: '2',
			spaceBetween: 14,
			pagination: {
				el: '.swiper-pagination',
				clickable:true,
			},
			navigation: {
				nextEl: '.time_sale_box .swiper-button-next',
				prevEl: '.time_sale_box .swiper-button-prev',
			},
		});

		//execute
		/* 탭메뉴 */
		$('.best_product_tab ul li').on('click',function() {
			var idx = $(this).index();
			$('.best_product_tab ul li').removeClass('on').eq(idx).addClass('on');
			$('.product_tab_content').removeClass('on').eq(idx).addClass('on');
		})

		$('.back_history').on('click', () => {
			//window.history.back();
		})

        /* //작업중
        $('#add_all').on('click', () => {
            let now_active = document.querySelector('li.on').dataset.tab.substr(-1);
            
            let now_ul = $(`ul[id=dataTab0${now_active}]`);
            let last_item = now_ul.children('li').last().last()[0].id;

            call_fetch('add_all',{'last_item':last_item, 'active':now_active})
            .then((result) => {
                return result.text();
            }).then((result) => {
                let final_result = JSON.parse(result);
                console.log(final_result);
            });
        })
        */

	})
</script>
