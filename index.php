<?php

require('functions.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
</head>
<body>
  welocome hello world
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
          <td><?php echo $todo['id'] ?></td>
          <td><?php echo $todo['todo'] ?></td>
          <td>
            <a href="">更新</a>
          </td>
          <td>
            <from action="store.php" method="POST">
              <input type="hidden" name="id" value="">
              <input type="hidden" name="type" value="delete">
              <button type="submit">削除</button>
            </from>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
