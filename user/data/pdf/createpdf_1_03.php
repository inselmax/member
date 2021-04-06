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
$pagePath = "https://wakasugi-bldg.jp/building/";
// $pagePath = "https://new.wakasugi-bldg.jp/building/";
// $pagePath = "https://wakasugi.dm-test-server01.com/building/";
// $pagePath = $Root . '/building/';


// *-----------------------------------------*/
// DB
// *-----------------------------------------*/

require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );


// ログインしているかどうか
if( isset($_SESSION['ADMIN']) ) {

  $company_id = "admin";
  $company_ctat = "b2km43";
  $company_upat = "qjm6az";

}elseif( isset($_SESSION['ID']) ) {

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

    // センター本館
    case '01':
      $ssPass = '145BUoKmqHDaKhHytTbiY2uEAgbX0b7D4WnJXvjg-6OA';
      break;

    // センター別館
    case '02':
      $ssPass = '1TSzkGF_u-Lqdfh1hIpC34xuMGGFyLzuOIrTK7puvrAk';
      break;

    // グランド本館
    case '03':
      $ssPass = '1r75Sm07BbVTdjpmiL3GGfnTu_NnfTETv_D00-WvrGmI';
      break;

    // グランド別館
    case '04':
      $ssPass = '1Zx2QcpSeFdVA8Om8JUrD_76GWt5lmQk8VnzCt-UaaPU';
      break;

    // 大阪駅前
    case '05':
      $ssPass = '1mvibMplMcgpPi0DFbf2-Pp_hvPx7fmgaKCT5XGnz1yc';
      break;

    // 西梅田
    case '06':
      $ssPass = '1HxHCY2hO0iZKjC28iSX-Bfrg-md5ZkibHb0565aQuKc';
      break;

    // 東梅田
    case '07':
      $ssPass = '1Ar3Bg0kSdkVF4cj1t0oLvEexXdVgAUKHJGaR5zYJ1Fc';
      break;

    // 若杉梅田
    case '08':
      $ssPass = '1O_awKB6ZKMKFpwvJ3zbc4odmeVFAWZP5qVFnjh6xDlQ';
      break;

    // 若杉ビル（中津）
    case '09':
      $ssPass = '1YG_9swASM4i60LObp8v6EsOr67lUGQ5b97kqAEcSKJc';
      break;

    // ニュー若杉
    case '10':
      $ssPass = '1keO5Rt73lMCXN4NUQl6MNehen3AEASHmks8mePy9-2Q';
      break;

    default:
      $ssPass = '145BUoKmqHDaKhHytTbiY2uEAgbX0b7D4WnJXvjg-6OA';
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
$office_create_at = "";
$office_kai = "";
$office_gou = "";
$office_tsubo = "";
$office_outline = "";
$office_date = "";
$office_other = "";
$office_staff = "";


// *-----------------------------------------*/
// データ取得
// *-----------------------------------------*/

// POSTされたデータをチェック
if ( !empty($_POST['office_id']) && strlen($_POST['office_id']) == 7 ) {

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
  }

  // 詳細データを変数に格納
  $office_create_at = $data_office['gsx$登録日']['$t'];
  $office_kai = $data_office['gsx$階数']['$t'];
  $office_gou = ltrim(substr( $office_id, 2, 4 ), '0');
  $office_tsubo = $data_office['gsx$契約面積坪']['$t'];
  $office_outline = $data_office['gsx$室内図面url']['$t'];
  $office_date = $data_office['gsx$登録日']['$t'];
  $office_update_at = $data_office['gsx$最終更新日']['$t'];
  $office_staff = $data_office['gsx$担当スタッフ名']['$t'];

  // 号室
  if( $data_office['gsx$連結']['$t'] != "" && $data_office['gsx$連結']['$t'] != "0" ) {
    $office_gou = $office_gou . " - " . $data_office['gsx$連結']['$t'];
  }

  // 賃料
  $office_chinryo = $data_office['gsx$賃料坪単価']['$t'];
  if( is_numeric($office_chinryo) ) {
    $tanka_chinryo = number_format($office_chinryo);

    $price_chinryo = $office_chinryo * $office_tsubo;
    $price_chinryo = number_format($price_chinryo);
  }else {
    $tanka_chinryo = "要相談";
    $price_chinryo = "要相談";
  }

  // 共益費
  $office_kyoekihi = $data_office['gsx$共益費坪単価']['$t'];
  if( is_numeric($office_kyoekihi) ) {
    $tanka_kyoekihi = number_format($office_kyoekihi);

    $price_kyoekihi = $office_kyoekihi * $office_tsubo;
    $price_kyoekihi = number_format($price_kyoekihi);
  }else {
    $tanka_kyoekihi = "要相談";
    $price_kyoekihi = "要相談";
  }

  // 保証金
  $office_hosho = $data_office['gsx$保証金坪単価']['$t'];
  if( is_numeric($office_hosho) ) {
    $tanka_hosho = number_format($office_hosho);
    $price_hosho = $office_hosho * $office_tsubo;
    $price_hosho = number_format($price_hosho);
  }else {
    $tanka_hosho = "要相談";
    $price_hosho = "要相談";
  }

  // 月額合計
  if( is_numeric($office_chinryo) && is_numeric($office_kyoekihi) ) {
    $price_total = ( $office_chinryo * $office_tsubo ) + ( $office_kyoekihi * $office_tsubo );
    $price_total = number_format($price_total);
  }else {
    $price_total = "要相談";
  }

  // 専有部床清掃費
  $tanka_clean = 1000;
  $price_clean = $tanka_clean * $office_tsubo;
  $price_clean = number_format($price_clean);
  $tanka_clean = number_format($tanka_clean);


  // ネームプレート
  $price_nameplate = "25,000";

  // 表看板掲載料
  $price_signboard = "要相談";

  // 火災保険料
  $price_fire = "23,020";

  // 駐車場
  $price_parkingMon = "28,500";
  $price_parkingDeposit = "57,000";

}else {

  return false;

}


