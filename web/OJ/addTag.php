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
    $result = $mysqli->query($sql);
    if ($result->num_rows == 0) {
      echo "<script>alert('You should solve this problem first!');history.go(-1);</script>";
      $result->free();
      exit(0);
    }


    $sql = "SELECT tag FROM tag WHERE user_id='$uid' AND problem_id='$pid'";
    $result = $mysqli->query($sql);
    if ($result->num_rows) {
      $sql = "UPDATE tag SET tag='$tag' WHERE user_id='$uid' AND problem_id='$pid'";
      $mysqli->query($sql);
    } else {
      $sql = "INSERT INTO tag(problem_id, tag, user_id) VALUES ('$pid', '$tag', '$uid')";
      $mysqli->query($sql);
    }
    $result->free();

    $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$pid') AS t GROUP BY tag ORDER BY sum DESC LIMIT 3";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $tag1 = $row['tag'];
    $row = $result->fetch_array();
    $tag2 = $row['tag'];
    $row = $result->fetch_array();
    $tag3 = $row['tag'];
    $result->free();
    $sql = "UPDATE problem SET tag1='$tag1', tag2='$tag2', tag3='$tag3' WHERE problem_id='$pid'";
    $mysqli->query($sql);
    $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$pid') AS t GROUP BY tag ORDER BY sum DESC LIMIT 3";
    $result = $mysqli->query($sql);
    while($row=$result->fetch_array()){
      print_r($row);
    }
  }
?>
