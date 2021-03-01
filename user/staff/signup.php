<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

if( !is_admin_login() ) {
  header('Location: /member/');
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

// 担当者名
if ( $_POST['name'] == '' ) {
  echo '担当者名が入力されていません。';
  return false;
}

// メールアドレス
if ( !$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
  echo '入力された値が不正です。';
  return false;
}

// 担当者名
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


// ユーザーデータ取得
$userRow = getUserData2( $pdo, $_SESSION['ID'] );
// ユーザーの数を取得
$userCount = getAryCount( $userRow );

if ( $userCount >= 5) {
  // リダイレクト
  header('Location: /member/user/staff/');
  exit();
}




// 仲介会社DB 内でPOSTされたメールアドレスを検索
$row = searchCompanyData( $pdo, $_POST['email'] );
// ユーザーDB 内でPOSTされたメールアドレスを検索
$row2 = searchUserData( $pdo, $_POST['email'] );

if( $row > 0 || $row2 > 0 ) {

  echo 'このメールアドレスは既に使用されています。';

  // DB切断
  $pdo = null;

  return false;

}else {

  // DBに登録
  insertUserData( $pdo, $_POST['name'], $_POST['email'], $password, $_SESSION['ID'] );

  // DB切断
  $pdo = null;

  // メール通知
  $mail_content = getMailTempAddStaff( $_POST );
  sendMail( $_POST['email'], MAIL_SUBJECT_STAFF_SIGNUP, $mail_content ); // ユーザー宛て

  // リダイレクト
  header('Location: /member/user/staff/index.php?err=success&flg=add');
  exit();

}
