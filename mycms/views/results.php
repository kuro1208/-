<?php
require_once('../db.php');
session_start();
$ct_events=['30m','60m','90m','150m','300m','立幅跳','立五段跳','助走付五段跳','メディ投げF','メディ投げB','CMJ','CMJ_L','CMJ_R','RJ_index','RJ_cm','RJ_sec','左RJ_index','左RJ_cm','左RJ_sec','右RJ_index','右RJ_cm','右RJ_sec','ベンチプレス','パワークリーン','ハングクリーン','スクワット'];
$events_track=array('100m','200m','110mh','100mh','400m','400mh','800m','1500m','5000m','10000m','3000msc');
$events_field=array('走幅跳','三段跳','走高跳','棒高跳','やり投','円盤投','砲丸投','ハンマー投','十種競技');

function ct_result($ct_name,$ct_date){
  global $dbh;
  global $ct_results;
  $stmt=$dbh->query(
    "SELECT * FROM ct_results where name='$ct_name' AND date = '$ct_date' "
  );
  $ct_results=$stmt->fetchall();
};
function ct_result_all($ct_name){
  global $dbh;
  global $ct_results_all;
  $stmt=$dbh->query(
    "SELECT * FROM ct_results where name='$ct_name'"
  );
  $ct_results_all=$stmt->fetchall();
};

