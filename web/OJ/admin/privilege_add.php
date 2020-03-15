<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.24
  **/
?>

<?php
  require_once("admin-header.php");
  require_once("../include/my_func.inc.php");
?>
<?php 
  if (!HAS_PRI("edit_privilege_group")) {
    require_once("error.php");
    exit(1);
  }
  if(isset($_POST['do'])){
    require_once("../include/check_post_key.php");
    $user_id=$mysqli->real_escape_string($_POST['user_id']);
    $rightstr =$_POST['rightstr'];
    if($rightstr<=get_order(get_group(""))){
      require_once("error.php");
      exit(1);
    }
	$sql = "SELECT user_id FROM users WHERE defunct='N' and user_id='$user_id' ";
    $result = $mysqli->query($sql);
	if($result->num_rows == 0){
	   echo "用户名".$user_id."不存在或未激活！";
	} else {
		$sql = "SELECT rightstr FROM `privilege` WHERE user_id='$user_id' and rightstr='$rightstr'";
        $result = $mysqli->query($sql);
		if($result->num_rows == 0){
			$sql="INSERT INTO `privilege` (user_id,rightstr) VALUES('$user_id','$rightstr')";
			$mysqli->query($sql);
			if ($mysqli->affected_rows==1) echo "$user_id $rightstr added!";
		} else echo "$user_id $rightstr has added!";
	}
  }
?>
  <title><?php echo $html_title.$MSG_ADD.$MSG_PRIVILEGE ?></title>
  <h1><?php echo $MSG_ADD.$MSG_PRIVILEGE ?></h1>
  <h4><?php echo $MSG_HELP_ADD_PRIVILEGE ?></h4>
  <hr/>
<form class="form-inline" method=post>
  <?php require("../include/set_post_key.php");?>
  <p><?php echo $MSG_USER_ID?> : <input class="form-control" type=text size=10 name="user_id">&nbsp;&nbsp;
  <?php echo $MSG_PRIVILEGE ?> : 
  <select class="selectpicker" name="rightstr">
    <?php
      $res=$mysqli->query("SELECT * FROM privilege_groups");
      while($row=$res->fetch_array()){
        if($row['group_order']>get_order(get_group(""))){
          echo '<option value="'.$row['group_name'].'">'.$row['group_name'].'</option>';
        }
      }
    ?>
  </select><br />
  <input type='hidden' name='do' value='do'>
  <input class="btn btn-default" type=submit value='<?php echo $MSG_ADD ?>'>
</form>

<!-- <form method=post>
  <b>Add contest for User:</b><br />
  User:<input type=text size=10 name="user_id"><br />
  Contest:<input type=text size=10 name="rightstr">c1000 for Contest1000<br />
  <input type='hidden' name='do' value='do'>
  <input type=submit value='Add'>
  <input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
</form> -->
<?php 
  require_once("admin-footer.php")
?>