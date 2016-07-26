<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.04.12
  **/
?>

<?php
  $title="Problem Status";
  if ($_GET['cid']) require_once("contest_header.php");
  else require_once("header.php");
?>
<div class="am-container" style="margin-top:40px;">
  <h3 style="color:red;">Problems <?php echo $id?> Status</h3>
  <hr />
  <div class="am-g">
    <div class="am-u-sm-3">
      <table class="am-table am-table-hover">
        <tbody>
          <?php
            foreach($view_problem as $row){
              echo "<tr>";
              echo "<td class='am-primary'>".$row[0]."</td>";
              echo "<td class='am-warning am-text-center'>".$row[1]."</td>";
              /*foreach($row as $table_cell){
                echo "<td>";
                echo $table_cell;
                echo "</td>";
              }*/
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="am-u-sm-9">
      <table class="am-table am-table-hover am-table-striped">
        <thead>
          <th>#</th>
          <th class='am-text-center'>Run.ID</th>
          <th class='am-text-center'>User ID</th>
          <th class='am-text-center'>Memory</th>
          <th class='am-text-center'>Time</th>
          <th class='am-text-center'>Language</th>
          <th class='am-text-center'>Length</th>
          <th class='am-text-center'>Sub Time</th>
        </thead>
        <tbody>
          <?php
            foreach($view_solution as $row){
              echo "<tr>";
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
      <ul class="am-pagination">
        <?php
          echo "<li><a href='problemstatus.php?id=$id'>[TOP]</a></li>";
          echo "<li><a href='status.php?problem_id=$id'>[STATUS]</a></li>";
          if ($page>$pagemin){
            $page--;
            echo "<li><a href='problemstatus.php?id=$id&page=$page'>[PREV]</a></li>";
            $page++;
          }
          if ($page<$pagemax){
            $page++;
            echo "<li><a href='problemstatus.php?id=$id&page=$page'>[NEXT]</a></li>";
            $page--;
          } 
        ?>
      </ul>
    </div>
  </div>
</div>

<?php require_once("footer.php")?>