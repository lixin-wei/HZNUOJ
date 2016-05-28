<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	$from=mysql_real_escape_string($_POST['from']);
	$to=mysql_real_escape_string($_POST['to']);
	$start=intval($_POST['start']);
	$end=intval($_POST['end']);
	$sql="update `solution` set `user_id`='$to' where `user_id`='$from' and problem_id>=$start and problem_id<=$end and result=4";
	echo $sql;
	mysql_query($sql);
	echo mysql_affected_rows()." source file given!";
	
}
?>
<form action='source_give.php' method=post>
	<b>Give source:</b><br />
	From:<input type=text size=10 name="from" value="zhblue"><br />
	To:<input type=text size=10 name="to" value="standard"><br />
	start pid:<input type=text size=10 name="start"><br />
	end pid:<input type=text size=10 name="end"><br />
	<input type='hidden' name='do' value='do'>
	
	<?php require_once("../include/set_post_key.php");?>
	<input type=submit value='GiveMySourceToHim'>
</form>
