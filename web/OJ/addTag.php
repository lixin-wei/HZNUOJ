<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.07
   * last modified
   * by yybird
   * @2016.05.08
  **/
?>

<?php
  require_once("include/db_info.inc.php");

  // tag太长，则弹出警告并退出
  if (strlen($_POST['myTag']) > 30) {
    echo "<script>alert('Your tag is too long!');history.go(-1);</script>";
    exit(0);
  }

  // 在user_id和problem_id都具备的情况下才能添加tag
  if (isset($_SESSION['user_id']) && isset($_POST['id'])) {
    
    $uid = $_SESSION['user_id'];
    $pid = $_POST['id'];
    $tag = $_POST['myTag'];

    // 如果用户还没AC本题，则弹出警告并退出
    $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$pid' AND result='4'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0) {
      echo "<script>alert('You should solve this problem first!');history.go(-1);</script>";
      mysql_free_result($result);
      exit(0);
    }


    $sql = "SELECT tag FROM tag WHERE user_id='$uid' AND problem_id='$pid'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) {
      $sql = "UPDATE tag SET tag='$tag' WHERE user_id='$uid' AND problem_id='$pid'";
      mysql_query($sql);
    } else {
      $sql = "INSERT INTO tag(problem_id, tag, user_id) VALUES ('$pid', '$tag', '$uid')";
      mysql_query($sql);
    }
    mysql_free_result($result);

    $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$pid') AS t GROUP BY tag ORDER BY sum DESC LIMIT 3";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $tag1 = $row['tag'];
    $row = mysql_fetch_array($result);
    $tag2 = $row['tag'];
    $row = mysql_fetch_array($result);
    $tag3 = $row['tag'];
    mysql_free_result($result);
    $sql = "UPDATE problem SET tag1='$tag1', tag2='$tag2', tag3='$tag3' WHERE problem_id='$pid'";
    mysql_query($sql);
    $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$pid') AS t GROUP BY tag ORDER BY sum DESC LIMIT 3";
    $result = mysql_query($sql);
    while($row=mysql_fetch_assoc($result)){
      print_r($row);
    }
  }
?>
