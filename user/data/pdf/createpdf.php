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
  $company_ctat = "4dx2dt";
  $company_upat = "wk4qs6";

  $company_name = "有限会社スペースソリューション オフィス営業部"; // 会社名
  $company_address = "大阪市北区天神橋2丁目５－２５ 若杉グランドビル本館"; // 住所
  $company_tel = "TEL:06-6357-7771 FAX:06-6357-7772"; // 電話番号
  $company_logo = "admin/logo.png"; // 会社ロゴ

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
  $company_shop = $row_data['shop']; // 屋号
  $company_address = $row_data['address']; // 住所
  $company_tel = $row_data['tel']; // 電話番号
  $company_fax = ""; // FAX
  if( $row_data['fax'] ) {
    $company_fax = "FAX:" . $row_data['fax'];
  }
  $company_license = $row_data['license']; // 宅建免許番号
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
$bldg_name = "";
$bldg_address = "";
$bldg_kozo = "";
$bldg_shunko = "";
$bldg_menseki = "";
$bldg_moyori = "";
$bldg_other = "";
$bldg_img = "";
$bldg_map = "";

$office_id = "";
$office_link = "";
$office_kai = "";
$office_gou = "";
$office_tsubo = "";
$office_status = "";
$office_outline = "";
$office_date = "";
$office_other = "";
$office_theta = "";

