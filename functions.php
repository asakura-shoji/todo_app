<?php
//画面側にデータを渡す

require('connection.php');
function create($data) {
  insertDb($data['todo']);
}

//全県取得
function index() {
  return $todos = selectAll();
  //returnしている理由は、index.phpにてこの関数を呼び出して一覧の表示を行うから
}

// 更新
function update($data) {
  updateDb($data['id'],$data['todo']);
}
//update関数とdetail関数でそれぞれ必要となるデータを渡している

// 詳細の取得
function detail($id) {
  return getSelectData($id);
}

function checkReferer() {
  $httpArr = parse_url($_SERVER['HTTP_REFERER']);
  //parse_url - URL の様々な構成要素のうち特定できるものに関して 連想配列にして返す
  return $res = transition($httpArr['path']);//ページの遷移を管理する
}

function transition($path) {
  $data = $_POST;
  if($path === '/index.php' && $data['type'] === 'delete'){
    deleteData($data['id']);
    return 'index';
  }elseif($path === '/new.php'){
    create($data);
  }elseif($path === '/edit.php'){
    update($data);
  }
}

function deleteData($id) {
  deleteDb($id);
}

?>
