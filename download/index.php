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
      <div class="main main-download">

        <div class="content">

          <h2 class="heading-01">各種申込書ダウンロード</h2>

          <table class="table table-normal">
            <tr>
              <th>ファイル名</th>
              <th>内容</th>
              <th>ダウンロード</th>
            </tr>
            <tr>
              <td>サンプル申込書（個人）</td><td>個人のお客様の場合はこちらをご利用下さい。印刷してご記入をお願い致します。</td>
              <td>
                <div class="ButtonE ButtonE-pdf">
                  <a class="pdfFormSubmit" href="/member/user/data/pdf/createpdf_copy.php" target="_brank">
                    <span class="ButtonB_inner">PDF</span>
                  </a>
                </div>
              </td>
            </tr>
          </table>

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