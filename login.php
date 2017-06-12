
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>ログイン</title>
  </head>
  <body>
    <h1>ログイン</h1>
      <form action="store.php" method="POST">
        ユーザ名：<input type="text" name="username" required="required">
        パスワード：<input type="password" name="password" required="required">
        <input type="submit" value="ログイン">
      </form>
      <a href="register.php">新規登録はこちら</a>
  </body>
</html>
