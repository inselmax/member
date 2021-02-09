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
  $user_id = $_POST['id'];
  $company_id = $_POST['company_id'];
}else {
  echo 'このアクセスは無効です';
  exit();
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// ユーザーを削除
deleteUserData( $pdo, $user_id, $company_id );

// DB切断
$pdo = null;

header('Location: /member/admin/dashboard.php');
exit();
