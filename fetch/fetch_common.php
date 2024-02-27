<?php
    /*###############################################################
        라이브러리 , 상수 기본 SET
    */###############################################################
    error_reporting(0);
    include_once("../config.php");      
    $dbconfig_file = '../data/dbconfig.php';
    if(file_exists($dbconfig_file)) {
        include_once($dbconfig_file);
        include_once("../lib/global.lib.php");
        include_once("../lib/common.lib.php");            

        $connect_db = sql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
        $select_db = sql_select_db(TB_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

        $tb['connect_db'] = $connect_db;

        sql_set_charset('utf8', $connect_db);
        if(defined('TB_MYSQL_SET_MODE') && TB_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
        
    }else {
        header('Content-Type: text/html; charset=utf-8');
        die($dbconfg_file.'파일을 찾을 수 없습니다.');
    }
?>