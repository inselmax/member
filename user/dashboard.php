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


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// 管理者
if( is_admin_login() ) {
  $company_id = $_SESSION['ID'];
}else {
  $company_id = $_SESSION['CID'];
}

// 仲介業者情報を取得
$row = getSearchCompanyData( $pdo, $company_id );
// 取扱物件フラグを格納
$office_flg = $row['type'];

// DB切断
$pdo = null;

switch( $office_flg ) {
  case 1:
    header('Location: /member/user/building.php');
    exit();
    break;

  case 2:
    header('Location: /member/user/mansion.php');
    exit();
    break;

  default:
    header('Location: /member/user/building.php');
    exit();
    break;
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

  <script src="/js/setcookie.js"></script>
  <script src="/member/assets/js/common.js"></script>

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

  <script src="/member/assets/js/setBukkenData.js"></script>

  <?php
  if( $page == 'building' ): //ビル
  ?>

	<script>
    setBukkenData('01');
    setBukkenData('02');
    setBukkenData('03');
    setBukkenData('04');
    setBukkenData('05');
    setBukkenData('06');
    setBukkenData('07');
    setBukkenData('08');
    setBukkenData('09');
    setBukkenData('10');
  </script>

  <?php
  else: // マンション
  ?>

  <script>
    setBukkenData('01', 100, 'm');
    setBukkenData('02', 100, 'm');
    setBukkenData('03', 100, 'm');
  </script>
  <?php
  endif;
  ?>

</body>
</html>
