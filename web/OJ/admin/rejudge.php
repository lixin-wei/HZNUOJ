<?php require("admin-header.php");

if (!HAS_PRI("rejudge")) {
	echo "Permission denied!";
	exit(1);
}
?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	if (isset($_POST['rjpid'])){
		$rjpid=intval($_POST['rjpid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `problem_id`='$rjpid'";
		$mysqli->query($sql) or die($mysqli->error);
		$sql="delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`='$rjpid')";
		$mysqli->query($sql) or die($mysqli->error);
		$url="../status.php?problem_id=".$rjpid;
		echo $MSG_REJUDGE.$MSG_PROBLEM_ID.":".$rjpid;
		echo "<script>location.href='$url';</script>";
	} else if (isset($_POST['rjsid'])){
		$rjsid=intval($_POST['rjsid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `solution_id`='$rjsid'";
		$mysqli->query($sql) or die($mysqli->error);
		$sql="delete from `sim` WHERE `s_id`='$rjsid'";
		$mysqli->query($sql) or die($mysqli->error);
		$sql="select contest_id from `solution` WHERE `solution_id`='$rjsid'";
		$data=$mysqli->query($sql);
		$cid=intval($data->fetch_row()[0]);
		if ($cid>0)
			$url="../status.php?cid=".$cid."&top=".($rjsid);
		else
			$url="../status.php?top=".($rjsid);
		echo $MSG_REJUDGE.$MSG_RUNID.":".$rjsid;
		$data->free();
		echo "<script>location.href='$url';</script>";
	} else if (isset($_POST['rjcid'])){
		$rjcid=intval($_POST['rjcid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `contest_id`='$rjcid'";
		$mysqli->query($sql) or die($mysqli->error);
		$url="../status.php?cid=".($rjcid);
		echo $MSG_REJUDGE.$MSG_CONTEST.$MSG_ID.":".$rjcid;
		echo "<script>location.href='$url';</script>";
	}

}
?>
    <title><?php echo $html_title.$MSG_REJUDGE ?></title>
	<h1><?php echo $MSG_REJUDGE ?></h1>
    <h4><?php echo $MSG_HELP_REJUDGE ?></h4>
    <hr/>
	<ol>
	<li><?php echo $MSG_PROBLEM ?>
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjpid' placeholder="<?php echo $MSG_PROBLEM_ID ?>" >	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default"><?php echo $MSG_SUBMIT ?></button>
		<?php require("../include/set_post_key.php");?>
	</form>
    </li>
	<li><?php echo $MSG_Solution ?>
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjsid' placeholder="<?php echo $MSG_RUNID ?>">	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default"><?php echo $MSG_SUBMIT ?></button>
		<?php require("../include/set_post_key.php");?>
	</form>
    </li>
	<li><?php echo $MSG_CONTEST  ?>
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjcid' placeholder="<?php echo $MSG_CONTEST.$MSG_ID ?>" >	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default"><?php echo $MSG_SUBMIT ?></button>
		<?php require("../include/set_post_key.php");?>
	</form>
    </li>
    </ol>
<?php 
  require_once("admin-footer.php")
?>