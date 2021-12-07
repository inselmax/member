<?php

@session_start();

// デバッグ用
// ini_set('display_errors', "On");
// タイムゾーンをセット
date_default_timezone_set('Asia/Tokyo');
// TimeStamp
$timestamp = date("ymd");
// ルートパス取得
$Root = $_SERVER['DOCUMENT_ROOT'];
// ビル・マンション パス
$pagePath = "https://wakasugi-bldg.jp/mansion/";
$mansion_name = "シャトー若杉マンション";
$mansion_slug = "shato";
// $pagePath = "http://new.wakasugi-bldg.jp/mansion/";
// $pagePath = "https://wakasugi.dm-test-server01.com/mansion/";
// $pagePath = $Root . '/mansion/';


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

  $company_ctat = preg_replace("/ |:|-/", "", $row_data['created_at']); // 作成日（ユーザー）
  $company_upat = preg_replace("/ |:|-/", "", $row_data['updated_at']); // 更新日（ユーザー）

  // DB切断
  $pdo = null;

}else {

  return false;

}


if( file_exists( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' ) ){

}else{
    // 仲介会社用 PDF保存ディレクトリがなければ作成
    if( mkdir( $Root . '/member/user/data/pdf/temp/' . $company_id . '/', 0777, TRUE ) ){

        chmod( $Root . '/member/user/data/pdf/temp/' . $company_id . '/', 0777 );

    }
}


// *-----------------------------------------*/
// 関数
// *-----------------------------------------*/

function getSeetData( $bldgId ) {

  $ssUrl = "";
  $ssPass = "";

  switch ($bldgId) {

    // シャトー若杉
    case '01':
      $ssPass = '1oCyOqL76GIi2MIQxC74TpysESyU2Mo2vk_PVprNZzH4';
      break;

    // 若杉ロイヤル
    case '02':
      $ssPass = '1k36g2V6dltMSK5BpsWFvz0gv8LZx9lDwlPULH3qsd_8';
      break;

    // エスポワール若杉
    case '03':
      $ssPass = '1kAfo0f2cHRE7zO_0jbX_hzHn8clUvnxB9FJhTM0bP6U';
      break;

    default:
      $ssPass = '1oCyOqL76GIi2MIQxC74TpysESyU2Mo2vk_PVprNZzH4';
      break;
  }

  $ssUrl = 'https://spreadsheets.google.com/feeds/list/' . $ssPass . '/od6/public/values?alt=json';

  $json_data = file_get_contents($ssUrl);
  $json_decode_data = json_decode($json_data, true);
  $data_ary = $json_decode_data['feed']['entry'];

  return $data_ary;

}


// *-----------------------------------------*/
// 初期化
// *-----------------------------------------*/

$mark = 0;
$data_office = array();
$office_data = "";

$bldg_id = "";

$office_id = "";
$office_kai = "";
$office_gou = "";
$office_madori = "";
$office_tsubo = "";
$office_status = "";
$office_outline = "";
$office_date = "";
$office_other = "";
$office_theta = "";


// *-----------------------------------------*/
// データ取得
// *-----------------------------------------*/

// POSTされたデータをチェック
if ( !empty($_POST['office_id']) && strlen($_POST['office_id']) == 6 ) {

  $office_id = $_POST['office_id'];
  $bldg_id = substr( $office_id, 0, 2 );

  $office_data = getSeetData( $bldg_id );

  // 物件データから該当の物件を抽出する
  foreach ( $office_data as $key => $value ) {
    if ( $value['gsx$物件id']['$t'] == $office_id ) {
      $data_office = $value;
      $mark = 1;
      break;
    }
  }

  // 該当の物件IDがなければ false を返す
  if ( $mark == 0 ) {
    return false;
    exit();
  }

  // 詳細データを変数に格納
  $office_kai = $data_office['gsx$階数']['$t'];
  $office_gou = $data_office['gsx$号室']['$t'];
  $office_madori = $data_office['gsx$間取']['$t'];
  $office_tsubo = $data_office['gsx$契約面積']['$t'];
  $office_status = $data_office['gsx$状態入居予定時期']['$t'];
  $office_outline = $data_office['gsx$室内図面url']['$t'];
  $office_date = $data_office['gsx$登録日']['$t'];
  $office_update_at = $data_office['gsx$最終更新日']['$t'];
  $office_other = $data_office['gsx$備考']['$t'];

  // 賃料
  $office_price_chinryo = $data_office['gsx$賃料']['$t'];

  // 共益費
  $office_price_kyoekihi = $data_office['gsx$共益費']['$t'];

  // 物件ページリンク
  $office_link = $pagePath . $mansion_slug . '/intro.php?room=' . $office_id;

  $office_theta = $data_office['gsx$室内画像シータurl']['$t'];
  $office_theta_head = "室内360°";
  if( $office_theta == "" ) {
    $office_theta_head = "";
  }

}else {

  return false;

}


// PDFファイル名
// $pdf_file_name = 'officeData_' . $office_id . '_' . $timestamp . '.pdf';
$pdf_file_name = 'm_' . $office_id . '_1_' . $office_update_at . $company_ctat . $company_upat . '.pdf';


// *-----------------------------------------*/
// TCPDF
// *-----------------------------------------*/

// ファイルチェック  ※実機アップの時は191、199、465のコメントアウトを外す
//  if( file_exists( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name ) ) {



  // ファイルが存在している



//  }else {



  // ファイルが存在しない



// PHPQRCODEライブラリ読み込み
require_once( $Root . "/phpqrcode/qrlib.php" );

$filepath = 'temp/qr/m_' . $office_id . '_qr.png';
QRcode::png($office_link, $filepath, QR_ECLEVEL_M, 6);
$filepath = '/pdf/' . $filepath;

if( $office_theta != "" ) {
  $filepath2 = 'temp/qr/m_' . $office_id . '_theta_qr.png';
  QRcode::png($office_theta, $filepath2, QR_ECLEVEL_M, 6);
  $filepath2 = '/pdf/' . $filepath2;
}

// TCPDFライブラリ読み込み
require_once( $Root . "/TCPDF/tcpdf.php" );

// 各設定
$tcpdf = new TCPDF( "L", "mm", "A4", true, "UTF-8" );
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




// 内容
$html = <<< EOF
<style>
  table {
    font-size: 7.5px;
  }
</style>

<div class="wrapper">
<table border="0" cellspacing="0" cellpadding="0">



</table>

</div>
EOF;

// 生成
$tcpdf->writeHTML($html);
// I -> ブラウザ出力, D -> ダウンロード, F -> サーバーに保存
$tcpdf->Output( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name, 'F' );

//  }

echo '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name;

 ?>
