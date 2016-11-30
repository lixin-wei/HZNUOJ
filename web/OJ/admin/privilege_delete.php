<?php require_once("admin-header.php");?>
<?php require_once("../include/check_get_key.php");
require_once("../include/my_func.inc.php");
if (!HAS_PRI("edit_privilege_group")) {
	require_once("error.php");
	exit(1);
}
if(isset($_GET['uid'])){
	$user_id=$mysqli->real_escape_string($_GET['uid']);
	$rightstr =$mysqli->real_escape_string($_GET['rightstr']);
	if (get_order($rightstr)<=get_order(get_group())) {
		require_once("error.php");
		exit(1);
	}
	$sql="DELETE from `privilege` where user_id='$user_id' and rightstr='$rightstr'";
	$mysqli->query($sql);
	if ($mysqli->affected_rows==1) echo "$user_id $rightstr deleted!";
	else echo "No such privilege!";
}
?>

<script>
	history.go(-1);
</script>

<?php require_once("admin-footer.php") ?>