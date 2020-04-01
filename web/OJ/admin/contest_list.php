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
  function formatTimeLength($length)
  {
    $hour = 0;
    $minute = 0;
    $second = 0;
    $result = '';
    global $OJ_LANG;
    //加个语言判断，cn则显示中文时间，其他的都显示英文
    if($OJ_LANG == "cn"){
      if($length >= 60){
      $second = $length%60;
      if($second > 0){ $result = $second.'秒';}
      $length = floor($length/60);
      if($length >= 60){
        $minute = $length%60;
        if($minute == 0){ if($result != ''){ $result = '0分' . $result;}}
        else{ $result = $minute.'分'.$result;}
        $length = floor($length/60);
        if($length >= 24){
        $hour = $length%24;
        if($hour == 0){ if($result != ''){ $result = '0小时' . $result;}}
        else{ $result = $hour . '小时' . $result;}
        $length = floor($length / 24);
        $result = $length . '天' . $result;
        } else{ $result = $length . '小时' . $result;}
      } else{ $result = $length . '分' . $result;}
      } else{ $result = $length . '秒';}
    } else {
      if($length >= 60){
      $second = $length%60;
      if($second > 0){ $result = $second.' Second'.($second>1?"s":"");}
      $length = floor($length/60);
      if($length >= 60){
        $minute = $length%60;
        if($minute == 0){ if($result != ''){ $result = '0 Minute' . $result;}}
        else{ $result = $minute.' Minute'.($length>1?"s":"")." ".$result;}
        $length = floor($length/60);
        if($length >= 24){
        $hour = $length%24;
        if($hour == 0){ if($result != ''){ $result = '0 Hour' . $result;}}
        else{ $result = $hour . ' Hour'.($length>1?"s":"")." " . $result;}
        $length = floor($length / 24);
        $result = $length . ' Day'.($length>1?"s":"")." " . $result;
        } else{ $result = $length . ' Hour'.($length>1?"s":"")." " . $result;}
      } else{ $result = $length . ' Minute'.($length>1?"s":"")." " . $result;}
      } else{ $result = $length . ' Second'.($length>1?"s":"");}
    }
    return $result;
  }

  $page = 1;
  if(isset($_GET['page'])) $page = intval($_GET['page']);
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
  $sql_filter = " 1 ";
  if(isset($_GET['keyword']) && trim($_GET['keyword']) != ""){
    $keyword=htmlentities($_GET['keyword']);
    $keyword=$mysqli->real_escape_string($keyword);
    $args['keyword']=$keyword;
    $sql_filter .= " AND `title` LIKE '%$keyword%' ";
  } 
  if(isset($_GET['type']) && trim($_GET['type']) != "" && trim($_GET['type']) != "all") {
    $type = trim($_GET['type']);
    $args['type'] = $type;
    switch ($type) {
      case "Special":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='Y') ";
      break;
      case "Private":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='N' AND `private`) ";
      break;
      case "Public":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='N' AND NOT `private`) ";
      break;
      case "Practice":
        $sql_filter .= "AND `practice` ";
      break;
    }
  }
  if(isset($_GET['status']) && trim($_GET['status']) != "" && trim($_GET['status']) != "all") {
    $status = trim($_GET['status']);
    $args['status'] = $status;
    switch ($status) {
      case "Available":
        $sql_filter .= " AND `defunct`='N' ";
      break;
      case "Reserved":
        $sql_filter .= " AND `defunct`='Y' ";
      break;
    }
  }
  if(isset($_GET['runstatus']) && trim($_GET['runstatus']) != "" && trim($_GET['runstatus']) != "all") {
    $runstatus = trim($_GET['runstatus']);
    $args['runstatus'] = $runstatus;
    switch ($runstatus) {
      case "noStart":
        $sql_filter .= " AND `start_time`>NOW() ";
      break;
      case "Running":
        $sql_filter .= " AND (`start_time`<NOW() AND `end_time`>NOW()) ";
      break;
      case "Ended":
        $sql_filter .= " AND `end_time`<NOW() ";
      break;
    }
  }
  $sql_page = "SELECT count(1) FROM `contest` WHERE".$sql_filter;  
  $rows =$mysqli->query($sql_page)->fetch_all(MYSQLI_BOTH) or die($mysqli->error);
  if($rows) $total = $rows[0][0];
  $page_cnt = 50;
  if(trim($_GET['keyword']) != "") { //查找结果全部显示在一页上
    $page_cnt = $total? $total:1;
  }
  $st=($page-1)*$page_cnt;
  $view_total_page = intval($total/$page_cnt)+($total%$page_cnt?1:0);//计算页数

  $sql = "SELECT `contest_id`,`title`,`start_time`,`end_time`,`private`,`user_limit`,`practice`,`defunct` FROM `contest` WHERE";
  $sql.= $sql_filter." order by `contest_id` desc LIMIT $st,$page_cnt";
  $result=$mysqli->query($sql) or die($mysqli->error);
