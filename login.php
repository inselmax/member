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

// メールアドレス
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  header('Location: /member/user/?err=not');
  return false;
}

// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// 接続 -
$pdo = dbConect();

$row = searchCompanyData( $pdo, $_POST['email'] );
$row2 = searchUserData( $pdo, $_POST['email'] );

// 切断
$pdo = null;

// -------------------------------------------------------------------
// データチェック
// -------------------------------------------------------------------

// メールアドレスをチェック
if ( isset($row['email']) ) { // 仲介会社

  // パスワードチェック
  if ( password_verify($_POST['password'], $row['password']) ) {

    if( $row['status'] == 1 ) { // 承認済み

      // セッションを渡してログイン
      session_regenerate_id(true);
      $_SESSION['ID'] = $row['id'];

      header('Location: /member/user/dashboard.php');
      exit();

    } else { // 未承認
      header('Location: /member/user/?err=dis');
      return false;
    }

  } else {
    header('Location: /member/user/?err=not');
    return false;
  }

}elseif( isset($row2['email']) ) { // ユーザー

  // パスワードチェック
  if ( password_verify($_POST['password'], $row2['password']) ) {

    // セッションを渡してログイン
    session_regenerate_id(true);
    $_SESSION['ID'] = $row2['id'];
    $_SESSION['CID'] = $row2['company_id'];

    header('Location: /member/user/dashboard.php');
    exit();

  } else {
    header('Location: /member/user/?err=not');
    return false;
  }

}else {
  header('Location: /member/user/?err=not');
  return false;
}

// //パスワード確認
// if ( password_verify($_POST['password'], $row['password']) ) {

//   // 管理者
//   if( $row['can'] == 1 ) {

//     if( $row['status'] == 1 ) { // 承認済み

//       // セッションを渡してログイン
//       session_regenerate_id(true);
//       $_SESSION['ID'] = $row['id'];

//       header('Location: /member/user/dashboard.php');
//       exit();

//     } else { // 未承認
//       echo 'このアカウントは現在使用できません。';
//       return false;
//     }

//   // スタッフ
//   }elseif( $_POST['can'] == 0 ) {

//     // セッションを渡してログイン
//     session_regenerate_id(true);
//     $_SESSION['ID'] = $row['id'];
//     $_SESSION['CID'] = $row['company_id'];

//     header('Location: /member/user/dashboard.php');
//     exit();

//   }

// } else {
//   echo 'メールアドレス又はパスワード、タイプが間違っています。';
//   return false;
// }
