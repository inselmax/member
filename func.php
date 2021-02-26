<?php

/* ------------------------------------------------------------------------

  DB / MySQL

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・DB接続
//
// return DBインスタンス
//
function dbConect() {

  try {
      $db = new PDO(DSN, DB_USER, DB_PASS);
  }   catch (PDOException $e) {
      $db =  "データベース接続エラー :" . $e->getMessage();
  }

  return $db;

}


// --------------------------------------------------------
// ・仲介業者検索 / email
// select * from company
// $db DBインスタンス
// $email フォームから入力された文字列（メールアドレス）
//
// return array[]
//
function searchCompanyData( $db, $email ) {

  $stmt = $db->prepare('select * from company where email = :email');
  $stmt->execute(array(
    ':email' => $email
  ));

  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  return $res;

}


// --------------------------------------------------------
// ・仲介業者検索 / id
// select * from company
// $db DBインスタンス
// $id 会社id
//
// return array[]
//
function getSearchCompanyData( $db, $id ) {

  $stmt = $db->prepare('select * from company where id = :id');
  $stmt->execute(array(
    ':id' => $id
  ));

  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  return $res;

}

// --------------------------------------------------------
// ・ユーザー検索 / email
// select * from users
// $db DBインスタンス
// $email フォームから入力された文字列（メールアドレス）
//
// return array[]
//
function searchUserData( $db, $email ) {

  $stmt = $db->prepare('select * from users where email = :email');
  $stmt->execute(array(
    ':email' => $email
  ));

  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  return $res;

}

// --------------------------------------------------------
// ・ユーザー検索 / id
// select * from users
// $db DBインスタンス
// $id ユーザーid
//
// return array[]
//
function getSearchUserData( $db, $id ) {

  // $stmt = $db->prepare('select * from users where id = :id');
  $stmt = $db->prepare('select users.*, company.name as c_name, company.logo_path as logo_path, company.type as type from users join company on users.company_id = company.id and users.id = :id');
  $stmt->execute(array(
    ':id' => $id
  ));

  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  return $res;

}

// --------------------------------------------------------
// ・仲介会社削除 / id
// delete from company
// $db DBインスタンス
// $id フォームから送信されたユーザーID（id）
function deleteCompanyData( $db, $id ) {

  $stmt = $db->prepare('delete from company where id = :id');
  $stmt->execute(array(
    ':id' => $id
  ));

}

// --------------------------------------------------------
// ・ユーザー削除（単一） / id , company_id
// delete from users
// $db DBインスタンス
// $id フォームから送信されたユーザーID（id）
// $company_id フォームから送信された会社ID（id）
function deleteUserData( $db, $id, $company_id ) {

  $stmt = $db->prepare('delete from users where id = :id and company_id = :company_id ');
  $stmt->execute(array(
    ':id' => $id,
    ':company_id' => $company_id
  ));

}

// --------------------------------------------------------
// ・ユーザー削除（複数） / company_id
// delete from users
// $db DBインスタンス
// $company_id フォームから送信された会社ID（id）
function deleteUserData2( $db, $company_id ) {

  $stmt = $db->prepare('delete from users where company_id = :company_id ');
  $stmt->execute(array(
    ':company_id' => $company_id
  ));

}

// --------------------------------------------------------
// ・仲介会社登録 / name, address, pic_name, email, password
// insert into  company
// $db DBインスタンス
// $nameフォームから送信された会社名（name）
// $shopフォームから送信された屋号（shop）
// $address フォームから送信された住所（address）
// $tel フォームから送信された電話番号（tel）
// $fax フォームから送信されたFAX（fax）
// $pic_name フォームから送信された担当者名（pic_name）
// $license フォームから送信された宅建免許番号（license）
// $type フォームから送信された取扱物件（type）
// $email フォームから送信されたメールアドレス（email）
// $password フォームから送信されたハッシュ化済みのパスワード（password）
function insertCompanyData( $db, $name, $shop, $address, $tel, $fax, $pic_name, $license, $type, $email, $password ) {

  $stmt = $db->prepare("insert into company(name, shop, address, tel, fax, pic_name, license, type, email, password) value(:name, :shop, :address, :tel, :fax, :pic_name, :license, :type, :email, :password)");
  $stmt->execute(array(
    ':name' => $name,
    ':shop' => $shop,
    ':address' => $address,
    ':tel' => $tel,
    ':fax' => $fax,
    ':pic_name' => $pic_name,
    ':license' => $license,
    ':type' => $type,
    ':email' => $email,
    ':password' => $password
  ));

}

// --------------------------------------------------------
// ・ユーザー登録 / name, address, pic_name, email, password
// insert into  users
// $db DBインスタンス
// $nameフォームから送信された担当者名（name）
// $email フォームから送信されたメールアドレス（email）
// $password フォームから送信されたハッシュ化済みのパスワード（password）
// $company_id セッションに格納されている会社ID
function insertuserData( $db, $name, $email, $password, $company_id ) {

  $stmt = $db->prepare("insert into users(name, email, password, company_id) value(:name, :email, :password, :company_id)");
  $stmt->execute(array(
    ':name' => $name,
    ':email' => $email,
    ':password' => $password,
    ':company_id' => $company_id
  ));

}

// --------------------------------------------------------
// ・仲介会社のステータス（承認状態）を更新 / id
// update
// $db DBインスタンス
// $id フォームから送信されたユーザーID（id）
function updateCompanyStatus( $db, $id ) {

  $stmt = $db->prepare("update company set status = 1 where id = :id");
  $stmt->execute(array(
    ':id' => $id
  ));

}

// --------------------------------------------------------
// ・仲介会社のレコード（プロフィール）を更新 / id, name, address, tel, email, pic_name, rate
// update
// $db DBインスタンス
// $id フォームから送信されたユーザーID（id）
// $name フォームから送信された会社名（name）
// $shop フォームから送信された屋号（shop）
// $address フォームから送信された住所（address）
// $tel フォームから送信されたユーザー電話番号（tel）
// $fax フォームから送信されたユーザーFAX（fax）
// $email フォームから送信されたユーザーメールアドレス（email）
// $pic_name フォームから送信されたユーザー責任者氏名（pic_name）
// $license フォームから送信されたユーザー宅建免許番号（license）
// $rate フォームから送信されたユーザー割引率（rate）
// $logo_path フォームから送信された会社ロゴのディレクトリパス（logo_path）
function updateCompanyProfile( $db, $id, $name, $shop, $address, $tel, $fax, $email, $pic_name, $license, $rate, $logo_path ) {

  $stmt = $db->prepare("update company set name = :name, shop = :shop, address = :address, tel = :tel, fax = :fax, email = :email, pic_name = :pic_name, license = :license, rate = :rate, logo_path = :logo_path where id = :id");
  $stmt->execute(array(
    ':id' => $id,
    ':name' => $name,
    ':shop' => $shop,
    ':address' => $address,
    ':tel' => $tel,
    ':fax' => $fax,
    ':email' => $email,
    ':pic_name' => $pic_name,
    ':license' => $license,
    ':rate' => $rate,
    ':logo_path' => $logo_path
  ));

}

// --------------------------------------------------------
// ・仲介業者データ取得
// select * from company
// $db DBインスタンス
//
// return array[]
//
function getCompanyData( $db ) {

  $res = $db->query('select * from company order by id desc');

  return $res;

}

// --------------------------------------------------------
// ・ユーザーデータ取得
// select * from users
// $db DBインスタンス
//
// return array[]
//
function getUserData( $db ) {

  // $res = $db->query('select * from users order by id desc');
  $res = $db->query('select users.*, company.name as c_name from users join company on users.company_id = company.id order by id desc');
  return $res;

}

// --------------------------------------------------------
// ・ユーザーデータ取得 / company_id
// select * from users where company_id
// $db DBインスタンス
// $company_id 会社ID
//
// return array[]
//
function getUserData2( $db, $company_id ) {

  // $stmt = $db->prepare('select users.*, company.name as company_name from users join company on users.company_id = company.id');
  $stmt = $db->prepare('select users.*, company.name as c_name from users join company on users.company_id = company.id and users.company_id = :company_id');
  $stmt->execute(array(
    ':company_id' => $company_id
  ));

  $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $res;

}

/* ------------------------------------------------------------------------

  汎用関数

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・エスケープ処理
// $str value
//
// return string
//
function escStr( $str ) {

  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');

}

// --------------------------------------------------------
// ・パスワードチェック
// $password 登録フォームから入力された文字列（パスワード）
// return 0 or 1
function passCheck( $password ) {

  // 半角英数字1文字以上含んだ8文字以上
  $pattern = '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i';
  $result = preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $password);

  return $result;

}

// --------------------------------------------------------
// ・count()の代替 / company_id
// $ary 配列
//
// return int
//
function getAryCount( $ary ) {

  $count = 0;

  if( $ary ) {
    foreach ($ary as $key => $value) { $count++; }
  }

  return $count;

}

// --------------------------------------------------------
// ・ログインチェック（仲介業者）
//
// return true or false
//
function is_login() {

  // ログインしていれば
  if ( isset($_SESSION['ID']) ) {
    $flg = true;
  }else {
    $flg = false;
  }

  return $flg;

}

// --------------------------------------------------------
// ・ログインチェック（仲介業者・管理者）
//
// return true or false
//
function is_admin_login() {

  // ログインしていれば
  if ( isset($_SESSION['ID']) && empty($_SESSION['CID']) ) {
    $flg = true;
  }else {
    $flg = false;
  }

  return $flg;

}

// --------------------------------------------------------
// ・ログインチェック（若杉管理者）
// return true or false
function is_wksg_login() {

  // ログインしていれば
  if ( isset($_SESSION['ADMIN']) ) {
    $flg = true;
  }else {
    $flg = false;
  }

  return $flg;

}


/* ------------------------------------------------------------------------

  Mail

------------------------------------------------------------------------ */


