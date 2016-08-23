<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php 
  require("admin-header.php");
  require_once("../include/set_get_key.php");

  if (!HAS_PRI("edit_privilege")) {
    echo "Permission denied!";
    exit(1);
  }
  echo "<title>Privilege List</title>"; 
  echo "<center><h2>Privilege List</h2></center>";
  $sql="select * FROM privilege where rightstr in ('administrator','teacher','teacher_assistant','source_browser','contest_creator','http_judge','problem_editor') ";
  $result=mysql_query($sql) or die(mysql_error());
  echo "<center><table class='table table-striped' width=60% border=1>";
  echo "<thead><tr><td>user<td>right<td>defunct</tr></thead>";
for (;$row=mysql_fetch_object($result);){
  echo "<tr>";
  echo "<td>".$row->user_id;
  echo "<td>".$row->rightstr;
//  echo "<td>".$row->start_time;
//  echo "<td>".$row->end_time;
//  echo "<td><a href=contest_pr_change.php?cid=$row->contest_id>".($row->private=="0"?"Public->Private":"Private->Public")."</a>";
  if (strtolower($row->rightstr) != "administrator") echo "<td><a href=privilege_delete.php?uid=$row->user_id&rightstr=$row->rightstr&getkey=".$_SESSION['getkey'].">Delete</a>";
//  echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
//  echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
  echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>
