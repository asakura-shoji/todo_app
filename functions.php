<?php
//画面側にデータを渡す

require('connection.php');
session_start();//SESSIONを使用すると宣言

//エスケープ処理
function h($s) {//フォームから送られてきた値や、データベースから取り出した値をブラウザ上に表示する際に使用
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//sessionに暗号化したtokenを入れる
function setToken() {//setToken - tokenをsessionに保存している
  $token = sha1(uniqid(mt_rand(),true));
  //sha1 - 文字列の sha1 ハッシュを計算する
  //uniqid - 一意な ID を生成する
  $_SESSION['token'] = $token;//IDを代入
}

//sessionのチェックを行いcsrf対策を行う
function checkToken($data) {
  if (empty($_SESSION['token'])|| ($_SESSION['token'] != $data)){
    $_SESSION['err'] = '不正な操作です';
    header('location: '.$_SERVER['HTTP_REFERER'].'');
    exit();
  }
  return true;
}

function unsetSession() {
  if(!empty($_SESSION['err'])) $_SESSION['err'] = '';
  //empty — 変数が空であるかどうかを検査する
}

function create($data) {
  if(checkToken($data['token'])){
    insertDb($data['todo']);
  }
}

//全県取得
function index() {
  return $todos = selectAll();
  //returnしている理由は、index.phpにてこの関数を呼び出して一覧の表示を行うから
}

// 更新
function update($data) {
  if(checkToken($data['token'])) {
    updateDb($data['id'], $data['todo']);
  }
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