// PDFファイル名
// $pdf_file_name = 'officeData_' . $office_id . '_' . $timestamp . '.pdf';
$pdf_file_name = $office_id . '_1_' . $office_update_at . $company_ctat . $company_upat . '.pdf';


// *-----------------------------------------*/
// TCPDF
// *-----------------------------------------*/

// ファイルチェック  ※実機アップの時は318、326、604のコメントアウトを外す
  // if( file_exists( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name ) ) {



  // ファイルが存在している



  // }else {



  // ファイルが存在しない



// TCPDFライブラリ読み込み
require_once( $Root . "/TCPDF/tcpdf.php" );

// 各設定
$tcpdf = new TCPDF( "L", "mm", "A4", true, "UTF-8" );
$tcpdf->setPrintHeader( false );
$tcpdf->setPrintFooter( false );
$tcpdf->SetAutoPageBreak(TRUE, 0);
// $tcpdf->SetMargins( 0 );
// $tcpdf->setCellPaddings(0,0,0,0);
$tcpdf->SetLeftMargin( 10 );
// $tcpdf->SetRightMargin( 3 );
$tcpdf->SetTopMargin( 13 );
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
  font-size: 7.8px;
  letter-spacing: 0.5em;
}


.tit_head {
text-align: center;
font-size: 14px;
background-color: #047298;
color:#fff;
}

.item {
font-size: 9.5px;
text-decoration: underline;
}

.p_text {
text-decoration: underline;
margin: 0px;
}

.b_border td {
border-bottom: 1px solid black;
}

.l_text {
font-size: 10px;
}

.bg_gray {
background-color: #f0f0f0;
}

.p_left {
padding-left: 2px;
}

.s_text {
font-size: 6.3px;
}

.border {
border: 1px solid black;
}

.dotborder {
border: 1px dashed black;
margin-top: 10px;
}

.noborder {
border: 1px solid white;
}

.txt_light {
  color: #555555;
  background-color: #f0f0f0;
}

.dottedline {
  border-bottom: 1px dashed #555555;
}

.dottedline-top {
  border-top: 0px dashed #555555;
}

.border-l {
  border-left: 1px solid #000;
}

.border-r {
  border-right: 1px solid #000;
}


</style>



<div class="wrapper">

