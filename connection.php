<?php

require_once('config.php');

//DBに接続するための記述
function connectPdo() {
  try{
    return new PDO(DSN,DB_USER,DB_PASSWORD);
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit;
  }
}

function insertDb($data) {
  $dbh = connectPdo();
  $sql = 'INSERT INTO todos (todo) VALUES (:todo)';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':todo', $data, PDO::PARAM_STR);
  //bindParam - 一個目はパラメータを指定。二個目にそれに入れる変数。三個目に型を指定。
  //PDO::PARAM_STR は「文字列」
  $stmt->execute();
}

//データ全件取得の記述
function selectAll() {
  $dbh = connectPdo();
  $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';//データの取得
  $todo =array();
  foreach($dbh->query($sql) as $row) {//配列を作成
    array_push($todo, $row);//配列の最後に追加
  }
  return $todo;//制作した＄todoを返す。
}

 ?>
