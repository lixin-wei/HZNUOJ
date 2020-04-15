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
  $sql = "";
  $total = 0;
  /* 获取sql语句中的筛选部分 start */
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
    $args['keyword']=$keyword;
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
  $st=($page-1)*$page_cnt;
  $view_total_page=$total/$page_cnt+($total%$page_cnt?1:0);// 页数
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
  $sql.= " WHERE ".$sql_filter. " ORDER BY `problem_id` DESC LIMIT $st,$page_cnt";
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
<!-- 罗列题目 start -->
<form id="form1" class="form-inline center" action="contest_add.php" method='post' onkeydown='if(event.keyCode==13){return false;}'>
    <table class='table table-hover table-bordered table-condensed table-striped' style='white-space: nowrap;'>
    <thead><tr>
    	<td colspan=13>
        <input type=submit name='problem2contest' class='btn btn-default' value='CheckToNewContest' >
        <input type=submit name='enable'  class='btn btn-default' value='<?php echo $MSG_Available ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>
        <input type=submit name='disable'  class='btn btn-default' value='<?php echo $MSG_Reserved ?>' onclick='$("form").attr("action","problem_df_change.php?getkey=<?php echo $_SESSION['getkey'] ?>")'>
        <input type=submit name='newPrblem'  class='btn btn-default' value='<?php echo $MSG_ADD.$MSG_PROBLEM ?>' onclick='$("form").attr("action","problem_edit.php?new_problem")'>&nbsp;|
        
        <input class="form-control" name="cate" id="cate" type="text" maxlength='50' placeholder="<?php echo $MSG_Source ?> 多个标签请以逗号分隔" style="width:340px;" title='先勾选需要<?php echo $MSG_ADD.$MSG_Source ?>标签的题目，填入相关词条（多个词条用逗号分隔）或从右侧的标签列表中选择，再点击‘<?php echo $MSG_ADD ?>’按钮。' >
        <input type='submit' name='addCategory' title='先勾选需要<?php echo $MSG_ADD.$MSG_Source ?>标签的题目，填入相关词条（多个词条用逗号分隔）或从右侧的标签列表中选择，再点击‘<?php echo $MSG_ADD ?>’按钮。' class='btn btn-default' value='<?php echo $MSG_ADD ?>' onclick='$("form").attr("action","<?php echo generate_url("")?>")'>
        <?php require_once("../include/set_post_key.php"); ?>
        <select multiple class="selectpicker show-tick" data-live-search="true" data-width="auto" data-size="10" data-live-search-placeholder="搜索" name="category[]" onchange='$("#cate").val($(this).val());' />
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
	
          <td><?php echo $row->author?$row->author:$MSG_IMPORTED ?></td>
          <?php
          $view_source = "<div pid='".$row->problem_id."' fd='source' class='center'>\n";
          if(HAS_PRI("edit_".get_set_name($row->problem_id)."_problem")) {
              $view_source .="<span><span class='am-icon-plus' pid='$row->problem_id' style='cursor: pointer;' onclick='problem_add_source(this,\"$row->problem_id\");'></span></span>&nbsp;\n";
          }
          $view_source .= show_category($row->source,"sm");
          $view_source .= "</div>";
          ?>
          <td style='white-space:normal;'><?php echo $view_source ?></td>
          <td><?php echo $row->set_name_show ?></td>
          <td><?php echo $row->in_date ?></td>
</tr>
 <?php   }?>
 </tbody>
 <tfoot>
<tr><td colspan=13><button type=submit name='problem2contest' class='btn btn-default'>CheckToNewContest</button></td>
</tr>
</tfoot>
</table></form>
<!-- 罗列题目 end -->
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
<?php
  require_once("admin-footer.php")
?>
<script type="text/javascript">
var color_theme=["primary","secondary","success","warning","danger"];
function problem_add_source(sp,pid){
  //console.log("pid:"+pid);
  let p=$(sp).parent();
  p.html("<form onsubmit='return false;'><input type='hidden' name='m' value='problem_add_source'><input type='hidden' name='ppid' value='"+pid+"'><input type='text' name='ns' maxlength='20'><input type='button' value='<?php echo $MSG_ADD ?>'></form>");
  p.find("input[name=ns]").focus();
  p.find("input[name=ns]").change(function(){
    //console.log($("#form1").serialize());
    let ns=p.find("input[name=ns]").val();
    //console.log("new source:"+ns);
    $.post("./ajax.php",$("#form1").serialize(),function(data,textStatus) {
      if(textStatus=="success") {
        if(data!=0) {
          p.parent().append("<a title='"+ns+"' class='am-badge am-badge-"+color_theme[Math.floor(Math.random()*5)]+" am-text-sm am-radius' href='problemset.php?search=" +ns+ "'>" +(ns.length>10 ? ns.substr(0,10)+"…" : ns) + "</a>&nbsp;");
        } else alert("‘"+ns+"’已存在！");
        p.html("<span class='am-icon-plus' pid='"+pid+"' style='cursor: pointer;' onclick='problem_add_source(this,"+pid+");'></span>");
      }
    });
  });
}
</script>
