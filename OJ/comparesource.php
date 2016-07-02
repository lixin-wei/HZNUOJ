<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

<?php
 $cache_time=90;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once("./include/my_func.inc.php");
  $view_title= "Source Code";
   
  require_once("./include/const.inc.php");
  if (!isset($_GET['left'])){
    $view_errors= "No such code!\n";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

  $ok=false;
  $id=strval(intval($_GET['left']));
  $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  $slanguage=$row->language;
  $sresult=$row->result;
  $stime=$row->time;
  $smemory=$row->memory;
  $sproblem_id=$row->problem_id;
  $view_user_id=$suser_id=$row->user_id;
  mysql_free_result($result);

  $ok = canSeeSource($id);

  $view_source="No source code available!";
  $sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  if($row) $view_source=$row->source;

  /////////////////////////Template
  require("template/".$OJ_TEMPLATE."/comparesource.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

