<?
require_once("admin-header.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
if (!HAS_PRI("inner_function")){
	echo "You are not allowed to view this page!";
	exit(1);
}
function update_for_user($user_id){
	$sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."' ";
	$result=$mysqli->query($sql);
	$row = $result->fetch_array();
	if ($row){
		$oldpw = $row['password'];
		if (!isOldPW($oldpw)) return False;
		$newpw = pwGen($row['password'],True);
		$sql="UPDATE `users` set `password`='$newpw' where `user_id`='$user_id' LIMIT 1";
		$mysqli->query($sql);
		return True;
	}
	return False;
}

$sql="select user_id from `users`";
$result=$mysqli->query($sql);
while ($row=$result->fetch_array()){
	$uid = $row['user_id'];
	echo $uid.">".update_for_user($uid)."\n";
}
unlink("update_pw.php");
?>
