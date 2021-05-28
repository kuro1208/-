<?php
require_once('../db.php');
session_start();
$events_track=array('100m','200m','400m','110mh','100mh','400mh','800m','1500m','5000m','3000msc');
$events_field=array('走幅跳','三段跳','走高跳','棒高跳','やり投','円盤投','砲丸投','ハンマー投');
$ct_events=['30m','60m','90m','150m','300m','立幅跳','立五段跳','助走付五段跳','メディ投げF','メディ投げB','CMJ','CMJ_L','CMJ_R','RJ_index','RJ_cm','RJ_sec','左RJ_index','左RJ_cm','左RJ_sec','右RJ_index','右RJ_cm','右RJ_sec','ベンチプレス','パワークリーン','ハングクリーン','スクワット'];

function members(){
  global $dbh;
  global $members_row;
  $stmt=$dbh->query(
    "SELECT * FROM members order by year desc"
  );
  $members_row=$stmt->fetchall();
}
function result(){
  global $dbh;
  global $results;
  $stmt=$dbh->query(
    "SELECT * FROM results order by results_date desc"
  );  
  $results=$stmt->fetchall();
};

function search_results(){
  global $name;
  global $dbh;
  global $results;
  global $search_title;
  $search_title='';
  $q='';
  $i=0;
  $search_query="SELECT * FROM results ";
  if(isset($_GET['s_sex'])&&$_GET['s_sex']!=''){
    $s_sex=$_GET['s_sex'];
    $query_sex="left join members on results.results_name = members.name where sex=$s_sex ";
    $search_query .= "$query_sex";
    $i++;
    if($s_sex==1){
      $search_title .= '男';
    }if($s_sex==2) {
      $search_title .= '女';
    }
    
  }
  if(isset($_GET['s_name'])&&$_GET['s_name']!=''){
    $s_name=$_GET['s_name'];
    if($i==0){
      $q='where';
    }else{
      $q='and';
    }
    $query_name="$q results_name='$s_name' ";
    $search_query .= "$query_name";
    $i++;
    $search_title .= $s_name;
  }
  if(isset($_GET['s_event'])&&$_GET['s_event']!=''){
    $s_event=$_GET['s_event'];
    if($i==0){
      $q='where';
    }else{
      $q='and';
    }
    $query_event="$q results_event='$s_event' ";
    $search_query .= "$query_event";
    $i++;
    $search_title .= $s_event;
  }
  if(isset($_GET['s_tou'])&&$_GET['s_tou']!=''){
    $s_tou=$_GET['s_tou'];
    if($i==0){$q='where';}else{$q='and';}
    $query_tou="$q results_tou='$s_tou' ";
    $search_query .= "$query_tou";
    $i++;
    $search_title .= $s_tou;
  }
  $search_query.="order by results_date desc";
  $stmt=$dbh->query($search_query);
  $results=$stmt->fetchall();
}




