<?php
//画面側にデータを渡す

require('connection.php');
session_start();//SESSIONを使用すると宣言
//サーバーとクライアントつまりユーザーとアプリケーション側がやり取りする上で
//アプリケーション側がユーザーを特定するために使用するもの
//session_start(); - サーバ上に保存してあるデータを呼び出す作業なんだよ。
//POSTやGETでも確かにデータを「送信」することはできる。
//セッションはデータをやり取りするわけではなくて、データをサーバ上に「保存」しておく

//エスケープ処理
function h($s) {//フォームから送られてきた値や、データベースから取り出した値をブラウザ上に表示する際に使用
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");//関数にしているだけ
  //悪意のあるコードの埋め込みを防ぐ目的で使われます。
}

//sessionに暗号化したtokenを入れる
function setToken() {//setToken - tokenをsessionに保存している
  $token = sha1(uniqid(mt_rand(),true));
  //セッションID - その場で生成したランダムな文字の並びなのでこの中に情報は盛り込まれてはいない。
  //アクセスしてきたユーザとは何の関連性もないただのランダムな文字列を発行することは、
  //セッションＩＤを他の情報から割り出されないようにするには重要なこと
  //発行したセッションＩＤは、一方はアクセスしてきた人のブラウザに保存、もう一方はサーバ側で持っておきます
  //ブラウザ保存はクッキーを使うので、クッキーを受け付けない状態だと使えない
  //ログイン認証の時には、ユーザ名とパスワードが一致すればセッションＩＤを発行してログイン状態となります。
  //そうなると認証以降は送られてきたセッションＩＤとサーバ側に保存されたセッションＩＤの合致を見てそこに
  //結び付けられたユーザ名を見ることでユーザ判別ができるようにな
  //sha1 - 文字列の sha1 ハッシュを計算する
  //uniqid - 一意な ID を生成する
  //mt_rand() - 乱数を生成します。範囲指定しない場合には0からRAND_MAX。の間のランダムな値を返します。
  $_SESSION['token'] = $token;//IDを代入
}

//sessionのチェックを行いcsrf対策を行う
function checkToken($data) {
  if (empty($_SESSION['token']) || ($_SESSION['token'] != $data)){// ||=or
    $_SESSION['err'] = '不正な操作です';
    header('location: '.$_SERVER['HTTP_REFERER'].'');//header - httpヘッダを送信する
    //$_SERVER - ヘッダ、パス、スクリプトの位置のような 情報を有する配列。
    //HTTP_REFERER - リンク元のURLが入ってきます
    exit();//exit - メッセージを出力し、現在のスクリプトを終了する
  }
  return true;
}

function unsetSession() {
  if(!empty($_SESSION['err'])) $_SESSION['err'] = '';
  //empty — 変数が空であるかどうかを検査する
  //つその値が空や0でなければ FALSE を返します。 それ以外の場合は TRUE を返します
  //! - 論理演算子の「否定」
}

function checkReferer() {
  $httpArr = parse_url($_SERVER['HTTP_REFERER']);
  //parse_url - URL の様々な構成要素のうち特定できるものに関して 連想配列にして返す
  //$_SERVER - ヘッダ、パス、スクリプトの位置のような 情報を有する配列。
  //HTTP_REFERER - リンク元のURLが入ってきます
  return $res = transition($httpArr['path']);//ページの遷移を管理する
  //transitionを読んでる
}

function transition($path) {
  unsetSession();
  $data = $_POST;
  if($path === '/register.php'){
    register($data);
  }elseif($path === '/login.php'){
    login($data);
  }

  if(isset($data['todo'])) $res = validate($data['todo']);//バリデーションの機能
  //データのバリデーションといった場合、記述・入力されたデータが、
  //あらかじめ規定された条件や仕様、形式などに適合しているかどうかを検証・確認することを表す。
  if($path === '/index.php' && $data['type'] === 'delete'){
    deleteData($data['id']);
    return 'index';
  }elseif(!$res || !empty($_SESSION['err'])){//empty - 変数が空であるかどうかを検査します
    return 'back';
  }elseif($path === '/new.php'){
    create($data);
  }elseif($path === '/edit.php'){
    update($data);
  }
}

function create($data) {//データの受け取りとDBへの処理を依頼する機能をまとめるファイル

  if(checkToken($data['token'])){
    insertDb($data['todo']);//連想配列の中にkeyがある。
    //$dataの中のtodoキーからvalueをとりだしている。
  }
}

//全県取得
function index() {
  return $todos = selectAll();
  //returnしている理由は、index.phpにてこの関数を呼び出して一覧の表示を行うから
}

// 更新
function update($data) {
   if(checkToken($data['token'])){
     updateDb($data['id'], $data['todo']);
    }
}
//update関数とdetail関数でそれぞれ必要となるデータを渡している

// 詳細の取得
function detail($id) {
  return getSelectData($id);
}

function register($data) {
  if(!empty($data["username"]) && !empty($data["password"])){
    $login = loginDb($data);
    if($login['username'] === NULL){//DBに入っていないなら
      loginInsertDb($data);
      loginDb($data);
      header('Location: index.php');
      exit;
    }else{
      echo "既に登録されているユーザです";
      exit;
    }
  }
}

function login($data) {
  $login = loginDb($data);

  if($login['username'] === $data['username'] && $login['password'] === $data['password']){
    header('Location: index.php');
    exit;
  }else{
    echo "ログインに失敗しました";
    exit;
  }
}

//バリデーションの機能
function validate($data) {
  return $res = $data != "" ? true : $_SESSION['err'] = '入力がありません';
  //!= - 等しくない
  //? - 三項演算子 式の結果がFALSEなら $_SESSION['err'] = '入力がありません'
}

function deleteData($id) {
  deleteDb($id);
}


?>
