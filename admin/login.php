<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( is_wksg_login() ) {
  header('Location: /member/admin/dashboard.php');
  exit();
}


// ----------------------------------------------------------
// * LOGIN
// ----------------------------------------------------------

$formData['id'] = $_POST['id'];
$formData['pass'] = $_POST['password'];

if( $formData['id'] == ADMINID && $formData['pass'] == ADMINPASS ) {

  $_SESSION['ADMIN'] = $formData['id'];
  header('Location: /member/admin/dashboard.php');
  exit();

}else {

  header('Location: /member/admin/?err=not');
  exit();

}
