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
  echo "<title>News List</title>";
  echo "<h1>News List</h1><hr/>";
  $sql="SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` order by defunct, `importance` desc";
  $result=$mysqli->query($sql) or die($mysqli->error);
  echo "<center><table class='table table-condensed table-striped table-hover'>";

  echo "<tr><th>PID<th>Title<th>Date<th>Status<th>Edit<th>Delete</tr>";
  for (;$row=$result->fetch_object();) {
    echo "<tr>";
    echo "<td>".$row->news_id;
    //echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
    echo "<td><a href='news_edit.php?id=$row->news_id'>".$row->title."</a>";
    echo "<td>".$row->time;
    echo "<td><a href=news_df_change.php?id=$row->news_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span class=green>Available</span>":"<span class=red>Reserved</span>")."</a>";
    echo "<td><a href=news_edit.php?id=$row->news_id>Edit</a>";
    echo "<td><a href='news_del.php?id=$row->news_id&getkey={$_SESSION['getkey']}' onclick=\"return confirm('Delete?');\">Delete</td>";
    echo "</tr>";
  }

  echo "</tr></form>";
  echo "</table></center>";
?>
<?php 
  require_once("admin-footer.php")
?>

<script type="text/javascript">
  $("#news_del").click(function(){
    alert("asdasd");
  });
</script>