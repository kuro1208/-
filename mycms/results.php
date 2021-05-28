<?php
require_once('db.php');
session_start();
$events_track=array('100m','200m','110mh','100mh','400m','400mh','800m','1500m','5000m','10000m','3000msc');
$events_field=array('走幅跳','三段跳','走高跳','棒高跳','やり投','円盤投','砲丸投','ハンマー投','十種競技');

//記録追加

if(isset($_POST['results'])){
  $name=$_POST['name'];
  $event=$_POST['event'];
  $date=$_POST['date'];
  $tou_name=$_POST['tou'];
  if($date==''){
    if($tou_name=='高校'){
      $date='0000-00-00';
    }else{
      header('Location:views/results.php');
      $output='日付が入力されていません。';
      $_SESSION['output']=$output;
      exit();
    }
  }
  if(in_array($event,$events_track)){
    $result_track=(float)$_POST['result'];
    $result_field=null;
  }
  if(in_array($event,$events_field)){
    $result_track=null;
    $result_field=(float)$_POST['result'];
  }
  $stmt=$dbh->prepare(
    'INSERT INTO results (results_name,results_event,results_date,results_tou,result_track,result_field) VALUES (?,?,?,?,?,?)'
  );
  $stmt->execute([$name,$event,$date,$tou_name,$result_track,$result_field]);
  
  header('Location:views/results.php');
  $output='追加しました。';
}else{
  header('Location:views/results.php');
  $output='追加できませんでした。';
}


$_SESSION['output']=$output;
?>

