<?php
/**
 * This file is written by D_Star
 **/
/** very dangerous!
ini_set("display_errors","On");
require_once('../include/db_info.inc.php');

$sql="SELECT * FROM problem";
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	// $str = $row["description"];
	// $isMatched = preg_match('/<img.*src=\"\/OJ\/.*\".*>/', $str, $matches);
	// var_dump($isMatched, $matches);
	$pattern="/(<img.*?src=\")(\/(OJ|JudgeOnline)\/.*?)(\".*?>)/";
	$replace="$1/web$2$4";
	$des=preg_replace($pattern, $replace, $row["description"]);
	$id=$row["problem_id"];
	$sql2="UPDATE problem SET description='$des' WHERE problem_id='$id'";
	//echo $sql2;
	mysql_query($sql2);
	//echo $row["description"];
}
echo "succeed!";
**/
?>
