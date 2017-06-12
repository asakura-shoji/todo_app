<?php
session_start();//SESSIONを使用すると宣言
$_SESSION = array();//セッション変数を全部解除する
session_destroy();//最終的にセッションを破壊する
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>ログアウト</title>
  </head>
  <body>
    <h1>ログアウト</h1>
    <p>完了</p>
    <a href="login.php"><p>ログイン画面へ</p></a>
  </body>
</html>
