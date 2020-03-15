<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

<?php 
  require_once("admin-header.php");
  require_once("../include/check_get_key.php");
  require_once("../include/my_func.inc.php");
  $plist = "";
  sort($_POST['pid']);
  foreach($_POST['pid'] as $i){
    $i = intval($mysqli->real_escape_string($i));
  	if($plist) {
        if(HAS_PRI("edit_".get_problemset($i)."_problem")) $plist.=','.$i;
  	} else{
    	if(HAS_PRI("edit_".get_problemset($i)."_problem")) $plist = $i;
  	}
  }  
  if (isset($_GET['id'])&& !HAS_PRI("edit_".get_problemset($_GET['id'])."_problem")) {
    echo "<script language=javascript>history.go(-1);</script>";
    exit(1);
  }
  if (!isset($_GET['id'])&& $plist=="") {
    echo "<script language=javascript>history.go(-1);</script>";
    exit(1);
  }

//20190808 批量改变状态
if(isset($_POST['enable'])&&$plist){
  $sql = "UPDATE `problem` SET defunct='N' WHERE `problem_id` IN ($plist)";           
}else if(isset($_POST['disable'])&&$plist){
  $sql = "UPDATE `problem` SET defunct='Y' WHERE `problem_id` IN ($plist)";           
}else{  
  $id = intval($mysqli->real_escape_string($_GET['id']));
$sql="SELECT `defunct` FROM `problem` WHERE `problem_id`=$id";
$result=$mysqli->query($sql);
$row=$result->fetch_row();
$defunct=$row[0];
echo $defunct;
$result->free();
if ($defunct=='Y') $sql="update `problem` set `defunct`='N' where `problem_id`=$id";
else $sql="update `problem` set `defunct`='Y' where `problem_id`=$id";
}
$mysqli->query($sql) or die($mysqli->error);
?>
<script language=javascript>
	history.go(-1);
</script>
