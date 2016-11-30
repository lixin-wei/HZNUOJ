<?php
  /**
   * This file is created
   * by yybird
   * @2016.05.07
   * last modified
   * by yybird
   * @2016.05.26
  **/
?>

<?php
  require_once("include/db_info.inc.php");

  if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
    $uid = $_SESSION['user_id'];
    $sql = "SELECT tag FROM users WHERE user_id='$uid'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $tag = $row['tag'];
    if ($tag == "Y") {
      $_SESSION['tag'] = "N";
      $tag = "N";
    } else {
      $_SESSION['tag'] = "Y";
      $tag = "Y";
    }
    $result->free();
    $sql = "UPDATE users SET tag='$tag' WHERE user_id='$uid'";
    $mysqli->query($sql);
  } else if (isset($_SESSION['tag'])) {
    if ($_SESSION['tag'] == "Y") $_SESSION['tag'] = "N";
    else $_SESSION['tag'] = "Y";
  }
?>
<script language='javascript'>history.go(-1);</script>
