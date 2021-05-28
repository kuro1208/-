<?php
require_once('db.php');
session_start();



if(isset($_POST['news'])){
  $title=$_POST['title'];
  $content=$_POST['content'];
  $date=$_POST['date'];
  $stmt=$dbh->prepare(
    'INSERT INTO news (title,content,deadline) VALUES (?,?,?)'
  );
  $stmt->execute([$title,$content,$date]);
  $output='お知らせを追加しました。';
  header('Location:views/index.php');

}else{

}

$_SESSION['output']=$output;
?>