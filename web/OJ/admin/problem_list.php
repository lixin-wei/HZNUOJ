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
  require_once("../include/set_get_key.php");
  if (!$can_see_problem) {
    require_once("error.php");
    exit(1);
  }
  // $keyword=$_GET['keyword'];
  // $keyword=mysql_real_escape_string($keyword);
  if($_GET['page'])$page=$_GET['page'];
  else $page=1;
  $page_cnt=100;
  // get problems START
  $res_set = mysql_query("SELECT set_name FROM problemset");
  $first = true;
  $sql = "";
  $cnt = 0;
  while($set_name=mysql_fetch_array($res_set)[0]){
    if($_GET['OJ']=='' || $_GET['OJ']==$set_name){
      if(HAS_PRI("see_hidden_".$set_name."_problem")){
        $t_sql=" FROM `problem` WHERE problemset='$set_name'";
      
        //count the number of problem START
        $res = mysql_query("SELECT COUNT('problem_id')".$t_sql);
        $cnt += mysql_fetch_array($res)[0];
        //count the number of problem END

        $t_sql = "SELECT `problem_id`, problemset ,`title`, `author`, `in_date`,`defunct` ".$t_sql;
        if($first) $first = false;
        else $t_sql = " UNION ".$t_sql;
        $sql .= $t_sql;
      }
    }
  }
  $sql.=" ORDER BY `problem_id` DESC ";
  $st=($page-1)*$page_cnt;
  $sql.=" LIMIT $st,$page_cnt";

  if($first) $sql="";
  // get problems END
  /* 计算页数cnt start */
  $view_total_page=$cnt/$page_cnt+($cnt%$page_cnt?1:0);// 页数
  /* 计算页数cnt end */

?>
  <title>Problem List</title>
  <h1>Problem List</h1>
  <hr/>
  <form action=problem_list.php>
    <select class='input-mini' onchange="location.href='problem_list.php?OJ=<?php echo $_GET['OJ']?>&page='+this.value;">
    <?php
      for ($i=1;$i<=$view_total_page;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<option value='$i' selected>";
        else  echo "<option value='$i'>";
        echo "page ".$i;
        echo "</option>";
      }
    ?>
    </select>
    <select class='input-mini' onchange="location.href='problem_list.php?OJ='+this.value;">
      
    <?php
      echo "<option value='' selected>All</option>";
      $res=mysql_query("SELECT set_name FROM problemset");
      while($set_name=mysql_fetch_array($res)[0]){
        if(HAS_PRI("edit_".$set_name."_problem"))
        echo "<option value='$set_name'". ($set_name==$_GET['OJ']?" selected ":" ").">$set_name</option>";
      }
    ?>
    </select>
    <hr/>
<?php
  //echo "<pre>$sql</pre>";
  $result=mysql_query($sql) or die(mysql_error());
  ?>

  <?php
  echo "<center><table class='table table-striped table-hover' width=90%>";
  echo "<form method=post action=contest_add.php>";
  echo "<tr><td colspan=9><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button>";
  echo "<tr><td>PID<td>Title<td>Author<td>Problemset<td>Date";
  echo "<td>Status<td>Delete";
  echo "<td>Edit<td>TestData</tr>";
  while($row=mysql_fetch_object($result)){
          echo "<tr>";
          echo "<td>".$row->problem_id;
          echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
          echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a>";
          echo "<td>".$row->author."</td>";
          echo "<td>".$row->problemset."</td>";
          echo "<td>".$row->in_date;
          echo "<td><a href=problem_df_change.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">"
          .($row->defunct=="N"?"<span titlc='click to reserve it' style='color: green;'>Available</span>":"<span style='color: red;' title='click to be available'>Reserved</span>")."</a><td>";
          if($OJ_SAE||function_exists("system")){
                ?>
                <a href='#' onclick='javascript:if(confirm("Delete?")) location.href="problem_del.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>";'>
                Delete</a>
                <?php
          }
          echo "<td><a href=problem_edit.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">Edit</a>";
          echo "<td><a href=quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes>TestData</a>";
          echo "</tr>";
  }
  echo "<tr><td colspan=9><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button>";
  echo "</tr></form>";
  echo "</table></center>";
  require("../oj-footer.php");
?>
<?php
  require_once("admin-footer.php")
?>