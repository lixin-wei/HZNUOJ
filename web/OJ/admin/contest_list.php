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
  if (!HAS_PRI("edit_contest")) {
    echo "Permission denied!";
    exit(1);
  }
  require_once("../include/set_get_key.php");
  
   
   //分页start
  $page = 1;
  if(isset($_GET['page'])) $page = intval($_GET['page']);
  $page_cnt = 50;
  $pstart = $page_cnt*$page-$page_cnt;
  $pend = $page_cnt;    
  if(isset($_GET['keyword']) && trim($_GET['keyword']) != "") $args['keyword']=htmlentities($keyword);
  if(isset($page)) $args['page']=$page;
function generate_url($data){
    global $args;
    $link="contest_list.php?";
    foreach ($args as $key => $value) {
        if(isset($data["$key"])){
            $value=htmlentities($data["$key"]);
            $link.="&$key=$value";
        }
        else if($value){
            $link.="&$key=".htmlentities($value);
        }
    }
    return $link;
}  
    // check the order_by arg
  $sql_filter = " FROM `contest` ";
  $sql_limit = " limit ".strval($pstart).",".strval($pend); 
  if(isset($_GET['keyword']) && trim($_GET['keyword']) != ""){
	$keyword=htmlentities($_GET['keyword']);
	$keyword=$mysqli->real_escape_string($keyword);
	$args['keyword']=$keyword;
	$sql_filter .=" WHERE title LIKE '%$keyword%' ";
	$sql_limit = "";
  } 
  $sql_page = "SELECT count(1) ".$sql_filter;  
  $rows =$mysqli->query($sql_page)->fetch_all(MYSQLI_BOTH) or die($mysqli->error);
  if($rows) $total = $rows[0][0];  
  if($sql_limit == "") { //查找结果全部显示在一页上
    $page_cnt = $total;
    $view_total_page = 1;
  } else
  $view_total_page = intval($total/$page_cnt)+($total%$page_cnt?1:0);//计算页数
  //分页end 
  
  $sql = "select `contest_id`,`title`,`start_time`,`end_time`,`private`,`user_limit`,`practice`,`defunct` ".$sql_filter." order by `contest_id` desc ".$sql_limit;
  $result=$mysqli->query($sql) or die($mysqli->error);
?>
<title><?php echo $html_title.$MSG_CONTEST.$MSG_LIST?></title>
  <h1><?php echo $MSG_CONTEST.$MSG_LIST?></h1>
  <h4><?php echo $MSG_HELP_CONTEST_LIST ?></h4>
  <hr/>  

 <div style="margin-top: 10px;margin-bottom: 10px;">
 <form class="form-inline center" action="contest_list.php">
  <input class="form-control"  name="keyword" type="text"  placeholder="<?php echo $MSG_KEYWORDS ?>" <?php if(isset($keyword)) echo "value='$keyword'"; ?>/>&nbsp;<input class="btn btn-default" type=submit value="<?php echo $MSG_SEARCH?>" >
</form>
</div>
<!-- 页标签 start -->
  <div >
    <ul class="pagination text-center" style="margin-top: 0px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=1;$i<=$view_total_page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            if($page==$i)
                echo "<li class='active'><a href=\"$link\">{$i}</a></li>";
            else
                echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
<!-- 页标签 end -->
<div style="margin-top: 0px;margin-bottom: 0px;">
<form method=post>
<table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap;'>
<thead>
<?php if(HAS_PRI("edit_contest")) { ?>
<tr>
    	<td colspan=10>
        <input type=submit name='enable'  class='btn btn-default' value='<?php echo $MSG_Available ?>' onclick='$("form").attr("action","contest_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>&nbsp;
        <input type=submit name='disable'  class='btn btn-default' value='<?php echo $MSG_Reserved ?>' onclick='$("form").attr("action","contest_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>&nbsp;
        <input type=submit name='newPrblem'  class='btn btn-default' value='<?php echo $MSG_ADD.$MSG_CONTEST ?>' onclick='$("form").attr("action","contest_add.php")'>
        </td>
    </tr>
<?php } ?>
<tr>
	<th  width='60px'><?php echo $MSG_ID ?>&nbsp;<input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'></th>
    <th><?php echo $MSG_TITLE ?></th>
    <th><?php echo $MSG_StartTime ?></th>
    <th><?php echo $MSG_EndTime ?></th>
    <th><?php echo $MSG_Type ?></th>
    <th><?php echo $MSG_STATUS ?></th>
    <th colspan=4 style="text-align: center"><?php echo $MSG_Operations ?></th>
</tr></thead>
<?php 
for (;$row=$result->fetch_object();){
    echo "<tr>\n";
    echo "<td>".$row->contest_id."&nbsp;<input type=checkbox name='cid[]' value='".$row->contest_id."' /></td>\n";
    echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a></td>\n";
    echo "<td>".$row->start_time."</td>\n";
    echo "<td>".$row->end_time."</td>\n";
	$type = "&gt;&gt;$MSG_Public";
    if($row->private) $type = $MSG_Private;
    if($row->user_limit=="Y") $type = "<span style='color: #f44336;'>$MSG_Special</span>";
    if($row->practice) $type = "<span style='color: #009688;'>$MSG_Practice</span>";
	$cid=$row->contest_id;
    if(HAS_PRI("edit_contest")) {
	  if($type == $MSG_Private || $type == "&gt;&gt;".$MSG_Public){
	      echo "<td><a href=contest_pr_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".$type."</a></td>\n";
	  } else {
		  echo "<td>".$type."</td>\n";
	  }
      echo "<td><a href=contest_df_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?$MSG_Available:"&gt;&gt;".$MSG_Reserved)."</a></td>\n";
      echo "<td><a href=contest_edit.php?cid=$row->contest_id>$MSG_EDIT</a></td>\n";
      echo "<td><a href=contest_add.php?cid=$row->contest_id>$MSG_Copy</a></td>\n";
      echo "<td><a href=\"problem_export_xml.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">$MSG_EXPORT</a></td>\n";
      echo "<td> <a href=\"../export_contest_code.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">$MSG_Logs</a></td>\n";
    } else {
	  echo "<td>".$type."</td>\n";		
	  echo "<td>".($row->defunct=="N"?"<span style='color: green;'>$MSG_Available</span>":"<span style='color: red;'>$MSG_Reserved</span>")."</td>\n";
      echo "<td colspan=4 align=right><a href=contest_add.php?cid=$row->contest_id>Copy</a><td>\n";
    }
    echo "</tr>\n";
  }
?>
</table>
</form>
</div>
<!-- 页标签 start -->
  <div >
    <ul class="pagination text-center" style="margin-top: 0px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=1;$i<=$view_total_page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            if($page==$i)
                echo "<li class='active'><a href=\"$link\">{$i}</a></li>";
            else
                echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
<!-- 页标签 end -->
<?php require_once("admin-footer.php"); ?>
