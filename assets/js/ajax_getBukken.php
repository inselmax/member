<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];

// *-----------------------------------------*/
// DB
// *-----------------------------------------*/

require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );


if( isset($_SESSION['ADMIN']) ) {

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

  // 割引率
  $company_rate = $row_data['rate']; // 割引率

  // DB切断
  $pdo = null;

}else {

  return false;

}

  function getSeetUrl( $bldgId ) {
    switch ($bldgId) {

      // センター本館
      case '01':
        return 'https://spreadsheets.google.com/feeds/list/145BUoKmqHDaKhHytTbiY2uEAgbX0b7D4WnJXvjg-6OA/od6/public/values?alt=json';
        break;

      // センター別館
      case '02':
        return 'https://spreadsheets.google.com/feeds/list/1TSzkGF_u-Lqdfh1hIpC34xuMGGFyLzuOIrTK7puvrAk/od6/public/values?alt=json';
        break;

      // グランド本館
      case '03':
        return 'https://spreadsheets.google.com/feeds/list/1r75Sm07BbVTdjpmiL3GGfnTu_NnfTETv_D00-WvrGmI/od6/public/values?alt=json';
        break;

      // グランド別館
      case '04':
        return 'https://spreadsheets.google.com/feeds/list/1Zx2QcpSeFdVA8Om8JUrD_76GWt5lmQk8VnzCt-UaaPU/od6/public/values?alt=json';
        break;

      // 大阪駅前
      case '05':
        return 'https://spreadsheets.google.com/feeds/list/1mvibMplMcgpPi0DFbf2-Pp_hvPx7fmgaKCT5XGnz1yc/od6/public/values?alt=json';
        break;

      // 西梅田
      case '06':
        return 'https://spreadsheets.google.com/feeds/list/1HxHCY2hO0iZKjC28iSX-Bfrg-md5ZkibHb0565aQuKc/od6/public/values?alt=json';
        break;

      // 東梅田
      case '07':
        return 'https://spreadsheets.google.com/feeds/list/1Ar3Bg0kSdkVF4cj1t0oLvEexXdVgAUKHJGaR5zYJ1Fc/od6/public/values?alt=json';
        break;

      // 若杉梅田
      case '08':
        return 'https://spreadsheets.google.com/feeds/list/1O_awKB6ZKMKFpwvJ3zbc4odmeVFAWZP5qVFnjh6xDlQ/od6/public/values?alt=json';
        break;

      // 若杉ビル（中津）
      case '09':
        return 'https://spreadsheets.google.com/feeds/list/1YG_9swASM4i60LObp8v6EsOr67lUGQ5b97kqAEcSKJc/od6/public/values?alt=json';
        break;

      // ニュー若杉
      case '10':
        return 'https://spreadsheets.google.com/feeds/list/1keO5Rt73lMCXN4NUQl6MNehen3AEASHmks8mePy9-2Q/od6/public/values?alt=json';
        break;

      case 'all':
        return 'https://spreadsheets.google.com/feeds/list/11Aa7EVPys8iC5AxE5X_X5CwJDZs2GyvM2Y7uTthWOts/od6/public/values?alt=json';
        break;

      default:
        return 'https://spreadsheets.google.com/feeds/list/145BUoKmqHDaKhHytTbiY2uEAgbX0b7D4WnJXvjg-6OA/od6/public/values?alt=json';
        break;
    }
  }
 ?>


