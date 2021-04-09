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
    $title=$mysqli->real_escape_string($title);
    $content=$mysqli->real_escape_string(str_replace("<br />\r\n<!---->","",$content));//火狐浏览器中kindeditor会在空白内容的末尾加入<br />\r\n<!---->
    $content = str_replace("<!---->","",$content);//火狐浏览器中kindeditor会在内容的末尾加入<!---->
    $user_id=$mysqli->real_escape_string($user_id);

    $sql="UPDATE `news` set `title`='$title',`time`=now(),`content`='$content',user_id='$user_id', importance='$importance' WHERE `news_id`=$news_id";
    //echo $sql;
    $mysqli->query($sql) or die($mysqli->error);
    echo "<script type='text/javascript'>window.location.href='news_list.php';</script>";
    exit(0);
  } else {
    $news_id=intval($_GET['id']);
    $sql="SELECT * FROM `news` WHERE `news_id`=$news_id";
    $result=$mysqli->query($sql);
    if ($result->num_rows!=1){
      $result->free();
      echo "No such News!";
      exit(0);
    }
    $row=$result->fetch_array();
    $title=htmlentities($row['title'],ENT_QUOTES,"UTF-8");
    $content=$row['content'];
    $importance = $row['importance'];
    $result->free();
  }
?>
<?php include("kindeditor.php"); ?>
<title><?php echo $html_title.$MSG_EDIT.$MSG_NEWS ?></title>
<form class="form-inline" method=POST action='news_edit.php'>
  <h1><?php echo $MSG_EDIT.$MSG_NEWS ?></h1><hr/>
  <input type=hidden name='news_id' value=<?php echo $news_id?>>
  <p align=left><?php echo $MSG_TITLE ?>:<input class="form-control" type=text name=title size=71 value='<?php echo $title?>'></p>
  <p align='left'><?php echo $MSG_Importance ?>:
    <select class="selectpicker" name='importance' style='width:70px'>
      <option value='10' <?php if ($importance==10) echo "selected" ?> >Top</option>
      <option value='3' <?php if ($importance==3) echo "selected" ?> >3</option>
      <option value='2' <?php if ($importance==2) echo "selected" ?> >2</option>
      <option value='1' <?php if ($importance==1) echo "selected" ?> >1</option>
      <option value='0' <?php if ($importance==0) echo "selected" ?> >0</option>
    </select>
  </p>
  <p align=left><?php echo $MSG_Content ?>:<br>
    <textarea class=kindeditor name=content ><?php echo htmlentities($content,ENT_QUOTES,"UTF-8")?></textarea>
  </p>
  <?php require_once("../include/set_post_key.php");?>
  <input class="btn btn-default" type="submit" value="<?php echo $MSG_SUBMIT ?>" name="submit" />
&nbsp;
<input class="btn btn-default" type="reset" value="<?php echo $MSG_RESET ?>" name="reset" />
</form>
<?php 
  require_once("admin-footer.php")
?>