// function result($re_name){
//   global $dbh;
//   global $results;
//   $stmt=$dbh->query(
//     "SELECT * FROM results where results_name='$re_name' "
//   );  
//   $results=$stmt->fetchall();
// };
function result(){
  global $dbh;
  global $results;
  $stmt=$dbh->query(
    "SELECT * FROM results order by results_date desc"
  );  
  $results=$stmt->fetchall();
};
function my_result(){
  global $dbh;
  global $results;
  global $name;

  $stmt=$dbh->query(
    "SELECT * FROM results where results_name='$name' order by results_date desc"
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
  
  if($i==0){
    $query_defo="where results_name='$name'";
    $search_query .= "$query_defo";
    $search_title .= $name;
  }
  $search_query.="order by results_date desc";

  $stmt=$dbh->query($search_query);
  $results=$stmt->fetchall();
}

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

function members(){
  global $dbh;
  global $members_row;
  $stmt=$dbh->query(
    "SELECT * FROM members order by year desc"
  );
  $members_row=$stmt->fetchall();
}


$plot_data=array();
function plot_data($date,$data){
  global $plot_data;
  $plot_datum=array();
  $plot_datum[0]=$date;
  $plot_datum[1]=$data;
  $plot_data=array_push($plot_data,$plot_datum);
  // var_dump($plot_data);
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
  <link rel="stylesheet" href="css/styles02.css">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>




<body ontouchstart="">
  <div class="body">

    <div class="main " id="main">

      <div class="top_nav">
        <ul class="conteiner">
          <li class="nav_item nav_active">大会</li>
          <li class="nav_item">CT</li>
        </ul>
      </div>
      <div class="main_contents " id="main_contents">

        <div class="main_conteiner conteiner">
  
  
          <div class="tou_contents">
            <div class="edit">
              <div class="form_button" id="form_button" >＋</div>

              <form action="../results.php" method='post' id="form" class="form form_active">
                <p><?=$name?>の大会結果を追加する(高校の記録は大会名で「高校」を選び、日付は入力しなくても良いです。)</p>
                <div class='conteiner'> 
                  <label for="tou_name">名前</label>
                  <input type="text" id='tou_name' value='<?=$name?>' name='name' readonly>
                </div>
                <div class='conteiner'>
                  <label for="tou_title">種目</label>
                  <select name="event" id="" required>
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
                    <option value="ハンマー投">ハンマー投</option>
                    <option value="砲丸投">砲丸投</option>
                    <option value="十種競技">十種競技</option>
                  </select>
                </div>
                <div class='conteiner'>
                  <label for="tou_date">日付</label>
                  <input type="date" name='date' class="input_text" placeholder="" id='tou_date'>
                </div>
                <div class='conteiner'>
                  <label for="tou_name">大会名</label>
                  <select name="tou" id="tou_name">
                    <option value="記録会">記録会</option>
                    <option value="インカレ">インカレ</option>
                    <option value="東国">東国</option>
                    <option value="高校">高校(日付は入力しなくても良い)</option>
                  </select>
                </div>
                <div class='conteiner'>
                  <label for="tou_result">記録(単位不要)</label>
                  <input type="number" name='result' step='0.01' id='tou_result' class="input_text" placeholder="例:11.23[s],13.46[m]" required>
                </div>
                <p>※注意　[分]を含む記録の場合はのみ、６桁で入力してください。例：１分１秒１０→010110</p>

                <!-- <div class='conteiner'> 
                  <label for="tou_result_field">フィールド競技の記録</label>
                  <input type="number" name='result_field' step='0.01' id='tou_result_field' class="input_text" placeholder="13.50">
                </div> -->
                
                
                
                
                
                <button name='results'>追加</button>
              </form>
            </div>

            <div class="tou_content">
              <h3>大会結果一覧</h3>
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
              <?php search_results()?>
              <p><?= $search_title?>の大会結果</p>
              <div class="tou_table">
                <table>
                  <thead>
                    <tr>
                      <th>名前</th>
                      <th>日付</th>
                      <th>大会名</th>
                      <th>種目</th>
                      <th>結果(T)</th>
                      <th>結果(F)</th>
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
          </div>

          <div class="ct_contents">
            <div class="edit">
              <div class="form_button" id="form_button" >＋</div>
              <form action="../results_ct.php" method='post' id="form" class="form form_active">
                <p>CT結果を追加する(日付を同じにすると、入力した記録のみ上書き保存されます。)</p>
                <div class='conteiner'>
                  <label for="ct_name">名前</label>
                  <input type="text" value='<?=$name?>' name='name' id='ct_name' readonly>
                </div>
                <div class='conteiner'>
                  <label for="ct_date">日付</label>
                  <select name="date" id="ct_date" required>
                    <option value="1">１月</option>
                    <option value="2">２月</option>
                    <option value="3">３月</option>
                    <option value="4">４月</option>
                    <option value="5">５月</option>
                    <option value="6">６月</option>
                    <option value="7">７月</option>
                    <option value="8">８月</option>
                    <option value="9">９月</option>
                    <option value="10">１０月</option>
                    <option value="11">１１月</option>
                    <option value="12">１２月</option>
                  </select>
                </div>
                <div class='conteiner'>
                  <label for="ct_30m">30m</label>
                  <input type="number" name='30m' step='0.01' id='ct_30m'>
                </div>
                <div class='conteiner'>
                  <label for="ct_60m">60m</label>
                  <input type="number" name='60m' step='0.01' id='ct_60m'>
                </div>
                <div class='conteiner'>
                  <label for="ct_90m">90m</label>
                  <input type="number" name='90m' step='0.01' id='ct_90m'>
                </div>
                <div class='conteiner'>
                  <label for="ct_150m">150m</label>
                  <input type="number" name='150m' step='0.01' id='ct_150m'>
                </div>
                <div class='conteiner'>
                  <label for="ct_300m">300m</label>
                  <input type="number" name='300m' step='0.01' id='ct_300m'>
                </div>
                <div class='conteiner'>
                  <label for="ct_slj">立幅跳</label>
                  <input type="number" name='slj' step='0.01' id='ct_slj'>
                </div>
                <div class='conteiner'>
                  <label for="">立ち五段跳</label>
                  <input type="number" name='s5j' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">助走付き五段跳</label>
                  <input type="number" name='r5j' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">メディ投げF</label>
                  <input type="number" name='mtf' step='0.10'>
                </div>
                <div class='conteiner'>
                  <label for="">メディ投げB</label>
                  <input type="number" name='mtb' step='0.10'>
                </div>
                <div class='conteiner'>
                  <label for="">CMJ</label>
                  <input type="number" name='cmj' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">CMJ(L)</label>
                  <input type="number" name='cmjl' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">CMJ(R)</label>
                  <input type="number" name='cmjr' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">RJ(index)</label>
                  <input type="number" name='rj_i' step='0.01'>     
                </div>
                <div class='conteiner'>
                  <label for="">RJ(跳躍高)</label>
                  <input type="number" name='rj_h' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">RJ(接地時間)</label>
                  <input type="number" name='rj_t' step='0.001'>
                </div>
                <div class='conteiner'>
                  <label for="">左Rj(index)</label>
                  <input type="number" name='rjl_i' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">左RJ(跳躍高)</label>
                  <input type="number" name='rjl_h' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">左RJ(接地時間)</label>
                  <input type="number" name='rjl_t' step='0.001'>
                </div>
                <div class='conteiner'>
                  <label for="">右Rj(index)</label>
                  <input type="number" name='rjr_i' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">右RJ(跳躍高)</label>
                  <input type="number" name='rjr_h' step='0.01'>
                </div>
                <div class='conteiner'>
                  <label for="">右RJ(接地時間)</label>
                  <input type="number" name='rjr_t' step='0.001'>
                </div>
                <div class='conteiner'>
                  <label for="">ベンチプレス</label>
                  <input type="number" name='bench' step='0.25'>      
                </div>
                <div class='conteiner'>
                  <label for="">クリーン(パワー)</label>
                  <input type="number" name='power' step='0.25'>
                </div>
                <div class='conteiner'>
                  <label for="">クリーン(ハング)</label>
                  <input type="number" name='hung' step='0.25'>
                </div>
                <div class='conteiner'>
                  <label for="">スクワット</label>
                  <input type="number" name='squat' step='0.25'>
                </div>
                <button name='result_ct'>追加</button>
              </form>

            </div>
            <div class="ct_content ">
              <h2>CTの記録</h2>
              <p></p>
              <div class='ct_select conteiner'>
                <div class='button' id='preButton'>
                  ◀︎
                </div>
                <p>月を変える</p>
                <div class='button' id='nextButton'>
                  ▶︎
                </div>
              </div>

              <div class='conteiner ct_select_balls'>
                <div class='ct_select_ball' id='select1'></div>
                <div class='ct_select_ball' id='select2'></div>
                <div class='ct_select_ball' id='select3'></div>
                <div class='ct_select_ball' id='select4'></div>
                <div class='ct_select_ball' id='select5'></div>
                <div class='ct_select_ball' id='select6'></div>
                <div class='ct_select_ball' id='select7'></div>
                <div class='ct_select_ball' id='select8'></div>
                <div class='ct_select_ball' id='select9'></div>
                <div class='ct_select_ball' id='select10'></div>
                <div class='ct_select_ball' id='select11'></div>
                <div class='ct_select_ball' id='select12'></div>
              </div>
              <div class="ct_record_conteiner conteiner">
                <div class="ct_record eve">
                  <ul class=''>
                    <li>種目</li>
                    <?php foreach($ct_events as $ct_event){?>
                      <li><?=$ct_event?></li>
                    <?php } ?>
                  </ul>
                </div>

                <div class="ct_record pre">
                  <ul class='' id='ct_1'>
                  <?php ct_result_all($name)?> 
                  <?php $json_ct_results=json_encode($ct_results_all);?>
                  
                  
                  </ul>

                </div>
                <div class="ct_record last">
                
                  <ul class='' id='ct_2'>

                  </ul>
                </div>
                <div class="ct_record rate">

                  <ul class='' id='rate'>
                    
                  </ul>
                </div>
              </div>
            </div>
            </div>
          </div>
  
        </div>


      </div>


      </div>



    </div>
  </div>










<div class="footer_nav">
  <div class="footer_nav_items conteiner">
    <div class="footer_nav_item">
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




  <script>
    let ct_results=<?= $json_ct_results?>;
  </script>
  <script type="text/javascript" src="../functions.js"></script>

</body>
</html>

<?php
