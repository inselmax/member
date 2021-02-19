<?php

/* ------------------------------------------------------------------------

  * 管理者（若杉）

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・全ての仲介業者情報を表示する
// $ary (Array) DBからselectした仲介業者データ配列
// return <table>
function htmlCompanyDataTable( $ary ) {

  if( $ary ) {

    echo '<table class="table table-normal">
            <tr>
              <th class="hidden">会社ID</th>
              <th>会社名</th>
              <th>責任者氏名</th>
              <th>登録日</th>
              <th class="hidden">更新日</th>
              <th>メールアドレス</th>
              <th>状態</th>
              <th>削除</th>
            </tr>';

    foreach ($ary as $key => $value) {
      echo '<tr>';
        echo '<td class="hidden">' . $value['id'] . '</td>';
        echo '<td>' . escStr( $value['name'] ) . '</td>';
        echo '<td>' . escStr( $value['pic_name'] ) . '</td>';
        echo '<td>' . date('Y年m月d日H時m分',  strtotime($value['created_at'])) . '</td>';
        echo '<td class="hidden">' . date('Y年m月d日H時m分',  strtotime($value['updated_at'])) . '</td>';
        echo '<td>' . escStr( $value['email'] ) . '</td>';

        if( $value['status'] == 0 ) {
          echo '<td>
                  <form class="admin_approve_form" action="approve.php" method="post">
                    <input type="hidden" name="id" value="' . $value['id'] . '">
                    <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
                    <button type="submit" name="approveBtn">承認する</button>
                  </form>
                </td>';
        }else {
          echo '<td>承認済み</td>';
        }

        echo '<td>
                <form class="admin_delete_form" action="delete.php" method="post">
                  <input type="hidden" name="id" value="' . $value['id'] . '">
                  <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
                  <button type="submit" name="deleteBtn">削除</button>
                </form>
              </td>';
      echo '</tr>';
    }

    echo '</table>';

  }

}

// --------------------------------------------------------
// ・全てのユーザー情報を表示する
// $ary (Array) DBからselectしたユーザーデータ配列
// return <table>
function htmlUserDataTable( $ary ) {

  if( $ary ) {

    echo '<table class="table table-normal">
            <tr>
              <th>ユーザーID</th>
              <th>会社ID</th>
              <th>会社名</th>
              <th>担当者名</th>
              <th>メールアドレス</th>
              <th>削除</th>
            </tr>';

    foreach ($ary as $key => $value) {
      echo '<tr>';
        echo '<td>' . $value['id'] . '</td>';
        echo '<td>' . $value['company_id'] . '</td>';
        echo '<td>' . escStr( $value['c_name'] ) . '</td>';
        echo '<td>' . escStr( $value['name'] ) . '</td>';
        echo '<td>' . escStr( $value['email'] ) . '</td>';
        echo '<td>
                <form action="delete_user.php" method="post">
                  <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
                  <input type="hidden" name="id" value="' . $value['id'] . '">
                  <input type="hidden" name="company_id" value="' . $value['company_id'] . '">
                  <button type="submit" name="deleteBtn">削除</button>
                </form>
              </td>';
      echo '</tr>';
    }

    echo '</table>';

  }else {
    echo '<p>該当ユーザーなし</p>';
  }

}

/* ------------------------------------------------------------------------

  * 仲介業者（管理者）

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・会社IDからユーザー情報を表示する
// $ary (Array) DBからselectしたユーザーデータ配列
// return <table>
function htmlUserDataTable2( $ary ) {

  if( $ary ) {

    echo '<table class="table table-normal">
            <tr>
              <th>会社名</th>
              <th>担当者名</th>
              <th>メールアドレス</th>
              <th>削除</th>
            </tr>';

    foreach ($ary as $key => $value) {
      echo '<tr>';
        echo '<td>' . escStr( $value['c_name'] ) . '</td>';
        echo '<td>' . escStr( $value['name'] ) . '</td>';
        echo '<td>' . escStr( $value['email'] ) . '</td>';
        echo '<td>
                <form action="delete.php" method="post">
                  <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
                  <input type="hidden" name="id" value="' . $value['id'] . '">
                  <button type="submit" name="deleteBtn">削除</button>
                </form>
              </td>';
      echo '</tr>';
    }

    echo '</table>';

  }else {
    echo '<p>該当ユーザーなし</p>';
  }

}


/* ------------------------------------------------------------------------

  * 仲介業者（管理者＆ユーザー）

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・お問い合わせフォームを表示する
// $company_name (string) 会社名
// $name (string) 担当者・責任者氏名
// $email (string) メールアドレス
// return <form>
function htmlContactForm( $company_name, $name, $email ) {

  echo '
      <form action="" method="post"><table class="table table-normal">
        <tr><th>会社名</th><td><input type="text" name="company" value="' . escStr( $company_name ) . '"></td></tr>
        <tr><th>担当者氏名</th><td><input type="text" name="name" value="' . escStr( $name ) . '"></td></tr>
        <tr><th>メールアドレス</th><td><input type="email" name="email" value="' . escStr( $email ) . '"></td></tr>
        <tr><th>件名</th><td><input type="text" name="title"></td></tr>
        <tr><th>お問い合わせ内容</th><td><textarea name="content" cols="30" rows="10"></textarea></td></tr>
        <tr><td colspan="2"><button type="submit" class="customBtn">送信</button></td></tr>
      </table></form>
    ';

}


// --------------------------------------------------------
// ・プロフィール情報を表示する（管理者）
// $ary (Array) DBからselectした仲介業者データ配列
// return <form>
function htmlCompanyProfile( $ary ) {

  if( $ary ) {

    echo '
          <form id="media_form"><table class="table table-normal">
            <tr><th>会社ロゴ画像 アップローダー</th></tr>
            <tr>
              <td>
                <div class="mb-10">
                  <input type="file" id="mediafile" class="input_btn_01" name="mediafile">
                  <button type="button" onclick="file_upload()" class="custom_submit_btn file_upload_btn">アップロード</button>
                  <button type="button" onclick="file_reset()" class="custom_submit_btn file_reset_btn">画像を初期化</button>
                </div>
                <p class="small">※会社ロゴ画像は<span class="red">100KB以内</span>でお願いします</p>
                <div class="progress-box hidden">
                  <p class="progress-bar"></p>
                  <p class="progress-data"><span class="progress-data-num">0</span>%</p>
                </div>
              </td>
            </tr>
          </table></form>
    ';

    echo '<form action="update.php" method="post"><table class="table table-normal">';
      echo '<tr><th>会社ロゴ</th>
            <td>
              <img id="company_logo" class="f-clogo-img" src="/member/user/data/upload/' . escStr( $ary['logo_path'] ) . '">
              <input type="hidden" name="logo_path" id="logo_path" class="form_input_js" value="' . escStr( $ary['logo_path'] ) . '" />
            </td>
          </tr>';
      echo '<tr><th>会社名</th><td><input type="text" name="name" value="' . escStr( $ary['name'] ) . '"></td></tr>';
      echo '<tr><th>屋号</th><td><input type="text" name="shop" value="' . escStr( $ary['shop'] ) . '"></td></tr>';
      echo '<tr><th>住所</th><td><input type="text" name="address" value="' . escStr( $ary['address'] ) . '"></td></tr>';
      echo '<tr><th>電話番号</th><td><input type="tel" name="tel" value="' . escStr( $ary['tel'] ) . '"></td></tr>';
      echo '<tr><th>FAX</th><td><input type="tel" name="fax" value="' . escStr( $ary['fax'] ) . '"></td></tr>';
      echo '<tr><th>メールアドレス</th><td><input type="email" name="email" value="' . escStr( $ary['email'] ) . '"></td></tr>';
      echo '<tr><th>責任者氏名</th><td><input type="text" name="pic_name" value="' . escStr( $ary['pic_name'] ) . '"></td></tr>';
      echo '<tr><th>宅建免許番号</th><td><input type="text" name="license" value="' . escStr( $ary['license'] ) . '"></td></tr>';
      echo '<tr><th>取扱物件</th><td>';
        if( $ary['type'] == 1 ) {
          echo 'ビル';
        }elseif( $ary['type'] == 2 ) {
          echo 'マンション';
        }else {
          echo 'ビルとマンション';
        }
      echo '</td></tr>';
      // echo '<tr><th>割引率</th><td><input type="text" name="rate" value="' . escStr( $ary['rate'] ) . '"></td></tr>';
      echo '<tr><th class="bg-gray">パスワード（確認用）</td><td><input type="password" name="password"></td></tr>';
      echo '<tr>
              <td colspan="2">
                <div class="mb-10">
                  <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
                  <button type="submit" class="customBtn">更新</button>
                </div>
                <p class="small"><span class="red">パスワード入力後、更新ボタンを押して下さい</span></p>
              </td>
            </tr>';
    echo '</table></form>';

  }else {
    echo '<p>情報が見つかりませんでした。</p>';
  }

}


// --------------------------------------------------------
// ・プロフィール情報を表示する
// $ary (Array) DBからselectしたユーザーデータ配列
// return <form>
function htmlUserProfile( $ary ) {

  if( $ary ) {

    echo '<form><table class="table table-normal">';
      echo '<tr><th>担当者氏名</th><td>' . escStr( $ary['name'] ) . '</td></tr>';
      echo '<tr><th>メールアドレス</th><td>' . escStr( $ary['email'] ) . '</td></tr>';
      // echo '<p>' . $ary['password'] . '</p>';
    echo '</table></form>';

  }else {
    echo '<p>情報が見つかりませんでした。</p>';
  }

}


// --------------------------------------------------------
// ・仲介業者登録フォームを表示する
// return <form>
function htmlCompanyAddForm() {

  echo '
    <form action="register.php" method="post">
      <p class="mb-10">※下記フォーム全てご入力の上ご申請ください。</p>
      <table class="table table-normal">
        <tr><th>会社名</th><td><input type="text" name="name" placeholder="株式会社スペースソリューション"></td></tr>
        <tr><th>屋号</th><td><input type="text" name="shop" placeholder=""></td></tr>
        <tr><th>住所</th><td><input type="text" name="address" placeholder="大阪市北区東天満2丁目9番1号"></td></tr>
        <tr><th>電話番号</th><td><input type="tel" name="tel" pattern="[\d\-]*" placeholder="06-6357-7771"></td></tr>
        <tr><th>FAX</th><td><input type="tel" name="fax" pattern="[\d\-]*" placeholder="06-6357-7772"></td></tr>
        <tr><th>責任者氏名</th><td><input type="text" name="pic_name" placeholder="若杉太郎"></td></tr>
        <tr><th>宅建免許番号</th><td><input type="text" name="license" placeholder="大阪府知事(1)第00000号"></td></tr>
        <tr><th>取扱物件</th>
          <td>
            <label><input type="radio" name="type" value="1" checked="checked">ビル</label>　
            <label><input type="radio" name="type" value="2">マンション</label>　
            <label><input type="radio" name="type" value="3">両方</label>
          </td>
        </tr>
        <tr><th>メールアドレス</th><td><input type="email" name="email"></td></tr>
        <tr>
          <th>
            パスワード
            <p class="red small">※半角英数字それぞれ１文字以上含む、８文字以上</p>
          </th>
          <td><input type="password" name="password"></td>
        </tr>
        <tr><th>パスワード（確認）</th><td><input type="password" name="password_confirm"></td></tr>
        <tr>
          <td colspan="2">
            <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
            <button type="submit" class="customBtn">確認</button>
          </td>
        </tr>
      </table>
    </form>
  ';

}


// --------------------------------------------------------
// ・仲介業者登録フォームを表示する（確認）
// $post_data ($_POST) フォームからPOSTされたデータ郡
// return <form>
function htmlCompanyAddForm2( $post_data = null ) {

  $err = array();

  // 会社名
  if( $post_data["name"] == "" ) {
    $err["name"] = '<p class="red small mt-5">会社名が入力されていません</p>';
  }

  // 会社名
  if( $post_data["shop"] == "" ) {
    $err["shop"] = '<p class="red small mt-5">屋号が入力されていません</p>';
  }

  // 住所
  if( $post_data["address"] == "" ) {
    $err["address"] = '<p class="red small mt-5">住所が入力されていません</p>';
  }

  // 電話番号
  if( $post_data["tel"] == "" ) {
    $err["tel"] = '<p class="red small mt-5">電話番号が入力されていません</p>';
  }

  // FAX
  if( $post_data["fax"] == "" ) {
    $err["fax"] = '<p class="red small mt-5">FAXが入力されていません</p>';
  }

  // 責任者氏名
  if( $post_data["pic_name"] == "" ) {
    $err["pic_name"] = '<p class="red small mt-5">責任者氏名が入力されていません</p>';
  }

  // 宅建免許番号
  if( $post_data["license"] == "" ) {
    $err["license"] = '<p class="red small mt-5">宅建免許番号が入力されていません</p>';
  }

  // 取扱物件
  $radio_01 = $radio_02 = $radio_03 = $radio_name = "";

  if( $post_data["type"] == 1 ) {
    $radio_name = 'ビル';
    $radio_01 = 'checked';
  }elseif( $post_data["type"] == 2 ) {
    $radio_name = 'マンション';
    $radio_02 = 'checked';
  }elseif( $post_data["type"] == 3 ) {
    $radio_name = 'ビルとマンション';
    $radio_03 = 'checked';
  }else {
    $err["type"] = '<p class="red small mt-5">値が不正です</p>';
  }

  // メールアドレス
  if( $post_data["email"] == "" ) {
    $err["email"] = '<p class="red small mt-5">メールアドレスが入力されていません</p>';
  }

  // パスワード
  if( $post_data["password"] == "" ) {
    $err["password"] = '<p class="red small mt-5">パスワードが入力されていません</p>';
  }elseif( !passCheck( $post_data["password"] ) ) {
    $err["password"] = '<p class="red small mt-5">半角英数字をそれぞれ1文字以上、8文字以上で設定してください</p>';
  }

  // パスワード（確認用）
  if( $post_data["password_confirm"] == "" ) {
    $err["password_confirm"] = '<p class="red small mt-5">パスワード（確認用）が入力されていません</p>';
  }elseif( $_POST['password_confirm'] != $_POST['password'] ) {
    $err["password_confirm"] = '<p class="red small mt-5">パスワードが一致しません</p>';
  }


  if( count($err) > 0 ) { // エラー有

    echo '
      <form action="register.php" method="post">
        <p class="mb-10">※下記フォーム全てご入力の上ご申請ください。</p>
        <table class="table table-normal">
          <tr><th>会社名</th><td><input type="text" name="name" value="' . escStr( $post_data["name"] ) . '">' . $err["name"] . '</td></tr>
          <tr><th>屋号</th><td><input type="text" name="shop" value="' . escStr( $post_data["shop"] ) . '">' . $err["shop"] . '</td></tr>
          <tr><th>住所</th><td><input type="text" name="address" value="' . escStr( $post_data["address"] ) . '">' . $err["address"] . '</td></tr>
          <tr><th>電話番号</th><td><input type="tel" name="tel" pattern="[\d\-]*" value="' . escStr( $post_data["tel"] ) . '">' . $err["tel"] . '</td></tr>
          <tr><th>FAX</th><td><input type="tel" name="fax" pattern="[\d\-]*" value="' . escStr( $post_data["fax"] ) . '">' . $err["fax"] . '</td></tr>
          <tr><th>責任者氏名</th><td><input type="text" name="pic_name" value="' . escStr( $post_data["pic_name"] ) . '">' . $err["pic_name"] . '</td></tr>
          <tr><th>宅建免許番号</th><td><input type="text" name="pic_name" value="' . escStr( $post_data["license"] ) . '">' . $err["license"] . '</td></tr>
          <tr><th>取扱物件</th>
            <td>
              <label class="mr-5"><input type="radio" name="type" value="1" '. $radio_01 .'>ビル</label>
              <label class="mr-5"><input type="radio" name="type" value="2" '. $radio_02 .'>マンション</label>
              <label><input type="radio" name="type" value="3" '. $radio_03 .'>両方</label>
              ' . $err["type"] . '
            </td>
          </tr>
          <tr><th>メールアドレス</th><td><input type="email" name="email" value="' . escStr( $post_data["email"] ) . '">' . $err["email"] . '</td></tr>
          <tr>
            <th>
              パスワード
              <p class="red small">※半角英数字それぞれ１文字以上含む、８文字以上</p>
            </th>
            <td><input type="password" name="password" value="' . escStr( $post_data["password"] ) . '">' . $err["password"] . '</td>
          </tr>
          <tr><th>パスワード（確認）</th><td><input type="password" name="password_confirm" value="' . escStr( $post_data["password_confirm"] ) . '">' . $err["password_confirm"] . '</td></tr>
          <tr>
            <td colspan="2">
              <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
              <button type="submit" class="customBtn">確認</button>
            </td>
          </tr>
        </table>
      </form>
    ';

  }else { // エラー無

    echo '
      <form action="signup.php" method="post">
        <p class="mb-10">※下記フォーム全てご入力の上ご申請ください。</p>
        <table class="table table-normal">
          <tr><th>会社名</th><td><input type="hidden" name="name" value="' . escStr( $post_data["name"] ) . '">' . escStr( $post_data["name"] ) . '</td></tr>
          <tr><th>屋号</th><td><input type="hidden" name="shop" value="' . escStr( $post_data["shop"] ) . '">' . escStr( $post_data["shop"] ) . '</td></tr>
          <tr><th>住所</th><td><input type="hidden" name="address" value="' . escStr( $post_data["address"] ) . '">' . escStr( $post_data["address"] ) . '</td></tr>
          <tr><th>電話番号</th><td><input type="hidden" name="tel" value="' . escStr( $post_data["tel"] ) . '">' . escStr( $post_data["tel"] ) . '</td></tr>
          <tr><th>FAX</th><td><input type="hidden" name="fax" value="' . escStr( $post_data["fax"] ) . '">' . escStr( $post_data["fax"] ) . '</td></tr>
          <tr><th>責任者氏名</th><td><input type="hidden" name="pic_name" value="' . escStr( $post_data["pic_name"] ) . '">' . escStr( $post_data["pic_name"] ) . '</td></tr>
          <tr><th>宅建免許番号</th><td><input type="hidden" name="license" value="' . escStr( $post_data["license"] ) . '">' . escStr( $post_data["license"] ) . '</td></tr>
          <tr><th>取扱物件</th><td><input type="hidden" name="type" value="' . escStr( $post_data["type"] ) . '">' . $radio_name . '</td>
          </tr>
          <tr><th>メールアドレス</th><td><input type="hidden" name="email" value="' . escStr( $post_data["email"] ) . '">' . escStr( $post_data["email"] ) . '</td></tr>
          <tr><th>パスワード</th><td><input type="hidden" name="password" value="' . escStr( $post_data["password"] ) . '">＊＊＊＊＊＊＊＊＊</td></tr>
          <tr><th>パスワード（確認）</th><td><input type="hidden" name="password_confirm" value="' . escStr( $post_data["password_confirm"] ) . '">＊＊＊＊＊＊＊＊＊</td></tr>
          <tr>
            <td colspan="2">
              <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
              <a class="customBtn back mr-10" href="javascript:history.back();">戻る</a>
              <button type="submit" class="customBtn">登録</button>
            </td>
          </tr>
        </table>
      </form>
    ';

  }

}


// --------------------------------------------------------
// ・ユーザー登録フォームを表示する
// $num (Int) 登録済みユーザー数
// return <form>
function htmlUserAddForm( $num ) {

  if( $num < 20 ) {

    echo '
      <form action="signup.php" method="post"><table class="table table-normal">
        <tr><th>担当者氏名</th><td><input type="text" name="name"></td></tr>
        <tr><th>メールアドレス</th><td><input type="email" name="email"></td></tr>
        <tr><th>パスワード<p class="red small">※半角英数字それぞれ１文字以上含む、８文字以上</p></th><td><input type="password" name="password"></td></tr>
        <tr><th>パスワード（確認）</th><td><input type="password" name="password_confirm"></td></tr>
        <tr>
          <td colspan="2">
            <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
            <button type="submit" class="customBtn">登録</button>
          </td>
        </tr>
      </table></form>
    ';

  }else {
    echo '<p>登録上限に達しました。</p>';
  }

}


// --------------------------------------------------------
// ・管理者（若杉）ログインフォームを表示する
// return <form>
function htmlAdminLoginForm( $err = null ) {

  if( $err == "dis" ) {
    $err = '<p class="red mb-10">※このアカウントは現在使用できません。</p>';
  }elseif( $err == "not" ) {
    $err = '<p class="red mb-10">※メールアドレス又はパスワードが間違っています。</p>';
  }else {
    $err = "";
  }

  echo '
    <form action="login.php" method="post">
      ' . $err . '
      <table class="table table-normal">
        <tr><th>ID</th><td><input type="text" name="id"></td></tr>
        <tr><th>パスワード</th><td><input type="password" name="password"></td></tr>
        <tr><td colspan="2"><button type="submit" class="customBtn">ログイン</button></td></tr>
      </table>
    </form>
  ';

}


// --------------------------------------------------------
// ・ログインフォームを表示する
// return <form>
function htmlUserLoginForm( $err = null ) {

  if( $err == "not" ) {
    $err = '<p class="red mb-10">※メールアドレス又はパスワードが間違っています。</p>';
  }else {
    $err = "";
  }

  echo '
    <form action="login.php" method="post">
      ' . $err . '
      <table class="table table-normal">
        <tr><th>ID<p>（メールアドレス）</p></th><td><input type="email" name="email"></td></tr>
        <tr><th>パスワード</th><td><input type="password" name="password"></td></tr>
        <tr>
          <td colspan="2">
            <input type="hidden" name="access_token" value="' . ACCESS_TOKEN . '">
            <button type="submit" class="customBtn">ログイン</button>
          </td>
        </tr>
      </table>
    </form>
  ';

}

