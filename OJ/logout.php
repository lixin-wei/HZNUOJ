<?php session_start();
unset($_SESSION['user_id']);
session_destroy();

echo "<script language='javascript'>\n";
echo "history.go(-1);\n";
echo "</script>";
?>
