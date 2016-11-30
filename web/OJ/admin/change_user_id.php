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
  require_once("admin-header.php");
  require_once("../include/my_func.inc.php");
  if (!HAS_PRI("edit_user_profile")) {
    $view_error="You can't edit this user!";
    require_once("error.php");
    exit(1);
  }
  if (isset($_POST['origin']) && isset($_POST['dest'])) {

    $origin = $_POST['origin'];
    $dest = $_POST['dest'];
    $exist = 0;
    if(get_order(get_group($origin))<=get_order(get_group())){
      $view_error="You can't edit this user!";
      require_once("error.php");
      exit(1);
    }
    // OJ中判断用户是否存在
    $sql = "SELECT user_id FROM users WHERE user_id='$dest'";
    $result = $mysqli->query($sql);
    $exist += $result->num_rows;

    // 连接转入vjudge
    $mysqli->close();
    $mysqli = new mysqli($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,"vhoj");
    if ($mysqli->connect_errno) die('Could not connect: ' . $mysqli->error);
    $mysqli->query("set names utf8");

    // VJ中判断用户是否存在
    $sql = "SELECT C_USERNAME FROM t_user WHERE C_USERNAME='$dest'";
    $result = $mysqli->query($sql);
    $exist += $result->num_rows;

    // 判断用户是否存在
    if ($exist != 0) {
      echo "该用户已存在！";
    } else {
      $sql = "UPDATE t_user SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE t_submission SET C_USERNAME='$dest' WHERE C_USERNAME='$origin'";
      $mysqli->query($sql);

      // 连接转回OJ

      $mysqli->close();
      $mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASS,"jol");
      if ($mysqli->connect_errno) die('Could not connect: ' . $mysqli->error);
      $mysqli->query("set names utf8");
      $sql = "UPDATE loginlog SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE mail SET to_user='$dest' WHERE to_user='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE mail SET from_user='$dest' WHERE from_user='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE message SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE news SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE privilege SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE reply SET author_id='$dest' WHERE author_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE solution SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE topic SET author_id='$dest' WHERE author_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE users SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);
      $sql = "UPDATE tag SET user_id='$dest' WHERE user_id='$origin'";
      $mysqli->query($sql);

      echo "done.";
    }

  }
?>
<title>Change User ID</title>
<h1>Change User ID</h1><hr>
<form class="form-inline" method='post' action='change_user_id.php'>
  Change user ID : <input class="form-control" type='text' name='origin'>
  to : <input class="form-control" type='text' name='dest'>
  <input class="btn btn-default" type='submit' value='Submit'>
</form>
<?php 
  require_once("admin-footer.php")
?>