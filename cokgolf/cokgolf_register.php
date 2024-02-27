<?php
include_once("../common.php");

$c_url = isset($_GET['url']) ? $_GET['url'] : null;
$c_uuid = $_GET['c_uuid'];

login_sso_log($pt_id, null, get_session("c_uuid"), null, null, null, null,'Y','Level0.5');
$register_action_url = './cokgolf_register_action.php';
include_once('./cokgolf_head.skin.php');
include_once('./cokgolf_register.skin.php');
?>