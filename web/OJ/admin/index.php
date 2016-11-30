<?php
require_once("admin-header.php");
if(isset($OJ_LANG)) require_once("../lang/$OJ_LANG.php");
require_once "../include/my_func.inc.php";
?>
  <title>JudgeOnline Administration</title>
  <div class="container-fluid">
    <div class="row">
    	<h1>Welcome To Administration Page</h1><hr>
    	Your group is: <b><?php echo get_group($_SESSION['user_id']); ?></b><br>
    	<a href="privilege_distribution.php">See what you can do here.</a>
    </div>
  </div>
<?php 
  require_once("admin-footer.php")
?>