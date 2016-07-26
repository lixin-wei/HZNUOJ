<?php
if (!isset($_SESSION['postkey'])||!isset($_POST['postkey'])||$_SESSION['postkey']!=$_POST['postkey'])
	exit(1);
?>
