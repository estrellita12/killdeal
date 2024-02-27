<?php 
    /*###############################################################
        2022-02-25 인플루언서몰 스크롤 하단 접촉 시 상품 데이터 추가 API
    */###############################################################

    //setting
    $data = json_decode(file_get_contents('php://input'));
    include_once("./fetch_common.php");

    //initialization
    $last_item = $data->last_item;
    $active = $data->active;
    
    //execution
    $sql = "SELECT * FROM shop_goods WHERE index_no < {$last_item} and LEFT(ca_id, 3) = '00{$active}' and isopen = 1 ORDER BY index_no DESC LIMIT 10";
    $result = sql_query($sql);

    $arr = array();
    
    while($row = sql_fetch_array($result)){
        
        $thumbnail = is_file("../data/goods/{$row['simg1']}") ? TB_DATA_PATH."/goods/{$row['simg1']}" : TB_IMG_URL."/noimage.gif";        
        $it_name = cut_str($row['gname'], 100);
        $it_price = get_price($row['index_no']);
        $it_amount = get_sale_price($row['index_no']);
        $is_uncase = is_uncase($row['index_no']);

        // (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
        $it_sprice = $sale = '';
        if($row['normal_price'] > $it_amount && !$is_uncase) {
            $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
            $sale = $sett >= 1 ? number_format($sett,0) : "0";
            $it_sprice = display_price2($row['normal_price']);
        }else{
            $sale = "10";
            $it_sprice = display_price2(round($it_amount/0.9,-3));                
        }
        $arr[] = array(
            "gcode" => $row['index_no'],
            "it_href" => TB_MSHOP_PATH."/view.php?gs_id={$row['index_no']}",            
            "it_image" => $thumbnail,
            "it_name" => $it_name,
            "sale" => $sale,            
            "it_sprice" => $it_sprice,                                                                                    
            "it_price" => $it_price,
        );        
    }
    echo json_encode($arr);
?>