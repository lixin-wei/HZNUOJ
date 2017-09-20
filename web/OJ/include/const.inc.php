<?php
  /**
   * This file is modified
   * by yybird
   * @2015.07.03
  **/
?>

<?php
  if(file_exists("include/db_info.inc.php")){
    require_once("include/db_info.inc.php");
    if(isset($OJ_LANG)){
      require_once("./lang/$OJ_LANG.php");
    }
  }
  $judge_result=Array(
    $MSG_Pending,              //0
    $MSG_Pending_Rejudging,    //1
    $MSG_Compiling,            //2
    $MSG_Running_Judging,      //3
    $MSG_Accepted,             //4
    $MSG_Presentation_Error,   //5
    $MSG_Wrong_Answer,         //6
    $MSG_Time_Limit_Exceed,    //7
    $MSG_Memory_Limit_Exceed,  //8
    $MSG_Output_Limit_Exceed,  //9
    $MSG_Runtime_Error,        //10
    $MSG_Compile_Error,        //11
    $MSG_Compile_OK,           //12
    $MSG_TEST_RUN              //13
  );
  $jresult=Array($MSG_PD,$MSG_PR,$MSG_CI,$MSG_RJ,$MSG_AC,$MSG_PE,$MSG_WA,$MSG_TLE,$MSG_MLE,$MSG_OLE,$MSG_RE,$MSG_CE,$MSG_CO,$MSG_TR);
  $judge_color=Array("gray","gray","orange","orange","green","red","red","red","red","red","red","navy ","navy");
  $language_name=Array("C","C++","Pascal","Java","Ruby","Bash","Python2","PHP","Perl","C#","Obj-C","FreeBasic","Schema","Clang","Clang++","Lua","Swift","Other Language");
  $language_ext=Array( "c", "cc", "pas", "java", "rb", "sh", "py", "php","pl", "cs","m","bas","scm","c","cc","lua","swift" );
  function PID($id) {
    $id++;
    $res = "";
    while($id) {
      $id --;
      $res .= chr($id%26+65);
      $id = floor($id/26);
    }
    $res = strrev($res);
    return $res;
  }
  function get_id_from_label($label) {
    $len = strlen($label);
    $res = 0;
    for($i = 0 ; $i < $len ; ++$i) {
      $res *= 26;
      $res += ord($label[$i]) - ord('A') + 1;
    }
    return $res-1;
  }
?>
