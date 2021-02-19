<?php

$Root = $_SERVER['DOCUMENT_ROOT'];

require_once($Root . "/office_search/html_lib.php");

$form_data = array();

// 簡単検索
htmlSearchForm_01($form_data);

// こだわり検索
htmlSearchForm_02($form_data);

// テーマ検索
htmlSearchForm_03($form_data);

?>