$area_data_arr = array(
  '01' => array(
    'name' => '若杉センタービル本館',
    'slug' => 'center_bldg_honkan',
    'address' => '〒530-0044　大阪市北区東天満2丁目9番1号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:17階建',
    'shunko' => '1992年　新耐震基準対応',
    'menseki' => '9515.01',
    'moyori' => 'JR東西線「大阪天満宮」駅9号出口より徒歩0分<br>大阪メトロ谷町線「南森町」駅より徒歩3分<br>大阪メトロ堺筋線「南森町」駅より徒歩3分<br>JR環状線「天満」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_01.png',
    'img' => '/pdf/image_uniq/center_bldg_honkan.png',
    'map' => '/pdf/image_uniq/center_bldg_honkan_map.png'
  ),
  '02' => array(
    'name' => '若杉センタービル別館',
    'slug' => 'center_bldg_bekkan',
    'address' => '〒530-0044　大阪市北区東天満2丁目8番1号',
    'kozo' => '鉄骨造　地上:10階建',
    'shunko' => '1991年　新耐震基準対応',
    'menseki' => '3166.17',
    'moyori' => 'JR東西線「大阪天満宮」駅9号出口より徒歩1分<br>大阪メトロ谷町線「南森町」駅より徒歩4分<br>大阪メトロ堺筋線「南森町」駅より徒歩4分<br>JR環状線「天満」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_02.png',
    'img' => '/pdf/image_uniq/center_bldg_bekkan.png',
    'map' => '/pdf/image_uniq/center_bldg_bekkan_map.png'
  ),
  '03' => array(
    'name' => '若杉グランドビル本館',
    'slug' => 'grand_bldg_honkan',
    'address' => '〒530-0041　大阪市北区天神橋2丁目5番25号',
    'kozo' => '鉄骨鉄筋コンクリート（SRC）地上:11階建/地下1階',
    'shunko' => '1987年　新耐震基準対応',
    'menseki' => '5666.82',
    'moyori' => 'JR東西線「大阪天満宮」駅7号出口より徒歩1分<br>大阪メトロ谷町線「南森町」駅より徒歩3分<br>大阪メトロ堺筋線「南森町」駅より徒歩3分<br>JR環状線「天満」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_03.png',
    'img' => '/pdf/image_uniq/grand_bldg_honkan.png',
    'map' => '/pdf/image_uniq/grand_bldg_honkan_map.png'
  ),
  '04' => array(
    'name' => '若杉グランドビル別館',
    'slug' => 'grand_bldg_bekkan',
    'address' => '〒530-0044　大阪市北区東天満1丁目11番15号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:13階建',
    'shunko' => '1987年　新耐震基準対応',
    'menseki' => '1645.92',
    'moyori' => 'JR東西線「大阪天満宮」駅8号出口より徒歩1分<br>大阪メトロ谷町線「南森町」駅より徒歩3分<br>大阪メトロ堺筋線「南森町」駅より徒歩3分<br>JR環状線「天満」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_04.png',
    'img' => '/pdf/image_uniq/grand_bldg_bekkan.png',
    'map' => '/pdf/image_uniq/grand_bldg_bekkan_map.png'
  ),
  '05' => array(
    'name' => '若杉大阪駅前ビル',
    'slug' => 'osakaekimae_bldg',
    'address' => '〒530-0002　大阪市北区曽根崎新地2丁目3番13号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:19階建/地下1階',
    'shunko' => '1982年　旧耐震基準対応',
    'menseki' => '3850.3',
    'moyori' => '大阪メトロ四ツ橋線「西梅田」駅9番出口より徒歩2分<br>JR東西線「北新地」駅より徒歩3分<br>大阪メトロ御堂筋線「梅田」駅より徒歩6分<br>大阪メトロ谷町線「東梅田」駅より徒歩9分<br>阪神本線「梅田」駅より徒歩6分<br>JR環状線「大阪」駅より徒歩7分',
    'other' => '/pdf/image_uniq/other_05.png',
    'img' => '/pdf/image_uniq/osakaekimae_bldg.png',
    'map' => '/pdf/image_uniq/osakaekimae_bldg_map.png'
  ),
  '06' => array(
    'name' => '若杉西梅田ビル',
    'slug' => 'nishiumeda_bldg',
    'address' => '〒553-0003　大阪市福島区福島5丁目1番1号',
    'kozo' => '鉄骨造　地上:10階建',
    'shunko' => '1990年　新耐震基準対応',
    'menseki' => '1019.85',
    'moyori' => '阪神本線「福島」駅1番出口より徒歩2分<br>JR環状線「福島」駅より徒歩5分<br>JR東西線「新福島」駅より徒歩5分<br>大阪メトロ四ツ橋線「西梅田」駅より徒歩9分<br>JR東西線「北新地」駅より徒歩8分<br>JR環状線「大阪」駅より徒歩9分<br>大阪メトロ御堂筋線「梅田」駅より徒歩12分',
    'other' => '/pdf/image_uniq/other_06.png',
    'img' => '/pdf/image_uniq/nishiumeda_bldg.png',
    'map' => '/pdf/image_uniq/nishiumeda_bldg_map.png'
  ),
  '07' => array(
    'name' => '若杉東梅田ビル',
    'slug' => 'higashiumeda_bldg',
    'address' => '〒530-0027　大阪市北区堂山町18番2号',
    'kozo' => '鉄骨造　地上:14階建/地下1階',
    'shunko' => '1988年　新耐震基準対応',
    'menseki' => '1082.14',
    'moyori' => '阪急線「大阪梅田」駅より徒歩約6分<br>大阪メトロ谷町線「中崎町」駅より徒歩約4分<br>大阪メトロ谷町線「東梅田」駅より徒歩約10分<br>大阪メトロ御堂筋線「梅田」駅より徒歩約10分<br>阪神本線「梅田」駅より徒歩約10分<br>JR環状線「大阪」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_07.png',
    'img' => '/pdf/image_uniq/higashiumeda_bldg.png',
    'map' => '/pdf/image_uniq/higashiumeda_bldg_map.png'
  ),
  '08' => array(
    'name' => '若杉梅田ビル',
    'slug' => 'umeda_bldg',
    'address' => '〒530-0026　大阪市北区神山町2番1号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:8階建',
    'shunko' => '1974年　旧耐震基準対応',
    'menseki' => '721.81',
    'moyori' => '大阪メトロ堺筋線「扇町」駅5番出口より徒歩5分<br>阪神本線「梅田」駅より徒歩6分<br>大阪メトロ谷町線「東梅田」駅より徒歩7分<br>JR環状線「天満」駅より徒歩10分<br>JR環状線「大阪」駅より徒歩10分',
    'other' => '/pdf/image_uniq/other_08.png',
    'img' => '/pdf/image_uniq/umeda_bldg.png',
    'map' => '/pdf/image_uniq/umeda_bldg_map.png'
  ),
  '09' => array(
    'name' => '若杉ビル',
    'slug' => 'wakasugi_bldg_nakatsu',
    'address' => '〒531-0071　大阪市北区中津1丁目18番18号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:10階建',
    'shunko' => '1973年　旧耐震基準対応',
    'menseki' => '2757.65',
    'moyori' => '大阪メトロ御堂筋線「中津」駅2番出口より徒歩1分<br>阪急神戸線「中津」駅より徒歩8分',
    'other' => '/pdf/image_uniq/other_09.png',
    'img' => '/pdf/image_uniq/wakasugi_bldg_nakatsu.png',
    'map' => '/pdf/image_uniq/wakasugi_bldg_nakatsu_map.png'
  ),
  '10' => array(
    'name' => 'ニュー若杉ビル',
    'slug' => 'new_bldg',
    'address' => '〒534-0024　大阪市都島区東野田町1丁目21番14号',
    'kozo' => '鉄骨鉄筋コンクリート　地上:10階建',
    'shunko' => '1974年　旧耐震基準対応',
    'menseki' => '2873.03',
    'moyori' => '大阪メトロ長堀鶴見緑地線「京橋」駅1番出口より徒歩1分<br>JR環状線「京橋」駅より徒歩2分<br>京阪本線「京橋」駅より徒歩3分',
    'other' => '/pdf/image_uniq/other_10.png',
    'img' => '/pdf/image_uniq/new_bldg.png',
    'map' => '/pdf/image_uniq/new_bldg_map.png'
  )
);


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
  $bldg_name = $area_data_arr[$bldg_id]['name'];
  $bldg_address = $area_data_arr[$bldg_id]['address'];
  $bldg_kozo = $area_data_arr[$bldg_id]['kozo'];
  $bldg_shunko = $area_data_arr[$bldg_id]['shunko'];
  $bldg_menseki = $area_data_arr[$bldg_id]['menseki'];
  $bldg_moyori = $area_data_arr[$bldg_id]['moyori'];
  $bldg_other = $area_data_arr[$bldg_id]['other'];
  $bldg_img = $area_data_arr[$bldg_id]['img'];
  $bldg_map = $area_data_arr[$bldg_id]['map'];

  $office_kai = $data_office['gsx$階数']['$t'];
  $office_gou = ltrim(substr( $office_id, 2, 4 ), '0');
  $office_tsubo = $data_office['gsx$契約面積坪']['$t'];
  $office_status = $data_office['gsx$状態入居予定時期']['$t'];
  $office_outline = $data_office['gsx$室内図面url']['$t'];
  $office_date = $data_office['gsx$登録日']['$t'];
  $office_update_at = $data_office['gsx$最終更新日']['$t'];
  $office_other = $data_office['gsx$備考']['$t'];

  // 号室
  if( $data_office['gsx$連結']['$t'] != "" && $data_office['gsx$連結']['$t'] != "0" ) {
    $office_gou = $office_gou . " - " . $data_office['gsx$連結']['$t'];
  }

  // 賃料
  $chinryo = $data_office['gsx$賃料坪単価']['$t'];
  if( is_numeric($chinryo) ) {
    $chinryo = ( $chinryo * $office_tsubo ); // 計算式
    $p_chinryo = number_format($chinryo) . '円';
  }

  // 共益費
  $kyoekihi = $data_office['gsx$共益費坪単価']['$t'];
  if( is_numeric($kyoekihi) ) {
    $kyoekihi = ( $kyoekihi * $office_tsubo ); // 計算式
    $p_kyoekihi = number_format($kyoekihi) . '円';
  }

  // 賃料合計
  $total = "応相談";
  if( is_numeric($chinryo) && is_numeric($kyoekihi) ) {
    $total = ( $chinryo + $kyoekihi ); // 計算式
    $p_total = number_format($total) . '円';
  }

  // 物件ページリンク
  $office_link = $pagePath . $area_data_arr[$bldg_id]['slug'] . '/intro.php?office=' . $office_id;

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
$pdf_file_name = $office_id . '_' . $office_update_at . $company_ctat . $company_upat . '.pdf';


