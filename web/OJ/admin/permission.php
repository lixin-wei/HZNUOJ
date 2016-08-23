<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.25
   * last modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  require_once("../include/db_info.inc.php");
  $id = $_GET['id'];
  $p_ok = false; // 该权限是否能操作对应的题目
  if (HAS_PRI("edit_hznu_problem")) $p_ok = true;
  else if (HAS_PRI("edit_c_problem") && $id>=$BORDER) $p_ok = true; 
?>