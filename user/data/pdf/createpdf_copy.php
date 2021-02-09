<?php

@session_start();

// デバッグ用
// ini_set('display_errors', "On");
// タイムゾーンをセット
date_default_timezone_set('Asia/Tokyo');
// TimeStamp
$timestamp = date("ymdhms");
// ルートパス取得
$Root = $_SERVER['DOCUMENT_ROOT'];


// *-----------------------------------------*/
// DB
// *-----------------------------------------*/

require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );


// ログインしているかどうか
if( isset($_SESSION['ID']) ) {

  if( isset($_SESSION['CID']) ) { // 一般
    $company_id = $_SESSION['CID'];
  }else { // 管理者
    $company_id = $_SESSION['ID'];
  }

  // DB接続
  $pdo = dbConect();

  // 仲介業者のデータを取得
  $row_data = getSearchCompanyData( $pdo, $company_id );

  $company_name = $row_data['name']; // 会社名
  $company_address = $row_data['address']; // 住所
  $company_tel = $row_data['tel']; // 電話番号
  $company_logo = $row_data['logo_path']; // 会社ロゴ
  $company_rate = $row_data['rate']; // 割引率

  // DB切断
  $pdo = null;

}else {

  return false;

}


// *-----------------------------------------*/
// TCPDF
// *-----------------------------------------*/

// TCPDFライブラリ読み込み
require_once( $Root . "/TCPDF/tcpdf.php" );


// *----------------------------------*/
// 各設定

// PDFファイルの名前
$pdf_file_name = 'sample01'; // (※★)
$pdf_file_name = $pdf_file_name . '_' . $timestamp . '.pdf';

$tcpdf = new TCPDF( "P", "mm", "A4", true, "UTF-8" );
$tcpdf->setPrintHeader( false );
$tcpdf->setPrintFooter( false );
$tcpdf->SetAutoPageBreak(TRUE, 0);
// $tcpdf->SetMargins( 0 );
// $tcpdf->setCellPaddings(0,0,0,0);
$tcpdf->SetLeftMargin( 9 );
$tcpdf->SetRightMargin( 5 );
$tcpdf->SetTopMargin( 10 );
$tcpdf->SetFooterMargin( 0 );
$tcpdf->AddPage();
// $tcpdf->SetFont("kozminproregular", "", 10 );

$font = new TCPDF_FONTS();
$fontX = $font->addTTFfont($Root . "/pdf/font/ipaexg.ttf");
$tcpdf->SetFont($fontX, "", 12);


// *----------------------------------*/
// 内容

$html = <<< EOF
<style>
  table {
    font-size: 7.5px;
  }
</style>

<div class="wrapper">
<table border="0" cellspacing="0" cellpadding="0">


  <tr><td>hoge</td></tr>


</table>

</div>
EOF;


// *----------------------------------*/
// 書き出し

// HTMLを元にPDFを生成
$tcpdf->writeHTML($html);

// I -> ブラウザ出力
// D -> ダウンロード
// F -> サーバーに保存
$tcpdf->Output( $pdf_file_name, 'I' );

 ?>
