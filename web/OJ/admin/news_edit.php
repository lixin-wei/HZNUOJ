<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php 
  require_once ("admin-header.php");
  if (!HAS_PRI("edit_news")) {
    echo "Permission denied!";
    exit(1);
  }
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>News Edit</title>

<?php 
  require_once("../include/db_info.inc.php");
  if (isset($_POST['news_id'])) {
    require_once("../include/check_post_key.php");
    $title = $_POST ['title'];
    $content = $_POST ['content'];
    $importance = $_POST ['importance'];
    $user_id=$_SESSION['user_id'];
    $news_id=intval($_POST['news_id']);
    if (get_magic_quotes_gpc ()) {
      $title = stripslashes ( $title);
      $content = stripslashes ( $content );
    }
    $title=mysql_real_escape_string($title);
    $content=mysql_real_escape_string($content);
    $user_id=mysql_real_escape_string($user_id);

    $sql="UPDATE `news` set `title`='$title',`time`=now(),`content`='$content',user_id='$user_id', importance='$importance' WHERE `news_id`=$news_id";
    //echo $sql;
    mysql_query($sql) or die(mysql_error());
    header("location:news_list.php");
    exit();
  } else {
    $news_id=intval($_GET['id']);
    $sql="SELECT * FROM `news` WHERE `news_id`=$news_id";
    $result=mysql_query($sql);
    if (mysql_num_rows($result)!=1){
      mysql_free_result($result);
      echo "No such News!";
      exit(0);
    }
    $row=mysql_fetch_assoc($result);
    $title=htmlentities($row['title'],ENT_QUOTES,"UTF-8");
    $content=$row['content'];
    $importance = $row['importance'];
    mysql_free_result($result);
  }
?>
<?php include("kindeditor.php"); ?>

<form method=POST action='news_edit.php'>
  <p align=center><font size=4 color=#333399>Edit a Contest</font></p>
  <input type=hidden name='news_id' value=<?php echo $news_id?>>
  <p align=left>Title:<input type=text name=title size=71 value='<?php echo $title?>'></p>
  <p align='left'>
    Importance:
    <select name='importance' style='width:70px'>
      <option value='10' <?php if ($importance==10) echo "selected" ?> >Top</option>
      <option value='3' <?php if ($importance==3) echo "selected" ?> >3</option>
      <option value='2' <?php if ($importance==2) echo "selected" ?> >2</option>
      <option value='1' <?php if ($importance==1) echo "selected" ?> >1</option>
      <option value='0' <?php if ($importance==0) echo "selected" ?> >0</option>
    </select>
  </p>
  <p align=left>Content:<br>
    <textarea class=kindeditor name=content ><?php echo htmlentities($content,ENT_QUOTES,"UTF-8")?></textarea>
  </p>
  <?php require_once("../include/set_post_key.php");?>
  <input type=submit></input>
</form>
<?php require_once("../oj-footer.php");?>

