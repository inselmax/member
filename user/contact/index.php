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


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

$company_name = ''; // 会社名
$name = '';         // 担当者名
$email = '';        // メールアドレス

if( is_admin_login() ) {

    $row = getSearchCompanyData( $pdo, $_SESSION['ID'] );

    $company_name = $row['name'];
    $name = $row['pic_name'];
    $email = $row['email'];

}else {

    $row = getSearchUserData( $pdo, $_SESSION['ID'] );

    $company_name = $row['c_name'];
    $name = $row['name'];
    $email = $row['email'];

}

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
            <div class="main main-contact">

                <div class="content">

                    <h2 class="heading-01">お問い合わせ</h2>

                    <?php

                    // アラートを出力
                    if( $err_type ) {
                        htmlErrMessage( $err_type, "メールを送信しました" );
                    }

                    // お問い合わせフォーム出力
                    htmlContactForm( $company_name, $name, $email );
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
            if (!confirm('送信しますか？')) {
                return false;
            }
        });
    });
    </script>

</body>
</html>
