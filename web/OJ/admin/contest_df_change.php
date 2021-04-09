<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<?php
require_once("admin-header.php");
require_once("../include/check_get_key.php");
$clist="";
sort($_POST['cid']);
foreach($_POST['cid'] as $i){
    $i = intval($mysqli->real_escape_string($i));
    if($clist) {
        if(isset($_SESSION["m$i"])||HAS_PRI("edit_contest")) $clist.=','.$i;
  	} else{
    	if(isset($_SESSION["m$i"])||HAS_PRI("edit_contest")) $clist = $i;
  	}
}  


$cid=intval($mysqli->real_escape_string($_GET['cid']));
if(!(isset($_GET['cid']) && isset($_SESSION["m$cid"])||HAS_PRI("edit_contest"))){
	echo "<script language=javascript>history.go(-1);</script>";
	exit(1);
}
if (!isset($_GET['cid'])&& $clist=="") {
  echo "<script language=javascript>history.go(-1);</script>";
  exit(1);
}
if (isset($_GET['isTop'])) {
  $sql="UPDATE `contest` SET `isTop`=not `isTop` WHERE `contest_id`=$cid";
} else {

//20190826 批量改变状态
if(isset($_POST['enable'])&&$clist){
  $sql = "UPDATE `contest` SET `defunct`='N' WHERE `contest_id` IN ($clist)";           
}else if(isset($_POST['disable'])&&$clist){
  $sql = "UPDATE `contest` SET `defunct`='Y' WHERE `contest_id` IN ($clist)";           
}else{  
	$sql="select `defunct` FROM `contest` WHERE `contest_id`=$cid";
	$result=$mysqli->query($sql);
	$row=$result->fetch_row();
	$defunct = $row[0];
  $result->free();
	if ($defunct=='Y') $sql="UPDATE `contest` SET `defunct`='N' WHERE `contest_id`=$cid";
	else $sql="UPDATE `contest` SET `defunct`='Y' WHERE `contest_id`=$cid";
}

}
$mysqli->query($sql) or die($mysqli->error);
?>
<script language=javascript>
	history.go(-1);
</script>

