<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.03.23
  **/
?>

<?php $title="ContestSet";?>
<?php include "header.php" ?>

<div class="am-container">
  <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
    <ul class="am-nav am-nav-tabs">
      <li class="am-active"><a href="/OJ/contest.php">Local</a></li>
      <li><a href="/OJ/recent-contest.php">Remote</a></li>
    </ul>
  </div>
  <div class="am-avg-md-1">
    <table class="am-table">
      <thead>
      <th class='am-text-center'>ID</th>
      <th class='am-text-center'>Name</th>
      <th class='am-text-center'>Status</th>
      <th class='am-text-center'>Type</th>
      </thead>
      <tbody>
      <?php
      foreach($view_contest as $row){
          echo "<tr class='am-text-center'>";
          foreach($row as $table_cell){
              echo "<td>";
              echo $table_cell;
              echo "</td>";
          }
          echo "</tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
</div>
<?php include "footer.php" ?>
