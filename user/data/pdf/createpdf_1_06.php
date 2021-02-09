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

  // 火災保険料・テナント総合保険
  $price_fire = "22,180";

  // 駐車場
  $price_parkingMon = "";
  $price_parkingDeposit = "";

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




// 内容
$html = <<< EOF
<style>

table {
    font-size: 7px;
  }


.tit_head {
  text-align: center;
  font-size: 14px;
  background-color: #047298;
  color:#fff;
}

.item {
  font-size: 8px;
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
  font-size: 9.3px;
}

.bg_gray {
  background-color: #f0f0f0;
}

.p_left {
  padding-left: 2px;
}

.s_text th, .s_text td{
  font-size: 6.5px;
}


</style>



<div class="wrapper">

<table border="0" cellspacing="0" cellpadding="2" style="width: 190mm;">
    <tr>
      <td align="right">{$office_create_at}</td>
    </tr>
    <tr>
      <th>
        <table cellpadding="3" style="width: 190mm;">
          <tr>
            <th class="tit_head" align="right" style="width: 105mm;">若杉西梅田ビル</th>　
            <th class="tit_head" align="left" style="width: 85mm;">諸条件書</th>
          </tr>
        </table>
      </th>
    </tr>
    <tr>
      <td>
        <table style="width: 190mm;">
          <tr>
            <td align="right" style="width: 180mm;">担当：有限会社スペースソリューション</td>
            <td align="right" style="width: 10mm;">{$office_staff}</td>
          </tr>
        </table>
      </td>
    </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" style="width: 190mm;">
    <tr>
      <td class="tbl_01" style="width: 80mm;">
          <table cellspacing="0" cellpadding="1" style="width: 75mm;">
              <tr>
                  <th class="item">1.建物概要</th>
              </tr>
              <tr>
                  <td>
                    <table border="1" cellspacing="0" cellpadding="1" style="width: 75mm;">
                      <tr>
                          <th align="center" class="bg_gray" style="width: 20mm;">所在地</th>
                          <td style="width: 50mm;">
                            <table style="width: 50mm;" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td>福島区福島5丁目1番1号</td>
                              </tr>
                            </table>
                          </td>
                      </tr>
                      <tr>
                          <th align="center" class="bg_gray" style="width: 20mm;">竣工年</th>
                          <td style="width: 50mm;">
                            <table style="width: 50mm;" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td>1990年6月</td>
                              </tr>
                            </table>
                          </td>
                      </tr>
                      <tr>
                          <th align="center" class="bg_gray" style="width: 20mm;">構造</th>
                          <td style="width: 50mm;">
                            <table style="width: 50mm;" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td>鉄骨造（S造）</td>
                              </tr>
                              <tr>
                                <td>地上10階建て</td>
                              </tr>
                            </table>
                          </td>
                      </tr>
                    </table>
                  </td>
              </tr>
          </table>
        </td>
        <td rowspan="2" align="center" style="width: 40mm; height: 31.5mm;">
            <img src="/member/user/data/pdf/sample/img06_01.jpg" style="height: 31.5mm;">
        </td>
        <td rowspan="2" align="center" style="width: 70mm; height: 31.5mm;">
            <img src="{$office_outline}" style="height: 31.5mm;">
        </td>
    </tr>
    <tr>
      <td style="width: 80mm;">
          <table cellspacing="0" cellpadding="1" style="width: 75mm;">
            <tr>
                <th class="item">2.条件対象となる貸室</th>
            </tr>
            <tr>
              <td>
                <table border="1" cellspacing="0" cellpadding="1" style="width: 75mm;">
                    <tr>
                        <th align="center" class="bg_gray" style="width: 20mm;">階数</th>
                        <td style="width: 50mm;">
                          <table style="width: 50mm;">
                            <tr>
                              <td align="center" style="width: 15mm;">{$office_kai}</td>
                              <td align="left" style="width: 5mm;">階</td>
                              <td align="center" style="width: 20mm;">{$office_gou}</td>
                              <td align="left" style="width: 10mm;">号室</td>
                            </tr>
                          </table>
                        </td>
                    </tr>
                    <tr>
                        <th align="center" class="bg_gray" style="width: 20mm;">契約面積</th>
                        <td style="width: 50mm;">
                          <table style="width: 50mm;">
                            <tr>
                              <td align="center" style="width: 40mm;">{$office_tsubo}</td>
                              <td align="left" style="width: 10mm;">坪</td>
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

