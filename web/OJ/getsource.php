<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
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
  if (!isset($_GET['id'])){
    $view_errors= "No such code!\n";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

  $ok=false;
  $id=strval(intval($_GET['id']));
  $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  $slanguage=$row->language;
  $sresult=$row->result;
  $stime=$row->time;
  $smemory=$row->memory;
  $sproblem_id=$row->problem_id;
  $view_user_id=$suser_id=$row->user_id;
  $cid = $row->contest_id;
  $result->free();

  $ok = canSeeSource($id);

  $view_source="No source code available!";
  $sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  if($row) $view_source=$row->source;

 if ($ok==true) {
    $brush=strtolower($language_name[$slanguage]);
    if ($brush=='pascal') $brush='delphi';
    if ($brush=='obj-c') $brush='c';
    if ($brush=='freebasic') $brush='vb';
    ob_start();
    echo "/**************************************************************\n";
    echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
    echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
    if ($sresult==4){
      echo "\tTime:".$stime." ms\n";
      echo "\tMemory:".$smemory." kb\n";
    }
    echo "****************************************************************/\n\n";
    $auth=ob_get_contents();
    ob_end_clean();

    echo (str_replace("\n\r","\n",$view_source))."\n".$auth;
  } else {
    echo "I am sorry, You could not view this code!";
  }
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

