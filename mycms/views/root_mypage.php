<?php
require_once('../db.php');
session_start();
$events_track=array('100m','200m','110mh','100mh','400m','400mh','800m','1500m','5000m','10000m','3000msc');
$events_field=array('走幅跳','三段跳','走高跳','棒高跳','やり投','円盤投','砲丸投','ハンマー投');

function my_data(){
  global $dbh;
  global $my_data_row;
  global $name;

  $stmt = $dbh -> query(
    "SELECT * FROM members where name = '$name' "
  );
  $my_data_row=$stmt->fetch();
}

function my_result($event){
  global $dbh;
  global $my_result_row;
  global $name;

  $stmt=$dbh->query(
    "SELECT * FROM results where results_event='$event' and results_name='$name' order by results_date desc"
  );
  $my_result_row=$stmt->fetchall();
};

//resultが分を超えていないか確認
function check_result($result){
  global $result_str;
  if($result>60){
    $result_ms=$result %100;
    $result_s=($result %10000 - $result_ms);
    $result_m=($result - $result_s - $result_ms)/10000;
    $result_s=$result_s/100;
    $result_str="${result_m}分${result_s}.${result_ms}";
    }else{
    $result_str=$result;
    };
}
function best_field($event){
  global $dbh;
  global $besters;
  $now_date = date("Y/m/d");
  $stmt = $dbh -> query('SELECT name FROM members');
  $members = $stmt -> fetchall(PDO::FETCH_COLUMN);
  foreach($members as $member){
    $stmt = $dbh->query("SELECT * FROM results WHERE results_event = '$event' AND results_name = '$member' ORDER BY result_track ASC limit 1");
    $results_result_top = $stmt->fetch();
    if($results_result_top){
      if(strtotime($results_result_top['results_date']) < strtotime($now_date)){
         array_push ($besters,$results_result_top);
      }
    }
  };
  
};

$name=$_SESSION['user_name'];
if (!isset($_SESSION["user_name"])) {
  header("Location: login.php");
  exit;
}

if(isset($_SESSION['output'])){
  echo "<script type='text/javascript'>alert('". $_SESSION['output']. "');</script>";
  $_SESSION['output']=null;
}

// best_track('100m');
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>

    const currentMode = localStorage.getItem("color_mode");
    const currentSub = localStorage.getItem("color_sub");
    if (currentMode) {
      document.documentElement.setAttribute("data-main", currentMode);
    }
    if (currentSub) {
      document.documentElement.setAttribute("data-sub", currentSub);
    }
  </script>
  <link rel="stylesheet" href="css/styles03.css">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body ontouchstart="">
  <div class="body">
    <div class="main " id="main">
      <div class='header'>
      </div>
      <div class="main_contents " id="main_contents">
        <div class='main_content my_file'>
          <p>練習メニューをアップロードする。（拡張子は.xlsl or .pdf）</p>
          <form action="../file.php" method="post" enctype="multipart/form-data">
            <input type="file" name='menu'>
            <button name='file'>アップロード</button>
          </form>
          <div class='download_tmp'>
            <p>メニューのフォーマットは以下からダウンロードできます。</p>
            <button name='download'>ダウンロード</button>
          </div>
        </div>
        
        <div class='main_content setting'>
          <h3>設定</h3>
        <!-- <form id='setting_color'> -->
          <div class='color_mode radios'>
            <p>カラーモードを選択</p>
            <label><input type="radio" name='main_color' value='light'>ライト</label>
            <label><input type="radio" name='main_color' value='dark'>ダーク</label>
          </div>
          <div class='color_sub radios'>
            <p>サブカラーを選択</p>
            <label class='sumple blue'><input type="radio" name='sub_color' value='blue'>ブルー</label>
            <label class='sumple red'><input type="radio" name='sub_color' value='red'>レッド</label>
            <label class='sumple green'><input type="radio" name='sub_color' value='green'>グリーン</label>
            <label class='sumple purple'><input type="radio" name='sub_color' value='purple'>パープル</label>
          </div>
          <button name='color_setting' onclick="set_color_mode()">変更する</button>
        <!-- </form> -->
        </div>

      </div>
    </div>
  </div>



<div class="footer_nav">
  <div class="footer_nav_items conteiner">
    <div class="footer_nav_item ">
      <a href="index.php">
        <h4>ホーム</h4>
      </a>
    </div>
    <div class="footer_nav_item">
      <a href="<?php if($name!='root'):echo'results.php'; else:echo'root_results.php';endif;?>">
        <h4>CT・大会</h4>
      </a>
    </div>
    <div class="footer_nav_item now">
      <a href="<?php if($name!='root'):echo'mypage.php'; else:echo'root_mypage.php';endif;?>">
        <h4>MyPage</h4>
      </a>
    </div>

  </div>

</div>







  <script type="text/javascript" src="../functions.js"></script>

</body>
</html>