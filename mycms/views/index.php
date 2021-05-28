<?php
require_once('../db.php');
session_start();
$events_track=array('100m','200m','110mh','100mh','400m','400mh','800m','1500m','5000m','10000m','3000msc');
$events_field=array('走幅跳','三段跳','走高跳','棒高跳','やり投','円盤投','砲丸投','ハンマー投','十種競技');

function ranking_track($event,$sex){
  global $dbh;
  global $ranking_row;
  $stmt=$dbh->query(
    "SELECT members.name as results_name, min(results.result_track) as result_track  FROM results
    left join members on results.results_name = members.name
    where results_event='$event' and sex='$sex' and results_tou != '高校' 
    group by results.results_name
    order by min(result_track) asc limit 5"
  );
  $ranking_row=$stmt->fetchall();
};

function ranking_field($event,$sex){
  global $dbh;
  global $ranking_row;
  $stmt=$dbh->query(
    "SELECT members.name as results_name, max(results.result_field) as result_field FROM results 
    left join members on results.results_name = members.name
    where results_event='$event' and sex='$sex' and results_tou != '高校' 
    group by results.results_name
    order by max(result_field) desc limit 5"
  );
  $ranking_row=$stmt->fetchall();
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
  $start_date=date('Y').'/'.'04/01';
  $d=date('Y')+1;
  $finish_date=$d .'/'.'04/01';
  $stmt = $dbh -> query('SELECT name FROM members');
  $members = $stmt -> fetchall(PDO::FETCH_COLUMN);
  foreach($members as $member){
    $stmt = $dbh->query("SELECT * FROM results WHERE results_event = '$event' AND results_name = '$member' ORDER BY result_track ASC limit 1");
    $results_result_top = $stmt->fetch();
    if($results_result_top){
      if(strtotime($start_date) < strtotime($results_result_top['results_date']) && strtotime($results_result_top['results_date']) < strtotime($finish_date)){
         array_push ($besters,$results_result_top);
      }
    }
  };
  
};
function best_field($event){
  global $dbh;
  global $besters;
  $now_date = date("Y/m/d");
  $start_date=date('Y').'/'.'04/01';
  $d=date('Y')+1;
  $finish_date=$d .'/'.'04/01';
  $stmt = $dbh -> query('SELECT name FROM members');
  $members = $stmt -> fetchall(PDO::FETCH_COLUMN);
  foreach($members as $member){
    $stmt = $dbh->query("SELECT * FROM results WHERE results_event = '$event' AND results_name = '$member' ORDER BY result_field desc limit 1");
    $results_result_top = $stmt->fetch();
    if($results_result_top){
      if(strtotime($start_date) < strtotime($results_result_top['results_date']) && strtotime($results_result_top['results_date']) < strtotime($finish_date)){
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
  <link rel="stylesheet" href="css/styles01.css">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body ontouchstart="" class=''>
  <div class=" body">

    <div class="main " id="main">



      <div class="top_nav">
        <ul class="conteiner">
          <li class="nav_item nav_active">NEWS</li>
          <li class="nav_item">BEST</li>
          <li class="nav_item">RANKING</li>
        </ul>
      </div>
      <div class="main_contents " id="main_contents">

        <div class="main_conteiner conteiner">
  
  
          <div class="news_contents">
            <div class="edit">
              <div class="form_button" id="form_button" >＋</div>
              <form action="../news.php" method='post' id="form" class="form form_active">
                <p>お知らせを追加する</p>
                <div class='conteiner'>
                  <label for="news_title">タイトル</label>
                  <input type="text" id='news_title' name='title' class="input_text" placeholder="大会について" required>
                </div>
                <div class='conteiner'>
                  <label for="news_content">内容</label>
                  <textarea  id="news_content" name='content' required></textarea>
                </div>
                <div class='conteiner'>
                  <label for="news_date">削除する日付</label>
                  <input type="date" id='news_date' name='date' class="input_text" placeholder="" required>
                </div>
                
                <button name='news'>追加</button>
              </form> 
            </div>
            <div class='news_menu'>
              <h4>今日のメニュー（短距離）</h4>
              <?php
              $command="export LANG=ja_JP.UTF-8; /usr/bin/python ../menu_pdf.py 2>&1";
              exec($command,$output_py,$error_py);
              var_dump($error_py);
              var_dump($output_py);
              ?>
              <p>試作品</p>
              <h4>今日のメニュー（中長距離）</h4>
              <p>試作品</p>
            </div>
            <!-- <p>アップデートでアンケート機能とか追加したい。</p> -->
            <!-- <h2>NEWS</h2> -->
            <div class="news_items">
              <?php news()?>
              <?php foreach($news as $new){?>
                <div class='news_item'>
                  <h3><?= $new['title']?><span class='news_deadline'><?= $new['deadline']?>に自動消去</span></h3>
                  <p><?= $new['content']?></p>
                </div>
              <?php }?>

            </div>
          </div>

          <div class="best_contents">
            <div class="best_content best_rate">
              <h3>Best<label>/人</label></h3>
              <?php num_best()?>
              <p><?= $num_best?>%/目標30%</p>
              <?php if($num_best>30){echo '目標達成!';}?>
              <div class="best_bar">
                <div class="best_in">
                </div>
              </div>
            </div>
            <div class="best_content">
              <div class="best_mem">
                <h3>今シーズンベスト更新者</h3>
                <p>おめでとうございます。</p>
                <table>
                  <thead>
                    <tr>
                      <th>名前</th>
                      <th>日付</th>
                      <th>大会名</th>
                      <th>種目</th>
                      <th>記録(T)</th>
                      <th>記録(F)</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach($besters as $bester){ ?>
                      <tr>
                        <td><?=$bester['results_name']?></td>
                        <td><?=$bester['results_date']?></td>
                        <td><?=$bester['results_tou']?></td>
                        <td><?=$bester['results_event']?></td>
                        <?php check_result($bester['result_track'])?>
                        <td><?=$result_str?></td>
                        <td><?=$bester['result_field']?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
  
          <div class="ranking_contents">
            <div class="ranking_content">
              <div class="ranking_male ranking_items">
                <h3>男子</h3>
                <ul>
                  <?php foreach ($events_track as $event_track) {?>
                  <?php ranking_track($event_track,1);?>
                  <?php if($ranking_row!=[]):?>
                  <li class='ranking_item'>
                    <h4><?=$event_track?></h4>
                    <ul class='ranking_123'>
                    <?php $i=1;?>
                      <?php foreach($ranking_row as $ranking_123){?>
                        <?php check_result($ranking_123['result_track'])?>
                        <li><span><?= $i.'.'.$ranking_123['results_name']?></span> : <?=$result_str?> [s]</li>
                        <?php $i++;?>
                      <?php } ?>
                    </ul>
                  </li>
                  <?php endif?>
                  <?php }?>
                  <?php foreach ($events_field as $event_field) {?>
                    <?php ranking_field($event_field,1);?>
                    <?php if($ranking_row!=[]):?>
                    <li class='ranking_item'>
                      <h4><?=$event_field?></h4>
                      <ul class='ranking_123'>
                      <?php $i=1;?>
                        <?php foreach($ranking_row as $ranking_123){?>
                          <li><span><?= $i.'.'.$ranking_123['results_name']?></span> : <?=$ranking_123['result_field']?> [m]</li>
                          <?php $i++;?>
                        <?php } ?>
                      </ul>
                    </li>
                    <?php endif;?>
                  <?php }?>
                </ul>
              </div>
              <div class="ranking_female ranking_items">
                <h3>女子</h3>
                <ul >
                  <?php foreach ($events_track as $event_track) {?>
                    <?php ranking_track($event_track,2);?>
                    <?php if($ranking_row!=[]):?>
                    <li class='ranking_item'>
                      <h4><?=$event_track?></h4>
                      <ul class='ranking_123'>
                        <?php $i=1;?>
                        <?php foreach($ranking_row as $ranking_123){?>
                          <?php check_result($ranking_123['result_track'])?>
                          <li><span><?= $i.'.'.$ranking_123['results_name']?></span> : <?=$result_str?> [s]</li>
                          <?php $i++;?>
                        <?php } ?>
                      </ul>
                    </li>
                    <?php endif;?>
                    <?php }?>
                    <?php foreach ($events_field as $event_field) {?>
                      <?php ranking_field($event_field,2);?>
                      <?php if($ranking_row!=[]):?>
                      <li class='ranking_item'>
                        <h4><?=$event_field?></h4>
                        <ul class='ranking_123'>
                        <?php $i=1;?>
                          <?php foreach($ranking_row as $ranking_123){?>
                            <li><span><?= $i.'.'.$ranking_123['results_name']?></span> : <?=$ranking_123['result_field']?> [m]</li>
                            <?php $i++;?>
                          <?php } ?>
                        </ul>
                      </li>
                      <?php endif?>
                    <?php }?>
                  
                </ul>
              </div>
            </div>
          </div>


      </div>


      </div>



    </div>
  </div>




<div class="footer_nav">
  <div class="footer_nav_items conteiner">
    <div class="footer_nav_item now">
      <a href="index.php">
        <h4>ホーム</h4>
      </a>
    </div>
    <div class="footer_nav_item">
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