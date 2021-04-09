<?php
	$title="Error";
    if ($_GET['cid']) require_once("contest_header.php");
    else require_once("header.php");
	if ($view_errors=="") { //default text
		$view_errors="You don't have the privilege to view this page!";
	}
?>
<div class="am-container" style="padding-top: 30px;">
<h1 align="center"><?php echo $view_errors ?></h1>
</div>
<?php require_once("footer.php") ?>