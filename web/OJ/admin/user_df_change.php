<?php
 /**
   * This file is created
   * by lixun516@qq.com
   * @2020.02.04
  **/
?>

<?php 
  require_once("admin-header.php");
  require_once("../include/check_get_key.php");
  require_once("../include/my_func.inc.php");
  $back = "<script language=javascript>history.go(-1);</script>";
  if(!HAS_PRI("edit_user_profile")){
    echo $back ;
    exit(1);
  }
  $ulist = array();
  if (isset($_GET['cid'])&& trim($_GET['cid'])!=""){
    array_push($ulist, $mysqli->real_escape_string($_GET['cid']));
  } else {
        foreach($_POST['cid'] as $i){
            $i = $mysqli->real_escape_string($i);
            if(get_order(get_group($i)) > get_order(get_group("")) ){
              array_push($ulist, $i);
            }
        }
  }
if(!$ulist){ echo $back; exit(1); }
$sql_filter = "`user_id` NOT IN ('admin', '{$_SESSION['user_id']}')";//admin用户和登录用户本身不能修改状态
if(isset($_POST['enable'])){
  $sql = "UPDATE `users` SET defunct='N' WHERE `user_id` IN ('". implode("','", $ulist) ."') AND ".$sql_filter;
}else if(isset($_POST['disable'])){
  $sql = "UPDATE `users` SET defunct='Y' WHERE `user_id` IN ('". implode("','", $ulist) ."') AND ".$sql_filter;
}else{
    $sql="SELECT `defunct` FROM `users` WHERE $sql_filter AND `user_id`='$ulist[0]'";
    $result=$mysqli->query($sql);
    if($result->num_rows==0) {echo $back; exit(1);}
    $row=$result->fetch_row();
    $defunct=$row[0];
    $result->free();
    if ($defunct=='Y') $sql="UPDATE `users` SET `defunct`='N' WHERE $sql_filter AND  `user_id`='$ulist[0]'";
    else $sql="UPDATE `users` SET `defunct`='Y' WHERE $sql_filter AND `user_id`='$ulist[0]'";
}
//echo $sql;
$mysqli->query($sql) or die($mysqli->error);
?>
<script language=javascript>
	history.go(-1);
</script>
