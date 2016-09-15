<?php
require_once("admin-header.php");
if(isset($OJ_LANG)) require_once("../lang/$OJ_LANG.php");
?>
  <title>JudgeOnline Administration</title>
  <div class="container-fluid">
    <div class="row">
        <iframe name="main" src="watch.php" width="100%" height="100%" frameborder="0"></iframe>
    </div>
  </div>
<?php 
  require_once("admin-footer.php")
?>