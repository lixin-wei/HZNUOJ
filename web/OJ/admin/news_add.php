<?php 
  require_once ("admin-header.php");
  require_once("../include/check_post_key.php");
  if (!HAS_PRI("edit_news")) {
    require_once("error.php");
    exit(1);
  }
?>
<?php require_once ("../include/db_info.inc.php"); ?>

<?php // contest_id
  $title = $_POST ['title'];
  $content = $_POST ['content'];
  $user_id=$_SESSION['user_id'];
  $importance = $_POST ['importance'];
  if (get_magic_quotes_gpc ()) {
    $title = stripslashes ( $title);
    $content = stripslashes ( $content );
  }
  $title=$mysqli->real_escape_string($title);
  $content=$mysqli->real_escape_string($content);
  $user_id=$mysqli->real_escape_string($user_id);
  $sql="insert into news(`user_id`,`title`,`content`,`time`, importance) values('$user_id','$title','$content',now(), '$importance')";
  $mysqli->query ( $sql );
  echo "<script>window.location.href=\"news_list.php\";</script>";
?>

