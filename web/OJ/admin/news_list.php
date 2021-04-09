<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php 
  require("admin-header.php");
  require_once("../include/set_get_key.php");
  if (!HAS_PRI("edit_news")) {
    require_once("error.php");
    exit(1);
  }
  $sql="SELECT * FROM `news` order by defunct, `importance` desc";
  $result=$mysqli->query($sql) or die($mysqli->error);
  ?>
  <title><?php echo $html_title.$MSG_NEWS.$MSG_LIST ?></title>
  <h1><?php echo $MSG_NEWS.$MSG_LIST ?></h1>
  <h4><?php echo $MSG_HELP_NEWS_LIST ?></h4><hr/>
  <table class='table table-condensed table-hover table-bordered table-striped' style='white-space: nowrap;'>
  <thead><tr>
  <th style="width:50px"><?php echo $MSG_ID ?></th>
  <th><?php echo $MSG_TITLE ?></th>
  <th style="width:50px;"><?php echo $MSG_Importance ?></th>
  <th style="width:50px;"><?php echo $MSG_Creator ?></th>
  <th style="width:150px;"><?php echo $MSG_LastEditTime ?></th>
  <th style="width:50px;"><?php echo $MSG_STATUS ?></th>
  <th colspan="2" style="text-align: center;"><?php echo $MSG_Operations ?></th>
  </tr></thead>
  <tbody>
  <?php
  for (;$row=$result->fetch_object();) {
    echo "<tr>";
    echo "<td style='vertical-align:middle;'>".$row->news_id."</td>";
    //echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
    echo "<td style='vertical-align:middle;'>".$row->title."</td>";
    echo "<td style='vertical-align:middle;'>".$row->importance."</td>";
    echo "<td style='vertical-align:middle;'>".$row->user_id."</td>";
    echo "<td style='vertical-align:middle;'>".$row->time."</td>";
    echo "<td style='vertical-align:middle;'><a ".($row->defunct=="N"?"class='btn btn-primary'":"class='btn btn-danger'")." href='news_df_change.php?id=$row->news_id&getkey=".$_SESSION['getkey']."'>".($row->defunct=="N"?"<span class=green>$MSG_Available</span>":"<span class=red>$MSG_Reserved</span>")."</a>";
    echo "<td style='vertical-align:middle;width:50px;'><a class='btn btn-primary' href='news_edit.php?id=$row->news_id'>$MSG_EDIT</a></td>";
    echo "<td style='vertical-align:middle;width:50px;'><a class='btn btn-primary' href='news_del.php?id=$row->news_id&getkey={$_SESSION['getkey']}' onclick=\"return confirm('$MSG_DEL?');\">$MSG_DEL</td>";
    echo "</tr>";
  }
?>
</tbody></table>
<?php 
  require_once("admin-footer.php")
?>
