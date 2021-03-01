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
if( !is_login() ) {
  header('Location: /member/user/');
  exit();
}

$err_type = "";
if( !empty($_GET['err']) && $_GET['err'] === "success" ) {
  $err_type = "success";
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
      <div class="main main-profile">

        <div class="content">
          <h2 class="heading-01">登録情報</h2>

          <?php

          // アラートを出力
          if( $err_type ) {
            htmlErrMessage( $err_type, "データを更新しました" );
          }


          // DB接続
          $pdo = dbConect();

          // 管理者
          if( is_admin_login() ) {
            $row = getSearchCompanyData( $pdo, $_SESSION['ID'] );
            htmlCompanyProfile( $row );
          }else {
            $row = getSearchUserData( $pdo, $_SESSION['ID'] );
            htmlUserProfile( $row );
          }

          // DB切断
          $pdo = null;
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

  <script src="/member/assets/js/upload.js"></script>

</body>
</html>
