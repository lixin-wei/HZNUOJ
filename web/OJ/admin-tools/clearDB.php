<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.03.26
  **/
?>

<?php

  /*
   * 该文件用于清空OJ一些表中的冗余信息
   */

  require_once('../include/db_info.inc.php');

  $sql = "DELETE FROM contest_problem WHERE contest_id='0'";
  $mysqli->query($sql);


?>