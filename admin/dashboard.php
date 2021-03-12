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
if( !is_wksg_login() ) {
  header('Location: /member/admin/');
  exit();
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// 仲介業者情報を全件取得
$row = getCompanyData( $pdo );

// ユーザー情報を全件取得
// $row2 = getUserData( $pdo );

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
      <div class="main">

        <div class="content">
          <h1>仲介業者情報</h1>

          <h2>・仲介業者一覧</h2>
          <?php htmlCompanyDataTable( $row ); ?>

          <!-- <h2>・ユーザー一覧</h2> -->
          <?php //htmlUserDataTable( $row2 ); ?>

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

          var confirmText = "承認しますか？";
          if( $(this).data("ftype") === "delete" ) {
            confirmText = "削除しますか？";
          }


          if (!confirm(confirmText)) {
              return false;
          }
      });
  });
  </script>

</body>
</html>