<table border="0" cellspacing="0" cellpadding="2" style="width: 190mm;">
    <tr>
      <th class="item" colspan="3">3.契約の諸条件</th>
    </tr>
    <tr>
        <td style="width: 90mm;">
            <table border="1" cellspacing="0" cellpadding="1" style="width: 90mm;">
                <tr>
                    <th class="bg_gray" align="center" cellpadding="1" style="width: 35mm;">保証金総額</th>
                    <td style="width: 55mm;">
                      <table style="width: 55mm;">
                        <tr>
                          <td class="l_text" align="center" style="width: 45mm;">{$price_hosho}</td>
                          <td align="left" style="width: 10mm;">円</td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                      <table style="width: 90mm;">
                        <tr>
                          <td align="right" style="width: 35mm;">坪単価 (</td>
                          <td align="center" style="width: 30mm;">{$tanka_hosho}</td>
                          <td align="left" style="width: 25mm;">) 円</td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="1" style="width: 84mm;">
                          <tr>
                            <td>■保証金の償却引き</td>
                          </tr>
                          <tr>
                            <td>契約終了時に契約年数に応じて、以下の割合で保証金から償却引きが発生致します。</td>
                          </tr>
                          <tr>
                            <td>
                                <table class="s_text" align="center" border="1" cellspacing="0" cellpadding="1" style="width: 77mm;">
                                    <tr>
                                        <th class="bg_gray" style="width: 37mm;">3年未満</th>
                                        <td style="width: 37mm;">保証金の30％</td>
                                    </tr>
                                    <tr>
                                        <th class="bg_gray" style="width: 37mm;">10年未満</th>
                                        <td style="width: 37mm;">保証金の20％</td>
                                    </tr>
                                    <tr>
                                        <th class="bg_gray" style="width: 37mm;">15年未満</th>
                                        <td style="width: 37mm;">保証金の15％</td>
                                    </tr>
                                    <tr>
                                        <th class="bg_gray" style="width: 37mm;">20年未満</th>
                                        <td style="width: 37mm;">保証金の10％</td>
                                    </tr>
                                    <tr>
                                        <th class="bg_gray" style="width: 37mm;">20年以上</th>
                                        <td style="width: 37mm;">無し</td>
                                    </tr>
                                </table>
                            </td>
                          </tr>
                          <tr>
                            <td>※契約年数に関わらず、別途原状回復費用は発生致します。</td>
                          </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 10mm;"></td>
        <td style="width: 90mm;">
            <table border="1" cellspacing="0" cellpadding="1" style="width: 90mm;">
                    <tr>
                        <th class="bg_gray" align="center" style="width: 35mm;">月額固定費合計</th>
                        <td style="width: 55mm;">
                          <table style="width: 55mm;">
                            <tr>
                              <td class="l_text" align="center" style="width: 30mm;">{$price_total}</td>
                              <td align="left" style="width: 25mm;">円＋消費税</td>
                            </tr>
                          </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table>
                              <tr>
                                <td rowspan="2" align="center">
                                  <table cellpadding="8">
                                    <tr>
                                      <td>月額賃料</td>
                                    </tr>
                                  </table>
                                </td>
                                <td>
                                  <table class="b_border" style="width: 35mm;" cellpadding="3">
                                    <tr>
                                      <td align="center" style="width: 30mm;">{$price_chinryo}</td>
                                      <td align="left" style="width: 5mm;">円</td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <table style="width: 45mm;" cellpadding="3">
                                    <tr>
                                      <td align="left" style="width: 10mm;">坪単価</td>
                                      <td align="center" style="width: 20mm;">{$tanka_chinryo}</td>
                                      <td align="left" style="width: 5mm;">円</td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td rowspan="2" align="center">
                                  <table cellpadding="8">
                                    <tr>
                                      <td>月額共益費</td>
                                    </tr>
                                  </table>
                                </td>
                                <td>
                                  <table class="b_border" style="width: 35mm;" cellpadding="3">
                                    <tr>
                                      <td align="center" style="width: 30mm;">{$price_kyoekihi}</td>
                                      <td align="left" style="width: 5mm;">円</td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <table style="width: 45mm;" cellpadding="3">
                                    <tr>
                                      <td align="left" style="width: 10mm;">坪単価</td>
                                      <td align="center" style="width: 20mm;">{$tanka_kyoekihi}</td>
                                      <td align="left" style="width: 5mm;">円</td>
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

