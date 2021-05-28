<?php
require_once('../db.php');
session_start();

if(isset($_POST['login'])){
  $username=$_POST['name'];
  $password=$_POST['pass'];
  try{
    $stmt=$dbh->prepare('select * from members where name = ? AND pass = ?');
    $stmt->execute(array($username,$password));
    $login=$stmt->fetch(PDO::FETCH_ASSOC);
    if($username=='root'&&$password=='root'){
      $_SESSION['user_name']='root';
      header('Location:index.php');
      exit;
    }
    if($login){
      $_SESSION['user_name']=$login['name'];
      var_dump($_SESSION['username']);
      header('Location:index.php');
      exit;
    }else{
      $output='名前かパスワードが間違っています。';
      header('Location:login.php');
      exit;
    }
  }catch (\Exception $e) {
    echo $e->getMessage();
    exit;
  }
}
// $_SESSION['output']=$output;
if(isset($_SESSION['output'])){
  echo "<script type='text/javascript'>alert('". $_SESSION['output']. "');</script>";
  $_SESSION['output']=null;
}
?>



<!DOCTYPE html>
<html lang='ja'>
<head>
  <meta charset='utf-8'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/login.css">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
  <div class='form'>
    <div class='login'>
      <h1>ログイン</h1>
      <form action="" method='post'>
        <input type="text" name='name' required placeholder="名前">
        <input type="password" name='pass' required placeholder="パスワード">
        <button name='login'>LOGIN</button>
      </form>
    </div>
    <div class='sign_up'>
      <label for="checkbox"><h1>新規登録</h1></label>
      <input type="checkbox" id='checkbox' style='display:none;'>
      <div class='hidden'>
        <form action="../sign_up.php" method='post'>
          <input type="text" name='name' required placeholder='名前（岐大太郎）'>
          <div>
            <label for="sex">性別
              <select name="sex" id="sex">
                <option value="1">男</option>
                <option value="2">女</option>
              </select>
            </label>
          </div>
          <div>
            <label for="year">学年
              <select name="year" id="year">
                <option value="1">1年</option>
                <option value="2">2年</option>
                <option value="3">3年</option>
                <option value="4">4年</option>
                <option value="5">M1</option>
                <option value="6">M2</option>
              </select>
            </label>
          </div>
          <div>
            <label for="">種目１
              <select name="event1" id="">
                <option value="100m">100m</option>
                <option value="200m">200m</option>
                <option value="400m">400m</option>
                <option value="110mh">110mH</option>
                <option value="100mh">100mH</option>
                <option value="400mh">400mH</option>
                <option value="800m">800m</option>
                <option value="1500m">1500m</option>
                <option value="5000m">5000m</option>
                <option value="10000m">10000m</option>
                <option value="3000msc">3000mSC</option>
                <option value="走幅跳">走幅跳</option>
                <option value="三段跳">三段跳</option>
                <option value="走高跳">走高跳</option>
                <option value="棒高跳">棒高跳</option>
                <option value="やり投">やり投</option>
                <option value="円盤投">円盤投</option>
                <option value="砲丸投">砲丸投</option>
                <option value="ハンマー投">ハンマー投</option>
              </select>
            </label>
          </div>
          <div>
            <label for="">種目２
              <select name="event2" id="">
                <option value="">なし</option>
                <option value="100m">100m</option>
                <option value="200m">200m</option>
                <option value="400m">400m</option>
                <option value="110mh">110mH</option>
                <option value="100mh">100mH</option>
                <option value="400mh">400mH</option>
                <option value="800m">800m</option>
                <option value="1500m">1500m</option>
                <option value="3000msc">3000mSC</option>
                <option value="走幅跳">走幅跳</option>
                <option value="三段跳">三段跳</option>
                <option value="走高跳">走高跳</option>
                <option value="棒高跳">棒高跳</option>
                <option value="やり投">やり投</option>
                <option value="円盤投">円盤投</option>
                <option value="砲丸投">砲丸投</option>
                <option value="ハンマー投">ハンマー投</option>
              </select>
            </label>
          </div>
          <div>
            <label for="">種目３
              <select name="event3" id="">
                <option value="">なし</option>
                <option value="100m">100m</option>
                <option value="200m">200m</option>
                <option value="400m">400m</option>
                <option value="110mh">110mH</option>
                <option value="100mh">100mH</option>
                <option value="400mh">400mH</option>
                <option value="800m">800m</option>
                <option value="1500m">1500m</option>
                <option value="3000msc">3000mSC</option>
                <option value="走幅跳">走幅跳</option>
                <option value="三段跳">三段跳</option>
                <option value="走高跳">走高跳</option>
                <option value="棒高跳">棒高跳</option>
                <option value="やり投">やり投</option>
                <option value="円盤投">円盤投</option>
                <option value="砲丸投">砲丸投</option>
                <option value="ハンマー投">ハンマー投</option>
              </select>
            </label>
          </div>
          <input type="text" name='pass' required placeholder='パスワード（半角英数10文字以内）'>
          <button name='sign_up'>新規作成</button>
        </form>
      </div>
    </div>
  </div>
  <!-- <a href="sign_out.php">メンバーと記録を削除</a> -->
</div>