// --------------------------------------------------------
// ・メール送信
// $to メール送信先
// $subject メール件名
// $content メール本文
//
// mb_send_mail
//
function sendMail( $to, $subject, $content ) {

  if( SENDMAIL == 0 ) {
    return false;
  }

  // 現在の日付・時刻
  $request_datetime = date("Y/m/d H:i:s");

  $mail_to = $to; // メール送信先
  $mail_header = "From:".mb_encode_mimeheader( mb_convert_kana(escStr(MAIL_FROM_NAME), "rnasK", "utf-8"), "ISO-2022-JP")."<".MAIL_FROM.">";
  $mail_subject = escStr($subject); // メール件名
  $mail_content = $content; // メール本文

  $mail_content .= "\r\n";
  $mail_content .= "---------------------------------------\r\n";
  $mail_content .= "\r\n";
  $mail_content .= "  送信ログ " . $request_datetime . "\r\n";
  $mail_content .= "\r\n";
  $mail_content .= MAIL_NAIYO_FOOTER;

  mb_language("ja");
  mb_internal_encoding("UTF-8");

  //メール送信
  if(mb_send_mail($mail_to, $mail_subject, $mail_content, $mail_header)){
    // echo "メールの送信に成功しました";
  } else {
    // echo "メールの送信に失敗しました";
  };

}


