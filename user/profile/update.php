<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( !is_admin_login() ) {
    header('Location: /member/user/dashboard.php');
    exit();
}


// ----------------------------------------------------------
// * VALIDATION
// ----------------------------------------------------------

// トークンの有無をチェック
if( !empty($_POST['access_token']) && $_POST['access_token'] == ACCESS_TOKEN ) {

}else {
    echo 'このアクセスは無効です';
    exit();
}

// 会社名
if ( $_POST['name'] == '' ) {
    echo '会社名が入力されていません。';
    return false;
}

// 屋号
if ( $_POST['shop'] == '' ) {
    echo '屋号が入力されていません。';
    return false;
}

// 住所
if ( $_POST['address'] == '' ) {
    echo '住所が入力されていません。';
    return false;
}

// 電話番号
if ( $_POST['tel'] == '' ) {
    echo '電話番号が入力されていません。';
    return false;
}

// FAX
if ( $_POST['fax'] == '' ) {
    echo 'FAXが入力されていません。';
    return false;
}

// 責任者氏名
if ( $_POST['pic_name'] == '' ) {
    echo '担当者名が入力されていません。';
    return false;
}

// 宅建免許番号
if ( $_POST['license'] == '' ) {
    echo '宅建免許番号が入力されていません。';
    return false;
}

// 割引率
// if ( $_POST['rate'] == '' ) {
//     echo '割引率が入力されていません。';
//     return false;
// }

// メールアドレス
if ( !$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
    echo '入力された値が不正です。';
    return false;
}

// パスワード（確認用）
if ( $_POST['password'] == '' ) {
    echo 'パスワード（確認用）が入力されていません。';
    return false;
}


// ----------------------------------------------------------
// * DB
// ----------------------------------------------------------

// DB接続
$pdo = dbConect();

// 仲介業者情報を取得
$row = getSearchCompanyData( $pdo, $_SESSION['ID'] );

// パスワード確認
if ( password_verify($_POST['password'], $row['password']) ) {

}else {
    echo 'パスワードが間違っています。';
    return false;
}



// データを更新
// 1 => $_POST['rate']
updateCompanyProfile( $pdo, $_SESSION['ID'], $_POST['name'], $_POST['shop'], $_POST['address'], $_POST['tel'], $_POST['fax'], $_POST['email'], $_POST['pic_name'], $_POST['license'], 1, $_POST['logo_path'] );
// updateCompanyStatus( $pdo, $user_id );

// DB切断
$pdo = null;

header('Location: /member/user/profile');
exit();