// *-----------------------------------------*/
// TCPDF
// *-----------------------------------------*/

// ファイルチェック  ※実機アップの時は318、326、604のコメントアウトを外す
  // if( file_exists( $Root . '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name ) ) {



  // ファイルが存在している



  // }else {



  // ファイルが存在しない



// PHPQRCODEライブラリ読み込み
require_once( $Root . "/phpqrcode/qrlib.php" );

$filepath = 'temp/qr/' . $office_id . '_qr.png';
QRcode::png($office_link, $filepath, QR_ECLEVEL_M, 6);
$filepath = '/pdf/' . $filepath;

if( $office_theta != "" ) {
  $filepath2 = 'temp/qr/' . $office_id . '_theta_qr.png';
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
  .tit01 {
    font-size: 15px;
    font-weight: bold;
  }
  .tit02 {
    font-size: 12px;
    font-weight: bold;
  }
  .tit03 {
    font-size: 10px;
    font-weight: bold;
  }
  .tit04 {
    font-size: 10px;
    font-weight: bold;
  }
  .blue {
    color: #047298;
  }
  .white {
    color: #fff;
  }
  .line-top {
    border-top: 2px solid #047298;
  }
  .line-tit-border {
    border-bottom: 1px solid #047298;
  }
</style>

<div class="wrapper">



<table border="0" cellspacing="0" cellpadding="0">


  <tr>
    <!-- 左サイド -->
    <td valign="top" rowspan="2" width="190" align="center">
      <img src="{$bldg_img}" height="510px">
    </td>
    <!-- / 左サイド -->
    <!-- 帯 -->
    <td colspan="2" bgcolor="#047298">
      <table border="0" width="585" cellspacing="0" cellpadding="3" class="white">
        <tr bgcolor="#047298">
          <th width="460" align="left">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr width="460">
                <th class="tit01" width="180">{$bldg_name}</th>
                <td class="tit02" width="80">フロア：{$office_kai}F</td>
                <td class="tit02" width="90">号室：1102-1</td>
                <td class="tit02" width="110">契約面積：{$office_tsubo}坪</td>
              </tr>
              <tr width="460">
                <th class="tit04" colspan="4">{$bldg_address}</th>
              </tr>
            </table>
          </th>
          <td width="130" align="center">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr bgcolor="#fff" width="130">
                <th class="tit04">
                  <font color="#000">物件ID：{$office_id}</font>
                </th>
              </tr>
              <tr width="130">
                <td class="tit04">登録日：{$office_date}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

    </td>
		<!-- / 帯 -->
  </tr>

  <tr>
    <!-- 帯下-真ん中 -->
		<td valign="top" width="255">
      <table border="0" width="255" cellspacing="0" cellpadding="4">
        <tr>
          <th colspan="4" align="left" width="250" class="tit03 blue line-tit-border">契約情報</th>
        </tr>
        <tr>
          <th align="left" width="60">階数</th>
          <td align="left" width="195">{$office_kai}F</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">広さ</th>
          <td align="left" width="190">{$office_tsubo}坪</td>
        </tr>
        <tr>
          <th align="left">賃料</th>
          <td align="left">{$p_chinryo}（税別）</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">共益費</th>
          <td align="left" width="190">{$p_kyoekihi}（税別）</td>
        </tr>
        <tr>
          <th align="left">賃料合計</th>
          <td align="left">{$p_total}（税別）</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">保証金・敷金</th>
          <td align="left" width="190">応相談</td>
        </tr>
        <tr>
          <th align="left">解約引</th>
          <td align="left">スライド</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">礼金</th>
          <td align="left" width="190">なし</td>
        </tr>
        <tr>
          <th align="left">更新料</th>
          <td align="left">なし</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">定期借家契約</th>
          <td align="left" width="190">なし</td>
        </tr>
        <tr>
          <th align="left">入居時期</th>
          <td align="left">{$office_status}</td>
        </tr>

      </table>
      <table border="0" width="255" cellspacing="0" cellpadding="4">
        <tr>
          <th colspan="2" align="left" width="250" class="tit03 blue line-tit-border">物件情報</th>
        </tr>
        <tr>
          <th align="left" width="60">物件名</th>
          <td align="left" width="195">{$bldg_name}</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">所在地</th>
          <td align="left" width="190">{$bldg_address}</td>
        </tr>
        <tr>
          <th align="left">構造（規模）</th>
          <td align="left">{$bldg_kozo}</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">竣工</th>
          <td align="left" width="190">{$bldg_shunko}</td>
        </tr>
        <tr>
          <th align="left">延床面積</th>
          <td align="left">{$bldg_menseki}m<sup>2</sup></td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">沿線・<br>最寄り駅</th>
          <td align="left" width="190">{$bldg_moyori}</td>
        </tr>
        <tr>
          <th align="left">備考</th>
          <td align="left">{$office_other}</td>
        </tr>
      </table>
    </td>
		<!-- / 帯下-真ん中 -->
    <!-- 帯下-右サイド -->
    <td valign="top" width="339">
      <table border="0" cellspacing="0" cellpadding="4">
        <tr>
          <th align="left" class="tit03 blue line-tit-border" colspan="4">間取図</th>
        </tr>
        <tr>
          <td align="center" colspan="4"><img src="{$office_outline}" height="320px"></td>
        </tr>
        <tr>
          <td width="130" align="left"><img src="{$bldg_other}"></td>
          <td width="70"><img src="{$filepath2}" alt="THETA QRコード" /><br>　{$office_theta_head}</td>
          <td width="70"><img src="{$bldg_map}" alt="地図 QRコード" /><br>　周辺地図</td>
          <td width="70" align="left"><img src="{$filepath}" alt="物件 QRコード" /><br>　物件情報</td>
        </tr>
      </table>
    </td>
    <!-- / 帯下-右サイド -->
  </tr>
  <tr>
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="780" colspan="3" valign="top" class="line-top">
        </td>
      </tr>
      <tr>
        <td width="50">
          <img src="/member/user/data/upload/{$company_logo}" alt="logo" height="30px">
        </td>
        <td width="150" valign="top">
          <table border="0" cellspacing="0" cellpadding="1">
            <tr>
              <th class="tit03">{$company_name}</th>
            </tr>
            <tr>
              <td width="150">{$company_address}</td>
            </tr>
          </table>
        </td>
        <td width="180" valign="top"　align="left">
          <table border="0" cellspacing="0" cellpadding="1">
            <tr>
              <th class="tit03" colspan="2">{$company_shop}</th>
            </tr>
            <tr>
              <td width="90">TEL:{$company_tel}</td>
              <td width="90">{$company_fax}</td>
            </tr>
          </table>
        </td>
        <td class="tit03" width="170">{$company_license}</td>
        <td width="230" align="right">
          契約条件については弊社担当者までお問い合わせください。<br>図面現況が異なる場合は、現況を優先させていただきます。
        </td>
      </tr>
    </table>
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
