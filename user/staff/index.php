<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );
require_once( $Root . '/member/htmllib.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( !is_admin_login() ) {
  header('Location: /member/user/');
  exit();
}

$err_type = "";
$err_flg = "";
if( !empty($_GET['err']) && $_GET['err'] === "success" && !empty($_GET['flg']) ) {
  $err_type = "success";
  $err_flg = escStr( $_GET['flg'] );
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();
// ユーザーデータ取得
$row = getUserData2( $pdo, $_SESSION['ID'] );
// // ユーザーの数を取得
$userCount = getAryCount( $row );
// DB切断
$pdo = null;


 ?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>仲介業者専用サイト│大阪市の貸ビル、賃貸オフィススペースの若杉</title>

  <?php
  require_once( $Root . '/member/assets/parts/head.php');
  echoHeadOption();
  ?>

</head>

<body>

  <!-- wrap start -->
  <div class="wrap">

    <?php
    require_once( $Root . '/member/assets/parts/header.php');
    ?>

    <!-- container start -->
    <div class="container">

      <?php
      require_once( $Root . '/member/assets/parts/sidebar.php');
       ?>

      <!-- main start -->
      <div class="main main-staff">

        <div class="content">

          <h2 class="heading-01">ユーザー管理</h2>

          <?php
          // アラートを出力
          if( $err_type ) {
            if( $err_flg === "delete" ) {
              htmlErrMessage( $err_type, "ユーザーを削除しました" );
            }elseif( $err_flg === "add" ) {
              htmlErrMessage( $err_type, "ユーザーを追加しました" );
            }
          }
          ?>

          <div class="staff-add-box">
          <?php
            // ユーザー登録フォーム出力
            htmlUserAddForm( $userCount );
          ?>
          </div>

          <h2 class="heading-01">登録ユーザー（最大5名）</h2>

          <div class="staff-box">
          <?php
            // ユーザーテーブル出力
            htmlUserDataTable2( $row );
          ?>

        </div>

        <?php
        require_once( $Root . '/member/assets/parts/footer.php');
        ?>

      </div>
      <!-- main end -->
    </div>
    <!-- container end -->
  </div>
  <!-- wrap end -->

  <script>
  $(function() {
      $('form').submit(function() {

          var confirmText = "ユーザーを追加しますか？";
          if( $(this).data("ftype") === "delete" ) {
            confirmText = "ユーザーを削除しますか？";
          }


          if (!confirm(confirmText)) {
              return false;
          }
      });
  });
  </script>

</body>

</html>