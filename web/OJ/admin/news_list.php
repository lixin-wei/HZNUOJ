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
  $sql="SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` order by defunct, `importance` desc";
  $result=$mysqli->query($sql) or die($mysqli->error);
  ?>
  <title><?php echo $html_title.$MSG_NEWS.$MSG_LIST ?></title>
  <h1><?php echo $MSG_NEWS.$MSG_LIST ?></h1>
  <h4><?php echo $MSG_HELP_NEWS_LIST ?></h4><hr/>
  <table class='table table-condensed table-hover' style='white-space: nowrap;'>
  <thead><tr>
  <th><?php echo $MSG_ID ?></th>
  <th><?php echo $MSG_TITLE ?></th>
  <th><?php echo $MSG_CreatedDate ?></th>
  <th><?php echo $MSG_STATUS ?></th>
  <th><?php echo $MSG_EDIT ?></th>
  <th><?php echo $MSG_DEL ?></th>
  </tr></thead>
  <tbody>
  <?php
  for (;$row=$result->fetch_object();) {
    echo "<tr>";
    echo "<td>".$row->news_id;
    //echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
    echo "<td><a href='news_edit.php?id=$row->news_id'>".$row->title."</a>";
    echo "<td>".$row->time;
    echo "<td><a href=news_df_change.php?id=$row->news_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span class=green>$MSG_Available</span>":"<span class=red>$MSG_Reserved</span>")."</a>";
    echo "<td><a href=news_edit.php?id=$row->news_id>Edit</a>";
    echo "<td><a href='news_del.php?id=$row->news_id&getkey={$_SESSION['getkey']}' onclick=\"return confirm('$MSG_DEL?');\">Delete</td>";
    echo "</tr>";
  }
?>
</tbody></table>
<?php 
  require_once("admin-footer.php")
?>

<script type="text/javascript">
  $("#news_del").click(function(){
    alert("asdasd");
  });
</script>