<?php

  $data_count = 0;

  // ビルIDを取得
  $bldg_id = $_POST['bldg_id'];
  $b_count = $_POST['b_count'];

  // ビルIDからシートURL（json）を取得
  $seet_url = getSeetUrl( $bldg_id );

  $json_bukken = file_get_contents($seet_url);
  $json_decode_bukken = json_decode($json_bukken, true);
  $data_bukken = $json_decode_bukken['feed']['entry'];

  echo '<table class="t_office"><tbody>

      <tr>
        <th scope="col">階数</th>
        <th scope="col">号室</th>
        <th scope="col">状態</th>
        <th scope="col">広さ</th>
        <th scope="col">賃料</th>
        <th scope="col">共益費 </th>
        <th scope="col">保証金</th>
        <th scope="col">図面</th>
        <th scope="col">物件ページ</th>
        <th scope="col">物件詳細</th>
        <th scope="col">諸条件書</th>
        <th scope="col">検討リスト</th>
      </tr>';

  if( $data_bukken ) {

    $data_new = array();
    foreach ($data_bukken as $key => $value) {
      array_push($data_new, $value);
    }

    $area_arr = array(
      '01' => 'center_bldg_honkan',
      '02' => 'center_bldg_bekkan',
      '03' => 'grand_bldg_honkan',
      '04' => 'grand_bldg_bekkan',
      '05' => 'osakaekimae_bldg',
      '06' => 'nishiumeda_bldg',
      '07' => 'higashiumeda_bldg',
      '08' => 'umeda_bldg',
      '09' => 'wakasugi_bldg_nakatsu',
      '10' => 'new_bldg'
    );

    function compareByTimeStamp($time1, $time2)
    {
      if (strtotime($time1['gsx$登録日']['$t']) < strtotime($time2['gsx$登録日']['$t']))
        return 1;
      else if (strtotime($time1['gsx$登録日']['$t']) > strtotime($time2['gsx$登録日']['$t']))
        return -1;
      else
        return 0;
    }

    // usort($data_new, "compareByTimeStamp");
    // $data_new = array_slice($data_new, 0, 12);

    foreach ($data_new as $key => $value) {

      if( $data_count == $b_count ) {
        break;
      }

      if( $value['gsx$表示']['$t'] == 'on' ){

        if ( $value['gsx$状態']['$t'] != '空きなし' ) {

          // ID
          $b_id = $value['gsx$物件id']['$t'];
          // リンク
          $b_link = '/building/' . $area_arr[substr($b_id, 0, 2)] . '/intro.php?office=' . $b_id;
          // 号室
          $b_gou = ltrim( substr( $b_id, 2, 4 ), "0" );
          // 階数
          $b_kai = $value['gsx$階数']['$t'];
          // 坪数
          $b_tsubo = $value['gsx$契約面積坪']['$t'];
          // 登録日
          $b_date = date("Y.m.d", strtotime($value['gsx$登録日']['$t']));
          // 室内レイアウト（画像パス）
          $b_outline = $value['gsx$室内図面url']['$t'];
          // 空き状態
          $b_state = $value['gsx$状態']['$t'];
          // 空き状態 ( class )
          $b_state_class = 'topicsContent_state-will';
          if ($value['gsx$状態']['$t'] == '空きあり') {
            $b_state_class = 'topicsContent_state-now';
          }

          // 賃料
          $b_chinryo = $value['gsx$賃料坪単価']['$t'];
          if( is_numeric($b_chinryo) ) {
            $b_chinryo = $b_chinryo * $b_tsubo;
            $b_chinryo = number_format($b_chinryo) . '円';
          }else {
            $b_chinryo = "応相談";
          }

          // 共益費
          $b_kyoekihi = $value['gsx$共益費坪単価']['$t'];
          if( is_numeric($b_kyoekihi) ) {
            $b_kyoekihi = $b_kyoekihi * $b_tsubo;
            $b_kyoekihi = number_format($b_kyoekihi) . '円';
          }else {
            $b_kyoekihi = "応相談";
          }

          // 保証金
          $b_hoshokin = $value['gsx$保証金坪単価']['$t'];
          if( is_numeric($b_hoshokin) ) {
            $b_hoshokin = $b_hoshokin * $b_tsubo;
            $b_hoshokin = number_format($b_hoshokin) . '円';
          }else {
            $b_hoshokin = "応相談";
          }

          echo '<tr>

            <td>' . $b_kai . 'F</td>
            <td>' . $b_gou . '</td>
            <td class="topicsContent"><span class="topicsContent_state ' . $b_state_class . '">' . $b_state . '</span></td>
            <td>' . $b_tsubo . '坪</td>
            <td>' . $b_chinryo . '</td>
            <td>' . $b_kyoekihi . '</td>
            <td>' . $b_hoshokin . '</td>
            <td><a href="' . $b_outline . '" rel="lightbox"><img src="/image_common/layout_thumb.png" alt=""></a></td>
            <td class="btnCell"><div class="ButtonE"><a href="' . $b_link . '" target="_brank">物件詳細</a></div></td>
            <td class="btnCell">
              <div class="ButtonE ButtonE-pdf">
                <div class="pdf_form">
                  <button class="pdfFormSubmit" data-oid="' . $b_id . '" data-otype="b">
                    <span class="ButtonB_inner">PDF <span class="loading hidden"><img src="/js/ajax-loader.gif"></span></span>
                  </button>
                </div>
              </div>
            </td>
            <td class="btnCell">
              <div class="ButtonE ButtonE-pdf">
                <div class="pdf_form">
                  <button class="pdfFormSubmit" data-oid="' . $b_id . '" data-otype="b_1_' . substr($b_id, 0, 2) . '">
                    <span class="ButtonB_inner">PDF <span class="loading hidden"><img src="/js/ajax-loader.gif"></span></span>
                  </button>
                </div>
              </div>
            </td>
            <td class="btnCell">
              <div class="ButtonE ButtonE-favorite">
                <a class="bldg_id" href="javascript:void(0);" onclick="setCookie(this)" data-id="' . $b_id . '" tabindex="0"><span>検討リストに追加</span></a>
              </div>
            </td>

          </tr>';

        $data_count++;

        }

      }

    }
  }

  if( $data_count == 0 ) {
    echo '<tr><td colspan="12" style="background: #f6f6f6;text-align: left;padding: 15px;">ただ今、空室はございません。</td></tr>';
  }

  echo '</tbody></table>';

  exit;

 ?>
