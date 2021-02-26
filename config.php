<?php

/* ------------------------------------------------------------------------

  * DB / MySQL

------------------------------------------------------------------------ */

  // ローカル環境
  // define('DSN', 'mysql:host=localhost;dbname=wakasugi_system;charset=utf8;');
  // define('DB_USER', 'root');
  // define('DB_PASS', '');

  // テスト環境
  define('DSN', 'mysql:host=mysql10067.xserver.jp;dbname=dm0001_wksgmember;charset=utf8;');
  define('DB_USER', 'dm0001_yanagi');
  define('DB_PASS', 'kMBGUVSww535');

  // 運用機


/* ------------------------------------------------------------------------

  * 管理者情報

------------------------------------------------------------------------ */

  // 管理画面 ID / PASS
	define("ADMINID", "wakasugi");
  define("ADMINPASS", "6pspklbw");

  // 管理者担当者メールアドレス (,で区切って複数登録可能)
  define("ADMIN_MAILADDRESS", "yanagihara@insel-art.jp");


/* ------------------------------------------------------------------------

  * メール

------------------------------------------------------------------------ */

  // メール機能
  // 0 -> 無効, 1 -> 有効
  define("SENDMAIL", 1);

  // サイトドメイン
  define("SITE_DOMAIN", "https://wakasugi.dm-test-server01.com");

  // メール送信元
  define("MAIL_FROM", "yanagihara@insel-art.jp");

  // メール送信元名
  define("MAIL_FROM_NAME", "株式会社 スペースソリューション");

  // メール件名
  define("MAIL_SUBJECT_APPROVE", "仲介業者専用サイト登録完了のお知らせ");
  define("MAIL_SUBJECT_SIGNUP", "仲介業者専用サイトのお申し込み");
  define("MAIL_SUBJECT_STAFF_SIGNUP", "ユーザー登録のお知らせ");
  define("MAIL_SUBJECT_CONTACT", "仲介業者お問い合わせ");

  // メールフッター
  define("MAIL_NAIYO_FOOTER", "---------------------------------------
  有限会社 スペースソリューション
  〒530-0041　大阪府大阪市北区天神橋2丁目5-25
  TEL：06-6357-7771
  FAX：06‐6357‐7772
  ");

/* ------------------------------------------------------------------------

  * その他

------------------------------------------------------------------------ */

  // maintenance
  define("MAINTENANCE_MODE", 0);

  // アクセス用トークン
  define("ACCESS_TOKEN", "tTvzCBmY");