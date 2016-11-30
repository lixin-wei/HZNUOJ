<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.12
   * last modified
   * by yybird
   * @2016.05.12
  **/
?>

<?php

  /*
   * 该文件会将users表中的class字段替换为中文
   * 用处是更改旧的class命名方式
   */

  require_once('../include/db_info.inc.php');

  $sql = "SELECT user_id, class FROM users";
  $result = $mysqli->query($sql);
  while ($row = $result->fetch_array()) {
    $class = $row['class'];
    $uid = $row['user_id'];
    if (substr($class, 0, 2) == "se") {
      $class = "软工".substr($class, 2);
    } else if (substr($class, 0, 2) == "cs") {
      $class = "计算机".substr($class, 2);
    } else if (substr($class, 0, 3) == "iot") {
      $class = "物联网".substr($class, 3);
    }
    $sql_tmp = "UPDATE users SET class='$class' WHERE user_id='$uid'";
    $mysqli->query($sql_tmp);
  }
  $result->free();

  echo "update successfully!";

?>