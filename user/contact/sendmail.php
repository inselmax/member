<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

if( !is_login() ) {
  header('Location: /member/user/index.php');
  exit();
}


// ----------------------------------------------------------
// * VALIDATION
// ----------------------------------------------------------

// トークンの有無をチェック
if( !empty($_POST['access_token']) && $_POST['access_token'] == ACCESS_TOKEN ) {

}else {
  echo 'このアクセスは無効です';
  exit();
}

// 会社名
if ( $_POST['company'] == '' ) {
  echo '会社名が入力されていません。';
  return false;
}

// 担当者氏名
if ( $_POST['name'] == '' ) {
  echo '担当者氏名が入力されていません。';
  return false;
}

// メールアドレス
if ( !$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
  echo '入力された値が不正です。';
  return false;
}

// 件名
if ( $_POST['title'] == '' ) {
  echo '件名が入力されていません。';
  return false;
}

// お問い合わせ内容
if ( $_POST['content'] == '' ) {
  echo 'お問い合わせ内容が入力されていません。';
  return false;
}


// ----------------------------------------------------------
// * メール処理
// ----------------------------------------------------------

// メール通知
$mail_content = getMailTempContact( $_POST, 0 );
$mail_content2 = getMailTempContact( $_POST, 1 );
sendMail( $_POST['email'], MAIL_SUBJECT_CONTACT, $mail_content ); // ユーザー宛て
sendMail( ADMIN_MAILADDRESS, MAIL_SUBJECT_CONTACT, $mail_content2 ); // 管理者宛て

// リダイレクト
header('Location: /member/user/contact/index.php?err=success');
exit();