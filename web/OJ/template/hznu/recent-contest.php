<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.27
   * last modified
   * by yybird
   * @2016.03.27
  **/
?>

<?php $title="F.A.Qs";?>
<?php require_once("header.php"); ?>

<div class="am-container">
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
      <li><a href="./contest.php">Local</a></li>
      <li class="am-active"><a href="./recent-contest.php">Remote</a></li>
    </ul>
  </div>
  <div class="am-avg-md-1">
    <table class='am-table am-table-hover am-table-striped am-text-center'>
      <thead>
      <tr>
        <th class='am-text-center'>OJ</th>
        <th class='am-text-center'>Name</th>
        <th class='am-text-center'>Start Time</th>
        <th class='am-text-center'>Week</th>
        <th class='am-text-center'>Access</th>
      </tr>
      </thead>
      <tbody>
      <?php
      $odd=true;
      foreach($rows as $row) {
          $odd=!$odd;
          ?>
        <tr>
          <td><?php echo$row['oj']?></td>
          <td><a id="name_<?php echo$row['id']?>" href="<?php echo$row['link']?>" target="_blank"><?php echo$row['name']?></a></td>
          <td><?php echo$row['start_time']?></td>
          <td><?php echo$row['week']?></td>
          <td><?php echo$row['access']?></td>
        </tr>
          <?php
      }
      ?>
      </tbody>
    </table>
  </div>
</div>
<div class='am-text-center'>
  DataSource: <a href='http://contests.acmicpc.info/contests.json'>http://contests.acmicpc.info/contests.json</a>&nbsp;&nbsp;&nbsp;
  Spider Author: <a href="http://contests.acmicpc.info">doraemonok</a>
</div>
<?php require_once("footer.php") ?>


