<?php

 // var_dump($_POST);
// var_dump($path);
// exit;

require('functions.php');
$res = checkReferer();
//次の行が読まれるまで次の行は読まれない
if($res != 'back'){
  header('location: ./index.php');//header - HTTP ヘッダを送信する
}elseif($res == 'index'){
  header('location: ./index.php');
}else{
  header('location: '.$_SERVER['HTTP_REFERER'].'');
}

?>
