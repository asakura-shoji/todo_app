<?php
require('functions.php');
unsetSession();

if(!isset($_SESSION['username'])){
	//isset( $_SESSION[ キー ] ) - isset()関数は、指定したセッション変数が設定されている場合は、
	//TRUE、設定されてない場合はFALSEを返す
	session_start();
	header('Location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
</head>
<body>
	<header>
		<a href="logout.php">
			<p>ログアウト</p>
		</a>
	</header>
  <div>
    <a href="new.php">
      <p>新規作成</p>
    </a>
  </div>
  <div>

    <table>
      <tr>
        <th>ID</th>
        <th>内容</th>
        <th>更新</th>
        <th>削除</th>
      </tr>
      <?php foreach(index() as $todo): ?>
        <tr>
          <td><?php echo h($todo['id']) ?></td><!--htmlspecialchars()のラッパー関数-->
          <td><?php echo h($todo['todo']) ?></td>
          <td>
            <a href="edit.php?id=<?php echo h($todo['id']) ?>">更新</a><!--編集画面にて更新対象のデータの表示も行える -->
          <!-- URLクエリ - さまざまな情報をWebサーバーに伝えるためにURLに付け加える情報 -->
          <!-- URLクエリ - 「?」+「変数名」+「=」+「変数の値」というのが、基本構造 -->
          <!-- クエリ - 検索を行う際の検索条件のこと 一般に、データベースや表で何らかの検索をおこなう場合
          は、「検索対象となる項目」と「検索キーワード」を指定する必要がある。-->
          </td>
          <td>
            <form action="store.php" method="POST">
              <!--送信された値は$_POSTに配列で入ってきます。キーはinputのname属性の値-->
              <input type="hidden" name="id" value="<?php echo h($todo['id']) ?>">
              <input type="hidden" name="type" value="delete">
              <button type="submit">削除</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
