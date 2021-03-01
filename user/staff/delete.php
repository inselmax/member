<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( !is_admin_login() ) {
  header('Location: /member/user/dashboard.php');
  exit();
}


// ----------------------------------------------------------
// * VALIDATION
// ----------------------------------------------------------

// トークンの有無をチェック
if( !empty($_POST['access_token']) && $_POST['access_token'] == ACCESS_TOKEN ) {
  $user_id = $_POST['id'];
}else {
  echo 'このアクセスは無効です';
  exit();
}

// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

deleteUserData( $pdo, $user_id, $_SESSION['ID'] );

// DB切断
$pdo = null;

header('Location: /member/user/staff/index.php?err=success&flg=delete');
exit();