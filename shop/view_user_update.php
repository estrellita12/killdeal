<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
	alert("로그인 후 작성 가능합니다.");
}

if($mode == "" || $mode == "w") {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert("잘못된 접근 입니다.");
		exit;
	}

	$index_no = trim(strip_tags($_POST['index_no']));
	$score = trim(strip_tags($_POST['score']));
	$seller_id = trim(strip_tags($_POST['seller_id']));

	if(substr_count($_POST['memo'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

    // (2021-03-05)
    $upl_dir = TB_DATA_PATH."/review";
    $upl = new upload_files($upl_dir);

    unset($value);
    if($_POST['re_file_del']) {
        $upl->del($_POST['re_file_del']);
        $value['qna_file'] = '';
    }
    if($_FILES['re_file']['name']) {
        $value['re_file'] = $upl->upload($_FILES['re_file']);
    }

    $value['mb_id']          = $_POST['mb_id'];
    $value['mb_name']          = $member['name'];
    $value['gs_id']          = $_POST['gs_id'];
    $value['gender']          = $_POST['gender'];
    $value['age']          = $_POST['age'];
    $value['level']          = $_POST['level'];
    $value['pt_id']          = get_session('pt_id');
    $value['memo']          = $_POST['memo'];
    $value['score']           = $_POST['score'];
    $value['reg_time']          = TB_TIME_YMDHIS;
    $value['seller_id']           = $_POST['seller_id'];
    insert("shop_goods_review", $value);

	//상품평 카운터하기
	sql_query("update shop_goods set m_count=m_count+1 where index_no='$gs_id'");

    alert("정상적으로 등록 되었습니다","thash","tab2");
}
else if($mode == 'd') // 상품평 삭제
{
	if(is_admin())
		sql_query("delete from shop_goods_review where index_no='$it_mid'");
	else
		sql_query("delete from shop_goods_review where index_no='$it_mid' and mb_id='$member[id]'");

	// 상품평 삭제시 상품테이블에 상품평 카운터를 감소한다
	sql_query("update shop_goods set m_count=m_count-1 where index_no='$gs_id'");

	goto_url(TB_SHOP_URL."/view.php?index_no=$gs_id#tab2");
}
?>
