<?php

try {

  /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */
  
  // データベースに接続
  $dbh = new PDO(
      // 'mysql:dbname=myhp;host=localhost;charset=utf8mb4',
      // 'root',
      // '',
      'mysql:dbname=u0050227_0002;host=localhost;charset=utf8mb4',
      'u0050227_0002',
      '1f9gHUf8',
      [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]
  );
  return $dbh;
  print('接続成功');

} catch (PDOException $e) {

  // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
  // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
  // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  exit($e->getMessage()); 

}