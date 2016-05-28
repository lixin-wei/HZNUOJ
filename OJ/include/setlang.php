<?php
  /**
   * This file is modified
   * by yybird
   * @2015.06.29
  **/
?>

<?php 
  if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
    if (file_exists("./faqs.$OJ_LANG.php")) {
      $OJ_FAQ_LINK = "./faqs.$OJ_LANG.php";
    }
  } else {
    require_once("./lang/en.php");
  }
?>