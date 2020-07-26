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
  require_once('../include/my_func.inc.php');
  if (!$can_see_problem) {
    require_once("error.php");
    exit(1);
  }
  if($_GET['page'])$page=$_GET['page'];
  else $page=1;
  if(isset($page)) $args['page']=$page;
  function generate_url($data){
      global $args;
      $link="problem_list.php?";
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
  /* 来源/分类标签处理：给选定题目批量删掉指定标签、批量打标签 start */
  if(isset($_POST['addCategory']) || isset($_POST['delCategory'])){
    require_once("../include/check_post_key.php");
    $pids=array();
    foreach($_POST['pid'] as $i){
      $i = intval($mysqli->real_escape_string($i));
      if(HAS_PRI("edit_".get_problemset($i)."_problem")) array_push($pids,$i);
    }
    if(isset($_POST['addCategory'])){
      $addCate = str_replace(" ","",$_POST['cate']);
      $addCate = str_replace("，",",",$addCate);
      $addCate = array_unique(explode(",",$addCate));
      if ($addCate) {
        $sql = "SELECT `problem_id`,`source` FROM `problem` WHERE `problem_id` IN ('". implode("','", $pids) ."')";
        $result=$mysqli->query($sql) or die($mysqli->error);
        while($row = $result->fetch_array()) {//搜出来的结果，先和$addCate合并，再去重、回写
          $source = explode(" ",$row['source']);
          $source = array_unique(array_merge($source, $addCate));//合并、去重
          sortByPinYin($source);
          $sql = "UPDATE `problem` SET `source`='".implode(" ", $source) ."' WHERE `problem_id`='{$row['problem_id']}'";
          $mysqli->query($sql);
        }
      }
    }
    if(isset($_POST['delCategory'])){
      $delCategory = array_unique($_POST['category']);
      if ($delCategory) {
        $sql = "SELECT `problem_id`,`source` FROM `problem` WHERE `problem_id` IN ('". implode("','", $pids) ."')";
        $result=$mysqli->query($sql) or die($mysqli->error);        
        while($row = $result->fetch_array()) {//搜出来的结果，挨个先去重，再删除指定$delCategory、回写
          $source = array_unique(explode(" ",$row['source']));
          $flag = false;
          foreach($delCategory as $cate){
            if(in_array($cate,$source)){
              $flag = true;
              break;
            }
          }
          if($flag){
            $source = array_diff($source, $delCategory);
            sortByPinYin($source);
            $sql = "UPDATE `problem` SET `source`='".implode(" ", $source) ."' WHERE `problem_id`='{$row['problem_id']}'";
            $mysqli->query($sql);
          }
        }
      } // end of if ($delCategory) 
    }
  }
  /* 来源/分类标签处理：给选定题目批量删掉指定标签、批量打标签 end */
  else if(isset($_POST['changeProblemset'])){ //批量更改题目的题库归属
    require_once("../include/check_post_key.php");
    $pids=array();
    foreach($_POST['pid'] as $i){
      $i = intval($mysqli->real_escape_string($i));
      if(HAS_PRI("edit_".get_problemset($i)."_problem")) array_push($pids,$i);
    }
    $newProbelmset=$mysqli->real_escape_string(trim($_POST['newProblemset']));
    $sql="SELECT COUNT(`set_name`) FROM `problemset` WHERE `set_name`='$newProbelmset'";
    if($mysqli->query($sql)->fetch_array()[0]>0){
      $sql="UPDATE `problem` SET `problemset`='$newProbelmset' WHERE `problem_id` IN ('". implode("','", $pids) ."')";
      $mysqli->query($sql);
    }
  }
  $sql = "";
  $total = 0;
  /* 获取sql语句中的筛选部分 start */
  
  if (isset($_GET['sort_method']) && trim($_GET['sort_method'])!="" ) $args['sort_method'] = $_GET['sort_method'];
  else $args['sort_method'] = "";
  switch ($args['sort_method']){
    case "ac_DESC":
      $sql_orderby = " ORDER BY accepted DESC, submit ";
      $ac = "ac_ASC";
      $ac_icon = "am-icon-sort-amount-desc";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort";
      break;
    case "ac_ASC":
      $sql_orderby = " ORDER BY accepted, submit ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort-amount-asc";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort";
      break;    
    case "submit_DESC":
      $sql_orderby = " ORDER BY submit DESC, accepted  ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort";
      $submit = "submit_ASC";
      $submit_icon = "am-icon-sort-amount-desc";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort";
      break;
    case "submit_ASC":
      $sql_orderby = " ORDER BY submit, accepted ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort-amount-asc";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort";
      break;
    case "pass_DESC":
      $sql_orderby = " ORDER BY accepted/submit DESC, accepted DESC, submit ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort";
      $pass = "pass_ASC";
      $pass_icon = "am-icon-sort-amount-desc";
      break;
    case "pass_ASC":
      $sql_orderby = " ORDER BY accepted/submit, accepted, submit ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort-amount-asc";
      break;
    default:
      $sql_orderby = " ORDER BY `problem_id` DESC ";
      $ac = "ac_DESC";
      $ac_icon = "am-icon-sort";
      $submit = "submit_DESC";
      $submit_icon = "am-icon-sort";
      $pass = "pass_DESC";
      $pass_icon = "am-icon-sort";
  }

  $sql_filter = " 1 ";
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
  
  if(isset($_GET['keyword'])&&trim($_GET['keyword'])!="" && trim($_GET['keyword'])!="all") {
    $keyword=htmlentities(trim($_GET['keyword']));
    $keyword=$mysqli->real_escape_string($keyword);
    $args['keyword']=urlencode($keyword);
      $sql_filter .= " AND (title like '%$keyword%' or source like '%$keyword%' or author like '%$keyword%' OR tag1 like '%$keyword%' OR tag2 like '%$keyword%' OR tag3 like '%$keyword%')";
  }
  $problem_sets = array();
  $result = $mysqli->query("SELECT `set_name`,`set_name_show` FROM `problemset`");    
  while($row = $result->fetch_array()) {
    if(HAS_PRI("edit_".$row['set_name']."_problem")) array_push($problem_sets,$row);
  }
  if(isset($_GET['OJ'])&&trim($_GET['OJ'])!="" && trim($_GET['OJ'])!="all") {
    $OJ = trim($_GET['OJ']);
    $args['OJ'] = $OJ;
    if(HAS_PRI("edit_".$OJ."_problem")) {
      $sql_filter .= " AND `problemset`='".$OJ."'";
    } else $sql_filter .= " AND 0";
  } else {
    if($problem_sets) {
      $sql_filter .= " AND `problemset` IN ('". implode("','", array_column($problem_sets, 'set_name')) ."')";
    } else $sql_filter .= " AND 0";
  }
/* 获取sql语句中的筛选部分 end */

  /* 计算页数cnt start */
  $result = $mysqli->query("SELECT COUNT('problem_id') FROM `problem` WHERE ".$sql_filter);
  if($result) $total = $result->fetch_array()[0];
  $page_cnt=100;
  if(trim($_GET['keyword']) != "") { //查找结果全部显示在一页上
    $page_cnt = $total? $total:1;
  }
  $view_total_page = ceil($total / $page_cnt); //计算页数
  $view_total_page = $view_total_page>0?$view_total_page:1;
  if ($page > $view_total_page) $args['page'] = $page = $view_total_page;
  if ($page < 1) $page = 1;
  $st=($page-1)*$page_cnt;
  /* 计算页数cnt end */

  if($problem_sets) {
    $sql= "SELECT distinct `source` FROM `problem` WHERE `problemset` IN ('". implode("','", array_column($problem_sets, 'set_name')) ."')";
    if ($result = $mysqli->query($sql)){
      $categorys="";
      foreach ($result as $row){
        $cate=explode(" ",trim($row['source']));
        foreach($cate as $cat){
            $categorys .= trim($cat)." ";
        }
      }
      $categorys = array_unique(explode(" ",trim($categorys)));
      sortByPinYin($categorys);
    }
  } 
  $sql = "SELECT p.*,s.`set_name_show` FROM `problem` AS p LEFT JOIN `problemset` AS s ON p.`problemset`=s.`set_name`";
  $sql.= " WHERE ".$sql_filter. $sql_orderby." LIMIT $st,$page_cnt";
  //echo "<pre>$sql</pre>";
  $result=$mysqli->query($sql) or die($mysqli->error);
?>

  <title><?php echo $html_title.$MSG_PROBLEM.$MSG_LIST?></title>
  <h1><?php echo $MSG_PROBLEM.$MSG_LIST?></h1>
  <h4><?php echo $MSG_HELP_PROBLEM_LIST ?></h4>
  <hr/>
  <!-- 题目查找 start -->
  <div style="margin-top: 10px;margin-bottom: 10px;">
  <form class="form-inline center" action="problem_list.php" id="searchform">
    <select class="selectpicker show-tick" name="status" data-width="auto" onchange='javascript:document.getElementById("searchform").submit();'>
      <option value='all' <?php if (isset($_GET['status']) && ($_GET['status'] == "" || $_GET['status'] == "all")) echo "selected"; ?>><?php echo $MSG_ALL.$MSG_STATUS ?></option>
      <option value='Available' <?php if (isset($_GET['status']) && $_GET['status'] == "Available" ) echo "selected"; ?>><?php echo $MSG_Available ?></option>
      <option value='Reserved' <?php if (isset($_GET['status']) && $_GET['status'] == "Reserved" ) echo "selected"; ?>><?php echo $MSG_Reserved ?></option>
    </select>
    <select class='selectpicker show-tick' data-width="auto" name="OJ" onchange='javascript:document.getElementById("searchform").submit();'>
      <option value='all' <?php if (isset($_GET['OJ']) && ($_GET['OJ'] == "" || $_GET['OJ'] == "all")) echo "selected"; ?> ><?php echo $MSG_ALL.$MSG_PROBLEMSET ?></option>
    <?php
      foreach ($problem_sets as $row) {
        echo "<option value='$row[0]'". ($row[0]==$_GET['OJ']?" selected ":" ").">$row[1]</option>";
      }
    ?>
    </select>
    <input class="form-control" name="keyword" type="text" placeholder="<?php echo $MSG_KEYWORDS ?>" <?php if(isset($keyword)) echo "value='$keyword'"; ?> />&nbsp;<input type=submit value="<?php echo $MSG_SEARCH?>" class="btn btn-default" >
    </form>    
    </div>
    <!-- 题目查找 end -->
  <!-- 页标签 start -->
  <div >
    <ul class="pagination text-center" style="margin-top: 0px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>"1"))?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        $page_size=10;
        $page_start=max(ceil($page/$page_size-1)*$page_size+1,1);
        $page_end=min(ceil($page/$page_size-1)*$page_size+$page_size,$view_total_page);
        for ($i=$page_start;$i<$page;$i++){
            $link=generate_url(Array("page"=>"$i"));
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"));
        echo "<li class='active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
            $link=generate_url(Array("page"=>"$i"));
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
            $link=generate_url(Array("page"=>"$i"));
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
<!-- 页标签 end -->
<!-- 罗列题目 start -->
<style type="text/css" media="screen">
    #ac,#submit,#passrate {
        cursor: pointer;
    }
</style>
<form id="form1" class="form-inline center" action="contest_add.php" method='post' onkeydown='if(event.keyCode==13){return false;}'>
    <table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap;'>
    <thead><tr>
    	<td colspan="15">
        <input type=submit name='problem2contest' class='btn btn-default' value='CheckToNewContest' >
        <input type=submit name='enable'  class='btn btn-default' value='<?php echo $MSG_Available ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>
        <input type=submit name='disable'  class='btn btn-default' value='<?php echo $MSG_Reserved ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>
        <input type=submit name='newPrblem'  class='btn btn-default' value='<?php echo $MSG_ADD.$MSG_PROBLEM ?>' onclick='$("form").attr("action","problem_edit.php?new_problem")'>&nbsp;|
        <select class='selectpicker show-tick' data-width="auto" name="newProblemset" data-title="移动到新的<?php echo $MSG_PROBLEMSET ?>">
        <option value=''></option>
        <?php
          foreach ($problem_sets as $row) {
            echo "<option value='$row[0]'". ($row[0]==$_GET['OJ']?" selected ":" ").">$row[1]</option>";
          }
        ?>
        </select>
        <input type='submit' name='changeProblemset' title='先勾选需要更改<?php echo $MSG_PROBLEMSET ?>归属的题目，再选择目的<?php echo $MSG_PROBLEMSET ?>，最后点击‘<?php echo $MSG_SUBMIT ?>’按钮。' class='btn btn-default' value='<?php echo $MSG_SUBMIT ?>' onclick='$("form").attr("action","problem_list.php")'>
      </td>
    </tr>
    <td colspan="15">
    <?php echo $MSG_Source ?>：<input class="form-control" name="cate" id="cate" type="text" maxlength='50' placeholder="多个标签请以逗号分隔" style="width:340px;" title='先勾选需要<?php echo $MSG_ADD.$MSG_Source ?>标签的题目，填入相关词条（多个词条用逗号分隔）或从右侧的标签列表中选择，再点击‘<?php echo $MSG_ADD ?>’按钮。' >
        <input type='submit' name='addCategory' title='先勾选需要<?php echo $MSG_ADD.$MSG_Source ?>标签的题目，填入相关词条（多个词条用逗号分隔）或从右侧的标签列表中选择，再点击‘<?php echo $MSG_ADD ?>’按钮。' class='btn btn-default' value='<?php echo $MSG_ADD ?>' onclick='$("form").attr("action","<?php echo generate_url("")?>")'>
        <?php require_once("../include/set_post_key.php"); ?>
        <select multiple class="selectpicker show-tick" data-live-search="true" data-width="auto" data-size="10" data-live-search-placeholder="搜索" name="category[]" onchange='$("#cate").val($(this).val());' data-title="选择<?php echo $MSG_Source ?>标签" />
          <option value=''></option>
          <?php
          echo "<optgroup label='$MSG_Source$MSG_LIST'>\n";
          foreach($categorys as $cat){
            echo "<option value='$cat'>$cat</option>";
          }
          echo "</optgroup>\n";
          ?>
        </select>
        <input type='submit' name='delCategory' title='先勾选需要<?php echo $MSG_DEL.$MSG_Source ?>标签的题目，选择相关词条，再点击‘<?php echo $MSG_DEL ?>’按钮。' class='btn btn-default' value='<?php echo $MSG_DEL ?>' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) {$("form").attr("action","<?php echo generate_url("")?>")} else return false;' >
      </td>
    </tr>
    <tr>
    	  <th width=60px><?php echo $MSG_PROBLEM_ID ?>&nbsp;<input type=checkbox style='vertical-align:2px;' onchange='$("input[type=checkbox]").prop("checked", this.checked)'></th>
        <th><?php echo $MSG_TITLE ?></th>
        <th id="ac"><?php echo $MSG_Accepted ?>&nbsp;<span class="<?php echo $ac_icon ?>"></span></th>
        <th id="submit"><?php echo $MSG_SUBMIT ?>&nbsp;<span class="<?php echo $submit_icon ?>"></span></th>
        <th id='passrate'><?php echo $MSG_RATIO ?>&nbsp;<span class="<?php echo $pass_icon ?>"></span></th>
        <th><?php echo $MSG_SCORE ?></th>   
        <th><?php echo $MSG_STATUS ?></th>
        <th colspan=4 style="text-align: center"><?php echo $MSG_Operations ?></th>
        <th><?php echo $MSG_AUTHOR ?></th>        
        <th><?php echo $MSG_Source ?></th>
        <th><?php echo $MSG_PROBLEMSET ?></th>
        <th><?php echo $MSG_SUBMIT_TIME ?></th>
    </tr></thead><tbody>
