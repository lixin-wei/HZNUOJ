<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php 
  require("admin-header.php");
  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
  if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
  }
  echo "<title>Problem List</title>";
  echo "<center><h2>Contest List</h2></center>";
  require_once("../include/set_get_key.php");
  $sql="SELECT max(`contest_id`) as upid, min(`contest_id`) as btid  FROM `contest`";
  $page_cnt=50;
  $result=mysql_query($sql);
  echo mysql_error();
  $row=mysql_fetch_object($result);
  $base=intval($row->btid);
  $cnt=intval($row->upid)-$base;
  $cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);
  if (isset($_GET['page'])){
    $page=intval($_GET['page']);
  }else $page=$cnt;
  $pstart=$base+$page_cnt*intval($page-1);
  $pend=$pstart+$page_cnt;
  for ($i=1;$i<=$cnt;$i++){
    if ($i>1) echo '&nbsp;';
    if ($i==$page) echo "<span class=red>$i</span>";
    else echo "<a href='contest_list.php?page=".$i."'>".$i."</a>";
  }
  $sql="select `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` where contest_id>=$pstart and contest_id <=$pend order by `contest_id` desc";
  $keyword=$_GET['keyword'];
  $keyword=mysql_real_escape_string($keyword);
  if($keyword) $sql="select `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` where title like '%$keyword%' ";
  $result=mysql_query($sql) or die(mysql_error());
?>

<form action=contest_list.php class=center>
  <input name=keyword><input type=submit value="<?php echo $MSG_SEARCH?>" >
</form>


<?php
  echo "<center><table class='table table-striped table-hover' width=90%>";
  echo "<tr><td>ContestID<td>Title<td>StartTime<td>EndTime<td>Private<td>Status<td>Edit<td>Copy<td>Export<td>Logs";
  echo "</tr>";
  for (;$row=mysql_fetch_object($result);){
    echo "<tr>";
    echo "<td>".$row->contest_id;
    echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a>";
    echo "<td>".$row->start_time;
    echo "<td>".$row->end_time;
    $cid=$row->contest_id;
    if(HAS_PRI("edit_contest")) {
      echo "<td><a href=contest_pr_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->private=="0"?"<span class=green>Public</span>":"<span class=red>Private<span>")."</a>";
      echo "<td><a href=contest_df_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span style='color: green;'>Available</span>":"<span style='color: red;'>Reserved</span>")."</a>";
      echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
      echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
      echo "<td><a href=\"problem_export_xml.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Export</a>";
      echo "<td> <a href=\"../export_contest_code.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Logs</a>";
    } else {
      echo "<td colspan=5 align=right><a href=contest_add.php?cid=$row->contest_id>Copy</a><td>";
    }
    echo "</tr>";
  }
echo "</table></center>";
require_once("admin-footer.php")
?>
