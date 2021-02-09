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

// 仲介会社DB 内でPOSTされた会社を検索
$row = getSearchCompanyData( $pdo, $company_id );

if( $row > 0 ) {

  // データを更新
  updateCompanyStatus( $pdo, $company_id );

  // メール通知
  $mail_content = getMailTempApprove( $row );
  sendMail( $row['email'], MAIL_SUBJECT_APPROVE, $mail_content ); // ユーザー宛て

}else {

  echo 'データが見つかりませんでした。';

  return false;

}

// DB切断
$pdo = null;

header('Location: /member/admin/dashboard.php');
exit();
