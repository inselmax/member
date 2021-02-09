<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );
require_once( $Root . '/member/htmllib.php' );

// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

if( is_admin_login() ) {
  $row_data = getSearchCompanyData( $pdo, $_SESSION['ID'] );
  $company_name = $row_data['name'];
  $pic_name = $row_data['pic_name'];
  $company_logo = $row_data['logo_path'];
  $company_type = $row_data['type'];
}elseif( is_login() ) {
  $row_data = getSearchUserData( $pdo, $_SESSION['ID'] );
  $company_name = $row_data['c_name'];
  $pic_name = $row_data['name'];
  $company_logo = $row_data['logo_path'];
  $company_type = $row_data['type'];
}

// DB切断
$pdo = null;

?>

<!-- sidebar start -->
<div class="sidebar">
    <div class="sidebar_inner">

        <?php
        // ユーザー分岐
        if( is_login() ) {
        ?>

          <div class="user-box">
            <p class="c-logo"><img src="/member/user/data/upload/<?php echo $company_logo; ?>" alt="<?php echo $company_name; ?>"></p>
            <p class="c-name"><?php echo $company_name; ?></p>
            <p class="u-name">担当　<?php echo $pic_name; ?>　様</p>
          </div>

          <ul class="navi">
            <?php if( $company_type == 1 || $company_type == 3 ): ?>
            <li class="navi-item"><a href="/member/user/building.php"><i class="fa fa-angle-right" aria-hidden="true"></i>ビル空室情報</a></li>
            <?php endif; ?>

            <?php if( $company_type == 2 || $company_type == 3 ): ?>
            <li class="navi-item"><a href="/member/user/mansion.php"><i class="fa fa-angle-right" aria-hidden="true"></i>マンション空室情報</a></li>
            <?php endif; ?>

            <li class="navi-item"><a href="/member/user/download/"><i class="fa fa-angle-right" aria-hidden="true"></i>各種申込書</a></li>
            <li class="navi-item"><a href="/member/user/profile/"><i class="fa fa-angle-right" aria-hidden="true"></i>登録情報</a></li>

            <?php if( is_admin_login() ): ?>
            <li class="navi-item"><a href="/member/user/staff/"><i class="fa fa-angle-right" aria-hidden="true"></i>ユーザー管理</a></li>
            <?php endif; ?>

            <li class="navi-item"><a href="/member/user/contact/"><i class="fa fa-angle-right" aria-hidden="true"></i>お問い合わせ</a></li>
            <li class="navi-item"><a href="/member/user/logout.php"><i class="fa fa-angle-right" aria-hidden="true"></i>ログアウト</a></li>
          </ul>

        <?php
        // 若杉管理者
        }elseif( is_wksg_login() ) {
        ?>

          <ul class="navi">
            <li class="navi-item"><a href="/member/admin/dashboard.php"><i class="fa fa-angle-right" aria-hidden="true"></i>仲介業者情報</a></li>
            <li class="navi-item"><a href="/member/admin/building.php"><i class="fa fa-angle-right" aria-hidden="true"></i>ビル空室情報</a></li>
            <li class="navi-item"><a href="/member/admin/mansion.php"><i class="fa fa-angle-right" aria-hidden="true"></i>マンション空室情報</a></li>
            <li class="navi-item"><a href="/member/admin/logout.php"><i class="fa fa-angle-right" aria-hidden="true"></i>ログアウト</a></li>
          </ul>

        <?php
        // 未ログイン
        }else {
        ?>

          <ul class="navi">
            <li class="navi-item"><a href="/member/admin/"><i class="fa fa-angle-right" aria-hidden="true"></i>管理者ログインページ</a></li>
            <li class="navi-item"><a href="/member/user/"><i class="fa fa-angle-right" aria-hidden="true"></i>仲介業者ログインページ</a></li>
            <li class="navi-item"><a href="/member/user/register.php"><i class="fa fa-angle-right" aria-hidden="true"></i>仲介業者登録ページ</a></li>
          </ul>

        <?php
        }
        ?>
    </div>
</div>
<!-- sidebar end -->
