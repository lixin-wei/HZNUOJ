<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  require("admin-header.php");
  require_once('../include/setlang.php');
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
  /* 获取sql语句中的筛选部分 start */
if(isset($_GET['keyword'])&&trim($_GET['keyword'])!="") {
    $keyword=$mysqli->real_escape_string($_GET['keyword']);
    $filter_sql="(title like '%$keyword%' or source like '%$keyword%' or author like '%$keyword%' OR tag1 like '%$keyword%' OR tag2 like '%$keyword%' OR tag3 like '%$keyword%')";
} else {
    $filter_sql="1";
}

/* 获取sql语句中的筛选部分 end */
  foreach ($problem_sets as $key => $val){
    $set_name=$val["set_name"];
    if($_GET['OJ']=='' || $_GET['OJ']==$set_name){
      if(HAS_PRI("see_hidden_".$set_name."_problem")){
        $t_sql=" FROM `problem` WHERE $filter_sql AND problemset='$set_name'";
      
        //count the number of problem START
        $res = $mysqli->query("SELECT COUNT('problem_id')".$t_sql);
        $cnt += $res->fetch_array()[0];
        //count the number of problem END

        $t_sql = "SELECT `problem_id`, problemset ,`title`, `author`, source, `in_date`,`defunct`,`accepted`,`submit`,score, tag1, tag2, tag3 ".$t_sql;
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

  if(isset($_GET['OJ'])&& $_GET['OJ']!="" ) $href= "problem_list.php?OJ=".$_GET['OJ'];
  if(isset($keyword)&& $keyword!=""){	  
  	if(isset($href)&& $href!=""){
	  $href .="&keyword=".$keyword;
	} else {
      $href= "problem_list.php?keyword=".$keyword;
	}
  }
  if(isset($href)&& $href!=""){
	$href .="&page=";
  } else {
    $href= "problem_list.php?page=";
  }
?>

  <title><?php echo $html_title.$MSG_PROBLEM.$MSG_LIST?></title>
  <h1><?php echo $MSG_PROBLEM.$MSG_LIST?></h1>
  <h4><?php echo $MSG_HELP_PROBLEM_LIST ?></h4>
  <hr/>
  <div style="margin-top: 10px;margin-bottom: 10px;">
  <form action="problem_list.php">
    <select class='selectpicker' onchange="location.href='<?php echo $href?>'+this.value;">
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
    <input  name="keyword" type="text" placeholder="<?php echo $MSG_KEYWORDS ?>" <?php if(isset($keyword)) echo "value='$keyword'"; ?> />&nbsp;<input type=submit value="<?php echo $MSG_SEARCH?>" class="btn btn-default" >
    </form>
    </div>
<?php
  //echo "<pre>$sql</pre>";
  $result=$mysqli->query($sql) or die($mysqli->error);
?>
  
<form action="contest_add.php" method='post' >
    <table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap;'>
    <thead><tr>
    	<td colspan=13>
        <button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button>&nbsp;
        <input type=submit name='enable'  class='btn btn-default' value='<?php echo $MSG_Available ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>&nbsp;
        <input type=submit name='disable'  class='btn btn-default' value='<?php echo $MSG_Reserved ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>&nbsp;
        <input type=submit name='newPrblem'  class='btn btn-default' value='<?php echo $MSG_ADD.$MSG_PROBLEM ?>' onclick='$("form").attr("action","problem_edit.php?new_problem")'>
        </td>
    </tr>
    <tr>
    	<th width=60px><?php echo $MSG_PROBLEM_ID ?>&nbsp;<input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'></th>
        <th><?php echo $MSG_TITLE ?></th>
        <th><?php echo $MSG_Accepted."&nbsp;/&nbsp;".$MSG_SUBMIT ?></th>  
        <th><?php echo $MSG_SCORE ?></th>   
        <th><?php echo $MSG_TAGS ?></th>
        <th><?php echo $MSG_STATUS ?></th>
        <th colspan=3 style="text-align: center"><?php echo $MSG_Operations ?></th>         
        <th><?php echo $MSG_AUTHOR ?></th>        
        <th><?php echo $MSG_Source ?></th>
        <th><?php echo $MSG_PROBLEMSET ?></th>
        <th><?php echo $MSG_CreatedDate ?></th>
    </tr></thead><tbody>
<?php while($row=$result->fetch_object()){ 
  $tags= "<span style='background-color: #F37B1D;'>".$row->tag1."</span>";
  $tags .= "<span style='background-color: #dd514c;'>".$row->tag2."</span>";
  $tags .= "<span style='background-color: #0e90d2;'>".$row->tag3."</span>";
?>
      <tr>
          <td><?php echo $row->problem_id ?>&nbsp;<input type=checkbox name='pid[]' value='<?php echo $row->problem_id ?>' /></td>
          <td><a href='../problem.php?id=<?php echo $row->problem_id ?>'><?php echo $row->title ?></a></td>
          <td><?php echo $row->accepted."&nbsp;/&nbsp;".$row->submit ?></td>
           <td><?php echo $row->score ?></td>
          <td><?php echo $tags ?></td>
          <td><?php if($row->defunct=="N"){
				echo "<a href='problem_df_change.php?id=".$row->problem_id."&getkey=".$_SESSION['getkey']."'>".$MSG_Available."</a>";
				} else {
				echo "<a href='problem_df_change.php?id=".$row->problem_id."&getkey=".$_SESSION['getkey']."'>".$MSG_Reserved."</a>";
			} ?>
          </td>
<td><a href='#' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) location.href="problem_del.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey'] ?>"'><?php echo $MSG_DEL ?></a></td>
<td><a href='problem_edit.php?id=<?php echo $row->problem_id ?>&getkey=<?php echo $_SESSION['getkey'] ?>' target="_blank"><?php echo $MSG_EDIT ?></a></td>
<td><a href='quixplorer/index.php?action=list&dir=<?php echo $row->problem_id ?>&order=name&srt=yes' target="_blank"><?php echo $MSG_TestData ?></a></td>
	
          <td><?php echo $row->author ?></td>         
          <td><?php echo $row->source ?></td>
          <td><?php echo $set_name_show[$row->problemset] ?></td>
          <td><?php echo $row->in_date ?></td>
</tr>
 <?php   }?>
 </tbody>
 <tfoot>
<tr><td colspan=13><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button></td>
</tr>
</tfoot>
</table></form>
<?php
  require_once("admin-footer.php")
?>
