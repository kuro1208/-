<?php
require_once('db.php');
session_start();
$deleted=[];
$name=$_SESSION['user_name'];
if(isset($_POST['tou_delete'])){
  
  $deletes=$_POST['delete'];
  $stmt=$dbh->prepare(
    "DELETE FROM results WHERE id=? AND results_name= ? AND results_date = ? AND results_event = ? "
  );
  foreach($deletes as $delete){
    if($delete['flag']=='1'){
      $deleted=[$delete['id'],$name,$delete['date'],$delete['event']];
      $stmt->execute($deleted);
    }
  }
  
  header('Location:views/mypage.php');
  $output='削除しました。';
}else{
  header('Location:views/mypage.php');
  $output='削除できませんでした。';
}


$_SESSION['output']=$output;
?>