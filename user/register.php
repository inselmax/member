<?php
session_cache_limiter('public');
@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php');
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

$form_data = null;

if( !empty( $_POST ) ) {

  $form_data = $_POST;

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
      <div class="main main-register">

        <div class="content">

          <h2 class="heading-01">仲介業者登録フォーム</h2>

          <?php
          // 仲介業者登録フォームを出力
          if( isset( $form_data ) ) {
            htmlCompanyAddForm2( $form_data );
          }else {
            htmlCompanyAddForm();
          }
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
