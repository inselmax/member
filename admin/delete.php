<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( !is_wksg_login() ) {
  header('Location: /member/admin/');
  exit();
}


// ----------------------------------------------------------
// * VALIDATION
// ----------------------------------------------------------

// トークンの有無をチェック
if( !empty($_POST['access_token']) && $_POST['access_token'] == ACCESS_TOKEN ) {
  $company_id = $_POST['id'];
}else {
  echo 'このアクセスは無効です';
  exit();
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// 仲介会社を削除
deleteCompanyData( $pdo, $company_id );
// 会社IDに紐づくユーザーを削除
deleteUserData2( $pdo, $company_id );

// DB切断
$pdo = null;

header('Location: /member/admin/dashboard.php');
exit();
