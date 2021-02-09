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

  <script>
    // lightbox Option
    $(function(){
      lightbox.option({
      'resizeDuration': 0,
      'fadeDuration': 0
      })
		});
  </script>

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

          <h2 id="mansion" class="heading-01">各マンション空室状況</h2>

          <!-- 01 シャトー若杉マンション -->
          <ul class="LatestOffice_list">
            <li>
              <section class="LatestOffice_sect">
                <div class="LatestOffice_cont">
                  <div class="LatestOffice_thumb">
                    <img src="/image_common/mansion01_thumb.jpg" alt="">
                  </div>
                  <div class="LatestOffice_info">
                    <div class="LatestOffice_InfoHead">
                      <p class="LatestOffice_InfoBuilding">シャトー若杉マンション</p>
                      <p class="LatestOffice_InfoAddress">大阪府大阪市旭区大宮4丁目1-11</p>
                    </div>
                  </div>
                  <div class="DetailBtn">
                    <p class="ButtonD-light ButtonD-arrow"><a href="/mansion/shato/" target="_brank">マンション詳細</a></p>
                  </div>
								</div>
                <div class="LatestOffice_table" id="js-bukken-m01"><img src="/js/ajax-loader.gif"></div>
              </section>
            </li>
          </ul>
          <!-- 02 若杉ロイヤルマンション  -->
          <ul class="LatestOffice_list">
            <li>
              <section class="LatestOffice_sect">
                <div class="LatestOffice_cont">
                  <div class="LatestOffice_thumb">
                    <img src="/image_common/mansion02_thumb.jpg" alt="">
                  </div>
                  <div class="LatestOffice_info">
                    <div class="LatestOffice_InfoHead">
                      <p class="LatestOffice_InfoBuilding">若杉ロイヤルマンション</p>
                      <p class="LatestOffice_InfoAddress">大阪府大阪市旭区中宮3丁目13番8号</p>
                    </div>
                  </div>
                  <div class="DetailBtn">
                    <p class="ButtonD-light ButtonD-arrow"><a href="/mansion/royal/" target="_brank">マンション詳細</a></p>
                  </div>
								</div>
                <div class="LatestOffice_table" id="js-bukken-m02"><img src="/js/ajax-loader.gif"></div>
              </section>
            </li>
          </ul>
          <!-- 03 エスポワール若杉マンション  -->
          <ul class="LatestOffice_list">
            <li>
              <section class="LatestOffice_sect">
                <div class="LatestOffice_cont">
                  <div class="LatestOffice_thumb">
                    <img src="/image_common/mansion03_thumb.jpg" alt="">
                  </div>
                  <div class="LatestOffice_info">
                    <div class="LatestOffice_InfoHead">
                      <p class="LatestOffice_InfoBuilding">エスポワール若杉マンション</p>
                      <p class="LatestOffice_InfoAddress">大阪府大阪市都島区大東町2-3-7</p>
                    </div>
                  </div>
                  <div class="DetailBtn">
                    <p class="ButtonD-light ButtonD-arrow"><a href="/mansion/espoir/" target="_brank">マンション詳細</a></p>
                  </div>
								</div>
                <div class="LatestOffice_table" id="js-bukken-m03"><img src="/js/ajax-loader.gif"></div>
              </section>
            </li>
          </ul>

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
  <script>
    setBukkenData('01', 100, 'm');
    setBukkenData('02', 100, 'm');
    setBukkenData('03', 100, 'm');
  </script>

</body>
</html>
