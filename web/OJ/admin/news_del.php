<?php 
  require_once("admin-header.php");
  require_once("../include/check_get_key.php");
  if (!HAS_PRI("edit_news")) {
    require_once("error.php");
    exit(1);
  }
  
  if(isset($_GET['id'])){
  	
  	$sql="DELETE FROM news WHERE news_id='{$_GET['id']}'";
  	$mysqli->query($sql);
  }
  require_once("admin-footer.php");
?>
<script language='javascript'>history.go(-1);</script>
