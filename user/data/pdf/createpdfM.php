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
// $pagePath = "http://new.wakasugi-bldg.jp/mansion/";
// $pagePath = "https://wakasugi.dm-test-server01.com/mansion/";
// $pagePath = $Root . '/mansion/';


// *-----------------------------------------*/
// DB
// *-----------------------------------------*/

require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );


// ログインしているかどうか
if( isset($_SESSION['ADMIN']) ) {

  $company_id = "admin";
  $company_ctat = "zacre5";
  $company_upat = "5meetu";

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
$office_madori = "";
$office_tsubo = "";
$office_status = "";
$office_outline = "";
$office_date = "";
$office_other = "";
$office_theta = "";

$area_data_arr = array(
  '01' => array(
    'name' => 'シャトー若杉マンション',
    'slug' => 'shato',
    'address' => '〒535-0002　大阪府大阪市旭区大宮4丁目1-11',
    'kozo' => 'SRC(鉄骨鉄筋コンクリート)　地上:9階建',
    'shunko' => '1978年12月',
    'menseki' => '-',
    'moyori' => '大阪メトロ谷町線「千林大宮」駅より徒歩7分<br>京阪電気鉄道京阪線「千林」駅より徒歩14分<br>大阪メトロバス「中宮」バス停より徒歩4分',
    'other' => '/pdf/image_uniq/other_mansion.png',
    'img' => '/pdf/image_uniq/shato.png',
    'map' => '/pdf/image_uniq/shato_map.png'
  ),
  '02' => array(
    'name' => '若杉ロイヤルマンション',
    'slug' => 'royal',
    'address' => '〒535-0003　大阪府大阪市旭区中宮3丁目13−8',
    'kozo' => 'RC(鉄筋コンクリート)　地上:6階建',
    'shunko' => '1986年1月',
    'menseki' => '-',
    'moyori' => '大阪メトロ谷町線「千林大宮」駅より徒歩10分<br>京阪電気鉄道京阪線「千林」駅より徒歩17分<br>大阪メトロバス「中宮」バス停より徒歩3分',
    'other' => '/pdf/image_uniq/other_mansion.png',
    'img' => '/pdf/image_uniq/royal.png',
    'map' => '/pdf/image_uniq/royal_map.png'
  ),
  '03' => array(
    'name' => 'エスポワール若杉マンション',
    'slug' => 'espoir',
    'address' => '〒534-0002　大阪府大阪市都島区大東町2-3-7',
    'kozo' => 'RC(鉄筋コンクリート)　地上:5階建',
    'shunko' => '1983年6月',
    'menseki' => '-',
    'moyori' => 'おおさか東線「城北公園通」」駅より徒歩5分<br>大阪メトロ谷町線「都島」駅より自転車10分<br>大阪メトロバス「大東町」バス停より徒歩1分',
    'other' => '/pdf/image_uniq/other_mansion.png',
    'img' => '/pdf/image_uniq/espoir.png',
    'map' => '/pdf/image_uniq/espoir_map.png'
  )
);


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
  $office_madori = $data_office['gsx$間取']['$t'];
  $office_tsubo = $data_office['gsx$契約面積']['$t'];
  $office_status = $data_office['gsx$状態入居予定時期']['$t'];
  $office_outline = $data_office['gsx$室内図面url']['$t'];
  $office_date = $data_office['gsx$登録日']['$t'];
  $office_update_at = $data_office['gsx$最終更新日']['$t'];
  $office_other = $data_office['gsx$備考']['$t'];

  // 賃料
  $chinryo = $data_office['gsx$賃料']['$t'];
  if( is_numeric($chinryo) ) {
    $p_chinryo = number_format($chinryo) . '円';
  }else {
    $p_chinryo = "応相談";
  }

  // 共益費
  $kyoekihi = $data_office['gsx$共益費']['$t'];
  if( is_numeric($kyoekihi) ) {
    $p_kyoekihi = number_format($kyoekihi) . '円';
  }else {
    $p_kyoekihi = "応相談";
  }

  // 賃料合計
  $total = "応相談";
  if( is_numeric($chinryo) && is_numeric($kyoekihi) ) {
    $total = ( $chinryo + $kyoekihi ); // 計算式
    $p_total = number_format($total) . '円';
  }

  // 物件ページリンク
  $office_link = $pagePath . $area_data_arr[$bldg_id]['slug'] . '/intro.php?room=' . $office_id;

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
$pdf_file_name = 'm_' . $office_id . '_' . $office_update_at . $company_ctat . $company_upat . '.pdf';


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
      <table border="0" width="" cellspacing="0" cellpadding="3" class="white">
        <tr bgcolor="#047298">
          <td width="460" align="left">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <th class="tit01" width="235">{$bldg_name}</th>
                <td class="tit02" width="100" valign="bottom">フロア：{$office_kai}F</td>
                <td class="tit02" width="125" valign="bottom">間取り：{$office_madori}</td>
              </tr>
              <tr>
                <th colspan="3" class="tit04">{$bldg_address}</th>
              </tr>
            </table>
          </td>
          <td width="130" align="center">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr bgcolor="#fff" width="110">
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
          <th align="left">間取り</th>
          <td align="left" width="190">{$office_madori}</td>
        </tr>
        <tr>
          <th align="left">広さ</th>
          <td align="left" width="195">{$office_tsubo}m<sup>2</sup></td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">賃料</th>
          <td align="left" width="190">{$p_chinryo}</td>
        </tr>
        <tr>
          <th align="left">共益費</th>
          <td align="left">{$p_kyoekihi}</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">賃料合計</th>
          <td align="left" width="190">{$p_total}</td>
        </tr>
        <tr>
          <th align="left">保証金・敷金</th>
          <td align="left">応相談</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">解約引</th>
          <td align="left" width="190">スライド</td>
        </tr>
        <tr>
          <th align="left">礼金</th>
          <td align="left">あり</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">更新料</th>
          <td align="left" width="190">なし</td>
        </tr>
        <tr>
          <th align="left">火災保険</th>
          <td align="left">加入義務有り（弊社指定）</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">入居時期</th>
          <td align="left" width="190">{$office_status}</td>
        </tr>

      </table>
      <table border="0" width="255" cellspacing="0" cellpadding="4">
        <tr>
          <th colspan="2" align="left" width="250" class="tit03 blue line-tit-border">物件情報</th>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left" width="60">物件名</th>
          <td align="left" width="190">{$bldg_name}</td>
        </tr>
        <tr>
          <th align="left">所在地</th>
          <td align="left" width="195">{$bldg_address}</td>
        </tr>
        <tr bgcolor="#e1f0f5">
          <th align="left">構造（規模）</th>
          <td align="left" width="190">{$bldg_kozo}</td>
        </tr>
        <tr>
          <th align="left">築年月</th>
          <td align="left">{$bldg_shunko}</td>
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
        <td width="250" valign="top">
          <table border="0" cellspacing="0" cellpadding="1">
            <tr>
              <th class="tit03">{$company_name}</th>
            </tr>
            <tr>
              <td width="240">{$company_address}</td>
            </tr>
          </table>
        </td>
        <td width="250" align="left">
          <table border="0" cellspacing="0" cellpadding="1">
            <tr>
              <td>TEL:{$company_tel}</td>
            </tr>
          </table>
        </td>
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

//  }

echo '/member/user/data/pdf/temp/' . $company_id . '/' . $pdf_file_name;

 ?>
