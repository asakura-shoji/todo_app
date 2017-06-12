<?php

require_once('config.php');

//DBに接続するための記述
function connectPdo() {
  try{//try節の中には、例外が発生する可能性がある正常系処理のコードを記述します。
    return new PDO(DSN,DB_USER,DB_PASSWORD);
  } catch (PDOException $e) {//PDOException - エラーを投げる　
    //catch節の「（）」には、第一引数に、try節で発生した例外をcatchする例外クラス名を指定します。
    //第二引数には、catchした例外クラスのインスタンスを代入する変数を指定します。
    echo $e->getMessage();
    exit;//exit メッセージを出力し、現在のスクリプトを終了
  }
}

//新規作成の為の記述
function insertDb($data) {
  $dbh = connectPdo();
  $sql = 'INSERT INTO todos (todo) VALUES (:todo)';//INSERT INTO - テーブルにデータを追加する
  //プリペアドステートメント - 実行したい SQL をコンパイルした 一種のテンプレートのようなも
  $stmt = $dbh->prepare($sql);//値部分にパラメータを付けて準備状態。文オブジェクトを返す
  $stmt->bindParam(':todo', $data, PDO::PARAM_STR);
  //bindParam - 一個目はパラメータを指定。二個目にそれに入れる変数。三個目に型を指定。
  //PDO::PARAM_STR は「文字列」
  $stmt->execute();//準備したprepareに入っているSQL文を実行
}

//データ全件取得の記述
function selectAll() {
  $dbh = connectPdo();
  $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
  //* - カラム全部
  //SELECT文 - データベースのテーブルからデータを検索し、取得する
  //論理削除対応をしたテーブルは deleted_at というカラムが必要.
  //IS NULL演算子はデータがNULLなら真を，データがNULLでないのなら偽を返します．IS
  $todo =array();
  foreach($dbh->query($sql) as $row) {
    //query - SQL ステートメントを実行し、結果セットを PDOStatement オブジェクトとして返す
    array_push($todo, $row);//配列の最後に追加
  }
  return $todo;//制作した＄todoを返す。
}

//更新処理
function updateDb($id, $data) {
  $dbh = connectPdo();
  $sql = 'UPDATE todos SET todo = :todo WHERE id = :id';//更新
  //更新するレコードを選択してるだけ
  //WHEREの後に条件式で指定したものだけを選出できる
  //UPDATE table名 SET カラム名 = 格納する値 where カラム名 = 値
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':todo', $data, PDO::PARAM_STR);
  //bindParam - 第一引数に文字列(:todo)、第二引数に値、第三引数に型(PDO(保存対象データの型))
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
}

//更新対象の保存データの取得
function getSelectData($id) {
  $dbh = connectPdo();
  $sql = 'SELECT todo FROM todos WHERE id = :id AND deleted_at IS NULL';
  //deleted_atの値がnullでない項目を含めるように指定
  //削除しているかどうかも条件に入れている。

  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':id' => (int)$id));
  //「execute」メソッドを実行するSQL文に引数があった場合(後で値を指定するために「?」や名前付きパラメータを
  //指定した場合)、「execute」メソッドの引数に、値を配列の形で指定します。
  $data = $stmt->fetch();//fetch - 結果セットから次の行を取得する
  return $data['todo'];
}

function deleteDb($id) {
  $dbh = connectPdo();
  $nowTime = date("Y-m-d H:i:s");
  $sql = 'UPDATE todos SET deleted_at = :deleted_at WHERE id = :id';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':deleted_at', $nowTime);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

// if(!empty($_POST["username"]) && !empty($_POST["password"])){
function UserInfo($data) {
  $hash = password_hash($data['password'], PASSWORD_DEFAULT);
  //ハッシュ値生成
  $dbh = connectPdo();
  $sql = 'INSERT INTO login (username, password) VALUES (:username, :password)';//INSERT INTO - テーブルにデータを追加する
  //プリペアドステートメント - 実行したい SQL をコンパイルした 一種のテンプレートのようなも
  $stmt = $dbh->prepare($sql);//値部分にパラメータを付けて準備状態。文オブジェクトを返す
  $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
  $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
  //bindParam - 一個目はパラメータを指定。二個目にそれに入れる変数。三個目に型を指定。
  //PDO::PARAM_STR は「文字列」
  $stmt->execute();//準備したprepareに入っているSQL文を実行
}

function loginDb($data) {
  $dbh = connectPdo();
  $sql = 'SELECT * FROM login WHERE username = :username';
  //SELECT文 - データベースのテーブルからデータを検索し、取得する
  //* - 全ての列のデータを
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
  $stmt->execute();
  $res = $stmt->fetch();//結果セットから次の行を取得する
  $_SESSION['username'] = $res['username'];
  //$_SESSIONというセッション変数に$res['username']を格納
  return $res;
}

 ?>