// --------------------------------------------------------
// ・メール本文 / 仲介業者 登録
// $formData フォームから送信されたデータ
//
// return string
//
function getMailTempSignup( $formData, $admin ) {

  if( $formData ) {

    $mail_content = "";

    if( $admin ) { // 管理者宛て
      $mail_content .= escStr($formData['pic_name']) . "様より\r\n";
      $mail_content .= "仲介業者登録のお申し込みがありました\r\n\r\n";
    }else { // ユーザー宛て
      $mail_content .= escStr($formData['pic_name']) . "様\r\n";
      $mail_content .= "お問い合わせ、ありがとうございます。\r\n";
      $mail_content .= "以下の内容で仲介業者登録のお申し込みを受け付けました\r\n\r\n";
    }

    $mail_content .= "\r\n";
    $mail_content .= "【会社名】\r\n";
    $mail_content .= escStr($formData['name']) ."\r\n";
    $mail_content .= "【屋号】\r\n";
    $mail_content .= escStr($formData['shop']) ."\r\n";
    $mail_content .= "【住所】\r\n";
    $mail_content .= escStr($formData['address']) ."\r\n";
    $mail_content .= "【電話番号】\r\n";
    $mail_content .= escStr($formData['tel']) ."\r\n";
    $mail_content .= "【FAX】\r\n";
    $mail_content .= escStr($formData['fax']) ."\r\n";
    $mail_content .= "【責任者氏名】\r\n";
    $mail_content .= escStr($formData['pic_name']) ."\r\n";
    $mail_content .= "【宅建免許番号】\r\n";
    $mail_content .= escStr($formData['license']) ."\r\n";
    $mail_content .= "【メールアドレス】\r\n";
    $mail_content .= escStr($formData['email']) ."\r\n";

    if( $admin ) { // 管理者宛て
      $mail_content .= "\r\n\r\n";
      $mail_content .= "★ログインページ（承認はこちら）\r\n";
      $mail_content .= SITE_DOMAIN . "/member/admin/" . "\r\n";
    }

    $mail_content .= "\r\n";

    return $mail_content;

  }else {
    return false;
  }

}


