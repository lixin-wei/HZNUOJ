<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<?php require_once("admin-header.php");?>
<?php
if (!HAS_PRI("edit_user_profile")) {
	$view_error="You can't edit this user!";
	require_once("error.php");
	exit(1);
}
if(isset($_POST['do'])){
	//echo $_POST['user_id'];
	require_once("../include/check_post_key.php");
	//echo $_POST['passwd'];
	require_once("../include/my_func.inc.php");
	
	$user_id=$_POST['user_id'];
    $passwd =$_POST['passwd'];
    if(get_order(get_group($user_id))<=get_order(get_group())){
    	$view_error="You can't edit this user!";
		require_once("error.php");
		exit(1);
    }
    if (get_magic_quotes_gpc ()) {
		$user_id = stripslashes ( $user_id);
		$passwd = stripslashes ( $passwd);
	}
	$user_id=$mysqli->real_escape_string($user_id);
	$passwd=pwGen($passwd);
	$sql="update `users` set `password`='$passwd' where `user_id`='$user_id'  and user_id not in( select user_id from privilege where rightstr='administrator') ";
	$mysqli->query($sql);
	if ($mysqli->affected_rows) echo "Password Changed!";
  else echo "No such user! or He/Her is an administrator!";
}
?>
<title><?php echo $html_title.$MSG_SETPASSWORD?></title>
<h1><?php echo $MSG_SETPASSWORD?></h1>
<h4><?php echo $MSG_HELP_SETPASSWORD ?></h4>
<hr>
<form class="form-inline" action='changepass.php' method=post>
	<p><?php echo $MSG_USER_ID?> : <input class="form-control" type=text size=20 name="user_id" required>&nbsp;&nbsp;&nbsp;&nbsp;
	   <?php echo $MSG_New.$MSG_PASSWORD?> : <input class="form-control" type="password" minlength="6" maxlength="22"  size=20 name="passwd" required></p>
	<?php require_once("../include/set_post_key.php");?>
	<input type='hidden' name='do' value='do'>
	<input class="btn btn-default" type=submit value='<?php echo $MSG_SUBMIT ?>'>
</form>
<?php 
  require_once("admin-footer.php")
?>