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
  // $keyword=$mysqli->real_escape_string($keyword);
  if($_GET['page'])$page=$_GET['page'];
  else $page=1;
  $page_cnt=100;
  // get problems START
  $res_set = $mysqli->query("SELECT set_name,set_name_show FROM problemset");
  $problem_sets = array();
  while($row = $res_set->fetch_array()) {
    array_push($problem_sets,$row);
  }
  $set_name_show = array();
  foreach ($problem_sets as $key => $val){
    $set_name_show[$val["set_name"]]=$val['set_name_show'];
  }
  $first = true;
  $sql = "";
  $cnt = 0;
  foreach ($problem_sets as $key => $val){
    $set_name=$val["set_name"];
    if($_GET['OJ']=='' || $_GET['OJ']==$set_name){
      if(HAS_PRI("see_hidden_".$set_name."_problem")){
        $t_sql=" FROM `problem` WHERE problemset='$set_name'";
      
        //count the number of problem START
        $res = $mysqli->query("SELECT COUNT('problem_id')".$t_sql);
        $cnt += $res->fetch_array()[0];
        //count the number of problem END

        $t_sql = "SELECT `problem_id`, problemset ,`title`, `author`, source, `in_date`,`defunct` ".$t_sql;
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
    <select class='selectpicker' onchange="location.href='problem_list.php?OJ=<?php echo $_GET['OJ']?>&page='+this.value;">
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
    <select class='selectpicker' onchange="location.href='problem_list.php?OJ='+this.value;">
      
    <?php
      echo "<option value='' selected>All</option>";
      $res=$mysqli->query("SELECT set_name FROM problemset");
      while($set_name=$res->fetch_array()[0]){
        if(HAS_PRI("edit_".$set_name."_problem"))
        echo "<option value='$set_name'". ($set_name==$_GET['OJ']?" selected ":" ").">{$set_name_show[$set_name]}</option>";
      }
    ?>
    </select>
    <hr/>
<?php
  //echo "<pre>$sql</pre>";
  $result=$mysqli->query($sql) or die($mysqli->error);
?>
  <style>
    .table td {
      vertical-align: middle !important;
    }
  </style>
  <?php
  echo "<table class='table table-striped table-hover table-bordered table-condensed' style='white-space: nowrap;'>";
  echo "<form method=post action=contest_add.php>";
  echo "<tr><td colspan=9><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button>";
  echo "<tr><td>PID<td>Title<td>Status<td>Author<td>Source<td>Problemset<td>Date<td>Operations</tr>";
  while($row=$result->fetch_object()){
      echo "<tr>";
      echo "<td>".$row->problem_id;
      echo "<input type=checkbox name='pid[]' value='$row->problem_id'>"."</td>";
      echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a></td>";
      echo "<td>"
          .($row->defunct=="N"?"<span titlc='click to reserve it' style='color: green;'>Available</span>":
              "<span style='color: red;' title='click to be available'>Reserved</span>")
          ."</td>";
    
      echo "<td>".$row->author."</td>";
      echo "<td>{$row->source}</td>";
      echo "<td>".$set_name_show[$row->problemset]."</td>";
      echo "<td>".$row->in_date."</td>";
      echo <<<HTML
<td>
  <div class="dropdown">
    <button class="btn btn-default" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Operations
      <span class="caret"></span>
    </button> 
    <ul class="dropdown-menu" aria-labelledby="dLabel">
      <li><a href=problem_df_change.php?id=$row->problem_id&getkey={$_SESSION['getkey']}>Change status</a></li>
      <li><a href=problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}>Edit</a></li>
      <li>
        <a href='#' onclick='javascript:if(confirm("Delete?")) location.href="problem_del.php?id={$row->problem_id}&getkey={$_SESSION['getkey']}";'>
          Delete
        </a>
      </li>
      <li><a href=quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes>TestData</a></li>
    </ul>
  </div>
</td>
HTML;
      echo "</tr>";
  }
  echo "<tr><td colspan=9><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button>";
  echo "</tr></form>";
  echo "</table>";
  require("../oj-footer.php");
?>
<?php
  require_once("admin-footer.php")
?>