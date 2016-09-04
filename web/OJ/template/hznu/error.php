<?php
	$title="Error";
	require_once("header.php");
	if ($view_errors=="") { //default text
		$view_errors="You don't have the privilege to view this page!";
	}
?>
<h1 align="center"><?php echo $view_errors ?></h1>
<?php require_once("footer.php") ?>