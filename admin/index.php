<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/func.php' );
require_once( $Root . '/member/htmllib.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( is_login() ) {
  header('Location: /member/user/dashboard.php');
  exit();
}elseif( is_wksg_login() ) {
  header('Location: /member/admin/dashboard.php');
  exit();
}

// ----------------------------------------------------------
// * VALIDATION
// ----------------------------------------------------------

$err = null;
if( isset( $_GET['err'] ) ) {
  $err = $_GET['err'];
}

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
      <div class="main main-login admin">

        <div class="content">

          <h2 class="heading-01">管理者ログイン</h2>

          <?php
          // 管理者ログインフォームを出力
          htmlAdminLoginForm( $err );
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

</body>
</html>
