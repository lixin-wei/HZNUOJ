<?php


if(isset($_GET['id'])){
	require_once "include/db_info.inc.php";
	$id=$mysqli->real_escape_string($_GET['id']);
	$sql="SELECT content FROM news WHERE news_id='$id'";
	$result=$mysqli->query($sql);
	echo $result->fetch_array()['content'];
	$result->close;
}


?>