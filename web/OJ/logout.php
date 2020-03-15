<?php if(!session_id()) session_start();
unset($_SESSION['user_id']);
session_destroy();
header("Location:index.php");
?>