<?php while($row=$result->fetch_object()){ ?>
      <tr>
          <td style="vertical-align:middle;"><?php echo $row->problem_id ?>&nbsp;<input type=checkbox name='pid[]' value='<?php echo $row->problem_id ?>' /></td>
          <td style="vertical-align:middle;white-space:normal;"><a href='../problem.php?id=<?php echo $row->problem_id ?>'><?php echo $row->title ?></a></td>
          <td style="vertical-align:middle;"><?php echo $row->accepted ?></td>
          <td style="vertical-align:middle;"><?php echo $row->submit ?></td>
          <td style="vertical-align:middle;"><?php if($row->submit) echo round(100*$row->accepted/$row->submit,1)."%"; ?></td>
          <td style="vertical-align:middle;"><?php echo round($row->score) ?></td>
<?php if (HAS_PRI("edit_".get_problemset($row->problem_id)."_problem")) {?>
          <td style="vertical-align:middle;"><?php if($row->defunct=="N"){
				echo "<a class='btn btn-primary' href='problem_df_change.php?id=".$row->problem_id."&getkey=".$_SESSION['getkey']."'>".$MSG_Available."</a>";
				} else {
				echo "<a class='btn btn-danger' href='problem_df_change.php?id=".$row->problem_id."&getkey=".$_SESSION['getkey']."'>".$MSG_Reserved."</a>";
			} ?>
          </td>
          <td style="vertical-align:middle;"><a class='btn btn-primary' href='#' onclick='javascript:if(confirm("<?php echo $MSG_DEL ?>?")) location.href="problem_del.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey'] ?>"'><?php echo $MSG_DEL ?></a></td>
          <td style="vertical-align:middle;"><a class='btn btn-primary' href='problem_edit.php?id=<?php echo $row->problem_id ?>&getkey=<?php echo $_SESSION['getkey'] ?>' target="_blank"><?php echo $MSG_EDIT ?></a></td>
          <td style="vertical-align:middle;"><a class='btn btn-primary' href='problem_edit.php?copy_problem&id=<?php echo $row->problem_id ?>&getkey=<?php echo $_SESSION['getkey'] ?>' title="可将题目复制后人工改成代码附加题"><?php echo $MSG_Copy ?></a></td>
          <td style="vertical-align:middle;"><a class='btn btn-primary' href='quixplorer/index.php?action=list&dir=<?php echo $row->problem_id ?>&order=name&srt=yes' target="_blank"><?php echo $MSG_TestData ?></a></td>
<?php  } else { ?>
          <td style="vertical-align:middle;"><span class='<?php echo ($row->defunct=="N"?"btn btn-primary":"btn btn-danger")?>' disabled><?php echo ($row->defunct=="N"?$MSG_Available:$MSG_Reserved)?></span></td>
          <td style="vertical-align:middle;"><span class='btn btn-primary' disabled><?php echo $MSG_DEL ?></span></td>
          <td style="vertical-align:middle;"><span class='btn btn-primary' disabled><?php echo $MSG_EDIT ?></span></td>
          <td style="vertical-align:middle;"><span class='btn btn-primary' disabled><?php echo $MSG_Copy ?></span></td>
          <td style="vertical-align:middle;"><span class='btn btn-primary' disabled><?php echo $MSG_TestData ?></span></td>
<?php  } ?>
	
          <td style="vertical-align:middle;"><?php echo $row->author?$row->author:$MSG_IMPORTED ?></td>
          <?php
          $view_source = "<div pid='".$row->problem_id."' fd='source' class='center'>\n";
          if(HAS_PRI("edit_".get_problemset($row->problem_id)."_problem")) {
              $view_source .="<span><span class='am-icon-plus' pid='$row->problem_id' style='cursor: pointer;' onclick='problem_add_source(this,\"$row->problem_id\");'></span></span>&nbsp;\n";
          }
          $view_source .= show_category($row->source,"sm");
          $view_source .= "</div>";
          ?>
          <td style="vertical-align:middle;white-space:normal;"><?php echo $view_source ?></td>
          <td style="vertical-align:middle;"><?php echo $row->set_name_show ?></td>
          <td style="vertical-align:middle;"><?php echo $row->in_date ?></td>
