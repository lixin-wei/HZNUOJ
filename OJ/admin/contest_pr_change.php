<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
$cid=intval($_GET['cid']);
	if(!(isset($_SESSION["m$cid"])||isset($_SESSION['administrator']))) exit();
$sql="select `private` FROM `contest` WHERE `contest_id`=$cid";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num<1){
	mysql_free_result($result);
	echo "No Such Problem!";
	require_once("../oj-footer.php");
	exit(0);
}
$row=mysql_fetch_row($result);
if (intval($row[0])==0) $sql="UPDATE `contest` SET `private`='1' WHERE `contest_id`=$cid";
else $sql="UPDATE `contest` SET `private`='0' WHERE `contest_id`=$cid";
mysql_free_result($result);
mysql_query($sql);
?>
<script language=javascript>
	history.go(-1);
</script>

