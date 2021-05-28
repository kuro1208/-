<?php
require_once('db.php');
session_start();
$tempfile = $_FILES['menu']['tmp_name'];
$filename = './uploads/' . $_FILES['menu']['name'];
if(isset($_POST['file'])){
  if(is_uploaded_file($tempfile)){
    if ( move_uploaded_file($tempfile , $filename )) {
      $output="$filename をアップロードしました。" ;
      header('Location:views/root_mypage.php');
    } else {
        $output="ファイルをアップロードできません。";
        header('Location:views/root_mypage.php');
    }
  }else{
    $output= 'ファイルが選択されていません。';
    header('Location:views/root_mypage.php');
  }
}

$_SESSION['output']=$output;