<?php
define('_PURENESS_', true);
include_once("./_common.php");

// ��ȣ��� ���� �ڵ�
session_unset(); // ��� ���Ǻ����� �������� ������ 
session_destroy(); // ���������� 

// �ڵ��α��� ���� --------------------------------
set_cookie("ck_mb_id", "", 0);
set_cookie("ck_auto", "", 0);
// �ڵ��α��� ���� end --------------------------------

//partner.config.php ����X ->�Ʒ��� ������ �߰���._20190925
if($_SERVER['HTTP_HOST'] =='mall.golfpang.com')
{
	$pt_id = 'golfpang';
}	
else if($_SERVER['HTTP_HOST'] =='shopping.golfu.net') 
{
	$pt_id = 'golfu';
}	
else if($_SERVER['HTTP_HOST'] =='shop.uscore.co.kr') 
{
	$pt_id = 'uscore';
}
	
//�ܺο���_��Ű���� �ʿ�_20190925
if($pt_id == 'golfpang')
{
	
	//���� ���� ó���� ��������Ʈ �α׾ƿ�ó��
	goto_url('http://www.golfpang.com/web/logout.do');
}
else if($pt_id == 'golfu')
{
	//���� ���� ó���� ��������Ʈ �α׾ƿ�ó��
	goto_url('http://www.golfu.net/Member/LogOut.aspx');
}
else if($pt_id == 'uscore')
{
	goto_url('https://www.uscore.co.kr/mapp/index.php');
}


if($url) {
    $p = parse_url($url);
    if($p['scheme'] || $p['host']) {
        alert("url�� �������� ������ �� �����ϴ�.");
    }

    $link = $url;
} else {
    $link = TB_URL;
}

goto_url($link);
?>
