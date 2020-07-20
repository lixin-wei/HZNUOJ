<?php 
if(!session_id()) @session_start();
require_once("./include/setlang.php");
if (!isset($_SESSION['user_id'])){
	$view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
require_once('./include/db_info.inc.php');
$sql="select distinct source,problem_id from source_code right join 
		(select solution_id,problem_id from solution where user_id='".$_SESSION['user_id']."' and result=4) S 
		on source_code.solution_id=S.solution_id order by problem_id";

$result=$mysqli->query($sql);
while($row=$result->fetch_object()){
	echo "Problem".$row->problem_id.":";
	echo $row->source;
	echo "------------------------------------------------------";
}
$result->free();
?>