</tr>
 <?php   }?>
 </tbody>
 <tfoot>
<tr><td colspan="15"><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button></td>
</tr>
</tfoot>
</table></form>
<!-- 罗列题目 end -->
<!-- 页标签 start -->
<div >
    <ul class="pagination text-center" style="margin-top: 0px;margin-bottom: 0px;">
        <?php $link = generate_url(Array("page"=>"1"))?>
        <li><a href="<?php echo $link ?>">Top</a></li>
        <?php $link = generate_url(Array("page"=>max($page-1, 1)))?>
      <li><a href="<?php echo $link ?>">&laquo; Prev</a></li>
        <?php
        //分页
        for ($i=$page_start;$i<$page;$i++){
          $link=generate_url(Array("page"=>"$i"));
          echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        $link=generate_url(Array("page"=>"$page"));
        echo "<li class='active'><a href=\"$link\">{$page}</a></li>";
        for ($i=$page+1;$i<=$page_end;$i++){
            $link=generate_url(Array("page"=>"$i"));
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        if ($i <= $view_total_page){
            $link=generate_url(Array("page"=>"$i"));
            echo "<li><a href=\"$link\">{$i}</a></li>";
        }
        ?>
        <?php $link = generate_url(Array("page"=>min($page+1,intval($view_total_page)))) ?>
      <li><a href="<?php echo $link ?>">Next &raquo;</a></li>
    </ul>
  </div>
<!-- 页标签 end -->
<?php
  require_once("admin-footer.php");
  include("../template/$OJ_TEMPLATE/js.php");
?>
<!-- sort by ac、submit、passrate BEGIN -->
<script>
    <?php $args['sort_method'] = $ac; ?>
    $("#ac").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $submit; ?>
    $("#submit").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
    <?php $args['sort_method'] = $pass; ?>
    $("#passrate").click(function() {
        var link = "<?php echo generate_url(array("page" => "1")) ?>";
        window.location.href = link;
    });
</script>
<!-- sort by ac、submit、passrate END -->
