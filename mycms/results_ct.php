<?php
require_once('db.php');
session_start();
setcookie('message',null);
//記録追加

if(isset($_POST['result_ct'])){
  $name=$_POST['name'];
  $date=$_POST['date'];
  $m30=(float)$_POST['30m'];
  $m60=(float)$_POST['60m'];
  $m90=(float)$_POST['90m'];
  $m150=(float)$_POST['150m'];
  $m300=(float)$_POST['300m'];
  $slj=(float)$_POST['slj'];
  $s5j=(float)$_POST['s5j'];
  $r5j=(float)$_POST['r5j'];
  $mtf=(float)$_POST['mtf'];
  $mtb=(float)$_POST['mtb'];
  $cmj=(float)$_POST['cmj'];
  $cmjl=(float)$_POST['cmjl'];
  $cmjr=(float)$_POST['cmjr'];
  $rj_i=(float)$_POST['rj_i'];
  $rj_h=(float)$_POST['rj_h'];
  $rj_t=(float)$_POST['rj_t'];
  $rjl_i=(float)$_POST['rjl_i'];
  $rjl_h=(float)$_POST['rjl_h'];
  $rjl_t=(float)$_POST['rjl_t'];
  $rjr_i=(float)$_POST['rjr_i'];
  $rjr_h=(float)$_POST['rjr_h'];
  $rjr_t=(float)$_POST['rjr_t'];
  $bench=(float)$_POST['bench'];
  $power=(float)$_POST['power'];
  $hung=(float)$_POST['hung'];
  $squat=(float)$_POST['squat'];

  $stmt = $dbh -> query(
    "SELECT * FROM ct_results where name='$name' AND date = $date "
  );
  $ct_name_date=$stmt->fetch();
  var_dump($ct_name_date);
  
  if($ct_name_date){
    $stmt=$dbh->prepare(
      "UPDATE ct_results SET 30m = CASE WHEN $m30 = 0 THEN 30m ELSE ? END,60m = CASE WHEN $m60 = 0 THEN 60m ELSE ? END,90m = CASE WHEN $m90 = 0 THEN 90m ELSE ? END,150m = CASE WHEN $m150 = 0 THEN 150m ELSE ? END,300m = CASE WHEN $m300 = 0 THEN 300m ELSE ? END, 立幅跳 = CASE WHEN $slj = 0 THEN 立幅跳 ELSE ? END ,立五段跳 = CASE WHEN $s5j = 0 THEN 立五段跳 ELSE ? END, 助走付五段跳 = CASE WHEN $r5j = 0 THEN 助走付五段跳 ELSE ? END,メディ投げF = CASE WHEN $mtf = 0 THEN メディ投げF ELSE ? END,メディ投げB = CASE WHEN $mtb = 0 THEN メディ投げB ELSE ? END,CMJ = CASE WHEN $cmj = 0 THEN CMJ ELSE ? END,CMJ_L = CASE WHEN $cmjl = 0 THEN CMJ_L ELSE ? END,CMJ_R = CASE WHEN $cmjr = 0 THEN CMJ_R ELSE ? END,RJ_index = CASE WHEN $rj_i = 0 THEN RJ_index ELSE ? END,RJ_cm = CASE WHEN $rj_h = 0 THEN RJ_cm ELSE ? END,RJ_sec = CASE WHEN $rj_t = 0 THEN RJ_sec ELSE ? END,左RJ_index = CASE WHEN $rjl_i = 0 THEN 左RJ_index ELSE ? END,左RJ_cm = CASE WHEN $rjl_h = 0 THEN 左RJ_cm ELSE ? END,左RJ_sec = CASE WHEN $rjl_t = 0 THEN 左RJ_sec ELSE ? END,右RJ_index = CASE WHEN $rjr_i = 0 THEN 右RJ_index ELSE ? END,右RJ_cm = CASE WHEN $rjr_h = 0 THEN 右RJ_cm ELSE ? END,右RJ_sec = CASE WHEN $rjr_t = 0 THEN 右RJ_sec ELSE ? END,ベンチプレス = CASE WHEN $bench = 0 THEN ベンチプレス ELSE ? END,パワークリーン = CASE WHEN $power = 0 THEN パワークリーン ELSE ? END,ハングクリーン = CASE WHEN $hung = 0 THEN ハングクリーン ELSE ? END,スクワット = CASE WHEN $squat = 0 THEN スクワット ELSE ? END WHERE date= $date AND name='$name' "
    );
    $stmt->execute([$m30,$m60,$m90,$m150,$m300,$slj,$s5j,$r5j,$mtf,$mtb,$cmj,$cmjl,$cmjr,$rj_i,$rj_h,$rj_t,$rjl_i,$rjl_h,$rjl_t,$rjr_i,$rjr_h,$rjr_t,$bench,$power,$hung,$squat]);
    $output='CTの結果を上書きしました。';
  }else{
    $stmt=$dbh->prepare(
      'INSERT INTO ct_results (name,date,30m,60m,90m,150m,300m,立幅跳,立五段跳,助走付五段跳,メディ投げF,メディ投げB,CMJ,CMJ_L,CMJ_R,RJ_index,RJ_sec,RJ_cm,左RJ_index,左RJ_sec,左RJ_cm,右RJ_index,右RJ_sec,右RJ_cm,ベンチプレス,パワークリーン,ハングクリーン,スクワット) 
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
    );
    $stmt->execute([$name,$date,$m30,$m60,$m90,$m150,$m300,$slj,$s5j,$r5j,$mtf,$mtb,$cmj,$cmjl,$cmjr,$rj_i,$rj_h,$rj_t,$rjl_i,$rjl_h,$rjl_t,$rjr_i,$rjr_h,$rjr_t,$bench,$power,$hung,$squat]);
    
    $output='CTの結果を追加しました。';
  }

  
  setcookie('message',$output);
  header('Location:views/results.php');
}else{
  $output='CTの結果を追加できませんでした。';
  setcookie('message',null);
  header('Location:views/results.php');
}

$_SESSION['output']=$output;
?>

