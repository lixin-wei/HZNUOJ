<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php require_once("admin-header.php");?>
<?php 
  if (!HAS_PRI("edit_privilege")) {
    echo "Permission denied!";
    exit(1);
  }
  if(isset($_POST['do'])){
    require_once("../include/check_post_key.php");
    $user_id=mysql_real_escape_string($_POST['user_id']);
    $rightstr =$_POST['rightstr'];
    $sql="INSERT INTO `privilege` VALUES('$user_id','$rightstr','N')";
    mysql_query($sql);
    if (mysql_affected_rows()==1) echo "$user_id $rightstr added!";
    else echo "No such user!";
  }
?>

<form method=post>
  <?php require("../include/set_post_key.php");?>
  <b>Add privilege for User:</b><br />
  User:<input type=text size=10 name="user_id"><br />
  Privilege:
  <select name="rightstr">
    <?php
      $rightarray=array("teacher_assistant", "teacher", "http_judge");
      while(list($key, $val)=each($rightarray)) {
        if (isset($rightstr) && ($rightstr==$val)) {
          echo '<option value="'.$val.'" selected>'.$val.'</option>';
        } else {
          echo '<option value="'.$val.'">'.$val.'</option>';
        }
      }
    ?>
  </select><br />
  <input type='hidden' name='do' value='do'>
  <input type=submit value='Add'>
</form>

<!-- <form method=post>
  <b>Add contest for User:</b><br />
  User:<input type=text size=10 name="user_id"><br />
  Contest:<input type=text size=10 name="rightstr">c1000 for Contest1000<br />
  <input type='hidden' name='do' value='do'>
  <input type=submit value='Add'>
  <input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
</form> -->
