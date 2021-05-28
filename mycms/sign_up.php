<?php
require_once('db.php');
session_start();


//新規登録

if(isset($_POST['sign_up'])){
  $name=$_POST['name'];
  $sex=(int)$_POST['sex'];
  $year=$_POST['year'];
  $event1=$_POST['event1'];
  $event2=$_POST['event2'];
  $event3=$_POST['event3'];
  $pass=$_POST['pass'];
  $stmt=$dbh->prepare(
    'INSERT INTO members (name,sex,year,pass,event1,event2,event3) VALUES (?,?,?,?,?,?,?)',
    [$name,$sex,$year,$pass,$event1,$event2,$event3]
  );
  $stmt->execute(array($name,$sex,$year,$pass,$event1,$event2,$event3));
  
  header('Location:views/login.php');
  $output='登録完了しました。';
}else{
  header('Location:views/login.php');
  $output='登録できませんでした。';
}


$_SESSION['output']=$output;
?>

