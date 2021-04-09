<?php
  /**
   * This file is modified
   * by yybird
   * @2015.06.29
  **/
?>
<?php
  if(!preg_match("/\/admin\//i", $_SERVER['SCRIPT_NAME'])) {
    $baseDir=".";
  } else if(preg_match("/\/admin\/quixplorer\//i", $_SERVER['SCRIPT_NAME'])) {
    $baseDir="../..";//在admin/quixplorer目录下
  } else {
    $baseDir="..";
  }
  if (isset($GLOBALS["OJ_LANG"])) {
    require_once("$baseDir/lang/{$GLOBALS["OJ_LANG"]}.php");
  } else {
    require_once("$baseDir/lang/en.php");
  }
?>