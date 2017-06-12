
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>新規登録</title>
  </head>
  <body>
    <h1>新規登録</h1>
      <form action="store.php" method="POST">
        ユーザ名：<input type="text" name="username" required="required">
        パスワード：<input type="password" name="password" required="required">
        <input type="submit" value="登録">
      </form>
      <a href="login.php"><p>ログイン画面へ</p></a>
  </body>
</html>
