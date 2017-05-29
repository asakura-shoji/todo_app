<?php

require('connection.php');
function create($data) {
  insertDb($data['todo']);
}

//全県取得
function index() {
  return $todos = selectAll();
  //returnしている理由は、index.phpにてこの関数を呼び出して一覧の表示を行うから
}

 ?>
