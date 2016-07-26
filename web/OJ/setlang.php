<?php require_once("./include/db_info.inc.php");
	require_once("./include/my_func.inc.php");
	$newlang=strval($_GET['lang']);
	if(is_valid_user_name($newlang)&&strlen($newlang)<3){
		$_SESSION['OJ_LANG']=$newlang;
	}
		echo "<script language='javascript'>\n";
		echo "history.go(-1);\n";
		echo "</script>";
	
?>