function ct_result1($ct_name,$ct_date){
  global $dbh;
  global $ct_results1;
  $stmt=$dbh->query(
    "SELECT * FROM ct_results where name='$ct_name' AND date = '$ct_date' "
  );
  $ct_results1=$stmt->fetch();
};
function ct_result2($ct_name,$ct_date){
  global $dbh;
  global $ct_results2;
  $stmt=$dbh->query(
    "SELECT * FROM ct_results where name='$ct_name' AND date = '$ct_date' "
  );
  $ct_results2=$stmt->fetch();
};
function ct_result_all(){
  global $dbh;
  global $ct_results_all;
  $stmt=$dbh->query(
    "SELECT * FROM ct_results "
  );
  $ct_results_all=$stmt->fetchall();
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

$besters = array();
function best_track($event){
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
function best_field($event){
  global $dbh;
  global $besters;
  $now_date = date("Y/m/d");
  $stmt = $dbh -> query('SELECT name FROM members');
  $members = $stmt -> fetchall(PDO::FETCH_COLUMN);
  foreach($members as $member){
    $stmt = $dbh->query("SELECT * FROM results WHERE results_event = '$event' AND results_name = '$member' ORDER BY result_field desc limit 1");
    $results_result_top = $stmt->fetch();
    if($results_result_top){
      if(strtotime($results_result_top['results_date']) < strtotime($now_date)){
         array_push ($besters,$results_result_top);
      }
    }
  };
  
};
function num_best(){
  global $dbh;
  global $num_best;
  global $besters;
  $stmt = $dbh -> query('SELECT name FROM members');
  $members = $stmt -> fetchall(PDO::FETCH_COLUMN);
  if(count($members) > 0 && count($besters) > 0){
    $num_best=round(count($besters)/count($members),3)*100;
  }else{
    $num_best=0;
  }
}
function news(){
  global $dbh;
  global $news;
  $stmt = $dbh -> query('SELECT * FROM news ORDER BY deadline ASC');
  $news = $stmt -> fetchall();
}

function deleteNews(){
  global $dbh;
  $now_date=date("Y-m-d");
  $stmt = $dbh -> prepare(
    "DELETE FROM news WHERE deadline < '$now_date' "
  );
  $stmt -> execute();
}


$name=$_SESSION['user_name'];
if (!isset($_SESSION["user_name"])) {
  header("Location: login.php");
  exit;
}

if(isset($_SESSION['output'])){
  echo "<script type='text/javascript'>alert('". $_SESSION['output']. "');</script>";
  $_SESSION['output']=null;
}

foreach($events_track as $event_track){
  best_track($event_track);
};
foreach($events_field as $event_field){
  best_field($event_field);
};
deleteNews();
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
  <link rel="stylesheet" href="css/stylesr.css">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body ontouchstart="">
  <div class=" body">

    <div class="main " id="main">

      <div class="top_nav">
        <ul class="conteiner">
          <li class="nav_item">大会</li>
          <li class="nav_item">CT</li>
        </ul>
      </div>


      <div class="main_contents " id="main_contents">

        <div class="main_conteiner conteiner">
          <!-- <div class="members_contents">
            <table>
              <thead>
                <th>名前</th>
                <th>学年</th>
                <th>性別</th>
                <th>種目１</th>
                <th>種目２</th>
                <th>種目３</th>
              </thead>
              <tbody>
                <?php members()?>
                <?php foreach($members_row as $mem):?>
                  <tr>
                    <td><?= $mem['name']?></td>
                    <td><?= $mem['year']?></td>
                    <td><?= $mem['sex']?></td>
                    <td><?= $mem['event1']?></td>
                    <td><?= $mem['event2']?></td>
                    <td><?= $mem['event3']?></td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
            </table>
          </div> -->

          <div class="tou_contents">
            <?php search_results()?>
            <div class ='tou_search'>
                <form action="" method='get'>
                  <?php members()?>
                  <select name="s_name" id="">
                    <option value="">名前を選択</option>
                    <?php foreach($members_row as $member):?>
                      <option><?= $member['name']?></option>
                    <?php endforeach;?>
                  </select>
                  <select name="s_sex" id="">
                    <option value="">性別を選択</option>
                    <option value="1">男</option>
                    <option value="2">女</option>
                  </select>
                  <select name="s_event" id="">
                    <option value="">種目を選択</option>
                    <?php foreach($events_track as $event):?>
                      <option><?= $event?></option>
                    <?php endforeach;?>
                    <?php foreach($events_field as $event):?>
                      <option><?= $event?></option>
                    <?php endforeach;?>
                  </select>
                  <select name="s_tou" id="">
                    <option value="">大会名を選択</option>
                    <option>インカレ</option>
                    <option>東国</option>
                    <option>記録会</option>
                    <option>高校</option>
                  </select>
                  <button>検索</button>
                </form>
              </div>
            <h3><?= $search_title?>の大会結果</h3>
            <div class="tou_table">
            <table>
              <thead>
                <tr>
                  <th>名前</th>
                  <th>日付</th>
                  <th>大会名</th>
                  <th>種目</th>
                  <th>結果（T）</th>
                  <th>結果（F）</th>
                </tr>
              </thead>
              <tbody>
                  
                  <?php foreach($results as $result){?>
                  <tr>
                    <td><?=$result['results_name']?></td>
                    <td><?=$result['results_date']?></td>
                    <td><?=$result['results_tou']?></td>
                    <td><?=$result['results_event']?></td>
                    <?php check_result($result['result_track'])?>
                    <td><?=$result_str?></td>
                    <td><?=$result['result_field']?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="ct_contents">
            <div class="ct_content ">
              <h2>CTの記録</h2>
              <p>現在の月にCT記録がある場合は○がつきます。タッチすると記録を見ることができます。</p>
              <?php foreach($members_row as $mem):?>
                <?php ?>
                <?php ct_result1($mem['name'],date("m"));?> 
                <?php ct_result2($mem['name'],date("m")-1);?> 
                <?php if($ct_results1 || $ct_results2):?>
                  <div class='ct_item'>
                    <label for='ct_check_<?= $mem['name']?>'>○<?= $mem['name']?>：<?= $mem['year']?>年<?= $mem['event1']?><?= $mem['event2']?><?= $mem['event3']?></label>
                    <input type="checkbox" style='display:none;' id='ct_check_<?= $mem['name']?>' class='ct_check'>
                    <div class='ct_table'>
                      <table>
                        <thead>
                          <th>種目</th>
                          <th><?= date('n')-1?>月</th>
                          <th><?= date('n')?>月</th>
                        </thead>
                        <tbody>
                          <?php foreach($ct_events as $ct_event):?>
                            <tr>
                              <td><?=$ct_event?></td>
                              <td><?= $ct_results2["$ct_event"]?></td>
                              <td><?= $ct_results1["$ct_event"]?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    

                    
                  </div>
                <?php else:?>
                  <div class='ct_item ct_none'>
                  <label><?= $mem['name']?>：<?= $mem['year']?>年<?= $mem['event1']?><?= $mem['event2']?><?= $mem['event3']?></label>
                  </div>
                <?php endif;?>
              <?php endforeach;?>


            </div>
          </div>


        </div>


      </div>



    </div>
  </div>



  <div class="footer_nav">
  <div class="footer_nav_items conteiner">
    <div class="footer_nav_item now">
      <a href="index.php ">
        <h4>ホーム</h4>
        <i></i>
      </a>
    </div>
    <div class="footer_nav_item">
      <a href="results.php">
        <h4>CT・大会</h4>
      </a>
    </div>
    <div class="footer_nav_item">
      <a href="mypage.php">
        <h4>MyPage</h4>
      </a>
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
    <div class="footer_nav_item now">
      <a href="<?php if($name!='root'):echo'results.php'; else:echo'root_results.php';endif;?>">
        <h4>CT・大会</h4>
      </a>
    </div>
    <div class="footer_nav_item">
      <a href="<?php if($name!='root'):echo'mypage.php'; else:echo'root_mypage.php';endif;?>">
        <h4>MyPage</h4>
      </a>
    </div>

  </div>

</div>


  <script type="text/javascript" src="../functions.js"></script>

</body>
</html>