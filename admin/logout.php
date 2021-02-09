<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/func.php' );

// -------------------------------------------------------------------
// ログアウト処理
// -------------------------------------------------------------------

if ( is_wksg_login() ) {

  //セッション変数クリア
  $_SESSION = array();

  //セッションクッキー削除
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }

  //セッションクリア
  @session_destroy();

} else {
  // セッションタイムアウト
}

header('Location: /member/admin/');
exit();