<table border="0" cellspacing="0" cellpadding="2" style="width: 270mm;">
  <tr>
    <td style="width: 132mm;">
      <table cellpadding="3" style="width: 132mm;">
        <tr>
          <th class="tit_head" align="right" style="width: 65mm;">若杉グランドビル本館</th>　
          <th class="tit_head" align="left" style="width: 33mm;">諸条件書</th>
          <td style="width: 4mm;"></td>
          <td align="right" style="width: 30mm; height: 5mm;"><img src="/member/user/data/pdf/sample/logo.png"></td>
        </tr>
      </table>
      <table border="0" cellspacing="3" cellpadding="0" style="width: 132mm;">
        <tr>
          <td class="dotborder">
            <table border="0" cellspacing="0" cellpadding="2" style="width: 132mm;">
              <tr>
                <td align="center" style="width: 20mm;" class="border">外観写真
                </td>
                <td style="width: 38mm;"></td>
                <td align="center" style="width: 20mm;" class="border">募集区図面
                </td>
              </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="2" style="width: 132mm;">
              <tr>
                <td align="left" style="width: 55mm; height: 64mm;">
                <img src="/member/user/data/pdf/sample/image03_01.jpg" style="height: 64mm;">
                </td>
                <td align="left" style="width: 92mm; height: 64mm;">
                <img src="{$office_outline}" style="width: 58mm;">
                </td>
              </tr>
            </table>
          </td>
        </tr>    
      </table>
      <table cellpadding="0" cellspacing="1" style="width: 132mm;">
        <tr>
          <th class="item">1.建物概要</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellspacing="0" cellpadding="1" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">物件名</th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>若杉グランドビル本館</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">所在地</th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>〒530-0041&emsp;大阪市北区天神橋2-5-25</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">竣工年</th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>1987年&emsp;新耐震基準対応</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">構造</th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>鉄骨鉄筋コンクリート造（SRC造）</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">階数</th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>地上11階／地下1階</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="4">
                    <tr>
                      <td>沿線・最寄り駅</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>JR東西線「大阪天満宮」駅7号出入口より徒歩1分<br><span class="s_text">※他の沿線は『物件の図面』にて掲載</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table border="0" cellspacing="1" cellpadding="0" style="width: 101mm;">
        <tr>
          <th class="item">2.条件対象となる貸室</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellspacing="0" cellpadding="1" style="width: 101mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>階数</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 65mm;">
                  <table style="width: 65mm;" cellpadding="1">
                    <tr>
                      <td class="l_text" align="center" style="width: 20mm;">{$office_kai}</td>
                      <td align="right" style="width: 5mm;">階</td>
                      <td class="l_text" align="center" style="width: 27mm;">{$office_gou}</td>
                      <td align="right" style="width: 8mm;">号室</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>契約面積</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 65mm;">
                  <table style="width: 65mm;" cellpadding="1">
                    <tr>
                      <td class="l_text" align="center" style="width: 50mm;">{$office_tsubo}</td>
                      <td align="right" style="width: 10mm;">坪</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table border="0" cellspacing="1" cellpadding="0" style="width: 132mm;">
        <tr>
          <th class="item">3.契約の諸条件</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellspacing="0" cellpadding="1" style="width: 132mm;">
              <tr>
                <th rowspan="2" align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="2" style="height: 6mm;"></th>
                    </tr>
                    <tr>
                      <td style="width: 10.2mm;"></td>
                      <td align="left">保証金</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0">
                    <tr>
                      <td class="l_text" align="center" style="width: 85mm;">{$price_hosho}</td>
                      <td align="right" style="width: 10mm;">円</td>
                    </tr>
                    <tr>
                      <td align="right" style="width: 60mm;">坪単価 (</td>
                      <td class="l_text" align="center" style="width: 25mm;">{$tanka_hosho}</td>
                      <td align="right" style="width: 10mm;">) 円</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0">
                    <tr>
                      <td align="left" style="width: 98mm;"><span class="s_text">※契約終了時に契約年数に応じて償却引きが発生。<br>※償却引き率：3年未満30%、10年未満20％、15年未満15%、20年未満10％、20年以上無し</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="5">
                    <tr>
                      <td>月額固定費</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0" cellspacing="3">
                    <tr>
                      <td class="l_text" align="center" style="width: 67.7mm;">{$price_total}</td>
                      <td align="right" style="width: 24.4mm;">円＋消費税</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th rowspan="2" style="width: 8mm;" class="bg_gray">
                  <table cellpadding="2">
                    <tr>
                      <td style="height: 1mm;"></td>
                    </tr>
                    <tr>
                      <td align="center">&emsp;内訳</td>
                    </tr>
                  </table>
                </th>
                <th style="width: 25mm;" class="bg_gray txt_light dottedline border-r">
                  <table cellpadding="8">
                    <tr>
                      <td align="center">月額賃料</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;" class="dottedline border-l">
                  <table style="width: 98mm;">
                    <tr>
                      <td class="l_text" align="center" style="width: 70mm;">{$price_chinryo}</td>
                      <td align="right" style="width: 25mm;">円＋消費税</td>
                    </tr>
                    <tr>
                      <td align="right" style="width: 60mm;">坪単価 (</td>
                      <td class="l_text" align="center" style="width: 25mm;">{$tanka_chinryo}</td>
                      <td align="right" style="width: 10mm;">) 円</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th style="width: 25mm;" class="bg_gray txt_light">
                  <table cellpadding="8">
                    <tr>
                      <td align="center">月額共益費</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;" class="dottedline-top">
                  <table style="width: 98mm;">
                    <tr>
                      <td class="l_text" align="center" style="width: 70mm;">{$price_kyoekihi}</td>
                      <td align="right" style="width: 25mm;">円</td>
                    </tr>
                    <tr>
                      <td align="right" style="width: 60mm;">坪単価 (</td>
                      <td class="l_text" align="center" style="width: 25mm;">{$tanka_kyoekihi}</td>
                      <td align="right" style="width: 10mm;">) 円</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>

    <td style="width: 6mm;"></td>

    <td style="width: 132mm;">
      <table border="0" cellspacing="1" cellpadding="0" style="width: 101mm;">
        <tr>
          <th class="item">4.契約時、契約中に発生する諸費用</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="1" style="width: 101mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>ネームプレート作成費</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 65mm;">
                  <table style="width: 65mm;" cellpadding="0">
                    <tr>
                      <td class="l_text" align="center" style="width: 35mm;">{$price_nameplate}</td>
                      <td align="right" style="width: 25mm;">円＋消費税</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table style="width: 132mm;" cellpadding="0" cellspacing="2">
              <tr>
                <td class="s_text">※1階テナント案内板、ポスト、貸室ドアに社名を表記します。<br>※契約時のみ発生します。但し、貸借人都合による再作成は、同費用が必要となります。<br>※専用ロゴを作成する場合は、使用カラー、ロゴ形態によって別途費用が必要になります。</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="0" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>表看板掲載料 (月額)</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;" colspan="2">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td class="l_text" align="left" style="width: 15mm;">{$price_signboard}</td>
                      <td align="left" style="width: 80mm;">円＋消費税<span class="s_text">&emsp;&emsp;※別途、ネームプレート作成費が必要となります。</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>火災保険 (要加入)</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;" colspan="2">
                  <table style="width: 98mm;" cellpadding="2">
                    <tr>
                      <td align="left" style="width: 98mm;">当社指定の代理店より、直接ご提案させていただきます。</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>専有部床清掃費（※1）</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td class="l_text" align="left" style="width: 15mm;">{$price_clean}</td>
                      <td align="left" style="width: 80mm;">円＋消費税&emsp;（坪当たり1,000円＋消費税）</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table style="width: 132mm;" cellpadding="1" cellspacing="1">
              <tr>
                <td class="s_text">※1 年2回、ビル側で専有部内タイルカーペットの洗浄清掃を行います。上記金額は1回分の金額となり、原則年2回の床清掃を<br>実施いたします。</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="1" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>貸室内の光熱費</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">毎月メーター検針を行い、賃料等と併せてご請求となります。</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table style="width: 132mm;" cellpadding="1" cellspacing="1">
              <tr>
                <td class="s_text">※電気代単価：1kwhあたり35円（基本料なし）　ガス空調単価：1㎥あたり130円（基本料なし）</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="1" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="0">
                    <tr>
                      <td>駐車場<span class="s_text">(若杉グランドビル本館駐車場)</span></td>
                    </tr>
                  </table>
                </th>
                <td style="width: 49mm;">
                  <table style="width: 49mm;" cellpadding="2">
                    <tr>
                      <td align="left" style="width: 11mm;">月額</td>
                      <td class="l_text" align="center" style="width: 15mm;">{$price_parkingMon}</td>
                      <td align="right" style="width: 19mm;">円＋消費税</td>
                    </tr>
                  </table>
                </td>
                <td style="width: 49mm;">
                  <table style="width: 49mm;" cellpadding="2">
                    <tr>
                      <td align="left" style="width: 11mm;">保証金</td>
                      <td class="l_text" align="center" style="width: 15mm;">{$price_parkingDeposit}</td>
                      <td align="right" style="width: 19mm;">円（非課税）</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table style="width: 132mm;" cellpadding="0" cellspacing="1">
              <tr>
                <td class="s_text">※「若杉グランドビル本館駐車場」　立体駐車場<br>※入出庫可能時間：月曜～土曜7:30～21:30　日曜・祝日・お盆・年末年始は入出庫不可<br>※サイズ：高さ1,550mm × 車幅1,750mm × 全長5,050mm 重量1,600kg</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>  
      <table border="0" cellspacing="1" cellpadding="0" style="width: 101mm;">
        <tr>
          <th class="item">5.主な契約内容</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="1" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>賃貸借契約期間</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">2ヵ年契約　以降自動更新となります。</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>解約予告期間</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">6ヵ月前に書面による予告が必要です。</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>連帯保証人</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">基本1名の設定が必要です。</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="8">
                    <tr>
                      <td>原状回復</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0">
                    <tr>
                      <td align="left" style="width: 96mm;">損耗にかかわらず、床タイルカーペット張替え、天井・壁面塗装（又は、ク<br>ロス張替え）、扉枠、窓面カウンター塗装、等の原状回復費用は、退去時貸<br>借人様ご負担となります。</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>  
      <table border="0" cellspacing="1" cellpadding="0" style="width: 101mm;">
        <tr>
          <th class="item">6.その他</th>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="1" style="width: 132mm;">
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="10">
                    <tr>
                      <td>ビル開館時間</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0">
                    <tr>
                      <td align="left" style="width: 98mm;">月曜～土曜　7:30～21:30<br>時間外・日曜・祝日・お盆・年末年始は、セキュリティカードにて出入可能。<br>※引越は、「土曜日（祝日以外）」のみとなります。</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>空調</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">ビルマルチ空調<span class="s_text">&emsp;&emsp;※ビルマルチエアコン（冷暖房の切り替えはビル側にて実施）</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="1">
                    <tr>
                      <td>エレベーター</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">2基</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="2">
                    <tr>
                      <td>給湯・衛生設備</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">共用<span class="s_text">（トイレ全面リニューアル済み）</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th rowspan="2" align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="8">
                    <tr>
                      <td>セキュリティ</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 101mm;">管理人常駐による警備（月～土8:00～18:00　日祝なし）</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 101mm;">セコムによる機械警備及びビル共用部防犯カメラ</td>
                    </tr>
                  </table>
                  </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="4">
                    <tr>
                      <td>鍵</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="0">
                    <tr>
                      <td align="left" style="width: 98mm;">初回、シリンダーキー２本・セコムカードキー２枚を無料で貸与<br><span class="s_text">※追加セコムカードキー2,000円＋消費税／枚</span></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th align="center" class="bg_gray" style="width: 33mm;">
                  <table cellpadding="6">
                    <tr>
                      <td>喫煙</td>
                    </tr>
                  </table>
                </th>
                <td style="width: 98mm;">
                  <table style="width: 98mm;" cellpadding="1">
                    <tr>
                      <td align="left" style="width: 98mm;">室内を含み、全館禁煙です。<br>※電子タバコはご利用いただけます。</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table border="0" cellspacing="1" cellpadding="0" style="width: 101mm;">  
        <tr>
          <th class="item">7.契約時必要書類</th>
        </tr>
        <tr>
          <td style="width: 132mm;">
            <table style="width: 132mm;" cellpadding="0" cellspacing="1">
              <tr>
                <td align="left" style="width: 132mm;">契約の際は、契約者、連帯保証人それぞれの下記書類が必要です。</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" cellpadding="2">
              <tr>
                <td align="left" style="width: 132mm;">
                  <table cellspacing="3">
                    <tr>
                      <td>法人：①会社謄本&emsp;②印鑑登録証明&emsp;③決算書（直近）</td>
                    </tr>
                    <tr>
                      <td>個人：①住民票&emsp;②印鑑登録証明&emsp;③収入証明（源泉徴収票、市町村が発行する課税証明書など）</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>

  </tr>
</table>


</div>
EOF;

// 生成
$tcpdf->writeHTML($html);
// I -> ブラウザ出力, D -> ダウンロード, F -> サーバーに保存
$tcpdf->Output( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name, 'F' );

  // }

echo '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name;

 ?>
