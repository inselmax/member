<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

if( is_login() || is_wksg_login() ) {
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
if ( $_POST['name'] == '' ) {
  echo '会社名が入力されていません。';
  return false;
}

// 屋号
if ( $_POST['shop'] == '' ) {
  echo '屋号が入力されていません。';
  return false;
}

// 住所
if ( $_POST['address'] == '' ) {
  echo '住所が入力されていません。';
  return false;
}

// 電話番号
if ( $_POST['tel'] == '' ) {
  echo '電話番号が入力されていません。';
  return false;
}

// FAX
if ( $_POST['fax'] == '' ) {
  echo 'FAXが入力されていません。';
  return false;
}

// 責任者氏名
if ( $_POST['pic_name'] == '' ) {
  echo '責任者氏名が入力されていません。';
  return false;
}

// 宅建免許番号
if ( $_POST['license'] == '' ) {
  echo '責任者氏名が入力されていません。';
  return false;
}

// メールアドレス
if ( !$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
  echo '入力された値が不正です。';
  return false;
}

// 取扱物件
if ( $_POST['type'] == 1 || $_POST['type'] == 2 || $_POST['type'] == 3 ) {

}else {
  echo '取扱物件の値が不正です';
  return false;
}

// パスワード（確認用）
if ( $_POST['password_confirm'] != $_POST['password'] ) {
  echo 'パスワードが一致しません（確認用）';
  return false;
}

// パスワード
if ( passCheck($_POST['password']) ) {

  // パスワードをハッシュ化し格納
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

} else {

  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';

  return false;
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// 仲介会社DB 内でPOSTされたメールアドレスを検索
$row = searchCompanyData( $pdo, $_POST['email'] );
// ユーザーDB 内でPOSTされたメールアドレスを検索
$row2 = searchUserData( $pdo, $_POST['email'] );

if( $row > 0 || $row2 > 0 ) {

  echo 'このメールアドレスは既に使用されています。';

  return false;

}else {

  // DBに登録
  insertCompanyData( $pdo, $_POST['name'], $_POST['shop'], $_POST['address'], $_POST['tel'], $_POST['fax'], $_POST['pic_name'], $_POST['license'], $_POST['type'], $_POST['email'], $password );

  // DB切断
  $pdo = null;

  // メール通知
  $mail_content = getMailTempSignup( $_POST, 0 );
  $mail_content2 = getMailTempSignup( $_POST, 1 );
  sendMail( $_POST['email'], MAIL_SUBJECT_SIGNUP, $mail_content ); // ユーザー宛て
  sendMail( ADMIN_MAILADDRESS, MAIL_SUBJECT_SIGNUP, $mail_content2 ); // 管理者宛て

  // リダイレクト
  header('Location: /member/user/thanks.php');
  exit();

}
