<?php require_once("admin-header.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
?>
<?php if(isset($_POST['do'])){
  require_once("../include/check_post_key.php");
  $from=$mysqli->real_escape_string($_POST['from']);
  $to=$mysqli->real_escape_string($_POST['to']);
  $start=intval($_POST['start']);
  $end=intval($_POST['end']);
  $sql="update `solution` set `user_id`='$to' where `user_id`='$from' and problem_id>=$start and problem_id<=$end and result=4";
  echo $sql;
  $mysqli->query($sql);
  echo $mysqli->affected_rows." source file given!";
  
}
?>
<title>Give Source</title>
<h1>Give Source</h1><hr>
<form action='source_give.php' method=post>
  From:<input type=text size=10 name="from" value="zhblue"><br />
  To:<input type=text size=10 name="to" value="standard"><br />
  start pid:<input type=text size=10 name="start"><br />
  end pid:<input type=text size=10 name="end"><br />
  <input type='hidden' name='do' value='do'>
  
  <?php require_once("../include/set_post_key.php");?>
  <input type=submit value='GiveMySourceToHim'>
</form>
<?php 
  require_once("admin-footer.php")
?>