<?php
include('_header.php');

$stmt=$dbh->query('select * from members ');
$members=$stmt->fetchall();
?>

<div class='main'>
  <h1>メンバー一覧</h1>
  <div>
    <table>
      <thead>
        <th>名前</th>
        <th>学年</th>
        <th>種目</th>
      </thead>
      <tbody>
        <? foreach($members as $member){?>
          <tr>
            <td>
            <?=$member['name']?>
            </td>
            <td>
            <?=$member['year']?>
            </td>
            <td>
            <?=$member['event']?>
            </td>
          </tr>
        <?}?>
      </tbody>
    </table>
  </div>
  <a href='editer.php'>編集画面へ</a>
</div>



<?php

include('_footer.php');