<table border="0" cellspacing="0" cellpadding="2" style="width: 190mm;">
    <tr>
      <th class="item" colspan="3">4.契約時、契約中に発生する諸費用</th>
    </tr>
    <tr>
      <th style="width: 35mm;" rowspan="2">〇ネームプレート作成費</th>
      <td style="width: 150mm;" colspan="2">
        <table class="b_border" style="width: 150mm;">
          <tr>
            <td align="center" style="width: 20mm;">{$price_nameplate}</td>
            <th align="left" style="width: 15mm;">円(税別)</th>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="width: 150mm;" colspan="2">契約時のみ発生します。1階テナント案内板、ポスト、貸室ドアに社名を表記します。<br>専用ロゴを作成する場合は、使用カラー、ロゴ形態によって別途費用が必要になります。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇表看板掲載料(月額)</th>
      <td style="width: 150mm;" colspan="2">
        <table class="b_border" style="width: 150mm;">
          <tr>
            <td align="center" style="width: 20mm;">{$price_signboard}</td>
            <th align="left" style="width: 90mm;">円(税別)※別途ネームプレート作成費が必要となります。</th>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇テナント総合保険</th>
      <td style="width: 150mm;" colspan="2">
        <table class="b_border" style="width: 150mm;">
          <tr>
            <td align="center" style="width: 20mm;">{$price_fire}</td>
            <th align="left" style="width: 90mm;">円(非課税・2年)</th>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <th style="width: 35mm;" rowspan="2">〇専有部床清掃費</th>
      <td style="width: 150mm;" colspan="2">
        <table class="b_border" style="width: 150mm;">
          <tr>
            <td align="center" style="width: 20mm;">{$price_clean}</td>
            <th align="left" style="width: 40mm;">円(税別) (坪当たり{$tanka_clean}円)</th>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="width: 150mm;" colspan="2">※年2回、ビル側で専有部内タイルカーペットの洗浄清掃を行います。<br>※上記金額は1回分の金額となります。原則年2回の床清掃を実施いたします。</td>
    </tr>
    <tr>
      <th style="width: 35mm;" rowspan="2">〇エアコン洗浄費</th>
      <td style="width: 150mm;" colspan="2">契約開始から５年ごとに１回、エアコン分解洗浄に要する費用</td>
    </tr>
    <tr>
      <td style="width: 150mm;" colspan="2">
        <table style="width: 150mm;" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" style="width: 15mm;" cellspacing="0" cellpadding="0">1台あたり</th>
            <td align="center" style="width: 15mm;">20,000</td>
            <td align="left" style="width: 30mm;">円（消費税別）とする</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇貸室内の光熱費</th>
      <td style="width: 150mm;" colspan="2">毎月メーター検針を行い、賃料等と併せてご請求いたします。電気代単価：1kwhあたり35円（基本料無し）</td>
    </tr>
    <tr>
      <th class="item" colspan="3">5.主な契約内容</th>
    </tr>
    <tr>
      <th style="width: 35mm;">〇賃貸借期間</th>
      <td style="width: 150mm;" colspan="2">2ヵ年契約 以降自動更新となります。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇解約予告期間</th>
      <td style="width: 150mm;" colspan="2">6ヶ月前に書面による予告が必要です。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇連帯保証人</th>
      <td style="width: 150mm;" colspan="2">基本1名必要です。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇火災保険の加入</th>
      <td style="width: 150mm;" colspan="2">破壊・破損・汚損も対象とする借家人賠償責任担保条項・物損害担保条項・施設賠償責任担保条項を含むものにご加入ください。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇原状回復</th>
      <td style="width: 150mm;" colspan="2">床タイルカーペット張替え、天井・壁面塗装塗り替え(又はクロス張替え)、窓面カウンターの塗装関係等、原状回復に掛かる費用は退去時賃借人様ご負担となります。
      </td>
    </tr>
    <tr>
      <th class="item" colspan="3">6.その他</th>
    </tr>
    <tr>
      <th style="width: 35mm;" rowspan="2">〇ビル開館時間</th>
      <td style="width: 150mm;" colspan="2">月曜～土曜 7:30～20:30&emsp;時間外・日曜・祝日・お盆・年末年始はセキュリティカードにて出入りは可能です。</td>
    </tr>
    <tr>
      <td style="width: 150mm;" colspan="2"><span class="p_text">※引越しは「土曜日（祝日以外）」のみとなります。</span></td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇空調</th>
      <td style="width: 150mm;" colspan="2">個別空調</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇エレベーター</th>
      <td style="width: 150mm;" colspan="2">1基</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇セキュリティ</th>
      <td style="width: 150mm;" colspan="2">管理人常駐による警備（月曜～土曜&emsp;8:00～18:00実施、日祝は無）<br>セコムによる機械警備及びビル共用部防犯カメラ</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇鍵</th>
      <td style="width: 150mm;" colspan="2">初回は、シリンダーキー2本・セコムカードキー2枚を無料で貸与。<br>以降は、セコムカードキー１枚につき2,000円(税別)が必要となります。</td>
    </tr>
    <tr>
      <th style="width: 35mm;">〇館内での喫煙について</th>
      <td style="width: 150mm;" colspan="2">2021年より全館禁煙（室内含む）とさせて頂きます。<br>電子タバコはご利用頂けますが、灰皿の設置はございませんので、各自で処理頂きますようお願いいたします。
      </td>
    </tr>
    <tr>
      <th class="item" colspan="3">7.必要書類（契約時）</th>
    </tr>
    <tr>
      <th style="width: 35mm;"></th>
      <td style="width: 150mm;" colspan="2">契約の際は以下の書類が必要となります。法人契約、個人契約により異なります。</td>
    </tr>
    <tr>
      <th style="width: 35mm;"></th>
      <td style="width: 70mm;">
        <table class="s_text" border="1" cellspacing="0" cellpadding="1">
          <tr>
            <th class="bg_gray" align="center" colspan="2">法人契約</th>
          </tr>
          <tr>
            <th class="bg_gray" align="center">申込法人様</th>
            <th class="bg_gray" align="center">連帯保証人様</th>
          </tr>
          <tr>
            <td>①会社謄本</td>
            <td>①住民票</td>
          </tr>
          <tr>
            <td>②印鑑証明書</td>
            <td>②印鑑証明書</td>
          </tr>
          <tr>
            <td></td>
            <td>③収入証明</td>
          </tr>
        </table>
      </td>
      <td style="width: 80mm;">
        <table class="s_text" border="1" cellspacing="0" cellpadding="1">
          <tr>
            <th class="bg_gray" align="center" colspan="2">個人契約</th>
          </tr>
          <tr>
            <th class="bg_gray" align="center">申込法人様</th>
            <th class="bg_gray" align="center">連帯保証人様</th>
          </tr>
          <tr>
            <td colspan="2">①住民票</td>
          </tr>
          <tr>
            <td colspan="2">②印鑑証明書</td>
          </tr>
          <tr>
            <td colspan="2">③収入証明（源泉徴収票市町村が発行する課税証明書など）</td>
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
