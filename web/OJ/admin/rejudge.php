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
		$sql="UPDATE `solution` SET `result`=1 WHERE `problem_id`=".$rjpid;
		$mysqli->query($sql) or die($mysqli->error);
		$sql="delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`=".$rjpid.")";
		$mysqli->query($sql) or die($mysqli->error);
		$url="../status.php?problem_id=".$rjpid;
		echo "Rejudged Problem ".$rjpid;
		echo "<script>location.href='$url';</script>";
	}
	else if (isset($_POST['rjsid'])){
		$rjsid=intval($_POST['rjsid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `solution_id`=".$rjsid;
		$mysqli->query($sql) or die($mysqli->error);
		$sql="delete from `sim` WHERE `s_id`=".$rjsid;
		$mysqli->query($sql) or die($mysqli->error);
		$url="../status.php?top=".($rjsid+1);
		echo "Rejudged Runid ".$rjsid;
		echo "<script>location.href='$url';</script>";
	}else if (isset($_POST['rjcid'])){
		$rjcid=intval($_POST['rjcid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `contest_id`=".$rjcid;
		$mysqli->query($sql) or die($mysqli->error);
		$url="../status.php?cid=".($rjcid);
		echo "Rejudged Contest id :".$rjcid;
		echo "<script>location.href='$url';</script>";
	}

}
?>
	<title>Rejudge</title>
	<h1>Rejudge</h1><hr/>
	<ol>
	<li>Problem
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjpid'>	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default">Submit</button>
		<?php require("../include/set_post_key.php");?>
	</form>
	<li>Solution
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjsid'>	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default">Submit</button>
    <?php require("../include/set_post_key.php");?>
	</form>
	<li>Contest
	<form class="form-inline" action='rejudge.php' method=post>
		<input class="form-control" type=input name='rjcid'>	<input type='hidden' name='do' value='do'>
		<button type=submit class="btn btn-default">Submit</button>
    <?php require("../include/set_post_key.php");?>
	</form>
<?php 
  require_once("admin-footer.php")
?>