?>
<title><?php echo $html_title.$MSG_CONTEST.$MSG_LIST?></title>
  <h1><?php echo $MSG_CONTEST.$MSG_LIST?></h1>
  <h4><?php echo $MSG_HELP_CONTEST_LIST ?></h4>
  <hr/>  

 <div style="margin-top: 10px;margin-bottom: 10px;">
 <form class="form-inline center" id="searchform" action="contest_list.php">
  <select class="selectpicker show-tick" name="type" data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
      <option value='all' <?php if (isset($_GET['type']) && ($_GET['type'] == "" || $_GET['type'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_Type ?></option>
      <option value='Public' <?php if (isset($_GET['type']) && $_GET['type'] == "Public" ) echo "selected"; ?>><?php echo $MSG_Public ?></option>
      <option value='Private' <?php if (isset($_GET['type']) && $_GET['type'] == "Private" ) echo "selected"; ?>><?php echo $MSG_Private ?></option>
      <option value='Practice' <?php if (isset($_GET['type']) && $_GET['type'] == "Practice" ) echo "selected"; ?>><?php echo $MSG_Practice ?></option>
      <option value='Special' <?php if (isset($_GET['type']) && $_GET['type'] == "Special" ) echo "selected"; ?>><?php echo $MSG_Special ?></option>
  </select>&nbsp;
  <select class="selectpicker show-tick" name="status" data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
      <option value='all' <?php if (isset($_GET['status']) && ($_GET['status'] == "" || $_GET['status'] == "all")) echo "selected"; ?>><?php echo $MSG_ALL.$MSG_STATUS ?></option>
      <option value='Available' <?php if (isset($_GET['status']) && $_GET['status'] == "Available" ) echo "selected"; ?>><?php echo $MSG_Available ?></option>
      <option value='Reserved' <?php if (isset($_GET['status']) && $_GET['status'] == "Reserved" ) echo "selected"; ?>><?php echo $MSG_Reserved ?></option>
  </select>&nbsp;
  <select class="selectpicker show-tick" name="runstatus" data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
      <option value='all' <?php if (isset($_GET['runstatus']) && ($_GET['runstatus'] == "" || $_GET['runstatus'] == "all")) echo "selected"; ?>> <?php echo $MSG_ALL.$MSG_STATUS ?></option>
      <option value='noStart' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "noStart" ) echo "selected"; ?>><?php echo $MSG_notStart2 ?></option>
      <option value='Running' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "Running" ) echo "selected"; ?>><?php echo $MSG_Running ?></option>
      <option value='Ended' <?php if (isset($_GET['runstatus']) && $_GET['runstatus'] == "Ended" ) echo "selected"; ?>><?php echo $MSG_Ended ?></option>
  </select>&nbsp;
  <input class="form-control"  name="keyword" type="text"  placeholder="<?php echo $MSG_KEYWORDS ?>" <?php if(isset($keyword)) echo "value='$keyword'"; ?>/>&nbsp;
  <input class="btn btn-default" type=submit value="<?php echo $MSG_SEARCH?>" >
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
    <th><?php echo $MSG_TotalTime ?></th>
    <th><?php echo $MSG_Type ?></th>
    <th><?php echo $MSG_STATUS ?></th>
    <th colspan=4 style="text-align: center"><?php echo $MSG_Operations ?></th>
</tr></thead>
<?php 
while ($row=$result->fetch_object()){
    echo "<tr>\n";
    echo "<td>".$row->contest_id."&nbsp;<input type=checkbox name='cid[]' value='".$row->contest_id."' /></td>\n";
    echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a></td>\n";
    echo "<td>".date('Y-m-d H:i',strtotime($row->start_time))."</td>\n";
    echo "<td>".date('Y-m-d H:i',strtotime($row->end_time))."</td>\n";
    $start_time=strtotime($row->start_time);
    $end_time=strtotime($row->end_time);
    $now=time();
    $length=$end_time-$start_time;
    $runstatus = " ";
    if ($start_time>$now) {
      $runstatus .= "<b>$MSG_notStart2</b>";
    } else if ($end_time>=$now) {
      $runstatus .= "<b>$MSG_Running</b>";
    } else $runstatus .= $MSG_Ended;
    echo "<td>".formatTimeLength($length).$runstatus."</td>\n";
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
