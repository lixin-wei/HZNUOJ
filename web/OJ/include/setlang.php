<?php
  /**
   * This file is modified
   * by yybird
   * @2015.06.29
  **/
?>
<?php
  if (isset($GLOBALS["OJ_LANG"])) {
    require_once($_SERVER['DOCUMENT_ROOT']."/OJ/lang/{$GLOBALS["OJ_LANG"]}.php");
    if (file_exists($_SERVER['DOCUMENT_ROOT']."/OJ/faqs.{$GLOBALS["OJ_LANG"]}.php")) {
      $OJ_FAQ_LINK = $_SERVER['DOCUMENT_ROOT']."/OJ/faqs.{$GLOBALS["OJ_LANG"]}.php";
    }
  } else {
    require_once($_SERVER['DOCUMENT_ROOT']."/OJ/lang/en.php");
  }
?>