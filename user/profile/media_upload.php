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
// * アップロード処理
// ----------------------------------------------------------

$tempfile = $_FILES['mediafile']['tmp_name'];

// 36文字のランダムな文字列を生成（これがディレクトリ名になる）
$dir_name = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 36);

$dir_path = '../data/upload/' . $_SESSION['ID'] . '/' . $dir_name . '/';
$filename = $dir_path . $_FILES['mediafile']['name'];
$db_filename = $_SESSION['ID'] . '/' . $dir_name . '/' . $_FILES['mediafile']['name'];

if( file_exists($dir_path) ){

}else{
    // ディレクトリが存在しなければ作成する 数字はパーミッション
    if( mkdir( $dir_path, 0777, TRUE ) ){

        chmod( $dir_path, 0777 );

    }
}

if ( is_uploaded_file($tempfile) ) {

    if ( move_uploaded_file( $tempfile , $filename )) {

        echo $db_filename;

    } else {
        // echo "ファイルをアップロードできません。";
        echo "upError";
    }
} else {
    // echo "ファイルが選択されていません。";
    echo "nofile";
}