// --------------------------------------------------------
// ・メール本文 / 仲介業者 承認
// $formData フォームから送信されたデータ
//
// return string
//
function getMailTempApprove( $formData ) {

  if( $formData ) {

    $mail_content = "";
    $mail_content .= escStr($formData['pic_name']) . "様\r\n";
    $mail_content .= "仲介業者専用サイトの登録が承認されました\r\n";
    $mail_content .= "登録時に入力したID（メールアドレス）・パスワードを使ってご利用ください\r\n";
    $mail_content .= "\r\n";
    $mail_content .= "★ログインページ\r\n";
    $mail_content .= SITE_DOMAIN . "/member/user/" . "\r\n";
    $mail_content .= "\r\n";

    return $mail_content;

  }else {
    return false;
  }

}


// --------------------------------------------------------
// ・メール本文 / ユーザー 登録
// $formData フォームから送信されたデータ
//
// return string
//
function getMailTempAddStaff( $formData ) {

  if( $formData ) {

    $mail_content = "";
    $mail_content .= escStr($formData['name']) . "様\r\n";
    $mail_content .= "仲介業者専用サイトのユーザー登録が完了しました\r\n";
    $mail_content .= "下記ID（メールアドレス）・パスワードを使ってご利用ください\r\n";
    $mail_content .= "\r\n";
    $mail_content .= "ID：" . escStr($formData['email']) . "\r\n";
    $mail_content .= "PASS：" . escStr($formData['password']) . "\r\n";
    $mail_content .= "\r\n";
    $mail_content .= "★ログインページ\r\n";
    $mail_content .= SITE_DOMAIN . "/member/user/" . "\r\n";
    $mail_content .= "\r\n";

    return $mail_content;

  }else {
    return false;
  }

}


// --------------------------------------------------------
// ・メール本文 / 仲介業者 お問い合わせ
// $formData フォームから送信されたデータ
//
// return string
//
function getMailTempContact( $formData, $admin ) {

  if( $formData ) {

    $mail_content = "";

    if( $admin ) { // 管理者宛て
      $mail_content .= escStr($formData['name']) . "様より\r\n";
      $mail_content .= "お問い合わせがありました\r\n\r\n";
    }else { // ユーザー宛て
      $mail_content .= escStr($formData['name']) . "様\r\n";
      $mail_content .= "お問い合わせ、ありがとうございます。\r\n";
      $mail_content .= "以下の内容でお問い合わせを受け付けました\r\n\r\n";
    }

    $mail_content .= "\r\n";
    $mail_content .= "【会社名】\r\n";
    $mail_content .= escStr($formData['company']) ."\r\n";
    $mail_content .= "【担当者氏名】\r\n";
    $mail_content .= escStr($formData['name']) ."\r\n";
    $mail_content .= "【メールアドレス】\r\n";
    $mail_content .= escStr($formData['email']) ."\r\n";
    $mail_content .= "【件名】\r\n";
    $mail_content .= escStr($formData['title']) ."\r\n";
    $mail_content .= "【お問い合わせ内容】\r\n";
    $mail_content .= escStr($formData['content']) ."\r\n";

    // if( $admin ) { // 管理者宛て
    //   $mail_content .= "\r\n\r\n";
    //   $mail_content .= "★管理者ページ（承認はこちら）\r\n";
    //   $mail_content .= SITE_DOMAIN . "/member/admin/" . "\r\n";
    // }

    $mail_content .= "\r\n";

    return $mail_content;

  }else {
    return false;
  }

}