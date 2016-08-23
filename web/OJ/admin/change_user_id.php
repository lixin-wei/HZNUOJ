<?php
  /**
   * This file is created
   * by yybird
   * @2016.02.29
   * last modified
   * by yybird
   * @2016.02.29
  **/
?>

<?php


  /*
   * 该文件会将oj和vj中所有与$origin用户相关的信息替换为$dest
   * 用处是更换user_id
   */


  require_once('../include/db_info.inc.php');
  if (!HAS_PRI("edit_user_profile")) {
    echo "Permission denied!";
    exit(1);
  }
  if (isset($_POST['origin']) && isset($_POST['dest'])) {

    $origin = $_POST['origin'];
    $dest = $_POST['dest'];
    $exist = 0;

    // OJ中判断用户是否存在
    $sql = "SELECT user_id FROM users WHERE user_id='$dest'";
    $result = mysql_query($sql);
    $exist += mysql_num_rows($result);

    // 连接转入vjudge
    $connvj = mysql_connect($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,true);
    if (!$connvj) die('Could not connect: ' . mysql_error());
    mysql_select_db("vhoj", $connvj);
    mysql_query("set names utf8");

    // VJ中判断用户是否存在
    $sql = "SELECT C_USERNAME FROM t_user WHERE C_USERNAME='$dest'";
    $result = mysql_query($sql);
    $exist += mysql_num_rows($result);

    // 判断用户是否存在
    if ($exist != 0) {
      echo "该用户已存在！";
    } else {
      $sql = "UPDATE t_user SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
      mysql_query($sql);
      $sql = "UPDATE t_submission SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
      mysql_query($sql);

      // 连接转回OJ
      $conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS,true);
      if (!$conn) die('Could not connect: ' . mysql_error());
      mysql_select_db("jol", $conn);
      mysql_query("set names utf8");
      $sql = "UPDATE loginlog SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE mail SET to_user='$dest' WHERE to_user='$origin'";
      mysql_query($sql);
      $sql = "UPDATE mail SET from_user='$dest' WHERE from_user='$origin'";
      mysql_query($sql);
      $sql = "UPDATE message SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE news SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE privilege SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE reply SET author_id='$dest' WHERE author_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE solution SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE topic SET author_id='$dest' WHERE author_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE users SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);
      $sql = "UPDATE tag SET user_id='$dest' WHERE user_id='$origin'";
      mysql_query($sql);

      echo "done.";
    }

  }
?>

<form method='post' action='change_user_id.php'>
  Change user ID : <input type='text' name='origin'>
  to : <input type='text' name='dest'>
  <input type='submit' value='Submit'>
</form>