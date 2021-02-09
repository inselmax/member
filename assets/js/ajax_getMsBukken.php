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

      // シャトー若杉
      case '01':
        return 'https://spreadsheets.google.com/feeds/list/1oCyOqL76GIi2MIQxC74TpysESyU2Mo2vk_PVprNZzH4/od6/public/values?alt=json';
        break;

      // 若杉ロイヤル
      case '02':
        return 'https://spreadsheets.google.com/feeds/list/1k36g2V6dltMSK5BpsWFvz0gv8LZx9lDwlPULH3qsd_8/od6/public/values?alt=json';
        break;

      // エスポワール若杉
      case '03':
        return 'https://spreadsheets.google.com/feeds/list/1kAfo0f2cHRE7zO_0jbX_hzHn8clUvnxB9FJhTM0bP6U/od6/public/values?alt=json';
        break;

      default:
        return 'https://spreadsheets.google.com/feeds/list/1oCyOqL76GIi2MIQxC74TpysESyU2Mo2vk_PVprNZzH4/od6/public/values?alt=json';
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
      <th scope="col">間取</th>
      <th scope="col">広さ</th>
      <th scope="col">賃料</th>
      <th scope="col">共益費</th>
      <th scope="col">礼金</th>
      <th scope="col">図面</th>
      <th scope="col">物件ページ</th>
      <th scope="col">物件詳細</th>
      <th scope="col" class="hidden">諸条件書</th>
      <th scope="col">検討リスト</th>
    </tr>';

  if( $data_bukken ) {

    $data_new = array();
    foreach ($data_bukken as $key => $value) {
      array_push($data_new, $value);
    }

    $area_arr = array(
      '01' => 'shato',
      '02' => 'royal',
      '03' => 'espoir'
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
          $b_link = '/mansion/' . $area_arr[substr($b_id, 0, 2)] . '/intro.php?room=' . $b_id;
          // 号室
          $b_gou = ltrim( substr( $b_id, 2, 4 ), "0" );
          // 階数
          $b_kai = $value['gsx$階数']['$t'];
          // 間取
          $b_madori = $value['gsx$間取']['$t'];
          // 坪数
          $b_tsubo = $value['gsx$契約面積']['$t'];
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
          $chinryo = $value['gsx$賃料']['$t'];
          if( is_numeric($chinryo) ) {
            $p_chinryo = number_format($chinryo) . '円';
          }else {
            $p_chinryo = "応相談";
          }

          // 共益費
          $kyoekihi = $value['gsx$共益費']['$t'];
          if( is_numeric($kyoekihi) ) {
            $p_kyoekihi = number_format($kyoekihi) . '円';
          }else {
            $p_kyoekihi = "応相談";
          }

          echo '<tr>

            <td>' . $b_kai . 'F</td>
            <td>' . $b_gou . '</td>
            <td class="topicsContent"><span class="topicsContent_state ' . $b_state_class . '">' . $b_state . '</span></td>
            <td>' . $b_madori . '</td>
            <td>' . $b_tsubo . '&#13217;</td>
            <td>' . $p_chinryo . '</td>
            <td>' . $p_kyoekihi . '</td>
            <td>あり</td>
            <td><a href="' . $b_outline . '" rel="lightbox"><img src="/image_common/layout_thumb_m.png" alt=""></a></td>
            <td class="btnCell"><div class="ButtonE"><a href="' . $b_link . '" target="_brank">物件詳細</a></div></td>
            <td class="btnCell">
              <div class="ButtonE ButtonE-pdf">
                <div class="pdf_form">
                  <button class="pdfFormSubmit" data-oid="' . $b_id . '" data-otype="m">
                    <span class="ButtonB_inner">PDF <span class="loading hidden"><img src="/js/ajax-loader.gif"></span></span>
                  </button>
                </div>
              </div>
            </td>
            <td class="btnCell hidden">
              <div class="ButtonE ButtonE-pdf">
                <div class="pdf_form">
                  <button class="pdfFormSubmit" data-oid="' . $b_id . '" data-otype="m_1">
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
    echo '<tr><td colspan="13" style="background: #f6f6f6;text-align: left;padding: 15px;">ただ今、空室はございません。</td></tr>';
  }

  echo '</tbody></table>';

  exit;

 ?>
